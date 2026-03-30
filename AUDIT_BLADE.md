# 📋 AUDIT LENGKAP — SISTEM LEGACY BLADE
### ISP JABBAR23 — PT Fakta Jabbar Industri
> **Sumber Audit:** Backend Controllers (Laravel Blade)
> **Tujuan:** Peta lengkap fitur untuk migrasi ke Next.js Frontend
> **Tanggal Audit:** Maret 2026

---

## 📊 RINGKASAN EKSEKUTIF

| Kategori | Jumlah |
|---|---|
| **Total Menu Utama (Grup Sidebar)** | **12 Grup** |
| **Total Sub-Menu / Halaman** | **~47 Halaman** |
| **Total Controller** | **40 Controllers** |
| **Total Fitur/Action** | **~150+ Fitur** |
| **Status Migrasi ke Next.js** | ~20% selesai |

---

## 🗂️ DETAIL AUDIT PER MENU

---

### 1. 🏠 DASHBOARD
**Controller:** `DashboardController.php`
**Halaman:** 1 (role-based)

| Fitur | Role yang Bisa Akses |
|---|---|
| Statistik pelanggan aktif, pending, suspended | super-admin, admin |
| Revenue bulan ini (chart & angka) | super-admin, admin, finance |
| Ticket open / in-progress / resolved | semua |
| WO (Work Order) pending & on-progress | semua |
| Peringatan stok inventory low stock | super-admin, admin |
| Statistik leads & konversi | super-admin, admin, sales-cs |
| Performance staff (absensi hari ini) | super-admin, admin, hrd |
| Network overview (ODP, OLT, Router status) | super-admin, admin, noc |
| Tech dashboard (WO assigned ke dia) | technician |
| CS dashboard (tickets & messages) | sales-cs |
| Finance dashboard (piutang, pendapatan) | finance |

**Total Fitur:** 11 widget/panel berbeda

---

### 2. 👥 CRM (Customer Relationship Management)

#### 2.1 Leads
**Controller:** `LeadController.php`
**Halaman:** index, create, show, edit

| Fitur | Keterangan |
|---|---|
| List leads + filter (status, sumber, sales) | Filter: year, month, search |
| Statistik (total, new, in-progress, won, lost) | Stats header cards |
| Tambah lead baru | Form lengkap + alamat detail |
| Edit lead | Update status pipeline |
| Detail lead | + riwayat aktivitas |
| **Convert Lead → Customer** | Fitur kunci: otomatis buat Customer |
| Filter sumber (website, WA, referral, walk-in) | |
| Assign ke sales | |
| Filter status pipeline (new→won/lost) | 7 stage pipeline |

**Total Fitur:** 9 fitur

#### 2.2 Customers (Pelanggan)
**Controller:** `CustomerController.php`
**Halaman:** index, create, show, edit

| Fitur | Keterangan |
|---|---|
| List pelanggan + filter multi-kriteria | status, paket, area, search |
| Statistik (active, pending, suspended, total) | |
| Tambah pelanggan baru | Form + alamat + pilih paket |
| Edit data pelanggan | |
| Detail pelanggan | + riwayat invoice, ticket, WO |
| **Suspend pelanggan (Mikrotik API)** | Kunci: blokir via Mikrotik |
| **Activate pelanggan (Mikrotik API)** | Restore akses internet |
| **Sync status dari Mikrotik** | Real-time sync |
| Upload bukti pembayaran | |
| Export daftar pelanggan | |
| Filter per area/kelurahan | |
| Ganti paket internet | |

**Total Fitur:** 12 fitur

#### 2.3 Partners (Mitra Reseller)
**Controller:** `PartnerController.php`
**Halaman:** index, create, edit, show

| Fitur | Keterangan |
|---|---|
| List mitra + statistik | |
| Tambah/edit mitra | Nama, komisi %, area coverage |
| Lihat pelanggan per mitra | |

**Total Fitur:** 4 fitur

#### 2.4 Referral
**Controller:** `ReferralController.php`
**Halaman:** index, create

