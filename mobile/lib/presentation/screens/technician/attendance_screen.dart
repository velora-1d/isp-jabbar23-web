import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_config.dart';
import '../../../core/widgets/app_dialog.dart';

class AttendanceScreen extends StatefulWidget {
  const AttendanceScreen({super.key});

  @override
  State<AttendanceScreen> createState() => _AttendanceScreenState();
}

class _AttendanceScreenState extends State<AttendanceScreen> {
  bool _isLoading = true;
  String _status = 'unknown'; // present, absent, unknown
  String? _clockInTime;
  String? _clockOutTime;

  final ImagePicker _picker = ImagePicker();

  @override
  void initState() {
    super.initState();
    _loadStatus();
  }

  Future<void> _loadStatus() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    try {
      final response = await http.get(
        Uri.parse('${AppConfig.baseUrl}/technician/attendance'),
        headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (mounted) {
          setState(() {
            _status = data['status'];
            _clockInTime = data['clock_in_time'];
            _clockOutTime = data['clock_out_time'];
            _isLoading = false;
          });
        }
      } else {
        if (mounted) setState(() => _isLoading = false);
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Future<void> _handleAttendance(String type) async {
    debugPrint('Tombol Absen diklik: $type');

    try {
      // 1. Open Camera for Selfie
      final XFile? photo = await _picker.pickImage(
        source: ImageSource.camera,
        imageQuality: 50,
        preferredCameraDevice: CameraDevice.front
      );

      if (photo == null) {
        debugPrint('Foto dibatalkan oleh user');
        return; // Cancelled
      }

      debugPrint('Foto berhasil diambil: ${photo.path}');

      // 2. Upload
      await _submitAttendance(type, File(photo.path));
    } catch (e) {
      debugPrint('Error saat ambil foto: $e');
      if (mounted) AppDialog.showError(context, message: 'Gagal membuka kamera: $e');
    }
  }

  Future<void> _submitAttendance(String type, File photo) async {
    setState(() => _isLoading = true);

    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    try {
      var request = http.MultipartRequest(
        'POST',
        Uri.parse('${AppConfig.baseUrl}/technician/attendance/$type'),
      );

      request.headers['Authorization'] = 'Bearer $token';
      request.files.add(await http.MultipartFile.fromPath('photo', photo.path));

      final streamResponse = await request.send();
      final response = await http.Response.fromStream(streamResponse);

      if (response.statusCode == 200) {
        if(mounted){
          AppDialog.show(
            context: context,
            title: 'Berhasil',
            message: type == 'in' ? 'Absen Masuk Berhasil' : 'Absen Pulang Berhasil',
            type: AppDialogType.success
          );
          _loadStatus();
        }
      } else {
        final data = json.decode(response.body);
        if(mounted) AppDialog.showError(context, message: data['message'] ?? 'Gagal absen');
        setState(() => _isLoading = false);
      }
    } catch (e) {
      if(mounted) AppDialog.showNetworkError(context);
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Absensi Harian'),
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: Colors.white,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                   const SizedBox(height: 20),
                  Text(
                    DateTime.now().toString().substring(0, 10),
                    style: const TextStyle(fontSize: 18, color: Colors.grey),
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'Halo, Semangat Bekerja!',
                    style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 40),

                  // Status Card
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(24),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [
                         BoxShadow(color: Colors.blue.withOpacity(0.1), blurRadius: 20, offset: const Offset(0, 8))
                      ]
                    ),
                    child: Column(
                      children: [
                        const Text("Status Kehadiran Hari Ini", style: TextStyle(color: Colors.grey)),
                        const SizedBox(height: 12),
                        Text(
                          _getStatusLabel(),
                          style: TextStyle(
                            fontSize: 28,
                            fontWeight: FontWeight.bold,
                            color: _getStatusColor()
                          ),
                        ),
                        if (_clockInTime != null) ...[
                           const SizedBox(height: 12),
                           const Divider(),
                           Row(
                             mainAxisAlignment: MainAxisAlignment.spaceAround,
                             children: [
                               Column(
                                 children: [
                                   const Text("Masuk", style: TextStyle(fontSize: 12, color: Colors.grey)),
                                   Text(_clockInTime!, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                                 ],
                               ),
                               if (_clockOutTime != null)
                                 Column(
                                   children: [
                                     const Text("Pulang", style: TextStyle(fontSize: 12, color: Colors.grey)),
                                     Text(_clockOutTime!, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                                   ],
                                 ),
                             ],
                           )
                        ]
                      ],
                    ),
                  ),

                  const Spacer(),

                  if (_status == 'absent' || _status == 'unknown')
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: () => _handleAttendance('in'),
                        icon: const Icon(Icons.camera_alt, size: 28),
                        label: const Text("Absen MASUK (Selfie)", style: TextStyle(fontSize: 18)),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.green,
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        ),
                      ),
                    )
                  else if (_status == 'present')
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: () => _handleAttendance('out'),
                        icon: const Icon(Icons.camera_alt, size: 28),
                        label: const Text("Absen PULANG (Selfie)", style: TextStyle(fontSize: 18)),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.red,
                          foregroundColor: Colors.white,
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        ),
                      ),
                    )
                  else
                    const Center(child: Text("Sudah Absen Pulang ✅", style: TextStyle(fontSize: 18, color: Colors.green))),

                  const SizedBox(height: 40),
                ],
              ),
            ),
    );
  }

  String _getStatusLabel() {
    if (_status == 'present') return 'SUDAH HADIR';
    if (_status == 'done') return 'SELESAI KERJA';
    return 'BELUM ABSEN';
  }

  Color _getStatusColor() {
    if (_status == 'present') return Colors.green;
    if (_status == 'done') return Colors.blue;
    return Colors.orange;
  }
}
