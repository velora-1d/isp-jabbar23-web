import 'package:flutter/material.dart';
import '../../../core/constants/app_colors.dart';

enum AppDialogType {
  error,
  warning,
  success,
  maintenance,
  update,
}

class AppDialog {
  static Future<void> show({
    required BuildContext context,
    required String title,
    required String message,
    AppDialogType type = AppDialogType.error,
    String? buttonText,
    VoidCallback? onButtonPressed,
    bool dismissible = true,
  }) {
    IconData icon;
    Color iconColor;
    Color buttonColor;

    switch (type) {
      case AppDialogType.error:
        icon = Icons.error_outline_rounded;
        iconColor = AppColors.error;
        buttonColor = AppColors.error;
        break;
      case AppDialogType.warning:
        icon = Icons.warning_amber_rounded;
        iconColor = AppColors.warning;
        buttonColor = AppColors.warning;
        break;
      case AppDialogType.success:
        icon = Icons.check_circle_outline_rounded;
        iconColor = AppColors.success;
        buttonColor = AppColors.success;
        break;
      case AppDialogType.maintenance:
        icon = Icons.construction_rounded;
        iconColor = AppColors.warning;
        buttonColor = AppColors.primaryBlue;
        break;
      case AppDialogType.update:
        icon = Icons.system_update_rounded;
        iconColor = AppColors.primaryBlue;
        buttonColor = AppColors.primaryBlue;
        break;
    }

    return showDialog(
      context: context,
      barrierDismissible: dismissible,
      builder: (context) => Dialog(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(24),
        ),
        elevation: 16,
        child: Container(
          padding: const EdgeInsets.all(24),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(24),
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Animated Icon Container
              Container(
                width: 80,
                height: 80,
                decoration: BoxDecoration(
                  color: iconColor.withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: Icon(
                  icon,
                  size: 48,
                  color: iconColor,
                ),
              ),
              const SizedBox(height: 24),

              // Title
              Text(
                title,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: AppColors.textPrimary,
                ),
              ),
              const SizedBox(height: 12),

              // Message
              Text(
                message,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  fontSize: 14,
                  color: AppColors.textSecondary,
                  height: 1.5,
                ),
              ),
              const SizedBox(height: 24),

              // Button
              SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton(
                  onPressed: () {
                    Navigator.of(context).pop();
                    onButtonPressed?.call();
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: buttonColor,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16),
                    ),
                    elevation: 0,
                  ),
                  child: Text(
                    buttonText ?? 'Mengerti',
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  // Shorthand methods
  static Future<void> showError(
    BuildContext context, {
    String title = 'Oops!',
    required String message,
  }) {
    return show(
      context: context,
      title: title,
      message: message,
      type: AppDialogType.error,
    );
  }

  static Future<void> showNetworkError(BuildContext context) {
    return show(
      context: context,
      title: 'Koneksi Terputus',
      message: 'Tidak dapat terhubung ke server.\nPeriksa koneksi internet Anda dan coba lagi.',
      type: AppDialogType.error,
      buttonText: 'Coba Lagi',
    );
  }

  static Future<void> showMaintenance(BuildContext context) {
    return show(
      context: context,
      title: 'Sedang Maintenance',
      message: 'Sistem sedang dalam perbaikan.\nMohon tunggu beberapa saat dan coba lagi nanti.',
      type: AppDialogType.maintenance,
      dismissible: false,
      buttonText: 'OK',
    );
  }

  static Future<void> showUpdateRequired(
    BuildContext context, {
    VoidCallback? onUpdate,
  }) {
    return show(
      context: context,
      title: 'Update Tersedia',
      message: 'Versi baru aplikasi sudah tersedia.\nSilakan update untuk pengalaman terbaik.',
      type: AppDialogType.update,
      dismissible: false,
      buttonText: 'Update Sekarang',
      onButtonPressed: onUpdate,
    );
  }

  static Future<void> showDataNotFound(BuildContext context) {
    return show(
      context: context,
      title: 'Data Tidak Ditemukan',
      message: 'Data yang Anda cari tidak tersedia.\nSilakan coba lagi atau hubungi customer service.',
      type: AppDialogType.warning,
    );
  }

  static Future<void> showComingSoon(BuildContext context, {String? featureName}) {
    return show(
      context: context,
      title: 'Segera Hadir!',
      message: featureName != null
          ? 'Fitur "$featureName" sedang dalam pengembangan.\nNantikan update selanjutnya!'
          : 'Fitur ini sedang dalam pengembangan.\nNantikan update selanjutnya!',
      type: AppDialogType.update,
      buttonText: 'OK, Mengerti',
    );
  }
}
