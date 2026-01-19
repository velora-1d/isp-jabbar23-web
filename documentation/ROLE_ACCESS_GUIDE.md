# 📋 DOKUMENTASI ROLE & HAK AKSES SISTEM

## ISP Jabbar - Management System

---

**Dokumen Versi:** 1.0  
**Tanggal:** 19 Januari 2026  
**Tujuan:** Panduan untuk HRD dalam menentukan role setiap karyawan

---

## 📊 RINGKASAN ROLE

| No  | Role        | Target User                         | Jumlah Menu    |
| --- | ----------- | ----------------------------------- | -------------- |
| 1   | Super Admin | Owner/Direktur, IT Manager          | 46 menu (100%) |
| 2   | Admin       | Manager Operasional, Supervisor     | 42 menu (~91%) |
| 3   | Sales       | Tim Sales, Customer Service         | 18 menu        |
| 4   | Finance     | Bendahara, Akuntan                  | 14 menu        |
| 5   | NOC         | Admin Jaringan, Koordinator Teknisi | 22 menu        |
| 6   | Warehouse   | Admin Gudang, Staf Logistik         | 5 menu         |
| 7   | HRD         | Manager HRD, Staf Personalia        | 5 menu         |

---

## 🔐 DETAIL SETIAP ROLE

---

### 1️⃣ SUPER ADMIN

**Deskripsi:**  
Role dengan akses penuh ke seluruh sistem tanpa pembatasan. Hanya diberikan kepada pemilik perusahaan atau pengelola IT senior yang bertanggung jawab atas keamanan sistem.

**Target User:**

- Owner/Direktur Perusahaan
- IT Manager/System Administrator

**Akses Menu:** SEMUA (46 Menu)

**Fitur Eksklusif (Hanya Super Admin):**
| Menu | Fungsi |
|------|--------|
| General Settings | Mengatur nama perusahaan, logo, timezone, dan konfigurasi global sistem |
| Audit Logs | Melihat rekam jejak aktivitas seluruh user (siapa melakukan apa, kapan) |
| Backup & Restore | Mencadangkan database dan memulihkan data jika terjadi kerusakan |
| API Management | Mengelola integrasi dengan sistem eksternal (Midtrans, Fonnte, dll) |

**Catatan Keamanan:**  
⚠️ Role ini dapat menghapus data permanen dan mengubah pengaturan kritis sistem. Berikan hanya kepada orang yang 100% dipercaya.

---

### 2️⃣ ADMIN

**Deskripsi:**  
Role untuk manager operasional yang membutuhkan akses ke hampir semua fitur untuk pengawasan harian, TANPA akses ke pengaturan sistem yang dapat membahayakan data.

**Target User:**

- Manager Operasional
- Supervisor/Kepala Cabang
- Wakil Direktur

**Akses Menu:**

| Kategori        | Menu                 | Fungsi                                |
| --------------- | -------------------- | ------------------------------------- |
| **CRM & Sales** |                      |                                       |
|                 | Leads/Prospects      | Mengelola calon pelanggan potensial   |
|                 | Customers            | Mengelola data pelanggan aktif        |
|                 | Contracts            | Mengelola kontrak berlangganan        |
|                 | Partners             | Mengelola mitra/reseller              |
| **Billing**     |                      |                                       |
|                 | Invoices             | Membuat dan mengelola tagihan         |
|                 | Payments             | Mencatat dan memverifikasi pembayaran |
|                 | Recurring Billing    | Mengatur tagihan bulanan otomatis     |
|                 | Proforma Invoice     | Membuat invoice penawaran             |
|                 | Credit Notes         | Mengelola nota kredit/refund          |
|                 | Financial Reports    | Melihat laporan keuangan              |
| **Network**     |                      |                                       |
|                 | Net Monitor          | Memantau status jaringan (ping)       |
|                 | OLT Management       | Mengelola perangkat OLT               |
|                 | ODP Management       | Mengelola titik distribusi optik      |
|                 | Routers/Mikrotik     | Mengelola router                      |
|                 | IP Management        | Mengelola alokasi IP Address          |
|                 | Bandwidth            | Mengatur limit bandwidth pelanggan    |
|                 | Topology Map         | Melihat peta topologi jaringan        |
| **Support**     |                      |                                       |
|                 | Tickets              | Mengelola tiket keluhan pelanggan     |
|                 | Customer Messages    | Kirim pesan ke pelanggan              |
|                 | Knowledge Base       | Mengelola artikel bantuan             |
|                 | SLA Management       | Mengatur standar level layanan        |
| **Field Ops**   |                      |                                       |
|                 | Technicians          | Mengelola data teknisi                |
|                 | Work Orders          | Membuat perintah kerja (SPK)          |
|                 | Scheduling           | Mengatur jadwal teknisi               |
|                 | GPS Tracking         | Melacak lokasi teknisi real-time      |
|                 | Installation Reports | Laporan hasil instalasi               |
| **Inventory**   |                      |                                       |
|                 | Stock Items          | Mengelola stok barang                 |
|                 | Assets               | Mengelola aset perusahaan             |
|                 | Vendors              | Mengelola supplier                    |
|                 | Purchase Orders      | Membuat pesanan pembelian             |
| **HRD**         |                      |                                       |
|                 | Employees            | Mengelola data karyawan               |
|                 | Attendance           | Mengelola absensi                     |
|                 | Payroll              | Mengelola penggajian                  |
|                 | Leave Management     | Mengelola cuti karyawan               |
| **Reports**     |                      |                                       |
|                 | Revenue Report       | Laporan pendapatan                    |
|                 | Customer Report      | Laporan statistik pelanggan           |
|                 | Network Report       | Laporan performa jaringan             |
|                 | Commission Report    | Laporan komisi reseller               |
| **Marketing**   |                      |                                       |
|                 | Campaigns            | Mengelola kampanye marketing          |
|                 | Promotions           | Mengelola diskon/voucher              |
|                 | Referral Program     | Mengelola program referral            |
| **Settings**    |                      |                                       |
|                 | Packages             | Mengelola paket internet              |

