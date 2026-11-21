'use client';

import React from 'react';
import { useAuth } from '@/hooks/use-auth';
import { useAnalytics } from '@/hooks/use-analytics';
import {
    AlertTriangle, Zap, Globe, Banknote,
    ClipboardList, Package, LayoutDashboard, RefreshCw,
    CalendarDays, ChevronDown, Filter, Calendar,
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { motion, AnimatePresence } from 'framer-motion';
import { format, startOfDay, startOfWeek, startOfMonth, startOfYear, subDays } from 'date-fns';
import { id as localeId } from 'date-fns/locale';

// Section components
import { OverviewSection } from './_sections/OverviewSection';
import { NetworkSection } from './_sections/NetworkSection';
import { FinanceSection } from './_sections/FinanceSection';
import { OperationsSection } from './_sections/OperationsSection';
import { InventorySection } from './_sections/InventorySection';

// ─── Tipe Filter ──────────────────────────────────────────────────────────────
type DateRange = 'today' | 'yesterday' | 'week' | 'last7' | 'month' | 'last30' | 'quarter' | 'year' | 'custom';
type JenisFilter = 'all' | 'pppoe' | 'hotspot' | 'corporate';

interface ActiveFilters {
    dateRange: DateRange;
    jenis: JenisFilter;
    customStart?: string;
    customEnd?: string;
}

// ─── Konfigurasi Tab Kategori ─────────────────────────────────────────────────
const categories = [
    { id: 'overview',    label: 'Overview',    icon: LayoutDashboard },
    { id: 'network',     label: 'Network',     icon: Globe           },
    { id: 'finance',     label: 'Finance',     icon: Banknote        },
    { id: 'operations',  label: 'Operations',  icon: ClipboardList   },
    { id: 'inventory',   label: 'Inventory',   icon: Package         },
];

// ─── Konfigurasi Filter Tanggal ───────────────────────────────────────────────
const dateRangeOptions: { id: DateRange; label: string; short: string }[] = [
    { id: 'today',     label: 'Hari Ini',       short: 'Today'   },
    { id: 'yesterday', label: 'Kemarin',         short: 'Kemarin' },
    { id: 'week',      label: 'Minggu Ini',      short: 'Minggu'  },
    { id: 'last7',     label: '7 Hari Terakhir', short: '7 Hari'  },
    { id: 'month',     label: 'Bulan Ini',       short: 'Bulan'   },
    { id: 'last30',    label: '30 Hari Terakhir',short: '30 Hari' },
    { id: 'quarter',   label: 'Kuartal Ini',     short: 'Kuartal' },
    { id: 'year',      label: 'Tahun Ini',       short: 'Tahun'   },
    { id: 'custom',    label: 'Periode Custom',  short: 'Custom'  },
];

const jenisOptions: { id: JenisFilter; label: string }[] = [
    { id: 'all',       label: 'Semua Jenis'   },
    { id: 'pppoe',     label: 'PPPoE'         },
    { id: 'hotspot',   label: 'Hotspot'       },
    { id: 'corporate', label: 'Korporat'      },
];

// ─── Helper: Hitung range tanggal dari filter ─────────────────────────────────
function getDateRangeLabel(filters: ActiveFilters): string {
    const now = new Date();
    switch (filters.dateRange) {
        case 'today':     return format(now, 'd MMM yyyy', { locale: localeId });
        case 'yesterday': return format(subDays(now, 1), 'd MMM yyyy', { locale: localeId });
        case 'week':      return `${format(startOfWeek(now, { weekStartsOn: 1 }), 'd', { locale: localeId })} – ${format(now, 'd MMM yyyy', { locale: localeId })}`;
        case 'last7':     return `${format(subDays(now, 7), 'd MMM', { locale: localeId })} – ${format(now, 'd MMM yyyy', { locale: localeId })}`;
        case 'month':     return format(now, 'MMMM yyyy', { locale: localeId });
        case 'last30':    return `${format(subDays(now, 30), 'd MMM', { locale: localeId })} – ${format(now, 'd MMM yyyy', { locale: localeId })}`;
        case 'quarter':   return `Q${Math.ceil((now.getMonth() + 1) / 3)} ${now.getFullYear()}`;
        case 'year':      return String(now.getFullYear());
        case 'custom':    return filters.customStart && filters.customEnd
            ? `${filters.customStart} – ${filters.customEnd}` : 'Pilih Periode';
        default:          return '–';
    }
}

// ─── Animasi ──────────────────────────────────────────────────────────────────
const sectionVariants = {
    hidden:  { opacity: 0, y: 16 },
    visible: { opacity: 1, y: 0,  transition: { duration: 0.3, ease: [0.25, 0.1, 0.25, 1.0] as const } },
    exit:    { opacity: 0, y: -8, transition: { duration: 0.15 } },
} as const;

// =============================================================================
export default function DashboardPage() {
    const { user, isLoading: authLoading } = useAuth();
    const { data: analytics, isLoading: statsLoading, isError, refetch } = useAnalytics();

    const [activeCategory, setActiveCategory] = React.useState('overview');
    const [filters, setFilters] = React.useState<ActiveFilters>({
        dateRange: 'month',
        jenis:     'all',
    });
    const [showDateMenu,  setShowDateMenu]  = React.useState(false);
    const [showJenisMenu, setShowJenisMenu] = React.useState(false);
    const [showCustomDate, setShowCustomDate] = React.useState(false);

    const isLoading = authLoading || statsLoading;

    // Close dropdown saat klik di luar
    React.useEffect(() => {
        const handler = () => { setShowDateMenu(false); setShowJenisMenu(false); };
        document.addEventListener('mousedown', handler);
        return () => document.removeEventListener('mousedown', handler);
    }, []);

    // ── Loading ───────────────────────────────────────────────────────────────
    if (isLoading) {
        return (
            <div className="flex h-[60vh] items-center justify-center">
                <div className="flex flex-col items-center gap-6">
                    <div className="relative">
                        <div className="h-20 w-20 animate-spin rounded-full border-2 border-emerald-500/10 border-t-emerald-500 shadow-[0_0_30px_rgba(16,185,129,0.15)]" />
                        <div className="absolute inset-0 flex items-center justify-center">
                            <Zap className="h-8 w-8 text-emerald-500 animate-pulse" />
                        </div>
                    </div>
                    <p className="text-zinc-400 font-medium tracking-[0.2em] text-[10px] uppercase">Syncing Mission Control</p>
                </div>
            </div>
        );
    }

    // ── Error ─────────────────────────────────────────────────────────────────
    if (isError) {
        return (
            <div className="flex h-[60vh] items-center justify-center">
                <div className="bg-rose-500/5 border border-rose-500/20 p-8 rounded-3xl max-w-md w-full text-center">
                    <AlertTriangle className="h-10 w-10 text-rose-500 mx-auto mb-4" />
                    <h3 className="text-white font-semibold text-lg mb-2">Connection Timeout</h3>
                    <p className="text-zinc-400 text-sm mb-6">Gagal terhubung ke Mission Control.</p>
                    <button onClick={() => refetch()} className="w-full flex items-center justify-center gap-2 bg-rose-500 hover:bg-rose-600 text-white font-medium py-3 rounded-xl transition-all">
                        <RefreshCw className="h-4 w-4" /> Coba Ulang
                    </button>
                </div>
            </div>
        );
    }

    if (!user || !analytics?.data) return null;

    const data = analytics.data;

    const renderSection = () => {
        switch (activeCategory) {
            case 'network':    return <NetworkSection    data={data} />;
            case 'finance':    return <FinanceSection    data={data} />;
            case 'operations': return <OperationsSection data={data} />;
            case 'inventory':  return <InventorySection  data={data} />;
            default:           return <OverviewSection   data={data} />;
        }
    };

    const activeDateLabel = dateRangeOptions.find(d => d.id === filters.dateRange)?.label ?? '–';
    const activeJenisLabel = jenisOptions.find(j => j.id === filters.jenis)?.label ?? 'Semua';

    return (
        <div className="p-4 md:p-6 space-y-4 max-w-[1600px] mx-auto">

            {/* ── Welcome Header ────────────────────────────────────────────── */}
            <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-gradient-to-br from-emerald-500/10 via-transparent to-transparent p-6 rounded-3xl border border-emerald-500/10 backdrop-blur-xl mb-6">
                <div className="space-y-1">
                    <div className="flex items-center space-x-2 text-emerald-400 font-bold tracking-tighter text-xs uppercase">
                        <Zap className="w-3 h-3 animate-pulse" />
                        <span>System Operational</span>
                    </div>
                    <h1 className="text-3xl md:text-4xl font-black tracking-tight text-white">
                        Halo, <span className="text-emerald-500">JABBAR23</span> Admin
                    </h1>
                    <p className="text-sm text-slate-400 font-medium">
                        Selamat datang kembali. Berikut adalah ringkasan performa jaringan hari ini.
                    </p>
                </div>
                <div className="flex items-center gap-3">
                    <div className="hidden md:block text-right pr-4 border-r border-slate-800">
                        <p className="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none mb-1">Status Server</p>
                        <p className="text-xs text-emerald-400 font-mono font-bold">STABLE 99.9%</p>
                    </div>
                    <button
                        onClick={() => refetch()}
                        className="flex items-center gap-2 px-6 py-3 text-xs font-bold uppercase tracking-widest text-white bg-slate-900 border border-slate-800 rounded-2xl hover:bg-slate-800 transition-all active:scale-95 shadow-xl"
                    >
                        <RefreshCw className="h-3.5 w-3.5" />
                        Refresh Data
                    </button>
                </div>
            </div>

            {/* ── Category Tab Switcher — FULL WIDTH ────────────────────────── */}
            <div className="flex w-full gap-1 p-1 bg-white/[0.025] border border-white/5 rounded-2xl">
                {categories.map(cat => {
                    const isActive = activeCategory === cat.id;
                    return (
                        <button
                            key={cat.id}
                            onClick={() => setActiveCategory(cat.id)}
                            className={cn(
                                'flex flex-1 items-center justify-center gap-1.5 py-2.5 rounded-xl text-[11px] font-semibold transition-all duration-200 whitespace-nowrap',
                                isActive
                                    ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25'
                                    : 'text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04]'
                            )}
                        >
                            <cat.icon className="h-3.5 w-3.5 flex-shrink-0" />
                            <span className="hidden sm:inline">{cat.label}</span>
                        </button>
                    );
                })}
            </div>

            {/* ── Filter Bar ────────────────────────────────────────────────── */}
            <div className="flex flex-wrap items-center gap-2">
                {/* Label */}
                <div className="flex items-center gap-1.5 text-[10px] text-zinc-600 uppercase tracking-wider font-semibold">
                    <Filter className="h-3 w-3" />
                    Filter
                </div>

                {/* ── Shortcut tanggal cepat ── */}
                <div className="flex items-center gap-1 bg-white/[0.025] border border-white/5 rounded-lg p-0.5">
                    {(['today', 'week', 'month', 'year'] as DateRange[]).map(id => (
                        <button
                            key={id}
                            onClick={() => setFilters(f => ({ ...f, dateRange: id }))}
                            className={cn(
                                'px-3 py-1.5 rounded-md text-[11px] font-medium transition-all',
                                filters.dateRange === id
                                    ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30'
                                    : 'text-zinc-500 hover:text-zinc-300'
                            )}
                        >
                            {dateRangeOptions.find(d => d.id === id)?.short}
                        </button>
                    ))}
                </div>

                {/* ── Dropdown Period Lengkap ── */}
                <div className="relative" onMouseDown={e => e.stopPropagation()}>
                    <button
                        onClick={() => { setShowDateMenu(v => !v); setShowJenisMenu(false); }}
                        className={cn(
                            'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium border transition-all',
                            showDateMenu
                                ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400'
                                : 'bg-white/[0.03] border-white/5 text-zinc-400 hover:text-zinc-200 hover:border-white/10'
                        )}
                    >
                        <CalendarDays className="h-3.5 w-3.5" />
                        {activeDateLabel}
                        <ChevronDown className={cn('h-3 w-3 transition-transform', showDateMenu && 'rotate-180')} />
                    </button>

                    <AnimatePresence>
                        {showDateMenu && (
                            <motion.div
                                initial={{ opacity: 0, y: -6, scale: 0.97 }}
                                animate={{ opacity: 1, y: 0,  scale: 1 }}
                                exit={{ opacity: 0, y: -4, scale: 0.97 }}
                                transition={{ duration: 0.15 }}
                                className="absolute top-full left-0 mt-2 z-50 w-52 bg-zinc-900 border border-white/10 rounded-xl shadow-2xl overflow-hidden"
                            >
                                <div className="p-1">
                                    {dateRangeOptions.map(opt => (
                                        <button
                                            key={opt.id}
                                            onClick={() => {
                                                setFilters(f => ({ ...f, dateRange: opt.id }));
                                                if (opt.id === 'custom') setShowCustomDate(true);
                                                else setShowCustomDate(false);
                                                setShowDateMenu(false);
                                            }}
                                            className={cn(
                                                'w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs transition-all',
                                                filters.dateRange === opt.id
                                                    ? 'bg-emerald-500/10 text-emerald-400'
                                                    : 'text-zinc-400 hover:bg-white/[0.05] hover:text-zinc-200'
                                            )}
                                        >
                                            <span>{opt.label}</span>
                                            {filters.dateRange === opt.id && <div className="h-1.5 w-1.5 rounded-full bg-emerald-400" />}
                                        </button>
                                    ))}
                                </div>
                            </motion.div>
                        )}
                    </AnimatePresence>
                </div>

                {/* ── Custom Date Range ── (muncul jika pilih "Custom") */}
                {(filters.dateRange === 'custom' || showCustomDate) && (
                    <motion.div
                        initial={{ opacity: 0, width: 0 }}
                        animate={{ opacity: 1, width: 'auto' }}
                        className="flex items-center gap-1.5"
                    >
                        <div className="flex items-center gap-1 bg-white/[0.03] border border-white/5 rounded-lg px-2 py-1">
                            <Calendar className="h-3 w-3 text-zinc-500" />
                            <input
                                type="date"
                                value={filters.customStart ?? ''}
                                onChange={e => setFilters(f => ({ ...f, customStart: e.target.value }))}
                                className="bg-transparent text-[11px] text-zinc-300 outline-none w-28 [color-scheme:dark]"
                            />
                        </div>
                        <span className="text-zinc-600 text-xs">–</span>
                        <div className="flex items-center gap-1 bg-white/[0.03] border border-white/5 rounded-lg px-2 py-1">
                            <Calendar className="h-3 w-3 text-zinc-500" />
                            <input
                                type="date"
                                value={filters.customEnd ?? ''}
                                onChange={e => setFilters(f => ({ ...f, customEnd: e.target.value }))}
                                className="bg-transparent text-[11px] text-zinc-300 outline-none w-28 [color-scheme:dark]"
                            />
                        </div>
                    </motion.div>
                )}

                {/* ── Divider ── */}
                <div className="h-5 w-px bg-white/[0.06]" />

                {/* ── Filter Jenis ── */}
                <div className="relative" onMouseDown={e => e.stopPropagation()}>
                    <button
                        onClick={() => { setShowJenisMenu(v => !v); setShowDateMenu(false); }}
                        className={cn(
                            'flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium border transition-all',
                            showJenisMenu
                                ? 'bg-sky-500/10 border-sky-500/30 text-sky-400'
                                : 'bg-white/[0.03] border-white/5 text-zinc-400 hover:text-zinc-200 hover:border-white/10'
                        )}
                    >
                        <span className="h-3.5 w-3.5 text-center text-[10px]">⊞</span>
                        {activeJenisLabel}
                        <ChevronDown className={cn('h-3 w-3 transition-transform', showJenisMenu && 'rotate-180')} />
                    </button>

                    <AnimatePresence>
                        {showJenisMenu && (
                            <motion.div
                                initial={{ opacity: 0, y: -6, scale: 0.97 }}
                                animate={{ opacity: 1, y: 0,  scale: 1 }}
                                exit={{ opacity: 0, y: -4, scale: 0.97 }}
                                transition={{ duration: 0.15 }}
                                className="absolute top-full left-0 mt-2 z-50 w-44 bg-zinc-900 border border-white/10 rounded-xl shadow-2xl overflow-hidden"
                            >
                                <div className="p-1">
                                    {jenisOptions.map(opt => (
                                        <button
                                            key={opt.id}
                                            onClick={() => { setFilters(f => ({ ...f, jenis: opt.id })); setShowJenisMenu(false); }}
                                            className={cn(
                                                'w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs transition-all',
                                                filters.jenis === opt.id
                                                    ? 'bg-sky-500/10 text-sky-400'
                                                    : 'text-zinc-400 hover:bg-white/[0.05] hover:text-zinc-200'
                                            )}
                                        >
                                            <span>{opt.label}</span>
                                            {filters.jenis === opt.id && <div className="h-1.5 w-1.5 rounded-full bg-sky-400" />}
                                        </button>
                                    ))}
                                </div>
                            </motion.div>
                        )}
                    </AnimatePresence>
                </div>

                {/* ── Active Filter Badges ── */}
                {(filters.dateRange !== 'month' || filters.jenis !== 'all') && (
                    <button
                        onClick={() => setFilters({ dateRange: 'month', jenis: 'all' })}
                        className="flex items-center gap-1 px-2 py-1 text-[10px] text-rose-400 bg-rose-500/5 border border-rose-500/20 rounded-md hover:bg-rose-500/10 transition-all"
                    >
                        <span>×</span> Reset Filter
                    </button>
                )}
            </div>

            {/* ── Info bar active filter ─────────────────────────────────────── */}
            {(filters.dateRange !== 'month' || filters.jenis !== 'all') && (
                <div className="flex items-center gap-2 text-[11px]">
                    <span className="text-zinc-600">Menampilkan data:</span>
                    <span className="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-md font-medium">
                        {activeDateLabel}
                    </span>
                    {filters.jenis !== 'all' && (
                        <span className="px-2 py-0.5 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded-md font-medium">
                            {activeJenisLabel}
                        </span>
                    )}
                </div>
            )}

            {/* ── Section Content ────────────────────────────────────────────── */}
            <AnimatePresence mode="wait">
                <motion.div
                    key={activeCategory}
                    variants={sectionVariants}
                    initial="hidden"
                    animate="visible"
                    exit="exit"
                >
                    {renderSection()}
                </motion.div>
            </AnimatePresence>

        </div>
    );
}
