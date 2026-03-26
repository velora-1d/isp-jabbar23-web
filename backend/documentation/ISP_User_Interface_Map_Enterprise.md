# ENTERPRISE GRADE ISP SITEMAP & FEATURE MAP

_Benchmark: Sonar, Splynx, Azotel (Global Standard)_

Dokumen ini adalah versi **UPGRADE** dari sitemap sebelumnya, disesuaikan dengan standar ISP Korporat Internasional.

---

## 1. GLOBAL NAVIGATION & UTILITIES

_(Fitur yang harus selalu ada di atas layar semua user)_

- **Universal Search Bar (Cmd+K):**
  - Bisa cari apa saja: Nama `Budi`, IP `192.168.1.50`, Serial Number `SN12345`, Invoice `INV-2024-001`.
  - _Kenapa Enterprise?_ Supaya CS tidak perlu klik 5 menu cuma buat cari 1 info.
- **Notification Center:**
  - Alert Kritis: "Router Core Down", "Stok Kabel Habis".
  - Alert Bisnis: "Approval Diskon", "Target Sales Tercapai".

---

## 2. ROLE: CUSTOMER SERVICE & DISPATCHER (Front Line)

_Gabungan fungsi CS dan Pengatur Jadwal Teknisi._

### A. Dashboard "Cockpit"

- **Live Map:** Peta sebaran teknisi (GPS Realtime) & Tiket pending.
- **Queue Info:** Jumlah antrian telepon masuk / chat WA.

### B. Menu: Subscriber Management 360°

- **(New) Account Overview Page:** Satu halaman lihat semua -> Tagihan, Status Modem, Tiket, Riwayat Chat.
- **(New) Provisioning Wizard:**
  - Input Alamat -> Cek Coverage Otomatis (Polygon) -> Suggest Paket yang tersedia -> Booking Jadwal Instalasi.

### C. Menu: Scheduling & Dispatch

- **Calendar View:** Drag & drop jadwal teknisi.
- **Route Optimizer:** Menyarankan rute tercepat buat teknisi (Biar bensin irit).

---

## 3. ROLE: FINANCE & REVENUE ASSURANCE

_Bukan cuma kasir, tapi penjaga kebocoran dana._

### A. Menu: Billing Engine

- **Service Plans:** Setting paket (Speed, Harga, Kuota, Pajak).
- **Billing Cycles:** Atur tanggal cetak tagihan (Tgl 1, Tgl 15, atau Anniversary Date).
- **Dunning Machine:**
  - _Step 1:_ Kirim WA H-3.
  - _Step 2:_ Kirim Email H+1.
  - _Step 3:_ Turunkan speed jadi 128kbps (Throttling).
  - _Step 4:_ Blokir Total H+7.

### B. Menu: Revenue Integrity

- **Audit Laporan:** Membandingkan "Jumlah User Aktif di Radius" vs "Jumlah User Bayar Invoice".
  - _Fitur Mahal:_ Alert jika ada user internet nyala tapi gak bayar (Kebocoran).

---

## 4. ROLE: WAREHOUSE MANAGER (Enterprise Logistics)

### A. Menu: Inventory Hierarchy

- **(New) Parent-Child Tracking:**
  - Track _Box Kardus_ isi 100 Modem.
  - Track _Satu Modem_ isi Adaptor + Kabel.
  - Kalau satu modem rusak, bisa track history batch pembeliannya.
- **Multi-Site Warehouse:** Gudang Pusat, Gudang Cabang A, Gudang Cabang B, Mobil Teknisi 1.

### B. Menu: Procurement (RMA)

- **(New) Vendor RMA:** Melacak barang rusak yang diklaim garansi ke pabrik. Jangan sampai barang rusak numpuk jadi sampah.

---

## 5. ROLE: NOC & NETWORK ENGINEER

### A. Dashboard "War Room"

- **Network Health:** Grafik CPU Router Utama, Suhu Server, Link Capacity (Merah kalau kabel putus).
- **Mass Outage Alert:** Deteksi otomatis "50 user mati di area X" -> Kemungkinan ODP putus/mati lampu.

### B. Menu: IP Address Management (IPAM)

- **Visual Subnet:** Kotak-kotak visualisasi IP Publik/Private yang terpakai.
- **DHCP Leases:** Siapa pakai IP berapa sekarang.

### C. Menu: Tower & Site Management

- **Site Database:** Data BTS/Tower (Sewa lahan, Izin RT/RW, Tanggal bayar listrik tower).

---

## 6. ROLE: FIELD TECHNICIAN (Mobile App)

### Fitur "Uber-Style" for Technician

- **Job Card:** Muncul notifikasi "Tugas Baru: Jarak 2km dari posisi Anda".
- **E-Signature:** Pelanggan tanda tangan digital di layar HP setelah pasang.
- **Signal Validation:** App memaksa teknisi upload foto speedtest & sinyal (-dbm) sebelum boleh close tiket.
- **(New) Offline Mode:** Bisa input data walau sinyal mati, upload nanti pas dapet sinyal.

---

## 7. ROLE: CUSTOMER (Self-Care Portal)

_Aplikasi Android/iOS untuk Pelanggan (MyTelkomsel style)_

- **Fitur:**
  - Cek Kuota / FUP.
  - Bayar Tagihan (QRIS/Virtual Account).
  - **Self-Diagnostic:** Tombol "Perbaiki Koneksi Saya" (Trigger script reset port di server tanpa nelpon CS).
  - Change Wifi Password (Integrasi ke TR-069 Router).

---

## 8. ROLE: RESELLER / MITRA

### Dashboard "Juragan"

- **Voucher Generator:** Bikin voucher wifi sendiri, print thermal, jual.
- **Sub-Reseller:** Reseller bisa punya anak buah lagi (Multi-Level).
- **Wallet:** Saldo deposit yang bisa ditarik/cairkan.

---

**Perubahan dari Draft Sebelumnya:**

1.  Penambahan **Global Search** (Wajib di Enterprise).
2.  Penambahan **Module RMA** di Gudang (Penting buat efisiensi).
3.  Penambahan **IPAM & Tower Management** di NOC.
4.  Upgrade Mobile App Teknisi dengan fitur **Signal Validation** & **Offline Mode**.
5.  Portal Pelanggan bisa **Reset WiFi sendiri** (Mengurangi komplain ke CS 40%).