**TIDAK DAPAT AKSES:**

- ❌ General Settings
- ❌ Audit Logs
- ❌ Backup & Restore
- ❌ API Management

---

### 3️⃣ SALES

**Deskripsi:**  
Role untuk tim penjualan dan customer service. Fokus pada akuisisi pelanggan baru, penanganan keluhan, dan aktivitas marketing.

**Target User:**

- Sales Executive
- Customer Service Representative
- Marketing Staff

**Akses Menu:**

| Kategori        | Menu              | Fungsi                                 |
| --------------- | ----------------- | -------------------------------------- |
| **Dashboard**   | Dashboard         | Melihat statistik penjualan            |
| **CRM & Sales** |                   |                                        |
|                 | Leads/Prospects   | Menambah dan mengelola calon pelanggan |
|                 | Customers         | Melihat dan menambah data pelanggan    |
|                 | Contracts         | Membuat kontrak berlangganan baru      |
|                 | Partners          | Mengelola data mitra/reseller          |
| **Support**     |                   |                                        |
|                 | Tickets           | Membuat dan menangani tiket keluhan    |
|                 | Customer Messages | Berkomunikasi dengan pelanggan         |
|                 | Knowledge Base    | Mencari solusi masalah pelanggan       |
| **Marketing**   |                   |                                        |
|                 | Campaigns         | Membuat dan mengelola kampanye         |
|                 | Promotions        | Membuat voucher/diskon                 |
|                 | Referral Program  | Mengelola kode referral                |
| **Settings**    |                   |                                        |
|                 | Packages          | Melihat daftar paket (untuk penawaran) |

**TIDAK DAPAT AKSES:**

- ❌ Billing & Finance (hanya lihat packages)
- ❌ Network (teknis)
- ❌ Field Operations
- ❌ Inventory
- ❌ HRD
- ❌ System Settings

---

### 4️⃣ FINANCE

**Deskripsi:**  
Role untuk bagian keuangan. Fokus pada penagihan, verifikasi pembayaran, dan pelaporan keuangan.

**Target User:**

- Bendahara
- Akuntan
- Staff Finance

**Akses Menu:**

| Kategori              | Menu              | Fungsi                                   |
| --------------------- | ----------------- | ---------------------------------------- |
| **Dashboard**         | Dashboard         | Melihat statistik keuangan               |
| **Billing & Finance** |                   |                                          |
|                       | Invoices          | Membuat tagihan pelanggan                |
|                       | Payments          | Verifikasi dan catat pembayaran          |
|                       | Recurring Billing | Atur tagihan bulanan otomatis            |
|                       | Proforma Invoice  | Buat invoice penawaran                   |
|                       | Credit Notes      | Proses refund/nota kredit                |
|                       | Financial Reports | Lihat laporan keuangan lengkap           |
|                       | Payment Gateways  | Konfigurasi Midtrans/pembayaran online   |
| **CRM**               |                   |                                          |
|                       | Customers         | Melihat data pelanggan (untuk penagihan) |
| **HRD**               |                   |                                          |
|                       | Payroll           | Proses penggajian karyawan               |
| **Inventory**         |                   |                                          |
|                       | Purchase Orders   | Approval pesanan pembelian               |
| **Settings**          |                   |                                          |
|                       | Packages          | Melihat harga paket                      |

**TIDAK DAPAT AKSES:**

- ❌ Leads/Prospects
- ❌ Network
- ❌ Field Operations
- ❌ Marketing (kecuali promotions)
- ❌ System Settings

