# Analisis Kesiapan Project (Production Readiness)

**Tanggal:** 19 Januari 2026
**Project:** ISP-Jabbar23 (Sistem Manajemen ISP)

Dokumen ini berisi analisis menyeluruh mengenai status pengembangan aplikasi, fitur yang siap digunakan, serta komponen software/hardware yang masih diperlukan sebelum _Full Production_.

## 1. Status Pengembangan Software

Secara keseluruhan, aplikasi berada dalam status **BETA (Siap Administrasi / Pencatatan)**.

### ✅ Fitur Siap (Ready)

Fitur-fitur Core Aplikasi sudah berfungsi dengan baik untuk kebutuhan administrasi manual:

1.  **User & Role Management:** Pembagian hak akses (Super Admin, Admin, Sales, Finance, NOC, Warehouse, HRD) sudah berfungsi dengan permission yang spesifik.
2.  **Database Pelanggan (CRM):** Pencatatan data lengkap pelanggan, paket berlangganan, dan status aktif/non-aktif.
3.  **Billing System:** Pembuatan Invoice (Tagihan), pencatatan pembayaran manual, dan laporan keuangan dasar.
4.  **Inventory Management:** Pencatatan stok barang (Router, Kabel, Modem), Supplier, dan Aset perusahaan.
5.  **Ticketing System:** Modul helpdesk untuk mencatat keluhan pelanggan.
6.  **Dashboard:** Statistik visual per role sudah berjalan.
7.  **Bahasa:** Interface menu utama sudah menggunakan Bahasa Indonesia.

### ⚠️ Fitur Belum Siap (Perlu Integrasi)

Fitur-fitur ini "ada" secara tampilan, namun logic di belakangnya masih bersifat **Simulasi (Dummy)** dan belum terhubung ke alat/pihak ketiga sebenarnya:

| Fitur                  | Status Saat Ini                                                            | Yang Harus Dilakukan                                                                                        |
| :--------------------- | :------------------------------------------------------------------------- | :---------------------------------------------------------------------------------------------------------- |
| **Integrasi MikroTik** | _Placeholder._ Tombol sync/test hanya simulasi sukses.                     | Install library `routeros-api-php`. Buat logic koneksi API ke Router fisik untuk tarik data user/interface. |
| **WhatsApp Gateway**   | _Offline._ Pesan tersimpan di database tapi tidak terkirim ke WA user.     | Integrasi API Vendor (contoh: Fonnte/Wablas). Buat Service Class untuk kirim pesan real-time.               |
| **Payment Gateway**    | _Partial._ Library Midtrans ada, tapi callback handler belum fully tested. | Setup akun Merchant Midtrans/Xendit. Konfigurasi Server Key & Callback URL.                                 |
| **OLT Management**     | _CRUD Only._ Hanya mencatat data OLT, belum bisa configure OLT via web.    | Butuh koneksi Telnet/SSH atau SNMP ke perangkat OLT fisik.                                                  |

---

## 2. Kebutuhan Persiapan (Pre-Requisites)

Jika ingin melangkah ke Production, berikut hal-hal yang harus disiapkan:

### A. Software & Layanan Pihak Ketiga (API)

1.  **WhatsApp Gateway API:**
    - Rekomendasi: **Fonnte** (Murah/Gratis terbatas, populer di Indo) atau **Wablas**.
    - _Biaya:_ ~Rp 50rb - 100rb/bulan.
    - _Fungsi:_ Kirim tagihan otomatis, notifikasi gangguan.
2.  **Payment Gateway Account:**
    - Rekomendasi: **Midtrans** atau **Xendit** atau **Tripay**.
    - _Syarat:_ KTP/NPWP Perusahaan.
    - _Fungsi:_ Auto-verifikasi pembayaran via Virtual Account/QRIS.
3.  **Maps API (Opsional):**
    - **Google Maps API Key** (JavaScript Maps & Geocoding).
    - _Fungsi:_ Menampilkan peta lokasi pelanggan dan tracking teknisi yang akurat. Jika tidak, bisa pakai OpenStreetMap (gratis tapi kurang detail).

### B. Hardware (Perangkat Keras)

1.  **MikroTik Router Utama:**
    - Wajib memiliki **IP Public Statis** (atau VPN Tunneling jika IP Private) agar Server VPS bisa mengakses Router API.
    - Pastikan port API (8728) terbuka untuk IP VPS.
2.  **Server / VPS:**
    - Sudah tersedia (IP: 72.62.124.123).
    - _Rekomendasi:_ Lakukan backup rutin database (Automated Backup) ke Cloud Storage (Google Drive/S3).
3.  **Printer:**
    - Printer Thermal (Bluetooth/USB) 58mm/80mm untuk admin jika melayani pembayaran tunai di loket.

### C. Sumber Daya Manusia (SOP)

1.  **Admin:** Harus mulai input data Master (Paket Internet, Data Teknisi, Data Pelanggan Existing).
2.  **NOC/Teknisi:** Perlu training cara input laporan instalasi via menu "Work Orders" atau "Installation Reports".

---

## 3. Rekomendasi Roadmap (Action Plan)

Kami menyarankan peluncuran bertahap:

### TAHAP 1: Go-Live Administrasi (Minggu ini)

    *   Gunakan aplikasi untuk mencatat pelanggan dan tagihan secara manual.
    *   Admin input data.
    *   Lupakan dulu integrasi Mikrotik/WA. Fokus ke kerapihan data.

### TAHAP 2: Integrasi Notifikasi (Minggu Depan)

    *   Hubungkan API WhatsApp.
    *   User mulai menerima tagihan via WA.

### TAHAP 3: Full Automation (Bulan Depan)

    *   Hubungkan API MikroTik.
    *   Fitur Isolir Otomatis dijalankan.
