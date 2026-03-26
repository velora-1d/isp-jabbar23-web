# DOKUMEN ARSITEKTUR SISTEM ISP (ISP SYSTEM ARCHITECTURE)

_Based on Professional ISP Standards (Splynx Benchmark)_

---

## === BAGIAN 1: GAMBARAN UMUM MODUL CUSTOMER MANAGEMENT ===

**Tujuan & Filosofi:**
Modul Customer Management adalah "jantung" dari sistem ISP. Tujuannya bukan sekadar menyimpan data nama dan alamat, tetapi **mengorkestrasi hubungan kompleks** antara:

1.  **Layanan Teknis** (Internet, IP, Kecepatan, Router)
2.  **Kewajiban Finansial** (Tagihan, Saldo, Jatuh Tempo)
3.  **Status Operasional** (Aktif, Terisolir, Non-Aktif)

**Masalah yang Diselesaikan:**

- **Sinkronisasi Billing & Teknis:** Memastikan pelanggan yang tidak bayar otomatis terisolir tanpa intervensi manual.
- **Akurasi Pendapatan:** Mengelola prorata (hitungan hari), refund otomatis, dan recurring billing yang presisi.
- **Sentralisasi Data:** Menghindari data ganda antara tim Sales, Teknisi, dan Admin Keuangan.

**Kenapa ini Core?**
Tanpa modul ini, ISP hanyalah sekumpulan router tanpa cashflow. Modul ini adalah jembatan yang mengubah trafik internet menjadi uang.

---

## === BAGIAN 2: STRUKTUR HALAMAN (PAGE STRUCTURE) ===

Berikut adalah hierarki sitemap aplikasi ISP profesional:

### 1. Customer List Page (Index)

- **Fungsi:** Dashboard utama untuk melihat seluruh basis pelanggan.
- **Data Utama:** ID, Nama, Status (Aktif/Blocked/Inactive), Paket Utama, Saldo Terkini, Portal Login.
- **Aksi:** Filter (by Status, Router, Partner), Search Global (by IP, Nama, No HP), Quick Actions (Edit, Delete, Login as Client).

### 2. Customer Creation Wizard (Add New)

- **Fungsi:** Alur pendaftaran pelanggan baru yang terstandarisasi.
- **Tahapan:** Data Diri -> Lokasi/Geo -> Pilih Layanan (Service) -> Konfigurasi Perangkat (CPE).

### 3. Customer Detail Dashboard (The "Single Source of Truth")

Ini adalah halaman induk yang memiliki banyak Tab/Sub-halaman:

**A. Tab Information (Main Profile)**

- **Fungsi:** Pusat data administratif.
- **Data:** Status Akun, Geo-location (Maps), Koordinat, Kontak Billing, Partner/Reseller.
- **Aksi:** Ubah Status (Suspend/Activate), Reset Password Portal.

**B. Tab Services (Management Layanan)**

- **Fungsi:** Mengelola produk apa yang dibeli pelanggan.
- **Data:** Nama Paket (Internet/Voice), Harga, Kecepatan, IP Address, MAC Address Perangkat, Status Layanan (bisa beda dengan status user).
- **Aksi:** Upgrade/Downgrade Paket, Stop Layanan, Ganti Router, Set IP Statis.

**C. Tab Billing (Keuangan Personal)**

- **Fungsi:** Buku besar keuangan per pelanggan.
- **Data:** Account Balance (Saldo), Next Billing Date, Payment Method.
- **Sub-Menu:**
  - _Invoices:_ List tagihan (Paid/Unpaid/Overdue).
  - _Payments:_ Riwayat pembayaran masuk.
  - _Transactions:_ Mutasi detail (Debit/Credit).
- **Aksi:** Buat Invoice Manual, Input Pembayaran Manual, Refund (Credit Note).

**D. Tab CPE / Hardware**

- **Fungsi:** Inventaris barang yang dipinjamkan ke pelanggan.
- **Data:** Serial Number, MAC Address, Model Router, Tanggal Pasang.

**E. Tab Statistics**

