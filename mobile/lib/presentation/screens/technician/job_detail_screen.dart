import 'dart:convert';
import 'dart:io';
import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:signature/signature.dart';
import 'package:geolocator/geolocator.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_config.dart';
import '../../../core/widgets/app_dialog.dart';

class JobDetailScreen extends StatefulWidget {
  final int jobId;

  const JobDetailScreen({super.key, required this.jobId});

  @override
  State<JobDetailScreen> createState() => _JobDetailScreenState();
}

class _JobDetailScreenState extends State<JobDetailScreen> {
  bool _isLoading = true;
  bool _isUpdating = false;
  Map<String, dynamic>? _job;

  // Fields
  final TextEditingController _opticalPowerController = TextEditingController();
  File? _evidencePhoto;
  final ImagePicker _picker = ImagePicker();

  // NEW: Signature controller
  final SignatureController _signatureController = SignatureController(
    penStrokeWidth: 3,
    penColor: Colors.black,
    exportBackgroundColor: Colors.white,
  );

  // NEW: GPS coordinates
  Position? _currentPosition;
  bool _isGettingLocation = false;

  @override
  void initState() {
    super.initState();
    _loadJobDetail();
  }

  @override
  void dispose() {
    _signatureController.dispose();
    _opticalPowerController.dispose();
    super.dispose();
  }

  Future<void> _loadJobDetail() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    try {
      final response = await http.get(
        Uri.parse('${AppConfig.baseUrl}/technician/jobs'),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json'
        },
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final jobs = data['data'] as List;
        final job = jobs.firstWhere((element) => element['id'] == widget.jobId,
            orElse: () => null);

        if (mounted) {
          setState(() {
            _job = job;
            _isLoading = false;
            if (job != null && job['optical_power'] != null) {
              _opticalPowerController.text = job['optical_power'];
            }
          });
        }
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  // NEW: Open Google Maps with customer address
  Future<void> _openMaps() async {
    final address = _job?['customer']?['address'];
    if (address == null || address.isEmpty) {
      if (mounted) {
        AppDialog.showError(context, message: 'Alamat tidak tersedia');
      }
      return;
    }

    final encodedAddress = Uri.encodeComponent(address);
    final googleMapsUrl = Uri.parse(
        'https://www.google.com/maps/search/?api=1&query=$encodedAddress');

    if (await canLaunchUrl(googleMapsUrl)) {
      await launchUrl(googleMapsUrl, mode: LaunchMode.externalApplication);
    } else {
      if (mounted) {
        AppDialog.showError(context, message: 'Tidak bisa membuka Maps');
      }
    }
  }

  // NEW: Get current GPS location
  Future<void> _getCurrentLocation() async {
    setState(() => _isGettingLocation = true);

    try {
      // Check permission
      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
          if (mounted) {
            AppDialog.showError(context, message: 'Izin lokasi ditolak');
          }
          setState(() => _isGettingLocation = false);
          return;
        }
      }

      if (permission == LocationPermission.deniedForever) {
        if (mounted) {
          AppDialog.showError(context,
              message: 'Izin lokasi diblokir permanen. Buka Settings.');
        }
        setState(() => _isGettingLocation = false);
        return;
      }

      // Get position
      final position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );

