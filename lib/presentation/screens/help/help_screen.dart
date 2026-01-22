import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/widgets/app_dialog.dart';

class HelpScreen extends StatelessWidget {
  const HelpScreen({super.key});

  void _launchWhatsApp(BuildContext context) async {
    // TODO: Ganti dengan nomor WhatsApp CS yang benar
    const phone = '6281234567890';
    const message = 'Halo, saya butuh bantuan terkait layanan ISP Jabbar.';
    final url = Uri.parse('https://wa.me/$phone?text=${Uri.encodeComponent(message)}');

    try {
      if (await canLaunchUrl(url)) {
        await launchUrl(url, mode: LaunchMode.externalApplication);
      } else {
        if (context.mounted) {
          AppDialog.showError(
            context,
            title: 'Tidak Dapat Membuka WhatsApp',
            message: 'Pastikan WhatsApp sudah terinstall di perangkat Anda.',
          );
        }
      }
    } catch (e) {
      if (context.mounted) {
        AppDialog.showError(
          context,
          title: 'Gagal',
          message: 'Tidak dapat membuka WhatsApp.',
        );
      }
    }
  }

  void _launchPhone(BuildContext context) async {
    // TODO: Ganti dengan nomor telepon CS yang benar
    final url = Uri.parse('tel:0211234567');

    try {
      if (await canLaunchUrl(url)) {
        await launchUrl(url);
      } else {
        if (context.mounted) {
          AppDialog.showError(
            context,
            title: 'Tidak Dapat Menelepon',
            message: 'Silakan hubungi CS di nomor:\n021-1234-567',
          );
        }
      }
    } catch (e) {
      if (context.mounted) {
        AppDialog.showError(
          context,
          title: 'Gagal',
          message: 'Tidak dapat melakukan panggilan.',
        );
      }
    }
  }

  void _launchEmail(BuildContext context) async {
    final Uri emailLaunchUri = Uri(
      scheme: 'mailto',
      path: 'support@ispjabbar.com',
      query: _encodeQueryParameters(<String, String>{
        'subject': 'Bantuan ISP Jabbar',
      }),
    );

    try {
      if (await canLaunchUrl(emailLaunchUri)) {
        await launchUrl(emailLaunchUri);
      } else {
        if (context.mounted) {
          AppDialog.showError(
            context,
            title: 'Tidak Dapat Membuka Email',
            message: 'Pastikan aplikasi email sudah terinstall.',
          );
        }
      }
    } catch (e) {
      if (context.mounted) {
        AppDialog.showError(
          context,
          title: 'Gagal',
          message: 'Tidak dapat membuka aplikasi email.',
        );
      }
    }
  }

  String? _encodeQueryParameters(Map<String, String> params) {
    return params.entries
        .map((MapEntry<String, String> e) =>
            '${Uri.encodeComponent(e.key)}=${Uri.encodeComponent(e.value)}')
        .join('&');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.surface,
      appBar: AppBar(
        title: const Text('Bantuan'),
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: Colors.white,
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header Card
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                gradient: AppColors.primaryGradient,
                borderRadius: BorderRadius.circular(20),
              ),
              child: Column(
                children: [
                  const Icon(
                    Icons.support_agent,
                    size: 64,
                    color: Colors.white,
                  ),
                  const SizedBox(height: 16),
                  const Text(
                    'Butuh Bantuan?',
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Tim kami siap membantu Anda\n24 jam sehari, 7 hari seminggu',
                    textAlign: TextAlign.center,
                    style: TextStyle(
                      fontSize: 14,
                      color: Colors.white.withOpacity(0.8),
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 24),

            const Text(
              'Hubungi Kami',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppColors.textPrimary,
              ),
            ),

            const SizedBox(height: 16),

            // WhatsApp Card
            _buildContactCard(
              context: context,
              icon: Icons.chat,
              iconColor: const Color(0xFF25D366),
              title: 'WhatsApp',
              subtitle: 'Chat langsung dengan CS kami',
              onTap: () => _launchWhatsApp(context),
            ),

            // Phone Card
            _buildContactCard(
              context: context,
              icon: Icons.phone,
              iconColor: AppColors.primaryBlue,
              title: 'Telepon',
              subtitle: '021-1234-567',
              onTap: () => _launchPhone(context),
            ),

            // Email Card
            _buildContactCard(
              context: context,
              icon: Icons.email_outlined,
              iconColor: AppColors.warning,
              title: 'Email',
              subtitle: 'support@ispjabbar.com',
              onTap: () => _launchEmail(context),
            ),

            const SizedBox(height: 24),

            const Text(
              'FAQ (Pertanyaan Umum)',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppColors.textPrimary,
              ),
            ),

            const SizedBox(height: 16),

            _buildFaqItem(
              question: 'Bagaimana cara bayar tagihan?',
              answer: 'Anda dapat membayar tagihan melalui transfer bank, e-wallet, atau langsung ke kantor kami.',
            ),

            _buildFaqItem(
              question: 'Internet saya lambat, apa yang harus dilakukan?',
              answer: 'Coba restart modem Anda. Jika masih bermasalah, laporkan gangguan melalui menu Lapor Gangguan.',
            ),

            _buildFaqItem(
              question: 'Bagaimana cara upgrade paket?',
              answer: 'Hubungi customer service kami melalui WhatsApp atau telepon untuk informasi upgrade paket.',
            ),

            const SizedBox(height: 32),
          ],
        ),
      ),
    );
  }

  Widget _buildContactCard({
    required BuildContext context,
    required IconData icon,
    required Color iconColor,
    required String title,
    required String subtitle,
    required VoidCallback onTap,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.04),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: ListTile(
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        leading: Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(
            color: iconColor.withOpacity(0.1),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(icon, color: iconColor),
        ),
        title: Text(
          title,
          style: const TextStyle(
            fontWeight: FontWeight.w600,
            color: AppColors.textPrimary,
          ),
        ),
        subtitle: Text(
          subtitle,
          style: const TextStyle(
            color: AppColors.textSecondary,
            fontSize: 12,
          ),
        ),
        trailing: Icon(
          Icons.arrow_forward_ios,
          size: 16,
          color: AppColors.textSecondary.withOpacity(0.5),
        ),
        onTap: onTap,
      ),
    );
  }

  Widget _buildFaqItem({
    required String question,
    required String answer,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.04),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: ExpansionTile(
        tilePadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
        childrenPadding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
        title: Text(
          question,
          style: const TextStyle(
            fontWeight: FontWeight.w600,
            color: AppColors.textPrimary,
            fontSize: 14,
          ),
        ),
        children: [
          Text(
            answer,
            style: const TextStyle(
              color: AppColors.textSecondary,
              fontSize: 14,
              height: 1.5,
            ),
          ),
        ],
      ),
    );
  }
}