- **Fungsi:** Bukti pemakaian layanan.
- **Data:** Grafik penggunaan trafik (Upload/Download), Sesi Login (Radius Logs), FUP Counter.

---

## === BAGIAN 3: STRUKTUR DATA (KONSEPTUAL) ===

**1. Entity: Customer (Pelanggan)**

- **Fungsi:** Entitas induk.
- **Atribut Wajib:** `System_ID`, `Portal_Login`, `Portal_Password`, `Status` (Active/Blocked/Inactive), `Billing_Email`, `Geo_Coordinates`, `Account_Balance`.
- **Relasi:** HasMany Services, HasMany Invoices.

**2. Entity: Tariff Plan (Produk Layanan)**

- **Fungsi:** Katalog produk yang dijual.
- **Atribut Wajib:** `Plan_Name`, `Price`, `Download_Speed`, `Upload_Speed`, `Service_Type` (Internet/VoIP).

**3. Entity: Service (Layanan Aktif)**

- **Fungsi:** Produk konkret yang sedang dipakai pelanggan tertentu.
- **Atribut Wajib:** `Customer_ID`, `Plan_ID`, `Status` (Active/Stopped/Pending), `Start_Date`, `End_Date`, `Device_MAC`, `IP_Allocation`, `Router_ID`.
- **Relasi:** BelongsTo Customer, BelongsTo Tariff Plan.

**4. Entity: Invoice (Tagihan)**

- **Fungsi:** Dokumen legal penagihan.
- **Atribut Wajib:** `Invoice_Number`, `Customer_ID`, `Total_Amount`, `Due_Date`, `Status` (Unpaid/Paid/Overdue/Partially Paid), `Period_Start`, `Period_End`.

**5. Entity: Transaction (Mutasi Keuangan)**

- **Fungsi:** Rekaman pergerakan uang nyata.
- **Atribut Wajib:** `Type` (Debit/Credit/Payment/Refund), `Amount`, `Timestamp`, `Related_Invoice_ID`.

---

## === BAGIAN 4: LOGIKA BISNIS (BUSINESS FLOW) ===

**Skenario: Siklus Hidup Pelanggan Standar**

1.  **Registrasi:** Admin membuat `Customer` baru dengan status "New".
2.  **Provisioning:** Admin menambahkan `Service` (misal: Paket 50Mbps). Status Service "Pending".
3.  **Aktivasi:** Teknisi memasang alat. Admin menginput MAC Address ke `Service` dan mengubah status Service menjadi "Active".
    - _System Effect:_ Router pusat otomatis mengizinkan akses internet ke MAC tersebut.
    - _Billing Effect:_ Sistem mencatat "Start Date" untuk perhitungan prorata tagihan bulan pertama.
4.  **Billing Cycle (Tiap tanggal 1):** Sistem men-generate `Invoice` untuk periode bulan berjalan.
    - Email tagihan dikirim otomatis.
5.  **Dunning Process (Penagihan):**
    - Jika tanggal `Due Date` lewat dan `Invoice` belum lunas -> Status Customer berubah jadi **Blocked**.
    - _System Effect:_ Internet pelanggan terisolir otomatis.
6.  **Pembayaran:** Pelanggan membayar via Payment Gateway / Transfer.
    - Sistem membuat `Transaction` (Payment).
    - Status `Invoice` berubah jadi **Paid**.
    - Status Customer kembali **Active**.
    - Internet jalan lagi dalam hitungan detik.
7.  **Deaktivasi (Jika tidak bayar lama):**
    - Jika status Blocked bertahan selama X hari (Deactivation Period), Status Customer berubah jadi **Inactive**.
    - Status `Service` berubah jadi **Stopped**.
    - Tagihan berhenti digenerate bulan depannya.

---

## === BAGIAN 5: STATUS & KONDISI PENTING ===

Sistem ISP profesional membedakan status **ORANG (Customer)** dan status **BARANG (Service)**.

**A. Customer Status**