---

### 5️⃣ NOC (Network Operations Center)

**Deskripsi:**  
Role untuk tim teknis yang menangani operasional jaringan dan koordinasi teknisi lapangan.

**Target User:**

- Admin NOC/Network Administrator
- Koordinator Teknisi
- Supervisor Technical Support

**Akses Menu:**

| Kategori             | Menu                 | Fungsi                                    |
| -------------------- | -------------------- | ----------------------------------------- |
| **Dashboard**        | Dashboard            | Monitoring status jaringan                |
| **Network**          |                      |                                           |
|                      | Net Monitor          | Memantau uptime perangkat (ping)          |
|                      | OLT Management       | Konfigurasi perangkat OLT                 |
|                      | ODP Management       | Mapping titik distribusi FO               |
|                      | Routers/Mikrotik     | Konfigurasi router pelanggan              |
|                      | IP Management        | Alokasi IP Address                        |
|                      | Bandwidth            | Setting limit bandwidth                   |
|                      | Topology Map         | Visualisasi topologi jaringan             |
| **Field Operations** |                      |                                           |
|                      | Technicians          | Mengelola data teknisi                    |
|                      | Work Orders          | Assign tugas ke teknisi                   |
|                      | Scheduling           | Jadwalkan kunjungan teknisi               |
|                      | GPS Tracking         | Pantau lokasi teknisi real-time           |
|                      | Installation Reports | Review laporan instalasi                  |
| **Support**          |                      |                                           |
|                      | Tickets              | Tangani tiket teknis                      |
|                      | Knowledge Base       | Kelola artikel troubleshooting            |
|                      | SLA Management       | Monitor SLA jaringan                      |
| **CRM**              |                      |                                           |
|                      | Customers            | Lihat data pelanggan (untuk troubleshoot) |

**TIDAK DAPAT AKSES:**

- ❌ Billing & Finance
- ❌ Inventory
- ❌ HRD
- ❌ Marketing
- ❌ System Settings

---

### 6️⃣ WAREHOUSE

**Deskripsi:**  
Role untuk bagian gudang/logistik. Fokus pada manajemen stok barang dan aset perusahaan.

**Target User:**

- Admin Gudang
- Staff Logistik
- Storekeeper

**Akses Menu:**

| Kategori      | Menu            | Fungsi                                   |
| ------------- | --------------- | ---------------------------------------- |
| **Dashboard** | Dashboard       | Melihat ringkasan stok                   |
| **Inventory** |                 |                                          |
|               | Stock Items     | Kelola stok modem/kabel/router           |
|               | Assets          | Kelola aset perusahaan (kendaraan, alat) |
|               | Vendors         | Kelola data supplier                     |
|               | Purchase Orders | Buat pesanan pembelian barang            |

**TIDAK DAPAT AKSES:**

- ❌ CRM & Sales
- ❌ Billing & Finance
- ❌ Network
- ❌ Support
- ❌ Field Operations
- ❌ HRD
- ❌ Marketing
- ❌ Settings

---

### 7️⃣ HRD

**Deskripsi:**  
Role untuk bagian personalia/SDM. Fokus pada manajemen karyawan, absensi, dan penggajian.

**Target User:**

- Manager HRD
- Staff Personalia
- Admin Payroll

**Akses Menu:**

| Kategori      | Menu             | Fungsi                       |
| ------------- | ---------------- | ---------------------------- |
| **Dashboard** | Dashboard        | Ringkasan data karyawan      |
| **HRD**       |                  |                              |
|               | Employees        | Kelola data karyawan lengkap |
|               | Attendance       | Kelola absensi harian        |
|               | Payroll          | Proses slip gaji bulanan     |
|               | Leave Management | Kelola pengajuan cuti        |

**TIDAK DAPAT AKSES:**

- ❌ CRM & Sales
- ❌ Billing & Finance (kecuali lihat payroll)
- ❌ Network
- ❌ Support
- ❌ Field Operations
- ❌ Inventory
- ❌ Marketing
- ❌ Settings

---

## 📋 MATRIKS AKSES CEPAT

