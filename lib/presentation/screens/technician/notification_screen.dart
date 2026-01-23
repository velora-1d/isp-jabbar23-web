import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_config.dart';

class NotificationScreen extends StatefulWidget {
  const NotificationScreen({super.key});

  @override
  State<NotificationScreen> createState() => _NotificationScreenState();
}

class _NotificationScreenState extends State<NotificationScreen> {
  bool _isLoading = true;
  List<dynamic> _notifications = [];

  @override
  void initState() {
    super.initState();
    _loadNotifications();
  }

  Future<void> _loadNotifications() async {
    // Note: Since we don't have a real notification table yet,
    // we will stick to a static list for now or try to fetch if endpoint exists.
    // Future proofing:
    await Future.delayed(const Duration(milliseconds: 500));

    if (mounted) {
      setState(() {
        _isLoading = false;
        _notifications = [
          {
            'title': 'Job Baru Masuk',
            'body': 'Tiket #1023 perlu penanganan segera di lokasi Jl. Mawar No. 10.',
            'time': '10 Menit lalu',
            'is_read': false,
            'type': 'job'
          },
          {
            'title': 'Absensi Berhasil',
            'body': 'Anda telah absen masuk pada pukul 08:00 WIB.',
            'time': '4 Jam lalu',
            'is_read': true,
            'type': 'info'
          },
          {
            'title': 'Maintenance Server',
            'body': 'Akan ada maintenance server nanti malam pukul 23:00 WIB.',
            'time': 'Kemarin',
            'is_read': true,
            'type': 'alert'
          },
        ];
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Notifikasi'),
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: Colors.white,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _notifications.isEmpty
              ? const Center(child: Text("Tidak ada notifikasi", style: TextStyle(color: Colors.grey)))
              : ListView.separated(
                  padding: const EdgeInsets.all(16),
                  itemCount: _notifications.length,
                  separatorBuilder: (context, index) => const SizedBox(height: 12),
                  itemBuilder: (context, index) {
                    final notif = _notifications[index];
                    final isRead = notif['is_read'];

                    return Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: isRead ? Colors.white : Colors.blue.shade50,
                        borderRadius: BorderRadius.circular(12),
                        border: isRead ? null : Border.all(color: Colors.blue.shade100),
                        boxShadow: [
                           BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 4, offset: const Offset(0, 2))
                        ]
                      ),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Icon(
                            notif['type'] == 'job' ? Icons.work : (notif['type'] == 'alert' ? Icons.warning : Icons.info),
                            color: notif['type'] == 'job' ? Colors.orange : (notif['type'] == 'alert' ? Colors.red : Colors.blue),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(notif['title'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                                    Text(notif['time'], style: const TextStyle(color: Colors.grey, fontSize: 12)),
                                  ],
                                ),
                                const SizedBox(height: 4),
                                Text(notif['body'], style: TextStyle(color: AppColors.textPrimary.withOpacity(0.8))),
                              ],
                            ),
                          ),
                        ],
                      ),
                    );
                  },
                ),
    );
  }
}