| Fitur | Keterangan |
|---|---|
| List program referral | |
| Buat/kelola kode referral | |
| Tracking reward referral | |

**Total Fitur:** 3 fitur

---

### 3. 💰 BILLING & FINANCE

#### 3.1 Invoices (Tagihan)
**Controller:** `InvoiceController.php`
**Halaman:** index, show, create

| Fitur | Keterangan |
|---|---|
| List invoice + filter (status, pelanggan, bulan) | |
| Statistik (unpaid total, paid total, overdue count) | |
| Detail invoice + riwayat pembayaran | |
| Buat invoice manual | Pilih pelanggan, periode, amount |
| **Generate invoice bulk** | Trigger artisan `billing:generate` |
| Filter status: unpaid, paid, partial, overdue | |

**Total Fitur:** 6 fitur

#### 3.2 Payments (Pembayaran)
**Controller:** `PaymentController.php`
**Halaman:** index

| Fitur | Keterangan |
|---|---|
| List pembayaran + filter | filter metode, status, bulan |
| Statistik (total, hari ini, bulan ini, pending) | |
| Catat pembayaran manual | Cash, transfer, QRIS, e-wallet |
| **Auto-restore Radius** saat lunas | Integrasi RadiusService |
| **Send WA notification** saat bayar parsial | Integrasi WhatsApp |
| Webhook handler (Midtrans) | Payment gateway integration |

**Total Fitur:** 6 fitur

#### 3.3 Expenses (Pengeluaran)
**Controller:** `ExpenseController.php`
**Halaman:** index, create, edit

| Fitur | Keterangan |
|---|---|
| List pengeluaran + filter bulan | kategori biaya |
| Tambah/edit/hapus pengeluaran | |
| Kategori expense | |

**Total Fitur:** 4 fitur

#### 3.4 Credit Notes
**Controller:** `CreditNoteController.php`
**Halaman:** index, create, show

| Fitur | Keterangan |
|---|---|
| List credit note | |
| Buat credit note | Nota kredit untuk pelanggan |
| Approve/reject credit note | |

**Total Fitur:** 3 fitur

#### 3.5 Proforma Invoice
**Controller:** `ProformaInvoiceController.php`
**Halaman:** index, create, show

| Fitur | Keterangan |
|---|---|
| List proforma | |
| Buat proforma invoice | |
| Convert Proforma → Invoice resmi | |

**Total Fitur:** 3 fitur

#### 3.6 Paket Internet
**Controller:** `PackageController.php`
**Halaman:** index, create, edit

| Fitur | Keterangan |
|---|---|
| List paket + statistik (total, aktif, nonaktif) | |
| Tambah paket baru | Nama, kecepatan up/down, harga |
| Edit paket | |
| Aktif/nonaktif paket | |

**Total Fitur:** 5 fitur

---

### 4. 🌐 NETWORK

#### 4.1 ODP (Optical Distribution Point)
**Controller:** `Network/OdpController.php` (terpisah)
**Halaman:** index, create, show, map

| Fitur | Keterangan |
|---|---|
| List ODP + filter status | |
| Statistik (total, available, full, maintenance) | |
| Tambah/edit ODP | koordinat GPS |
| Detail ODP + daftar pelanggan terpasang | |
| **Peta ODP** | Visualisasi peta geografis |
| Slot management | Berapa port terpakai |

**Total Fitur:** 6 fitur

#### 4.2 OLT (Optical Line Terminal)
**Controller:** `Network/OltController.php`
**Halaman:** index, create, show

| Fitur | Keterangan |
|---|---|
| List OLT + status online/offline | |
| Statistik | |
| Tambah/edit OLT | IP, lokasi, kapasitas |

**Total Fitur:** 3 fitur

#### 4.3 Router / Mikrotik
**Controller:** `Network/RouterController.php`
**Halaman:** index, create, show

| Fitur | Keterangan |
|---|---|
| List router | |
| Tambah/edit router | IP, credentials Mikrotik |
| **Test koneksi router** | Ping Mikrotik |
| **Status aktif pelanggan** via Mikrotik | |

