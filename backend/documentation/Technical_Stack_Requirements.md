# COMPLETE TECHNOLOGY STACK & REQUIREMENTS

_Blueprint Teknis untuk Tim IT / Developer_

Dokumen ini adalah daftar belanja teknologi yang kita butuhkan. Tidak ada "abu-abu", semua spesifik.

---

## 1. SOFTWARE STACK (THE BRAIN)

### A. Core Systems (Aplikasi Utama)

1.  **ERP & Backend Bisnis:**
    - **Software:** **ERPNext v15** (Versi terbaru, stabil).
    - **Framework:** Frappe Framework (Python).
    - **Database:** **MariaDB 10.6+** (Standard wajib ERPNext).
    - **Cache:** Redis (Untuk mempercepat loading menu).

2.  **Network Authentication (AAA):**
    - **Software:** **FreeRADIUS 3.x**.
    - **UI Management:** **daloRADIUS** (Web Interface based on PHP).
    - **Database:** **MySQL / MariaDB** (Radius sangat cepat di sini).
    - _Kenapa bukan PostgreSQL?_ Karena daloRADIUS native-nya optimal di MySQL.

3.  **Inventory & Asset:**
    - **Software:** **InvenTree**.
    - **Framework:** Django (Python).
    - **Database:** **PostgreSQL 14+** (InvenTree lebih stabil pakai Postgres).

---

### B. Middleware (Jembatan Integrasi)

_Aplikasi custom yang kita bangun sendiri untuk menghubungkan ketiganya._

1.  **Backend API (The Traffic Controller):**
    - **Language:** **Node.js (NestJS)** atau **PHP (Laravel 11)**.
    - _Rekomendasi:_ **Laravel 11**.
    - _Alasan:_ Library untuk koneksi ke Mikrotik (RouterOS API) paling lengkap dan matang di komunitas PHP. Integrasi ke Payment Gateway Indonesia (Midtrans/Xendit) juga sangat mudah.

2.  **Database Middleware:**
    - **Type:** **MySQL/MariaDB**.
    - _Fungsi:_ Menyimpan data "Temporary" saat sinkronisasi, dan data user Reseller Portal.

---

### C. Frontend Clients (Wajah Aplikasi)

1.  **Mobile App Teknisi & Pelanggan:**
    - **Tech:** **Flutter** (Google).
    - _Alasan:_ Bikin 1 kodingan, langsung jadi aplikasi **Android (APK)** dan **iOS (IPA)**. Hemat biaya develop 50%.
    - _Fitur Wajib:_ Camera Access (Scan QR), GPS Location (Absensi/Map), Signature Pad.

2.  **Reseller Web Portal:**
    - **Tech:** **React.js** atau **Blade Template (Laravel)**.
    - _Alasan:_ Ringan, cepat dibuka di browser HP kentang milik agen voucher.

---

## 2. HARDWARE STACK (THE BODY)

### A. Server Cloud (Sudah dibahas di step sebelumnya)

- **Operating System:** **Ubuntu 22.04 LTS** (Server Standard Industry). Jangan pakai Windows Server (berat & mahal lisensi).
- **Web Server:** **Nginx** (Jauh lebih kuat menahan ribuan request dibanding Apache).

### B. Perangkat Jaringan (Wajib Punya di NOC)

1.  **Router Utama (Core Router):**
    - **Merk:** **MikroTik Cloud Core Router (CCR)** series.
    - _Tipe:_ CCR1009 atau CCR2004 (Minimal).
    - _Fungsi:_ Menangani traffic routing & komunikasi ke Server Radius.
2.  **OLT (Optical Line Terminal) - Untuk Fiber Optic:**
    - **Merk:** ZTE C320 atau Huawei MA5608T.
    - _Fungsi:_ Pusat kabel Fiber Optik yang lari ke rumah pelanggan.
    - _Integrasi:_ Middleware kita nanti akan menembak perintah "Telnet" ke OLT ini untuk aktivasi modem baru.

### C. Perangkat Lapangan (Customer Premise Equipment - CPE)

1.  **Modem / ONU:**
    - **Merk:** ZTE F609 / Huawei HG8245 (Umum dipakai).
    - _Syarat:_ Harus support **TR-069** protocol (Supaya bisa di-remote setting dari kantor).

---

## 3. SUMMARY DATABASE ARCHITECTURE (UNIFIED MYSQL)

Demi kemudahan maintenance (Solo Dev), kita seragamkan semua ke **MySQL / MariaDB**.

1.  `db_erpnext` -> **MariaDB** (Bawaan)
2.  `db_radius` -> **MariaDB** (Compatible)
3.  `db_inventree` -> **MariaDB** (Kita config agar pakai MySQL backend, bukan Postgres)
4.  `db_middleware` -> **MariaDB** (Laravel)

**Keuntungan:** Bapak cukup maintain **SATU** jenis database engine. Backup lebih gampang, error handling lebih familiar.

**Tantangan:** Backup Strategy.

- Kita harus setup **Automated Backup Script** yang mem-backup keempat database ini setiap jam 3 pagi ke Cloud Storage (Google Drive / AWS S3) secara bersamaan.

---

## APAKAH HARDWARE KOMPUTER KANTOR PERLU UPGRADE?

Tidak perlu spek gaming.

- **RAM 8GB** standar untuk Admin (biar buka Chrome banyak tab lancar).
- **Monitor:** Disarankan ukuran **24 inch** (Full HD) agar dashboard ERPNext yang lebar bisa terlihat lega.

Apakah Anda setuju dengan Stack Teknologi ini (Python/Django/Frappe + PHP + Flutter)?
Atau tim IT Bapak punya keahlian khusus bahasa lain (misal Golang / Java)?
