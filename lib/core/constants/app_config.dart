import 'dart:io';

class AppConfig {
  // Ganti IP ini sesuai dengan IP Laptop Anda (cek pakai `ipconfig` atau `ip a`)
  // Jangan pakai localhost untuk Android fisik.
  static const String _androidIp = '192.168.137.69';

  static String get baseUrl {
    // URL VPS Production
    return 'http://72.62.124.123/api';
  }
}