**Total Fitur:** 4 fitur

#### 4.4 Hotspot Voucher
**Controller:** `HotspotController.php`
**Halaman:** index (vouchers), profiles

| Fitur | Keterangan |
|---|---|
| List voucher hotspot | |
| Kelola hotspot profiles | Harga, masa aktif, data limit |
| **Generate voucher bulk** | Kirim ke Mikrotik via API |
| **Print voucher** | Cetak voucher fisik |

**Total Fitur:** 4 fitur

---

### 5. 🔧 WORK ORDER (SPK)
**Controller:** `WorkOrderController.php`
**Halaman:** index, create, show

| Fitur | Keterangan |
|---|---|
| List WO + filter multi-kriteria | status, teknisi, tipe, prioritas |
| Statistik (pending, in-progress, done hari ini) | |
| Buat WO baru | Pelanggan, tipe, prioritas, teknisi, ODP |
| Detail WO | |
| **Update status WO** | pending→scheduled→on_way→in_progress→completed |
| **Tambah material** ke WO | Inventaris yang dipakai |
| Hapus material dari WO | |
| **Auto-deduct stock** saat WO selesai | Integrasi inventory |
| Filter tipe: installation, repair, dismantling, survey, maintenance | |

**Total Fitur:** 9 fitur

---

### 6. 🎫 SUPPORT (Tiket Gangguan)
**Controller:** `TicketController.php`
**Halaman:** index, create, show

| Fitur | Keterangan |
|---|---|
| List tiket + filter | status, prioritas, kategori, teknisi |
| Statistik (open, in-progress, resolved) | |
| Buat tiket baru | Pelanggan, subjek, deskripsi |
| Detail tiket | |
| Update status tiket | open → in_progress → resolved → closed |
| Update prioritas & assign teknisi | |
| Admin notes | |
| Auto-set `resolved_at` saat resolved | |
| Event `TicketCreated` dispatch | Trigger notifikasi |

**Total Fitur:** 9 fitur

#### 6.1 Messages (Pesan/KB)
**Controller:** `MessageController.php`
**Halaman:** index, show, create

| Fitur | Keterangan |
|---|---|
| Knowledge base / FAQ | |
| Kirim pesan internal | |
| Baca pesan | |

**Total Fitur:** 3 fitur

---

### 7. 📦 INVENTORY (Gudang)

#### 7.1 Stok Barang
**Controller:** `InventoryController.php`
**Halaman:** index (central hub)

| Fitur | Keterangan |
|---|---|
| List item + filter (kategori, stok status) | |
| Statistik (total item, total value, low stock, kategori) | |
| Tambah item baru | SKU, kategori, satuan, harga beli/jual |
| Edit item | |
| Hapus item (validasi stok 0 dulu) | |
| **Stock Adjustment** (Stok Masuk/Keluar) | Log movement |
| **Store Serials** (Scan SN Bulk) | Tambah item dengan serial number |
| **Assign Serial ke Pelanggan** | ONU/router terpasang |
| Filter: low stock, out of stock | |

**Total Fitur:** 9 fitur

#### 7.2 Purchase Orders (PO)
**Controller:** `PurchaseOrderController.php`
**Halaman:** index, create, show, edit

| Fitur | Keterangan |
|---|---|
| List PO + filter (vendor, status, bulan) | |
| Statistik (total, draft, pending, received, total value) | |
| Buat PO baru | Multi-item per PO |
| Edit PO (hanya draft/pending) | |
| Detail PO | |
| **Approve PO** | |
| Cancel PO | |
| Auto-generate PO number | |

**Total Fitur:** 8 fitur

#### 7.3 Vendor / Supplier
**Controller:** `VendorController.php`
**Halaman:** index, create, edit

| Fitur | Keterangan |
|---|---|
| List vendor | |
| Tambah/edit vendor | |
| Status aktif/nonaktif | |

**Total Fitur:** 3 fitur

