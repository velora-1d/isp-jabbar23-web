# SERVER INFRASTRUCTURE SPECIFICATION

_Untuk Sistem ISP Terintegrasi (ERPNext + Radius + InvenTree)_

Dokumen ini menjelaskan kebutuhan hardware (VPS/Dedicated Server) untuk menjalankan sistem Enterprise Grade yang telah kita rancang.

---

## 1. PRINSIF UTAMA: "JANGAN TARUH SEMUA TELUR DALAM SATU KERANJANG"

Untuk ISP Profesional, saya **SANGAT MENYARANKAN** menggunakan **2 Server Virtual (VPS)** terpisah, bukan 1 server raksasa.

**Kenapa?**
_Keandalan (Reliability):_ Jika Server ERP (Bisnis) crash karena kepenuhan data akuntansi, Server Radius (Internet) **TIDAK BOLEH MATI**. Kalau Radius mati, satu kota offline = Komplain Masal.

---

## 2. TOPOLOGI SERVER (RECOMMENDED)

### SERVER A: "The Business Core"

_Menjalankan: ERPNext, InvenTree, Middleware, Reseller Portal_

- **Fungsi:** Menangani admin kantor, sales input, stok barang, dan laporan keuangan.
- **Karakteristik:** Butuh RAM besar (Java/Python apps boros RAM).

**Spesifikasi Rekomendasi (VPS):**

- **CPU:** 4 vCPU (Processor harus cepat untuk generate report).
- **RAM:** 8 GB (Wajib! ERPNext sendiri butuh 4GB+ biar lancar).
- **Storage:** 160 GB NVMe SSD (Database ERP cepat bengkak + Foto upload dari teknisi).
- **OS:** Ubuntu 22.04 LTS / 24.04 LTS.

---

### SERVER B: "The Network Core" (Mission Critical)

_Menjalankan: daloRADIUS, FreeRADIUS, Database Radius_

- **Fungsi:** Otentikasi ribuan router pelanggan tiap detik.
- **Karakteristik:** Butuh I/O (Kecepatan tulis disk) sangat tinggi untuk log traffic.

**Spesifikasi Rekomendasi (VPS):**

- **CPU:** 2 vCPU (Cukup, proses radius ringan tapi sering).
- **RAM:** 4 GB (Cukup).
- **Storage:** 60 - 80 GB NVMe SSD (Fokus pada kecepatan Read/Write).
- **Lokasi:** **WAJIB LOKAL INDONESIA (IIX)** supaya latency/ping kecil.

---

## 3. ESTIMASI BIAYA BULANAN (CLOUD VPS)

Berikut perkiraan harga pasar VPS Lokal Indonesia (IDCloudHost / BiznetGio / JagoanHosting):

1.  **Server A (Business - 4 CPU / 8 GB RAM):**
    - Estimasi: Rp 400.000 - Rp 600.000 / bulan.
2.  **Server B (Network - 2 CPU / 4 GB RAM):**
    - Estimasi: Rp 200.000 - Rp 300.000 / bulan.
3.  **Domain & SSL:**
    - `isp-system.com`: Rp 150.000 / tahun.
    - SSL: Gratis (LetsEncrypt).

**Total Cost Bulanan:** ± Rp 600.000 - Rp 900.000 / bulan.
_(Sangat murah dibanding beli server fisik seharga 30 Juta + Listrik + AC 24 jam)._

---

## 4. OPSI HEMAT (ALL-IN-ONE)

_Untuk tahap awal (Start-up) dengan pelanggan di bawah 500 user._

Jika budget awal terbatas, Bapak bisa pakai 1 VPS saja.

- **Spec:** 4 vCPU, 8 GB RAM.
- **Risiko:** Jika ERP sedang generate Laporan Akhir Tahun berat, internet pelanggan _mungkin_ agak lag saat login (radius timeout).
- **Saran:** Tidak direkomendasikan untuk jangka panjang.

---

## 5. APAKAH BUTUH KOMPUTER KHUSUS DI KANTOR?

**Tidak.**
Karena sistem ini berbasis **Cloud/Web**, staff Bapak bisa akses dari:

- Laptop kentang (Core i3 lama) -> BISA.
- Tablet Android murah -> BISA.
- HP Sales -> BISA.

**Kecuali Admin NOC:** Disarankan punya PC dengan 2 Monitor untuk monitoring map dan log traffic dengan nyaman.

---

## KESIMPULAN

Rekomendasi saya: **Ambil Opsi 2 Server Terpisah** dari awal.
Biayanya cuma selisih 100-200 ribu per bulan, tapi Bapak tidur nyenyak karena internet pelanggan aman dari gangguan aplikasi kantor.

Apakah spec ini sesuai budget operasional Bapak?
