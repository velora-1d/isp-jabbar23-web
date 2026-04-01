```
Fix login page layout based on these exact specifications:

═══════════════════════════════════════
LEFT SECTION — fix padding & alignment
═══════════════════════════════════════

1. CONTAINER
   - Left padding: 40px (bukan 60px+)
   - Semua elemen wajib sejajar satu garis vertikal kiri di 40px
   - Elemen yang harus sejajar: logo, "MISSION CONTROL PLATFORM" label, 
     headline, description, cards grid, stats bar

2. CARDS GRID
   - Grid 2 kolom x 3 baris
   - Gap antar card: 12px horizontal & vertical
   - Card width: fill available space dalam container kiri
   - Semua 6 cards: background color SAMA (#111827), border 1px solid #1f2937
   - Hanya icon yang berbeda warna accent per card

3. STATS BAR
   - Padding left sejajar dengan cards di atasnya (40px dari edge)
   - Tambah divider vertikal tipis (1px, #1f2937) antar stat
   - Padding top: 16px dari cards

═══════════════════════════════════════
RIGHT SECTION — fix form card & dead space
═══════════════════════════════════════

4. FORM CARD HEIGHT
   - Form card harus stretch mengisi penuh tinggi right container
   - Gunakan: height: 100% atau min-h-full dengan flex flex-col
   - Jangan fixed height

5. ISI DEAD SPACE — tambah elemen ini di antara button dan footer:
   - Divider tipis (1px, #1f2937) margin top 24px dari button
   - Di bawah divider, tambah trust section:
     ```
     [icon shield kecil] "Digunakan oleh 2.4K+ pelanggan aktif"
     [icon lock kecil]   "Koneksi aman & terenkripsi"
     ```
   - Font size: 12px, warna: #4b5563 (muted)
   - Gap antar item: 8px

6. FOOTER
   - "PT Fakta Jabbar Industri © 2026 · JABBAR23 ISP"
   - Position: margin-top: auto (stick to bottom form card)
   - Font size: 11px, warna: #374151
   - Text align: center

═══════════════════════════════════════
OVERALL LAYOUT
═══════════════════════════════════════

7. VERTICAL CENTERING
   - Wrapper utama: min-h-screen flex items-center
   - Kedua section (kiri & kanan) harus sama tinggi: min-h-[600px]
   - Gunakan: items-stretch pada flex container

8. SPLIT PROPORSI
   - Left: flex-[1.1] atau w-[55%]
   - Right: flex-1 atau w-[45%]
   - Tidak ada gap/border visible di tengah — seamless dark background

9. RESPONSIVE (mobile)
   - Di bawah md: stack vertical, form card di bawah hero section
   - Left section: padding 24px semua sisi
   - Form card: border-radius 16px, margin 16px

═══════════════════════════════════════
JANGAN UBAH
═══════════════════════════════════════
- Warna dark theme yang sudah ada
- Teks konten (headline, card labels, button text)
- Logo JABBAR23
- Accent color hijau (#10b981)
- Animasi/efek yang sudah ada
```

Copy paste ke Antigravity, attach file login page-nya, dan bilang **"implement semua changes di atas, jangan ubah hal yang ada di bagian JANGAN UBAH"**. 🎯