1.  **New:** Baru daftar, belum ada layanan aktif.
2.  **Active:** Keuangan lancar, layanan jalan.
3.  **Blocked:** Masalah keuangan (kurang bayar). Layanan teknis masih ada, tapi akses ditutup (Redirect ke halaman isolir).
4.  **Inactive:** Kontrak putus / Churn. Tidak ada tagihan baru.
5.  **Archived:** Data lama untuk arsip.

**B. Service Status**

1.  **Pending:** Menunggu instalasi.
2.  **Active:** Sedang berjalan normal.
3.  **Paused:** Cuti layanan sementara (misal: pelanggan pulang kampung). Tidak ada tagihan, internet mati, tapi kontrak masih ada.
4.  **Stopped:** Layanan dicabut permanen.

**C. Invoice Status**

1.  **Unpaid:** Belum dibayar, belum jatuh tempo.
2.  **Overdue:** Belum dibayar, SUDAH lewat jatuh tempo (Pemicu status Blocked).
3.  **Paid:** Lunas.
4.  **Partially Paid:** Bayar mencicil (Sisa tagihan masih dianggap utang).

---

## === BAGIAN 6: ROLE & HAK AKSES ===

**1. Super Admin / Owner**

- **Akses:** Full Access.
- **Wewenang Khusus:** Menghapus Invoice, Mengubah harga kontrak manual, Unblock paksa pelanggan yang berutang.

**2. Finance / Billing Admin**

- **Akses:** Customer List, Customer Billing, Global Finance Reports.
- **Boleh:** Input pembayaran manual, buat Credit Note (Refund), Edit tanggal jatuh tempo.
- **TIDAK Boleh:** Mengubah konfigurasi router, Mengubah kecepatan bandwidth teknis.

**3. Technician / Support**

- **Akses:** Customer List, Customer Services, Customer CPE, Customer Statistics.
- **Boleh:** Ganti MAC Address, Cek sinyal/grafik trafik, Kick session user dari router.
- **TIDAK Boleh:** Melihat detail keuangan mendalam, Menghapus invoice, Mengubah harga paket.

---

## === BAGIAN 7: HUBUNGAN KE MODUL LAIN ===

1.  **Modul Networking (Radius/Router):**
    - Saat Status Customer berubah (Active <-> Blocked), modul ini mengirim perintah ke Router (Mikrotik/Cisco) untuk memutus/sambung koneksi.
    - Modul Service mengirim limit kecepatan (Rate Limit) ke Router.
2.  **Modul Ticketing:**
    - Pelanggan di Customer Management bisa langsung dibuatkan tiket komplain.
    - Riwayat tiket muncul di tab History pelanggan.
3.  **Modul Inventory:**
    - Saat CPE (Router/Modem) dipasang ke pelanggan, stok di Warehouse berkurang.
    - Serial Number alat terikat ke Customer ID.

---

## === BAGIAN 8: VERSI GENERIK (UNTUK APLIKASI ANDA) ===

Berikut adalah "Blueprint" untuk membangun ulang sistem ini menggunakan Framework Modern (Laravel/Node.js):

**Rekomendasi Struktur Database:**

- Tabel `users` (Role: Admin/Staff) terpisah dari `customers` (Pelanggan).
- Tabel `subscriptions` (pengganti istilah Services) menghubungkan `customers` dan `products`.
- Gunakan _State Machine_ untuk menangani perubahan status. Jangan gunakan `if-else` sederhana. Perubahan status HARUS mentrigger _Events_.
  - Event: `InvoiceOverdue` -> Listener: `BlockCustomerService`.
  - Event: `PaymentReceived` -> Listener: `ActivateCustomerService`.
- **Konsep "Saldo/Balance" sangat penting.** Jangan hanya cek "Invoice Lunas/Belum". Cek "Apakah Saldo >= 0?". Ini memungkinkan fitur deposit.

**Fitur Kunci Non-Negotiable:**

1.  **Prorata Calculation:** Jika pelanggan pasang tanggal 15, tagihan bulan pertama harus otomatis 50%.
2.  **Automated Isolation:** Cronjob yang jalan tiap malam mengecek `Due_Date`. Jika lewat -> Blokir.
3.  **Audit Log:** Catat SIAPA yang mengubah harga atau meng-unblock pelanggan manual.
