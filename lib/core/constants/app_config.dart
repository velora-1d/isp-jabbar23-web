import 'dart:io';

class AppConfig {
  // Ganti IP ini sesuai dengan IP Laptop Anda (cek pakai `ipconfig` atau `ip a`)
  // Jangan pakai localhost untuk Android fisik.
  static const String _androidIp = '192.168.137.69';

  static String get baseUrl {
    if (Platform.isAndroid) {
      return 'http://$_androidIp:8000/api';
    }
    // Untuk Desktop (Linux) & iOS Simulator gunakan localhost
    return 'http://127.0.0.1:8000/api';
  }
}