```
┌─────────────────────────┬────────┬───────┬───────┬─────────┬─────┬───────────┬─────┐
│ Menu                    │ Super  │ Admin │ Sales │ Finance │ NOC │ Warehouse │ HRD │
├─────────────────────────┼────────┼───────┼───────┼─────────┼─────┼───────────┼─────┤
│ Dashboard               │   ✓    │   ✓   │   ✓   │    ✓    │  ✓  │     ✓     │  ✓  │
│ Leads/Prospects         │   ✓    │   ✓   │   ✓   │         │     │           │     │
│ Customers               │   ✓    │   ✓   │   ✓   │    ✓    │  ✓  │           │     │
│ Contracts               │   ✓    │   ✓   │   ✓   │         │     │           │     │
│ Partners                │   ✓    │   ✓   │   ✓   │         │     │           │     │
│ Invoices                │   ✓    │   ✓   │       │    ✓    │     │           │     │
│ Payments                │   ✓    │   ✓   │       │    ✓    │     │           │     │
│ Recurring Billing       │   ✓    │   ✓   │       │    ✓    │     │           │     │
│ Proforma Invoice        │   ✓    │   ✓   │       │    ✓    │     │           │     │
│ Credit Notes            │   ✓    │   ✓   │       │    ✓    │     │           │     │
│ Financial Reports       │   ✓    │   ✓   │       │    ✓    │     │           │     │
│ Payment Gateways        │   ✓    │       │       │    ✓    │     │           │     │
│ Network Monitoring      │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ OLT Management          │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ ODP Management          │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Routers/Mikrotik        │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ IP Management           │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Bandwidth               │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Topology Map            │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Tickets                 │   ✓    │   ✓   │   ✓   │         │  ✓  │           │     │
│ Customer Messages       │   ✓    │   ✓   │   ✓   │         │     │           │     │
│ Knowledge Base          │   ✓    │   ✓   │   ✓   │         │  ✓  │           │     │
│ SLA Management          │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Technicians             │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Work Orders             │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Scheduling              │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ GPS Tracking            │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Installation Reports    │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Stock Items             │   ✓    │   ✓   │       │         │     │     ✓     │     │
│ Assets                  │   ✓    │   ✓   │       │         │     │     ✓     │     │
│ Vendors                 │   ✓    │   ✓   │       │         │     │     ✓     │     │
│ Purchase Orders         │   ✓    │   ✓   │       │    ✓    │     │     ✓     │     │
│ Employees               │   ✓    │   ✓   │       │         │     │           │  ✓  │
│ Attendance              │   ✓    │   ✓   │       │         │     │           │  ✓  │
│ Payroll                 │   ✓    │   ✓   │       │    ✓    │     │           │  ✓  │
│ Leave Management        │   ✓    │   ✓   │       │         │     │           │  ✓  │
│ Campaigns               │   ✓    │   ✓   │   ✓   │         │     │           │     │
│ Promotions              │   ✓    │   ✓   │   ✓   │         │     │           │     │
│ Referral Program        │   ✓    │   ✓   │   ✓   │         │     │           │     │
│ Revenue Report          │   ✓    │   ✓   │       │    ✓    │     │           │     │
│ Customer Report         │   ✓    │   ✓   │       │    ✓    │     │           │     │
│ Network Report          │   ✓    │   ✓   │       │         │  ✓  │           │     │
│ Commission Report       │   ✓    │   ✓   │       │    ✓    │     │           │     │
│ Packages                │   ✓    │   ✓   │   ✓   │    ✓    │     │           │     │
│ General Settings        │   ✓    │       │       │         │     │           │     │
│ Audit Logs              │   ✓    │       │       │         │     │           │     │
│ Backup & Restore        │   ✓    │       │       │         │     │           │     │
│ API Management          │   ✓    │       │       │         │     │           │     │
└─────────────────────────┴────────┴───────┴───────┴─────────┴─────┴───────────┴─────┘
```

---

## 👤 AKUN DEMO UNTUK TESTING

| Role        | Email               | Password |
| ----------- | ------------------- | -------- |
| Super Admin | super@isp.local     | password |
| Admin       | admin@isp.local     | password |
| Sales       | sales@isp.local     | password |
| Finance     | finance@isp.local   | password |
| NOC         | noc@isp.local       | password |
| Warehouse   | warehouse@isp.local | password |
| HRD         | hrd@isp.local       | password |

---

## 📝 PANDUAN PENENTUAN ROLE

### Pertanyaan untuk HRD:

1. **Apakah karyawan ini perlu akses ke pengaturan sistem (backup, API)?**
    - Ya → Super Admin
    - Tidak → Lanjut pertanyaan 2

2. **Apakah karyawan ini perlu mengawasi seluruh operasional?**
    - Ya → Admin
    - Tidak → Lanjut pertanyaan 3

3. **Apa tugas utama karyawan ini?**
    - Mencari pelanggan baru / Handle komplain → Sales
    - Urus tagihan / Keuangan → Finance
    - Urus jaringan / Koordinasi teknisi → NOC
    - Urus stok barang / Logistik → Warehouse
    - Urus karyawan / Gaji → HRD

---

**Dokumen ini dapat digunakan sebagai acuan untuk:**

- Onboarding karyawan baru
- Audit hak akses berkala
- Permintaan perubahan role

---

_Dokumen dibuat oleh: Tim Development_  
_Terakhir diperbarui: 19 Januari 2026_
