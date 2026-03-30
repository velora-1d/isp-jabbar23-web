# JABBAR23 ISP — Design System MASTER
> Modern · Futuristic · Green-accented · Dual Mode (Dark + Light)

---

## 1. IDENTITAS VISUAL

| Atribut | Nilai |
|---|---|
| **Nama Produk** | JABBAR23 — ISP Management |
| **Gaya Utama** | Glassmorphism + Bento Grid |
| **Mood** | Modern, Futuristic, Professional, Tech |
| **Target User** | Admin, NOC, Teknisi, Finance, HRD |

---

## 2. COLOR PALETTE

### Primary — Emerald / Cyber Green
```
--color-primary-50:  #f0fdf4
--color-primary-100: #dcfce7
--color-primary-200: #bbf7d0
--color-primary-300: #86efac
--color-primary-400: #4ade80
--color-primary-500: #22c55e   ← MAIN (brand color)
--color-primary-600: #16a34a
--color-primary-700: #15803d
--color-primary-800: #166534
--color-primary-900: #14532d
```

### Dark Mode Background (Utama)
```
--bg-base:       #060D12   ← Deep space black
--bg-surface:    #0D1B2A   ← Dark navy surface
--bg-elevated:   #112032   ← Card background
--bg-glass:      rgba(13, 27, 42, 0.7)   ← Glassmorphism
--bg-overlay:    rgba(255, 255, 255, 0.04)
```

### Light Mode Background
```
--bg-base-light:     #F0F4F8   ← Cool gray background
--bg-surface-light:  #FFFFFF
--bg-elevated-light: #F8FAFC
--bg-glass-light:    rgba(255, 255, 255, 0.85)
```

### Semantic Colors
```
--color-success:  #22c55e   (Emerald — Active, Online, Paid)
--color-warning:  #f59e0b   (Amber — Pending, Low Stock)
--color-danger:   #ef4444   (Red — Suspended, Overdue, Critical)
--color-info:     #38bdf8   (Sky Blue — Info, Network)
--color-purple:   #a78bfa   (Violet — HRD, Payroll)
--color-orange:   #fb923c   (Orange — Work Order)
```

### Text Colors
```
DARK MODE:
--text-primary:   #F1F5F9   (slate-100)
--text-secondary: #94A3B8   (slate-400)
--text-muted:     #475569   (slate-600)

LIGHT MODE:
--text-primary:   #0F172A   (slate-900)
--text-secondary: #475569   (slate-600)
--text-muted:     #94A3B8   (slate-400)
```

### Border Colors
```
DARK MODE:  rgba(255,255,255,0.08) normal | rgba(34,197,94,0.3) active/hover
LIGHT MODE: #E2E8F0 (slate-200) normal | #22c55e active
```

---

## 3. TYPOGRAPHY

### Font Stack
```
--font-heading: 'Space Grotesk', sans-serif   ← Futuristic, tegas
--font-body:    'Inter', sans-serif            ← Clean, readable
--font-mono:    'JetBrains Mono', monospace   ← Code, IP address, data
```

### Font Scale
```
--text-xs:   0.75rem  / 12px   (caption, label kecil)
--text-sm:   0.875rem / 14px   (tabel body, badge)
--text-base: 1rem     / 16px   (body default)
--text-lg:   1.125rem / 18px   (subheading)
--text-xl:   1.25rem  / 20px   (section title)
--text-2xl:  1.5rem   / 24px   (card heading)
--text-3xl:  1.875rem / 30px   (page title)
--text-4xl:  2.25rem  / 36px   (stat number besar)
```

### Font Weight
```
Regular: 400  (body text)
Medium:  500  (label, navigation)
SemiBold: 600 (card title, button)
Bold:    700  (stat angka, page title)
```

---

## 4. SPACING & LAYOUT

### Sidebar
```
Width (expanded):  260px
Width (collapsed): 72px
Transition:        300ms ease
```

### Content Area
```
Padding:      24px (desktop)
Max-width:    1400px
Gap (grid):   16px - 24px
```

### Border Radius
```
--radius-sm:  6px    (badge, chip)
--radius-md:  10px   (button, input)
--radius-lg:  16px   (card)
--radius-xl:  20px   (modal, panel)
--radius-2xl: 24px   (sheet, drawer)
```

---

## 5. GLASSMORPHISM SPECIFICATION

### Dark Mode Glass Card
```css
background: rgba(13, 27, 42, 0.7);
backdrop-filter: blur(12px);
-webkit-backdrop-filter: blur(12px);
border: 1px solid rgba(255, 255, 255, 0.08);
box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
```

