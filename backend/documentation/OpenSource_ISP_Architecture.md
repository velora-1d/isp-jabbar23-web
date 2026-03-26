# INTEGRATED ISP SYSTEM ARCHITECTURE (OPEN SOURCE STACK)

_Combining ERPNext + daloRADIUS + InvenTree_

Dokumen ini adalah blueprint arsitektur untuk sistem ISP terpadu yang menggabungkan fungsi Bisnis (ERP), Teknis (NOC), dan Logistik (Warehouse).

---

## === BAGIAN 1: PILIHAN STRATEGI & LOGIKA BISNIS ===

Berdasarkan diskusi dan kebutuhan Anda, sistem ini dirancang dengan aturan kunci:

1.  **Multi-Channel Registration:**
    - _Sales/Reseller_ bisa input data prospek sendiri (Self-Service via Mobile/Web).
    - _Admin_ memiliki kontrol penuh untuk verifikasi dan input manual.
2.  **Mobile Field Operations:**
    - Teknisi lapangan dilengkapi Aplikasi Mobile untuk scan barcode alat saat instalasi.
    - Tujuannya: Akurasi data aset 100% (Serial Number Modern X ada di Rumah Pelanggan Y).
3.  **Hybrid Billing System (Prepaid & Postpaid):**
    - Medukung sistem **Prabayar** (Voucher/Bayar di depan baru aktif).
    - Mendukung sistem **Pascabayar** (Pakai dulu, invoice akhir bulan).

---

## === BAGIAN 2: STRUKTUR ORGANISASI & HAK AKSES (7 ROLES) ===

Sistem ini akan dioperasikan oleh 7 peran kunci dengan hak akses spesifik:

### 1. Sales & CS (Front Office)

- **Fokus:** Penjualan & Pelayanan.
- **Akses ERPNext:** Modul CRM (Lead, Opportunity), Penjualan (Sales Order).
- **Akses Lain:** View-Only Coverage Map.

### 2. Finance / Kasir (Back Office)

- **Fokus:** Arus Kas & Penagihan.
- **Akses ERPNext:** Modul Accounting (Invoice, Payment Entry, Bank Reconciliation).
- **Limitasi:** Tidak bisa mengubah konfigurasi teknis internet.

### 3. Admin Gudang (Logistics)

- **Fokus:** Aset Fisik & Stok.
- **Akses InvenTree:** Full Access (In/Out Stock, Stock Opname).
- **Akses ERPNext:** Stock Module & Buying (Purchase Orders).

### 4. Admin NOC (Technical Lead)

- **Fokus:** Kualitas Jaringan & Manajemen User Internet.
- **Akses daloRADIUS:** Full Access (User Management, Bandwidth Profiles, NAS).
- **Akses ERPNext:** Support Module (Ticketing).

### 5. Teknisi Lapangan (Field Ops)

- **Fokus:** Instalasi & Maintenance Fisik.
- **Tools:** **Mobile App Khusus** (Middleware).
- **Fitur App:** Terima SPK (Work Order), Scan Barcode Alat, Update Status Tiket, Upload Bukti Foto.

### 6. HR Customer (HRD Manager)

- **Fokus:** Manajemen Karyawan & Kinerjanya.
- **Akses ERPNext:** Modul HR & Payroll.
- **Fitur Kunci:**
  - _Attendance:_ Pantau absensi teknisi (GPS) & staff kantor.
  - _Payroll:_ Hitung gaji otomatis + **Komisi Sales** & **Insentif Pasang Baru**.
  - _KPI:_ Analisis performa karyawan berdasarkan data sistem.

### 7. Reseller / Mitra (External Partner)

- **Fokus:** Jualan di wilayah masing-masing (Sub-Distributor).
- **Tools:** **Reseller Portal** (Web/Mobile via Middleware).
- **Fitur Portal:**
  - _Register Sub-Customer:_ Daftarkan pelanggan baru di bawah akun mereka.
  - _Deposit System:_ Topup saldo untuk transaksi.
  - _Commission Dashboard:_ Cek bagi hasil/profit secara realtime.
  - _1st Layer Support:_ Cek status online/offline modem pelanggan mereka sendiri.

---

## === BAGIAN 3: ALUR KERJA TERINTEGRASI (THE CORE FLOW) ===

Berikut adalah perjalanan data dari Sales hingga Uang Masuk:

### 1. Registrasi & Penjualan (Sales Phase)

- **Aktor:** Salesman / Reseller / Admin
- **System:** ERPNext (CRM Module) / Reseller Portal
- **Alur:**
  1.  Input **Lead** (Nama, Alamat, No HP, Koordinat GPS).
  2.  Pilih **Paket Internet** (Product).
  3.  Pilih **Metode Bayar**: _Prepaid_ atau _Postpaid_.
  4.  System cek coverage area. Jika OK -> Convert Lead to **Customer**.
  5.  Customer menerima notifikasi "Pendaftaran Berhasil, Menunggu Jadwal Teknisi".

### 2. Work Order & Logistik (Provisioning Phase)

