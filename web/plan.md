# MASTER PLAN: Refactoring Blade to Next.js (Admin ISP JABBAR23)

Dokumen ini merangkum hasil audit fitur Blade (Legacy) dan rencana kerja untuk migrasi ke sistem Next.js yang baru.

## 1. HASIL AUDIT FITUR (Legacy Blade)
Sistem lama terdiri dari ~40 Controller dengan fitur-fitur operasional ISP yang matang:

### Dashboard & Analytics
- Statistik harian/bulanan (Pelanggan, Pendapatan, Tiket).
- Grafik pertumbuhan (Revenue & Customer Growth).
- Notifikasi stok barang (Inventory Alert).

### Manajemen Pelanggan (CRM)
- Registrasi, Survey, Aktivasi, Suspend, Terminated.
- Filter wilayah: Kelurahan, Kecamatan, Kabupaten.
- **Mikrotik Sync**: Toggle status PPPoE Secret secara otomatis.
- Detail teknis: Mapping OLT, ONU Index, IP Mikrotik.

### Finance & Billing
- Invoice Recurring Otomatis.
- Integrasi Payment Gateway (Midtrans).
- Pencatatan pengeluaran (Expenses).
- Laporan Laba Rugi (Profit & Loss).

### Operations & HRD
- Manajemen Tiket Gangguan/Support.
- Work Orders (SPK) untuk teknisi lapangan.
- Absensi Staff (GPS/IP Based).
- Inventory & Gudang (Serial Number Tracking).

---

## 2. ROADMAP PENGERJAAN (Phase-by-Phase)

### FASE 1: Dashboard Admin Premium (PRIORITAS)
- [ ] Implementasi **Admin Dashboard** di Next.js.
- [ ] Integrasi data dari `AnalyticsController` (Network, Finance, Staff).
- [ ] UI Estetik: Glassmorphism, Chart interaktif, Progress tracker.

### FASE 2: Modul Pelanggan & Mikrotik Sync
- [ ] Refactor halaman Customer List & Detail.
- [ ] Implementasi tombol "Suspend/Activate" yang terhubung ke Mikrotik API.
- [ ] Form registrasi pelanggan baru dengan koordinat lokasi (Google Maps).

### FASE 3: Billing & Reporting Center
- [ ] Dashboard Finance (Outstanding, Paid, Unpaid).
- [ ] Fitur Print Invoice (PDF) langsung dari Frontend.
- [ ] Integrasi ulang Webhook untuk pembayaran otomatis.

### FASE 4: Inventory & Support
- [ ] Migrasi sistem Tiket & Live Chat.
- [ ] Manajemen gudang dan tracking S/N Perangkat.

---

## 3. STANDAR UI/UX (Vibe Coding)
- **Design System**: Shadcn/ui + Tailwind CSS.
- **Visual**: Dark Mode (Zinc-950), Vibrant Gradients (Cyan-Blue), Blur effects.
- **UX**: Micro-animations untuk setiap interaksi tombol.

---

*Update Terakhir: Maret 2026*