#### 7.4 Aset Perusahaan
**Controller:** `AssetController.php`
**Halaman:** index, create, show, edit

| Fitur | Keterangan |
|---|---|
| List aset perusahaan | |
| Tambah/edit aset | Nilai, lokasi, kondisi |
| Depreciation tracking | |

**Total Fitur:** 4 fitur

---

### 8. 👨‍💼 HRD

#### 8.1 User / Karyawan
**Controller:** `UserController.php`
**Halaman:** index, create, edit, show

| Fitur | Keterangan |
|---|---|
| List karyawan | |
| Tambah/edit user | |
| **Assign Role** | super-admin, admin, noc, technician, dll |
| Reset password | |
| Aktif/nonaktif user | |
| Profil karyawan | |

**Total Fitur:** 6 fitur

#### 8.2 Absensi
**Controller:** `AttendanceController.php`
**Halaman:** index (daily), history (monthly)

| Fitur | Keterangan |
|---|---|
| Rekapitulasi absensi harian | |
| Rekapitulasi absensi bulanan (history) | |
| Tambah record absensi manual | |
| Edit absensi | |
| Delete absensi | |
| **Clock In** (self-service) | Dengan lokasi GPS |
| **Clock Out** (self-service) | Dengan lokasi GPS |
| Filter status: present, late, absent, sick, leave, holiday | |
| Statistik harian: hadir, telat, absen | |

**Total Fitur:** 9 fitur

#### 8.3 Cuti / Izin
**Controller:** `LeaveController.php`
**Halaman:** index, create, show

| Fitur | Keterangan |
|---|---|
| List pengajuan cuti | |
| Ajukan cuti baru | Tipe: annual, sick, personal |
| Detail cuti | |
| **Approve/reject cuti** | Role: HRD/admin |
| Saldo cuti per karyawan | |

**Total Fitur:** 5 fitur

#### 8.4 Penggajian (Payroll)
**Controller:** `PayrollController.php`
**Halaman:** index (per periode), create, edit

| Fitur | Keterangan |
|---|---|
| List payroll per periode bulan | |
| Statistik (total, draft, approved, paid, total amount) | |
| Buat slip gaji | Auto-tarik data absensi |
| Edit slip gaji | |
| **Approve gaji** | |
| **Mark as Paid** | Gaji sudah dibayarkan |
| Kalkulasi otomatis (gaji pokok + tunjangan + lembur + bonus - potongan - pajak) | |
| Filter per karyawan atau status | |

**Total Fitur:** 8 fitur

---

### 9. 📈 LAPORAN (Reports)
**Controller:** `ReportController.php`
**Halaman:** index, revenue, profit-loss, customers (sub-views), network, commissions

| Halaman | Fitur |
|---|---|
| **Financial Report** | Ringkasan invoice paid/unpaid per bulan |
| **Revenue Report** | Total revenue, by month chart, by payment method, pending invoices |
| **Customer Report** | Total, aktif, baru, churn, by paket, growth chart, top customers |
| **Network Report** | ODP stats, OLT stats, bandwidth allocation total |
| **Commission Report** | Komisi per partner, top performers |
| **P&L (Profit & Loss)** | Pendapatan - Pengeluaran = Net Profit, per kategori expense |

**Total Halaman Laporan:** 6
**Total Fitur Laporan:** ~20 chart/metrik berbeda

---

### 10. 📱 MARKETING

#### 10.1 Campaigns
**Controller:** `CampaignController.php`
**Halaman:** index, create, show

| Fitur | Keterangan |
|---|---|
| List campaign | |
| Buat campaign WhatsApp blast | |
| Lihat status pengiriman | |

**Total Fitur:** 4 fitur

#### 10.2 Promotions
**Controller:** `PromotionController.php`
**Halaman:** index, create, edit

| Fitur | Keterangan |
|---|---|
| List promo aktif | |
| Buat/edit kode promo atau diskon | |
| Periode validitas | |
| Aktif/nonaktif promo | |

**Total Fitur:** 5 fitur

---

