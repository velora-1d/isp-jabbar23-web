'use client';

import React from 'react';
import {
    BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip,
    ResponsiveContainer, PieChart, Pie, Cell, Legend,
} from 'recharts';
import { Package, AlertTriangle, Layers, DollarSign, ShoppingCart, Tags, ArrowDownLeft, ArrowUpRight, Building2 } from 'lucide-react';
import { StatCard } from './StatCard';
import { SectionChart } from './SectionChart';
import type { AnalyticsResponse } from '@/hooks/use-analytics';
import { cn } from '@/lib/utils';

type Data = AnalyticsResponse['data'];
interface SectionProps { data: Data; }

const COLORS = {
    orange: '#f97316', emerald: '#10b981', sky: '#0ea5e9',
    red: '#ef4444', amber: '#f59e0b', purple: '#8b5cf6',
    rose: '#f43f5e', indigo: '#6366f1',
};
const PALETTE = [COLORS.orange, COLORS.sky, COLORS.emerald, COLORS.purple, COLORS.amber, COLORS.rose, COLORS.indigo];

export function InventorySection({ data }: SectionProps) {
    const inv = data.inventory;

    const totalItems = inv?.total_items ?? 0;
    const totalValue = inv?.total_value ?? 0;
    const criticalCount = inv?.critical_count ?? 0;
    const pendingPo = inv?.pending_po_count ?? 0;
    const totalCat = inv?.total_categories ?? 0;
    const itemsIn = inv?.items_in_month ?? 0;
    const itemsOut = inv?.items_out_month ?? 0;

    // Low stock items untuk horizontal bar
    const lowStockData = inv?.low_stock_items?.slice(0, 7).map(item => ({
        name: item.name.length > 18 ? item.name.slice(0, 18) + '…' : item.name,
        stock: item.stock,
        min: item.min_stock,
    })) ?? [];

    // By category donut
    const categoryData = inv?.by_category && inv.by_category.length > 0 
        ? inv.by_category.map((c, i) => ({
            name: c.category,
            value: c.count,
            color: PALETTE[i % PALETTE.length],
        })) 
        : [{ name: 'Belum ada data', value: 1, color: '#3f3f46' }];

    // Mutasi stok 
    const mutasiData = inv?.mutasi_stok_bulanan ?? [];

    return (
        <div className="space-y-6">
            {/* 9 KPI Cards */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatCard title="Low Stock Alert" value={inv?.low_stock_count ?? 0} sub="Item hampir habis" icon={AlertTriangle} color="orange" up={(inv?.low_stock_count ?? 0) === 0} />
                <StatCard title="Total Item SKU" value={totalItems.toLocaleString()} sub="Semua item terdaftar" icon={Package} color="emerald" />
                <StatCard title="Total Nilai Stok" value={totalValue > 0 ? `Rp ${(totalValue / 1e6).toFixed(1)}M` : '—'} sub="Valuasi gudang" icon={DollarSign} color="sky" />
                <StatCard title="Stok Kritis (≤0)" value={criticalCount} sub="Item habis total" icon={AlertTriangle} color="red" up={criticalCount === 0} />
                <StatCard title="PO Pending" value={pendingPo} sub="Purchase order aktif" icon={ShoppingCart} color="amber" />
                <StatCard title="Total Kategori" value={totalCat} sub="Kelompok item" icon={Tags} color="purple" />
                <StatCard title="Item Masuk Bulan Ini" value={itemsIn} sub="Penerimaan" icon={ArrowDownLeft} color="emerald" up={itemsIn > 0} />
                <StatCard title="Item Keluar Bulan Ini" value={itemsOut} sub="Pengeluaran" icon={ArrowUpRight} color="rose" />
                <StatCard title="Vendor / Supplier" value={inv?.total_vendors ?? 0} sub="Mitra Aktif" icon={Building2} color="indigo" />
            </div>

            {/* 4 Charts */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-4">
                {/* Chart 1: Top Low Stock Horizontal Bar (7 kol) */}
                <SectionChart title="Top Item Low Stock" className="lg:col-span-7" color="orange">
                    <div className="space-y-3 py-2">
                        {lowStockData.length > 0 ? lowStockData.map((item, i) => {
                            const pct = item.min > 0 ? Math.min(100, Math.round((item.stock / item.min) * 100)) : 100;
                            const getColor = () => {
                                if (pct === 0) return COLORS.red;
                                if (pct < 50) return COLORS.orange;
                                return COLORS.amber;
                            };
                            return (
                                <div key={i} className="space-y-1">
                                    <div className="flex items-center justify-between">
                                        <span className="text-xs text-zinc-400 truncate max-w-[180px]">{item.name}</span>
                                        <div className="flex items-center gap-2">
                                            <span className="text-xs font-bold" style={{ color: getColor() }}>{item.stock}</span>
                                            <span className="text-[10px] text-zinc-600">/ min {item.min}</span>
                                        </div>
                                    </div>
                                    <div className="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                        <div
                                            className="h-full rounded-full transition-all duration-1000"
                                            style={{ width: `${pct}%`, backgroundColor: getColor() }}
                                        />
                                    </div>
                                </div>
                            );
                        }) : (
                            <div className="flex items-center justify-center py-10 h-full">
                                <span className="text-xs text-zinc-500">Kondisi stok terpantau aman (tidak ada stok kritis).</span>
                            </div>
                        )}
                    </div>
                </SectionChart>

                {/* Chart 2: By Category Donut (5 kol) */}
                <SectionChart title="Distribusi per Kategori" className="lg:col-span-5" color="sky">
                    <ResponsiveContainer width="100%" height={220}>
                        <PieChart>
                            <Pie data={categoryData} cx="50%" cy="45%" innerRadius={50} outerRadius={75} paddingAngle={4} dataKey="value" stroke="none">
                                {categoryData.map((e, i) => <Cell key={i} fill={e.color} />)}
                            </Pie>
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} />
                            <Legend iconType="circle" iconSize={8} formatter={v => <span style={{ color: '#a1a1aa', fontSize: 10 }}>{v}</span>} />
                        </PieChart>
                    </ResponsiveContainer>
                </SectionChart>

                {/* Chart 3 & 4: Mutasi Stok Grouped Bar (full) */}
                <SectionChart title="Mutasi Stok Bulanan (Masuk vs Keluar)" className="lg:col-span-12" color="emerald">
                    <ResponsiveContainer width="100%" height={200}>
                        <BarChart data={mutasiData}>
                            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <YAxis axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} />
                            <Bar dataKey="masuk" name="Masuk" fill={COLORS.emerald} radius={[4, 4, 0, 0]} />
                            <Bar dataKey="keluar" name="Keluar" fill={COLORS.rose} radius={[4, 4, 0, 0]} />
                            <Legend iconType="circle" iconSize={8} formatter={v => <span style={{ color: '#a1a1aa', fontSize: 11 }}>{v}</span>} />
                        </BarChart>
                    </ResponsiveContainer>
                </SectionChart>
            </div>
        </div>
    );
}