- **Aktor:** Admin Gudang & Teknisi
- **System:** InvenTree (via Mobile App) + ERPNext (Stock)
- **Alur:**
  1.  ERPNext mengeluarkan **Installation Order** ke Tim Teknis.
  2.  Teknisi datang ke Gudang mengambil Modem (CPE).
  3.  Admin Gudang melakukan **Stock Transfer**: `Main Warehouse` -> `Technician Bag` (Scan Barcode).
  4.  Teknisi berangkat ke lokasi.

### 3. Instalasi & Mapping Aset (Field Phase)

- **Aktor:** Teknisi
- **System:** Mobile App (Custom/InvenTree App)
- **Alur:**
  1.  Teknisi pasang kabel dan modem di rumah pelanggan.
  2.  **CRITICAL STEP:** Teknisi buka HP, scan QR Code Modem yang terpasang.
  3.  App mengirim data: "Serial Number `123456` terinstal di Customer `Budi`".
  4.  Status Aset di InvenTree berubah: `In Stock/Transit` -> `Installed/Deployed`.

### 4. Aktivasi Teknis (NOC Phase)

- **Aktor:** NOC Admin / Otomatis (API)
- **System:** daloRADIUS
- **Alur:**
  1.  Admin membuat/validasi akun PPPoE di daloRADIUS.
  2.  Profile Bandwidth diset sesuai Paket Internet yang dipilih di Sales (langkah 1).
  3.  MAC Address modem dikunci ke akun tersebut (Security).
  4.  Internet Pelanggan NYALA.

### 5. Billing Activation (Finance Phase)

- **Aktor:** System Automation
- **System:** ERPNext (Accounts)
- **Alur:**
  - **Jika Prepaid:** Invoice terbit -> Pelanggan Bayar -> Status Active. (Jika belum bayar, Internet masih terisolir/redirect page).
  - **Jika Postpaid:** Status Active langsung. Sistem mulai menghitung hari (prorata) untuk tagihan bulan depan.

---

## === BAGIAN 4: STRUKTUR INTEGRASI SISTEM (TECH STACK) ===

### 1. ERPNext (The Brain - Business Logic)

Berperan sebagai "Master Data" dan "Wajah Utama" bagi karyawan.

- **Modules:**
  - `CRM`: Lead, Opportunity, Customer Data.
  - `Selling`: Sales Order, Subscription Management.
  - `Accounting`: Invoices, Payments, Bank Reconciliation.
  - `HR`: Data Gaji Sales & Teknisi, Absensi, KPI, Komisi.

### 2. daloRADIUS + FreeRADIUS (The Enforcer - Network Logic)

Berperan sebagai "Polisi Lalu Lintas" internet.

- **Functions:**
  - `AAA`: Otentikasi username/password router pelanggan.
  - `Bandwidth Control`: Membatasi kecepatan (20Mbps, 50Mbps).
  - `Kick User`: Memutus koneksi jika kuota habis atau masa aktif lewat.

### 3. InvenTree (The Tracker - Asset Logic)

Berperan sebagai "Mata Elang" aset perusahaan.

- **Functions:**
  - `Serialized Stock`: Melacak riwayat unik setiap modem/router.
  - `Location tracking`: Tahu persis barang ada di gudang mana atau pelanggan siapa.
  - `QR Codes`: Memudahkan teknisi kerja cepat tanpa ketik manual.

---

## === BAGIAN 5: IMPLEMENTASI HYBRID BILLING ===

Fitur ini membutuhkan pengaturan khusus pada **Logika Isolir**:

**A. Skenario PREPAID (Voucher Style)**

1.  Invoice Terbit tanggal 1.
2.  Pelanggan BELUM Bayar.
3.  **H+0 (Jam 00:01):** System cek "Apakah Lunas?". Tidak.
4.  **Action:** API call ke daloRADIUS -> Ubah Group jadi "Isolir".
5.  Pelanggan bayar -> API call -> Ubah group jadi "Premium" -> Reconnect.

**B. Skenario POSTPAID (Tagihan Konvensional)**

1.  Invoice Terbit tanggal 1.
2.  Internet TETAP JALAN.
3.  Jatuh Tempo tanggal 20.
4.  Pelanggan belum bayar tanggal 21.
5.  **H+21:** Baru system melakukan isolir.

---

## === BAGIAN 6: ROADMAP PENGEMBANGAN ===

1.  **Fase Foundation (Minggu 1-4):**
    - Install ERPNext, daloRADIUS (FreeRADIUS), InvenTree di Server.
    - Pastikan 3 server ini hidup dan bisa diakses.
2.  **Fase Data (Minggu 5-6):**
    - Desain Database "Bridge" (Tabel penghubung antara ERP Customer IP <-> Radius Username).
    - Import Data Paket Internet ke ERPNext.
3.  **Fase Integrasi API (Minggu 7-10):**
    - Bikin Script: Saat Customer "Active" di ERP -> Create User di Radius.
    - Bikin Script: Saat Invoice "Overdue" di ERP -> Suspend User di Radius.
    - **Reseller Portal Development:** Mulai bangun portal MVP untuk Mitra.
4.  **Fase Mobile Apps (Minggu 11+):**
    - Implementasi Scanner Barcode utk Teknisi.

---

**Next Steps:**

1.  **Server Sizing:** Menentukan spesifikasi VPS/Server untuk menjalankan 3 sistem + Middleware.
2.  **Database Design:** Merancang skema database untuk Middleware (Laravel) sebagai penghubung.
