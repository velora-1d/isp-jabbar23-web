import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_config.dart';
import '../../../core/widgets/app_dialog.dart';

class CreateTicketScreen extends StatefulWidget {
  const CreateTicketScreen({super.key});

  @override
  State<CreateTicketScreen> createState() => _CreateTicketScreenState();
}

class _CreateTicketScreenState extends State<CreateTicketScreen> {
  bool _isLoading = false;
  String? _selectedIssue;
  final TextEditingController _descriptionController = TextEditingController();

  final List<Map<String, dynamic>> _issueTypes = [
    {'id': 'no_internet', 'label': 'Tidak Ada Internet', 'icon': Icons.wifi_off},
    {'id': 'slow_connection', 'label': 'Koneksi Lambat', 'icon': Icons.speed},
    {'id': 'device_problem', 'label': 'Masalah Perangkat', 'icon': Icons.router},
    {'id': 'other', 'label': 'Lainnya', 'icon': Icons.help_outline},
  ];

  @override
  void dispose() {
    _descriptionController.dispose();
    super.dispose();
  }

  Future<void> _submitTicket() async {
    if (_selectedIssue == null) {
      AppDialog.showError(
        context,
        title: 'Pilih Jenis Gangguan',
        message: 'Mohon pilih salah satu jenis gangguan di atas.',
      );
      return;
    }

    if (_descriptionController.text.trim().isEmpty) {
      AppDialog.showError(
        context,
        title: 'Deskripsi Kosong',
        message: 'Mohon jelaskan detail gangguan Anda.',
      );
      return;
    }

    setState(() {
      _isLoading = true;
    });

    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    if (token == null) {
      Navigator.of(context).pop();
      return;
    }

    try {
      final issueLabel = _issueTypes.firstWhere((e) => e['id'] == _selectedIssue)['label'];

      final response = await http.post(
        Uri.parse('${AppConfig.baseUrl}/customer/tickets'),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: jsonEncode({
          'subject': issueLabel,
          'description': _descriptionController.text.trim(),
          'category': _selectedIssue,
        }),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        if (mounted) {
          AppDialog.show(
            context: context,
            title: 'Laporan Terkirim',
            message: 'Tim kami akan segera menangani laporan Anda.',
            type: AppDialogType.success,
            onButtonPressed: () {
              Navigator.of(context).pop(true);
            },
            dismissible: false,
          );
        }
      } else {
        if (mounted) {
          AppDialog.showError(
             context,
             title: 'Gagal Kirim',
             message: 'Gagal mengirim laporan: ${response.statusCode}',
          );
        }
      }
    } catch (e) {
      if (mounted) {
        AppDialog.showNetworkError(context);
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.surface,
      appBar: AppBar(
        title: const Text('Lapor Gangguan'),
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: Colors.white,
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Pilih Jenis Gangguan',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: AppColors.textPrimary,
              ),
            ),
            const SizedBox(height: 16),

            // Issue Type Grid
            GridView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                childAspectRatio: 1.3,
              ),
              itemCount: _issueTypes.length,
              itemBuilder: (context, index) {
                final issue = _issueTypes[index];
                final isSelected = _selectedIssue == issue['id'];

                return InkWell(
                  onTap: () {
                    setState(() {
                      _selectedIssue = issue['id'];
                    });
                  },
                  borderRadius: BorderRadius.circular(16),
                  child: Container(
                    decoration: BoxDecoration(
                      color: isSelected ? AppColors.primaryBlue : Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(
                        color: isSelected ? AppColors.primaryBlue : Colors.grey.shade200,
                        width: 2,
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.04),
                          blurRadius: 10,
                          offset: const Offset(0, 4),
                        ),
                      ],
                    ),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          issue['icon'],
                          size: 36,
                          color: isSelected ? Colors.white : AppColors.primaryBlue,
                        ),
                        const SizedBox(height: 8),
                        Text(
                          issue['label'],
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.w600,
                            color: isSelected ? Colors.white : AppColors.textPrimary,
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),

            const SizedBox(height: 24),

            const Text(
              'Deskripsi Gangguan',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: AppColors.textPrimary,
              ),
            ),
            const SizedBox(height: 12),

            // Description Field
            TextField(
              controller: _descriptionController,
              maxLines: 5,
              decoration: InputDecoration(
                hintText: 'Jelaskan detail gangguan yang Anda alami...',
                hintStyle: TextStyle(color: Colors.grey.shade400),
                filled: true,
                fillColor: Colors.white,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(16),
                  borderSide: BorderSide(color: Colors.grey.shade200),
                ),
                enabledBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(16),
                  borderSide: BorderSide(color: Colors.grey.shade200),
                ),
                focusedBorder: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(16),
                  borderSide: const BorderSide(color: AppColors.primaryBlue),
                ),
              ),
            ),

            const SizedBox(height: 32),

            // Submit Button
            SizedBox(
              width: double.infinity,
              height: 56,
              child: ElevatedButton(
                onPressed: _isLoading ? null : _submitTicket,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primaryBlue,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  elevation: 4,
                  shadowColor: AppColors.primaryBlue.withOpacity(0.4),
                ),
                child: _isLoading
                    ? const SizedBox(
                        width: 24,
                        height: 24,
                        child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                      )
                    : const Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.send_rounded),
                          SizedBox(width: 8),
                          Text(
                            'Kirim Laporan',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ],
                      ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