### 11. 🔐 SISTEM & KONFIGURASI

#### 11.1 Pengaturan Sistem
**Controller:** `SettingController.php`
**Halaman:** index (tabs)

| Fitur | Keterangan |
|---|---|
| Setting umum ISP | Nama, logo, alamat |
| Konfigurasi Billing | Tanggal cut-off, denda |
| Konfigurasi SMTP email | |
| Konfigurasi WhatsApp (Fonnte) | API key |
| Konfigurasi Mikrotik default | |

**Total Fitur:** 5 fitur

#### 11.2 Roles & Permissions
(Via Spatie Permission — di-manage lewat UserController)

| Fitur | Keterangan |
|---|---|
| Assign role ke user | |
| Daftar permissions per role | |

**Total Fitur:** 2 fitur

#### 11.3 Audit Log
**Controller:** `AuditLogController.php`
**Halaman:** index

| Fitur | Keterangan |
|---|---|
| Log aktivitas semua user | |
| Filter per user, aksi, tanggal | |

**Total Fitur:** 2 fitur

#### 11.4 API Management
**Controller:** `ApiManagementController.php`
**Halaman:** index

| Fitur | Keterangan |
|---|---|
| Kelola API token | |
| Log API request | |

**Total Fitur:** 2 fitur

#### 11.5 Backup
**Controller:** `BackupController.php`
**Halaman:** index

| Fitur | Keterangan |
|---|---|
| Backup database | |
| Download backup | |
| Restore backup | |

**Total Fitur:** 3 fitur

---

### 12. 🛰️ TRACKING & SCHEDULING

#### 12.1 Technician Tracking
**Controller:** `TechnicianController.php`, `TrackingController.php`
**Halaman:** index, map

| Fitur | Keterangan |
|---|---|
| List teknisi + KPI hari ini | |
| **Live tracking lokasi teknisi** | GPS real-time |
| Statistik WO per teknisi | |

**Total Fitur:** 3 fitur

#### 12.2 Installation Report
**Controller:** `InstallationReportController.php`
**Halaman:** index, show

| Fitur | Keterangan |
|---|---|
| Laporan pemasangan baru | |
| Detail per WO installation | |

**Total Fitur:** 2 fitur

#### 12.3 Scheduling
**Controller:** `SchedulingController.php`
**Halaman:** index (kalender)

| Fitur | Keterangan |
|---|---|
| Kalender jadwal teknisi | Drag & drop |
| Assign WO ke slot waktu | |

**Total Fitur:** 2 fitur

---

## 📌 TABEL REKAP FINAL

