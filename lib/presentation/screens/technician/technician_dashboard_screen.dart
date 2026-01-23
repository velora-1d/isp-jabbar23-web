import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_config.dart';

import '../auth/login_screen.dart';
import 'job_list_screen.dart';
import 'job_detail_screen.dart';
import '../profile/profile_screen.dart'; // Reuse user profile for now
import 'inventory_screen.dart';
import 'attendance_screen.dart';
import 'notification_screen.dart';

class TechnicianDashboardScreen extends StatefulWidget {
  const TechnicianDashboardScreen({super.key});

  @override
  State<TechnicianDashboardScreen> createState() =>
      _TechnicianDashboardScreenState();
}

class _TechnicianDashboardScreenState extends State<TechnicianDashboardScreen> {
  bool _isLoading = true;
  Map<String, dynamic>? _stats;
  Map<String, dynamic>? _activeJob;
  List<dynamic> _recentJobs = [];
  String? _userName;

  @override
  void initState() {
    super.initState();
    _loadDashboardData();
  }

  Future<void> _loadDashboardData() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    final name = prefs.getString('user_name');

    setState(() {
      _userName = name ?? 'Teknisi';
    });

    if (token == null) {
      _logout();
      return;
    }

    try {
      final response = await http.get(
        Uri.parse('${AppConfig.baseUrl}/technician/dashboard'),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (mounted) {
          setState(() {
            _stats = data['stats'];
            _activeJob = data['active_job'];
            _recentJobs = data['recent_jobs'] ?? [];
            _isLoading = false;
          });
        }
      } else if (response.statusCode == 401) {
        _logout();
      } else {
        setState(() => _isLoading = false);
      }
    } catch (e) {
      setState(() => _isLoading = false);
    }
  }

  Future<void> _logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();
    if (mounted) {
      Navigator.of(context).pushAndRemoveUntil(
        MaterialPageRoute(builder: (_) => const LoginScreen()),
        (route) => false,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: _loadDashboardData,
              child: SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                child: Column(
                  children: [
                    _buildHeader(),
                    Padding(
                      padding: const EdgeInsets.all(20),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _buildStatsOverview(),
                          const SizedBox(height: 24),

                          // ACTIVE JOB SECTION
                          if (_activeJob != null) ...[
                            const Text(
                              "Sedang Dikerjakan",
                              style: TextStyle(
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                  color: AppColors.textPrimary),
                            ),
                            const SizedBox(height: 12),
                            _buildActiveJobCard(),
                            const SizedBox(height: 24),
                          ],

                          // MENU GRID
                          const Text(
                            "Menu Teknisi",
                            style: TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                                color: AppColors.textPrimary),
                          ),
                          const SizedBox(height: 16),
                          _buildQuickMenuGrid(),

                          const SizedBox(height: 24),

                          // RECENT JOBS
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              const Text(
                                "Pekerjaan Terbaru",
                                style: TextStyle(
                                    fontSize: 18,
                                    fontWeight: FontWeight.bold,
                                    color: AppColors.textPrimary),
                              ),
                              TextButton(
                                onPressed: () {
                                  Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                          builder: (_) =>
                                              const JobListScreen()));
                                },
                                child: const Text('Lihat Semua'),
                              ),
                            ],
                          ),
                          _buildRecentJobsList(),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildHeader() {
    return Container(
      padding: const EdgeInsets.fromLTRB(20, 60, 20, 30),
      decoration: const BoxDecoration(
        color: AppColors.primaryBlue,
        borderRadius: BorderRadius.only(
            bottomLeft: Radius.circular(30), bottomRight: Radius.circular(30)),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text("Halo, Teknisi",
                  style: TextStyle(color: Colors.white70, fontSize: 14)),
              const SizedBox(height: 4),
              Text(
                _userName ?? "User",
                style: const TextStyle(
                    color: Colors.white,
                    fontSize: 20,
                    fontWeight: FontWeight.bold),
              ),
            ],
          ),
          GestureDetector(
            onTap: () {
              Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (_) => const NotificationScreen()));
            },
            child: Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12)),
              child: const Icon(Icons.notifications_none, color: Colors.white),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatsOverview() {
    return Row(
      children: [
        Expanded(
          child: _buildStatItem('Pending', _stats?['pending'] ?? 0,
              Colors.orange, Icons.pending_actions),
        ),
        const SizedBox(width: 16),
        Expanded(
          child: _buildStatItem('Selesai', _stats?['completed'] ?? 0,
              Colors.green, Icons.check_circle_outline),
        ),
      ],
    );
  }

  Widget _buildStatItem(String label, int count, Color color, IconData icon) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
              color: Colors.black.withOpacity(0.04),
              blurRadius: 10,
              offset: const Offset(0, 4))
        ],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
                color: color.withOpacity(0.1), shape: BoxShape.circle),
            child: Icon(icon, color: color, size: 24),
          ),
          const SizedBox(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(count.toString(),
                  style: TextStyle(
                      fontSize: 20, fontWeight: FontWeight.bold, color: color)),
              Text(label,
                  style: const TextStyle(
                      color: AppColors.textSecondary, fontSize: 12)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildActiveJobCard() {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
              builder: (_) => JobDetailScreen(jobId: _activeJob!['id'])),
        ).then((_) => _loadDashboardData());
      },
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          gradient: const LinearGradient(
              colors: [Color(0xFF2E3192), Color(0xFF1BFFFF)]),
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(
                color: Colors.blue.withOpacity(0.3),
                blurRadius: 10,
                offset: const Offset(0, 4))
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(8)),
                  child: const Text("ON PROGRESS",
                      style: TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                          fontSize: 10)),
                ),
                const Icon(Icons.arrow_forward, color: Colors.white, size: 20),
              ],
            ),
            const SizedBox(height: 16),
            Text(
              _activeJob?['subject'] ?? 'Pekerjaan',
              style: const TextStyle(
                  color: Colors.white,
                  fontSize: 18,
                  fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 4),
            Row(
              children: [
                const Icon(Icons.location_on, color: Colors.white70, size: 14),
                const SizedBox(width: 4),
                Expanded(
                  child: Text(
                    _activeJob?['customer']?['address'] ?? '-',
                    style: const TextStyle(color: Colors.white70, fontSize: 12),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ],
            )
          ],
        ),
      ),
    );
  }

  Widget _buildQuickMenuGrid() {
    return GridView.count(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      crossAxisCount: 2,
      childAspectRatio: 1.4,
      crossAxisSpacing: 16,
      mainAxisSpacing: 16,
      children: [
        _buildMenuCard("Daftar Pekerjaan", Icons.assignment, Colors.blue, () {
          Navigator.push(context,
              MaterialPageRoute(builder: (_) => const JobListScreen()));
        }),
        _buildMenuCard("Inventory", Icons.inventory_2_outlined, Colors.orange,
            () {
          Navigator.push(context,
              MaterialPageRoute(builder: (_) => const InventoryScreen()));
        }),
        _buildMenuCard("Absensi", Icons.fingerprint, Colors.purple, () {
          Navigator.push(context,
              MaterialPageRoute(builder: (_) => const AttendanceScreen()));
        }),
        _buildMenuCard("Profil Saya", Icons.person_outline, Colors.teal, () {
          Navigator.push(context,
              MaterialPageRoute(builder: (_) => const ProfileScreen()));
        }),
      ],
    );
  }

  Widget _buildMenuCard(
      String title, IconData icon, Color color, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(16),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
                color: Colors.black.withOpacity(0.04),
                blurRadius: 10,
                offset: const Offset(0, 4))
          ],
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 32, color: color),
            const SizedBox(height: 8),
            Text(title,
                style: const TextStyle(
                    fontWeight: FontWeight.w600,
                    fontSize: 13,
                    color: AppColors.textPrimary)),
          ],
        ),
      ),
    );
  }

  Widget _buildRecentJobsList() {
    if (_recentJobs.isEmpty) {
      return const Padding(
        padding: EdgeInsets.only(top: 20),
        child: Center(
            child: Text("Belum ada pekerjaan baru",
                style: TextStyle(color: Colors.grey))),
      );
    }
    // Reuse specific job card or simplify logic
    return Column(
      children: _recentJobs.map((job) {
        final status = job['status'];
        Color stColor = Colors.grey;
        if (status == 'open') stColor = Colors.orange;
        if (status == 'resolved') stColor = Colors.green;

        return Card(
          margin: const EdgeInsets.only(bottom: 12),
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          elevation: 0, // flat
          color: Colors.white,
          child: ListTile(
            contentPadding: const EdgeInsets.all(12),
            leading: CircleAvatar(
              backgroundColor: stColor.withOpacity(0.1),
              child: Icon(Icons.work_outline, color: stColor, size: 20),
            ),
            title: Text(job['subject'] ?? '-',
                style:
                    const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
            subtitle: Text(job['customer']?['name'] ?? '-',
                style: const TextStyle(fontSize: 12)),
            trailing: const Icon(Icons.chevron_right, size: 16),
            onTap: () {
              Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (_) => JobDetailScreen(jobId: job['id'])));
            },
          ),
        );
      }).toList(),
    );
  }
}