### Light Mode Glass Card
```css
background: rgba(255, 255, 255, 0.85);
backdrop-filter: blur(12px);
border: 1px solid rgba(0, 0, 0, 0.08);
box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
```

### Green Glow Effect (untuk active/highlight)
```css
box-shadow: 0 0 20px rgba(34, 197, 94, 0.15),
            0 0 40px rgba(34, 197, 94, 0.05);
border: 1px solid rgba(34, 197, 94, 0.3);
```

---

## 6. KOMPONEN UI

### Status Badges
| Status | Dark Mode | Light Mode |
|---|---|---|
| Active/Online | `#22c55e` bg + dark text | `#dcfce7` bg + `#15803d` text |
| Pending | `#f59e0b` | `#fef9c3` bg + `#92400e` text |
| Suspended/Error | `#ef4444` | `#fee2e2` bg + `#991b1b` text |
| Overdue | `#f97316` | `#ffedd5` bg + `#9a3412` text |
| Resolved | `#38bdf8` | `#e0f2fe` bg + `#0369a1` text |

### Buttons
```
Primary:   bg-emerald-500 hover:bg-emerald-400, text-white, shadow-emerald-500/20
Secondary: bg-white/10 hover:bg-white/15, text-white, border border-white/10
Danger:    bg-red-500/10 hover:bg-red-500/20, text-red-400, border border-red-500/20
```

### Sidebar Item States
```
Default: text-slate-400, no background
Hover:   text-white, bg-white/5
Active:  text-emerald-400, bg-emerald-500/10, border-l-2 border-emerald-500
```

---

## 7. ICON LIBRARY

**Set:** Lucide React (konsisten 24x24 viewBox)
**Size default:** 18px (sidebar), 20px (heading), 16px (badge/table)

### Warna Icon per Module
| Module | Warna | Icon |
|---|---|---|
| Dashboard | Emerald | LayoutDashboard |
| CRM / Leads | Sky Blue | Users |
| Billing | Amber | Receipt |
| Network | Cyan | Network |
| Work Order | Orange | Wrench |
| Support | Violet | Headphones |
| Inventory | Lime | Package |
| HRD | Purple | UserCheck |
| Reports | Pink | BarChart2 |
| Marketing | Rose | Megaphone |
| Settings | Slate | Settings |

---

## 8. CHART GUIDELINES

| Data Type | Chart Type | Library |
|---|---|---|
| Tren revenue (12 bulan) | Area Chart (gradient fill) | Recharts |
| Distribusi (status pelanggan) | Donut Chart | Recharts |
| Perbandingan (per paket) | Bar Chart horizontal | Recharts |
| Realtime (bandwidth) | Line Chart live | Recharts |
| Funnel (leads pipeline) | Funnel / Sankey | Recharts |

---

## 9. ANIMATION GUIDELINES

```
Micro-interaction: 150ms ease
State transition:  300ms ease
Page transition:   400ms ease-out
Skeleton loading:  pulse 1.5s infinite

Hover scale:       NO (avoid layout shift)
Hover color:       YES (opacity, color, shadow)
Sidebar collapse:  300ms cubic-bezier(0.4, 0, 0.2, 1)
```

---

## 10. SCREEN INVENTORY (20 Key Screens Desktop)

| # | Screen | Pattern | Priority |
|---|---|---|---|
| 1 | Dashboard Admin | Stats + Chart + Tables | P0 |
| 2 | List Pelanggan | Table + Filter + Search | P0 |
| 3 | Detail Pelanggan | Profile + Tabs + Actions | P0 |
| 4 | Form Create/Edit | Multi-section Form | P1 |
| 5 | List Invoice | Table + Stats Header | P0 |
| 6 | Detail Invoice | Invoice Card + History | P1 |
| 7 | List Work Order | Table + Kanban Toggle | P0 |
| 8 | Detail Work Order | Timeline + Materials | P1 |
| 9 | List Tiket | Table + Priority Filter | P0 |
| 10 | Detail Tiket | Thread View + Panel | P1 |
| 11 | Leads Pipeline | Kanban Board | P1 |
| 12 | Peta ODP | Map + Sidebar List | P1 |
| 13 | Inventory Stok | Table + Stock Bar | P1 |
| 14 | Absensi | Grid Calendar | P2 |
| 15 | Payroll | Table + Summary | P2 |
| 16 | Laporan Revenue | Full Charts Page | P2 |
| 17 | Hotspot Voucher | Grid Cards | P2 |
| 18 | Scheduling | Calendar Dragdrop | P2 |
| 19 | System Settings | Tab Form | P3 |
| 20 | User + Roles | Table + Modal | P3 |