      setState(() {
        _currentPosition = position;
        _isGettingLocation = false;
      });

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
                '📍 Lokasi: ${position.latitude.toStringAsFixed(6)}, ${position.longitude.toStringAsFixed(6)}'),
            backgroundColor: Colors.green,
          ),
        );
      }
    } catch (e) {
      setState(() => _isGettingLocation = false);
      if (mounted) {
        AppDialog.showError(context, message: 'Gagal mendapatkan lokasi: $e');
      }
    }
  }

  Future<void> _pickImage() async {
    final XFile? photo =
        await _picker.pickImage(source: ImageSource.gallery, imageQuality: 50);
    if (photo != null) {
      setState(() {
        _evidencePhoto = File(photo.path);
      });
    }
  }

  Future<void> _takePhoto() async {
    final source = (Platform.isLinux || Platform.isWindows || Platform.isMacOS)
        ? ImageSource.gallery
        : ImageSource.camera;

    final XFile? photo =
        await _picker.pickImage(source: source, imageQuality: 50);
    if (photo != null) {
      setState(() {
        _evidencePhoto = File(photo.path);
      });
    }
  }

  Future<void> _updateStatus(String newStatus) async {
    // Validation for Resolved status
    if (newStatus == 'resolved') {
      if (_opticalPowerController.text.isEmpty) {
        AppDialog.showError(context,
            message: 'Harap isi nilai Redaman (Optical Power)');
        return;
      }
      if (_evidencePhoto == null && _job?['evidence_photo'] == null) {
        AppDialog.showError(context,
            message: 'Harap lampirkan Foto Bukti Pekerjaan');
        return;
      }
      if (_signatureController.isEmpty) {
        AppDialog.showError(context,
            message: 'Harap minta tanda tangan pelanggan');
        return;
      }
      if (_currentPosition == null) {
        AppDialog.showError(context,
            message: 'Harap ambil lokasi GPS terlebih dahulu');
        return;
      }
    }

    setState(() => _isUpdating = true);

    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    try {
      var request = http.MultipartRequest(
        'POST',
        Uri.parse(
            '${AppConfig.baseUrl}/technician/jobs/${widget.jobId}/status'),
      );

      request.headers['Authorization'] = 'Bearer $token';
      request.fields['status'] = newStatus;
      request.fields['notes'] = 'Status diupdate via Mobile App';

      if (_opticalPowerController.text.isNotEmpty) {
        request.fields['optical_power'] = _opticalPowerController.text;
      }

      // NEW: Add GPS coordinates
      if (_currentPosition != null) {
        request.fields['latitude'] = _currentPosition!.latitude.toString();
        request.fields['longitude'] = _currentPosition!.longitude.toString();
      }

      // Evidence photo
      if (_evidencePhoto != null) {
        request.files.add(await http.MultipartFile.fromPath(
          'evidence_photo',
          _evidencePhoto!.path,
        ));
      }

      // NEW: Add signature as image
      if (_signatureController.isNotEmpty) {
        final Uint8List? signatureBytes =
            await _signatureController.toPngBytes();
        if (signatureBytes != null) {
          request.files.add(http.MultipartFile.fromBytes(
            'customer_signature',
            signatureBytes,
            filename: 'signature_${widget.jobId}.png',
          ));
        }
      }

      final streamResponse = await request.send();
      final response = await http.Response.fromStream(streamResponse);

      if (response.statusCode == 200) {
        if (mounted) {
          AppDialog.show(
              context: context,
              title: 'Berhasil',
              message: 'Status pekerjaan dan laporan berhasil dikirim',
              type: AppDialogType.success);
          _loadJobDetail();
        }
      } else {
        if (mounted) {
          AppDialog.showError(context,
              message: 'Gagal update status: ${response.statusCode}');
        }
      }
    } catch (e) {
      if (mounted) AppDialog.showNetworkError(context);
    } finally {
      if (mounted) setState(() => _isUpdating = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }
    if (_job == null) {
      return const Scaffold(body: Center(child: Text('Data tidak ditemukan')));
    }

    final status = _job?['status'];

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Detail Pekerjaan'),
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildSectionHeader('Info Tiket'),
            _buildInfoCard(
              children: [
                _buildInfoRow('Judul', _job?['subject']),
                _buildInfoRow(
                    'Prioritas', _job?['priority']?.toUpperCase() ?? '-'),
                _buildInfoRow('Status', status?.toUpperCase() ?? '-'),
                if (_job?['optical_power'] != null)
                  _buildInfoRow('Redaman', _job?['optical_power']),
                const Divider(),
                const Text('Deskripsi:',
                    style: TextStyle(fontWeight: FontWeight.bold)),
                const SizedBox(height: 4),
                Text(_job?['description'] ?? '-'),
              ],
            ),
            const SizedBox(height: 24),

            // CUSTOMER INFO with MAP BUTTON
            _buildSectionHeader('Info Pelanggan'),
            _buildInfoCard(
              children: [
                _buildInfoRow('Nama', _job?['customer']?['name']),
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const SizedBox(
                        width: 100,
                        child: Text('Alamat',
                            style: TextStyle(color: Colors.grey))),
                    Expanded(
                        child: Text(_job?['customer']?['address'] ?? '-',
                            style:
                                const TextStyle(fontWeight: FontWeight.w500))),
                    // NEW: Map button
                    IconButton(
                      onPressed: _openMaps,
                      icon: const Icon(Icons.map, color: AppColors.primaryBlue),
                      tooltip: 'Buka di Maps',
                      padding: EdgeInsets.zero,
                      constraints: const BoxConstraints(),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                _buildInfoRow('Telepon', _job?['customer']?['phone'] ?? '-'),
              ],
            ),
            const SizedBox(height: 24),

            // FORM INPUT (Only show if In Progress)
            if (status == 'in_progress') ...[
              _buildSectionHeader('Laporan Pengerjaan'),
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12)),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Optical Power
                    const Text('Optical Power (Redaman)',
                        style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    TextField(
                      controller: _opticalPowerController,
                      decoration: const InputDecoration(
                        hintText: 'Contoh: -18.5 dBm',
                        border: OutlineInputBorder(),
                        isDense: true,
                        suffixText: 'dBm',
                      ),
                    ),
                    const SizedBox(height: 20),

                    // GPS Location
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text('Lokasi GPS',
                            style: TextStyle(fontWeight: FontWeight.bold)),
                        if (_currentPosition != null)
                          Text(
                            '${_currentPosition!.latitude.toStringAsFixed(4)}, ${_currentPosition!.longitude.toStringAsFixed(4)}',
                            style: const TextStyle(
                                color: Colors.green, fontSize: 12),
                          ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    OutlinedButton.icon(
                      onPressed:
                          _isGettingLocation ? null : _getCurrentLocation,
                      icon: _isGettingLocation
                          ? const SizedBox(
                              width: 16,
                              height: 16,
                              child: CircularProgressIndicator(strokeWidth: 2))
                          : Icon(
                              _currentPosition != null
                                  ? Icons.check_circle
                                  : Icons.my_location,
                              color: _currentPosition != null
                                  ? Colors.green
                                  : null),
                      label: Text(_currentPosition != null
                          ? 'Lokasi Didapat ✓'
                          : 'Ambil Lokasi GPS'),
                    ),
                    const SizedBox(height: 20),

                    // Evidence Photo
                    const Text('Bukti Foto (Evidence)',
                        style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        if (_evidencePhoto != null)
                          ClipRRect(
                            borderRadius: BorderRadius.circular(8),
                            child: Image.file(_evidencePhoto!,
                                width: 80, height: 80, fit: BoxFit.cover),
                          )
                        else
                          Container(
                            width: 80,
                            height: 80,
                            decoration: BoxDecoration(
                                color: Colors.grey.shade100,
                                borderRadius: BorderRadius.circular(8)),
                            child: const Icon(Icons.image, color: Colors.grey),
                          ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: Column(
                            children: [
                              OutlinedButton.icon(
                                onPressed: _takePhoto,
                                icon: const Icon(Icons.camera_alt),
                                label: const Text('Ambil Foto'),
                              ),
                              OutlinedButton.icon(
                                onPressed: _pickImage,
                                icon: const Icon(Icons.photo_library),
                                label: const Text('Galeri'),
                              ),
                            ],
                          ),
                        )
                      ],
                    ),
                    const SizedBox(height: 20),

                    // NEW: Digital Signature
                    const Text('Tanda Tangan Pelanggan',
                        style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    Container(
                      height: 150,
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey.shade300),
                        borderRadius: BorderRadius.circular(8),
                        color: Colors.white,
                      ),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(8),
                        child: Signature(
                          controller: _signatureController,
                          backgroundColor: Colors.white,
                        ),
                      ),
                    ),
                    const SizedBox(height: 8),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        TextButton.icon(
                          onPressed: () => _signatureController.clear(),
                          icon: const Icon(Icons.refresh, size: 18),
                          label: const Text('Hapus'),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 32),
            ],

            if (status == 'open')
              _buildActionButton('Mulai Kerjakan', Colors.blue,
                  () => _updateStatus('in_progress')),
            if (status == 'in_progress')
              _buildActionButton('Selesai & Kirim Laporan', Colors.green,
                  () => _updateStatus('resolved')),
            if (status == 'resolved')
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                    color: Colors.green.shade50,
                    borderRadius: BorderRadius.circular(12)),
                child: const Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.check_circle, color: Colors.green),
                    SizedBox(width: 8),
                    Text('Pekerjaan Selesai',
                        style: TextStyle(
                            color: Colors.green, fontWeight: FontWeight.bold)),
                  ],
                ),
              )
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Text(title,
          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
    );
  }

  Widget _buildInfoCard({required List<Widget> children}) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withOpacity(0.04),
              blurRadius: 10,
              offset: const Offset(0, 4)),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: children,
      ),
    );
  }

  Widget _buildInfoRow(String label, String? value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
              width: 100,
              child: Text(label, style: const TextStyle(color: Colors.grey))),
          Expanded(
              child: Text(value ?? '-',
                  style: const TextStyle(fontWeight: FontWeight.w500))),
        ],
      ),
    );
  }

  Widget _buildActionButton(String label, Color color, VoidCallback onTap) {
    return SizedBox(
      width: double.infinity,
      height: 50,
      child: ElevatedButton(
        onPressed: _isUpdating ? null : onTap,
        style: ElevatedButton.styleFrom(
          backgroundColor: color,
          foregroundColor: Colors.white,
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        ),
        child: _isUpdating
            ? const SizedBox(
                width: 24,
                height: 24,
                child: CircularProgressIndicator(color: Colors.white))
            : Text(label,
                style:
                    const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
      ),
    );
  }
}
