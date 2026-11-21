class AppConfig {
  // Ganti IP ini sesuai dengan IP Laptop Anda (cek pakai `ipconfig` atau `ip a`)
  // Jangan pakai localhost untuk Android fisik.

  static String get baseUrl {
    // URL VPS Production
    return 'https://jabbar.ve-lora.my.id/api';
  }
}