| No | Menu Grup | Sub-halaman | Fitur Count | Status di Next.js |
|---|---|---|---|---|
| 1 | Dashboard | 1 (multi-role) | 11 widget | 🟡 Partial (statis) |
| 2 | CRM — Leads | 4 hal | 9 | ❌ Belum |
| 3 | CRM — Customers | 4 hal | 12 | 🟡 Partial (CRUD basic) |
| 4 | CRM — Partners | 3 hal | 4 | ❌ Belum |
| 5 | CRM — Referral | 2 hal | 3 | ❌ Belum |
| 6 | Billing — Invoice | 3 hal | 6 | ❌ Belum |
| 7 | Billing — Payment | 1 hal | 6 | ❌ Belum |
| 8 | Billing — Expenses | 2 hal | 4 | ❌ Belum |
| 9 | Billing — Credit Note | 3 hal | 3 | ❌ Belum |
| 10 | Billing — Proforma | 3 hal | 3 | ❌ Belum |
| 11 | Billing — Paket | 3 hal | 5 | ❌ Belum |
| 12 | Network — ODP | 4 hal | 6 | ❌ Belum |
| 13 | Network — OLT | 3 hal | 3 | ❌ Belum |
| 14 | Network — Router | 3 hal | 4 | ❌ Belum |
| 15 | Network — Hotspot | 2 hal | 4 | ❌ Belum |
| 16 | Work Order (SPK) | 3 hal | 9 | ❌ Belum |
| 17 | Support — Tiket | 3 hal | 9 | ❌ Belum |
| 18 | Support — Messages | 3 hal | 3 | ❌ Belum |
| 19 | Inventory — Stok | 1 hub | 9 | ❌ Belum |
| 20 | Inventory — PO | 4 hal | 8 | ❌ Belum |
| 21 | Inventory — Vendor | 3 hal | 3 | ❌ Belum |
| 22 | Inventory — Aset | 4 hal | 4 | ❌ Belum |
| 23 | HRD — Users | 4 hal | 6 | ❌ Belum |
| 24 | HRD — Absensi | 2 hal | 9 | ❌ Belum |
| 25 | HRD — Cuti | 3 hal | 5 | ❌ Belum |
| 26 | HRD — Payroll | 3 hal | 8 | ❌ Belum |
| 27 | Laporan — Financial | 1 hal | 4 | ❌ Belum |
| 28 | Laporan — Revenue | 1 hal | 4 | ❌ Belum |
| 29 | Laporan — P&L | 1 hal | 4 | ❌ Belum |
| 30 | Laporan — Customers | 1 hal | 5 | ❌ Belum |
| 31 | Laporan — Network | 1 hal | 4 | ❌ Belum |
| 32 | Laporan — Commissions | 1 hal | 3 | ❌ Belum |
| 33 | Marketing — Campaigns | 3 hal | 4 | ❌ Belum |
| 34 | Marketing — Promotions | 3 hal | 5 | ❌ Belum |
| 35 | Sistem — Settings | 1 hal | 5 | ❌ Belum |
| 36 | Sistem — Roles | (modal) | 2 | ❌ Belum |
| 37 | Sistem — Audit Log | 1 hal | 2 | ❌ Belum |
| 38 | Sistem — API Mgmt | 1 hal | 2 | ❌ Belum |
| 39 | Sistem — Backup | 1 hal | 3 | ❌ Belum |
| 40 | Tracking — Teknisi | 2 hal | 3 | ❌ Belum |
| 41 | Tracking — Install Report | 2 hal | 2 | ❌ Belum |
| 42 | Tracking — Scheduling | 1 hal | 2 | ❌ Belum |
| — | **TOTAL** | **~105 halaman** | **~171 fitur** | **~5% done** |

---

## 🚨 FITUR KRITIS YANG PERLU PRIORITAS MIGRASI

### 🔴 HIGH PRIORITY (Core Business)
1. **Dashboard Admin real-time** — data analytics dari AnalyticsService
2. **Customer CRUD + Mikrotik Suspend/Activate** — bisnis utama
3. **Invoice management + bulk generate** — billing utama
4. **Payment recording + WhatsApp notif** — revenue tracking
5. **Work Order (SPK) full flow** — operasional lapangan

### 🟡 MEDIUM PRIORITY (Operational)
6. **Ticket support system** — kepuasan pelanggan
7. **Leads CRM pipeline** — sales management
8. **Inventory stock management** — aset perusahaan
9. **Payroll + Absensi** — HRD internal

### 🟢 LOW PRIORITY (Can Stay Blade Longer)
10. Reports (sebagian sudah ada di web.php)
11. Hotspot voucher
12. Marketing campaigns
13. Backup & Audit log

---

## 🗓️ URUTAN MIGRASI YANG DISARANKAN

```
Fase 1 (Sekarang):    Dashboard Admin Premium
Fase 2 (Minggu 2-3):  CRM Customers + Leads
Fase 3 (Minggu 4-5):  Billing (Invoice + Payment + Paket)
Fase 4 (Minggu 6-7):  Work Order + Ticket Support
Fase 5 (Minggu 8-9):  Inventory + Network
Fase 6 (Minggu 10+):  HRD (Absensi, Payroll, Cuti)
Fase 7 (Optional):    Reports, Marketing, Sistem
```

---

*Dokumen ini adalah referensi teknis untuk proses migrasi Blade → Next.js*
*Dibuat berdasarkan audit langsung dari source code backend PHP/Laravel*
