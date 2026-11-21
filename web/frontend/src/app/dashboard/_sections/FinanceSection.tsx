'use client';

import React from 'react';
import {
    AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip,
    ResponsiveContainer, BarChart, Bar, PieChart, Pie, Cell, Legend
} from 'recharts';
import { CreditCard, TrendingUp, Wallet, AlertTriangle, FileText, Banknote, QrCode, ArrowDownRight, DollarSign } from 'lucide-react';
import { StatCard } from './StatCard';
import { SectionChart } from './SectionChart';
import type { AnalyticsResponse } from '@/hooks/use-analytics';

type Data = AnalyticsResponse['data'];
interface SectionProps { data: Data; }

const COLORS = {
    emerald: '#10b981', sky: '#0ea5e9', purple: '#8b5cf6',
    rose: '#f43f5e', amber: '#f59e0b', orange: '#f97316',
};

export function FinanceSection({ data }: SectionProps) {
    const f = data.finance;
    const inv = data.invoices;
    const pd = data.payment_dist;

    const revenueData = f.monthly_revenue?.map(item => ({
        name: item.month_name || `M${item.month}`,
        value: Number(item.total),
    })) ?? [];

    const totalPayment = (pd?.cash ?? 0) + (pd?.manual_transfer ?? 0) + (pd?.payment_gateway ?? 0);

    const paymentData = totalPayment > 0 ? [
        { name: 'Cash', value: pd?.cash ?? 0, color: COLORS.emerald, realValue: pd?.cash ?? 0 },
        { name: 'Transfer', value: pd?.manual_transfer ?? 0, color: COLORS.sky, realValue: pd?.manual_transfer ?? 0 },
        { name: 'Gateway', value: pd?.payment_gateway ?? 0, color: COLORS.purple, realValue: pd?.payment_gateway ?? 0 },
    ] : [{ name: 'Belum ada data', value: 1, color: '#3f3f46', realValue: 0 }];

    // Monthly invoice stats (gunakan fallback jika backend belum siap)
    const invoiceStatsData = f.monthly_invoice_stats?.map(item => ({
        name: item.month_name,
        paid: item.paid,
        unpaid: item.unpaid,
    })) ?? revenueData.map(r => ({ name: r.name, paid: r.value, unpaid: 0 }));

    // Total payment sudah dihitung di awal sebelum paymentData.

    return (
        <div className="space-y-6">
            {/* 9 KPI Cards */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatCard title="Revenue YTD" value={`Rp ${((f?.total_ytd ?? 0) / 1e6).toFixed(1)}M`} sub="Total penagihan lunas" icon={TrendingUp} color="emerald" up />
                <StatCard title="Revenue Bulan Ini" value={`Rp ${((f?.month_revenue ?? 0) / 1e6).toFixed(1)}M`} sub="MTD" icon={CreditCard} color="sky" />
                <StatCard title="Collection Rate" value={`${f?.collection_rate ?? 0}%`} sub="Rasio invoice terbayar" icon={TrendingUp} color="purple" up={(f?.collection_rate ?? 0) > 80} />
                <StatCard title="Piutang Outstanding" value={`Rp ${((f?.unpaid_receivables ?? 0) / 1e6).toFixed(1)}M`} sub="Belum terbayar" icon={Wallet} color="rose" up={false} />
                <StatCard title="Invoice Overdue" value={inv?.overdue_count ?? 0} sub="Melewati jatuh tempo" icon={AlertTriangle} color="orange" up={false} />
                <StatCard title="Invoice Belum Bayar" value={inv?.unpaid_count ?? 0} sub="Total unpaid" icon={FileText} color="amber" />
                <StatCard title="Pembayaran Cash" value={`Rp ${((pd?.cash ?? 0) / 1e6).toFixed(1)}M`} sub="Bulan ini" icon={Banknote} color="emerald" />
                <StatCard title="Pembayaran Transfer" value={`Rp ${((pd?.manual_transfer ?? 0) / 1e6).toFixed(1)}M`} sub="Bulan ini" icon={DollarSign} color="sky" />
                <StatCard title="Pembayaran Gateway" value={`Rp ${((pd?.payment_gateway ?? 0) / 1e6).toFixed(1)}M`} sub="QRIS/VA/eWallet" icon={QrCode} color="purple" />
            </div>

            {/* 4 Charts */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-4">
                {/* Chart 1: Revenue Area (8 kol) */}
                <SectionChart title="Revenue Per Bulan (YTD)" className="lg:col-span-8" color="emerald">
                    <ResponsiveContainer width="100%" height={220}>
                        <AreaChart data={revenueData}>
                            <defs>
                                <linearGradient id="fin-rev" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="5%" stopColor={COLORS.emerald} stopOpacity={0.3} />
                                    <stop offset="95%" stopColor={COLORS.emerald} stopOpacity={0} />
                                </linearGradient>
                            </defs>
                            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <YAxis axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} tickFormatter={v => `${(v / 1e6).toFixed(0)}M`} />
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} itemStyle={{ color: COLORS.emerald }} formatter={(v) => [`Rp ${(Number(v) / 1e6).toFixed(1)}M`, 'Revenue']} />
                            <Area type="monotone" dataKey="value" stroke={COLORS.emerald} strokeWidth={2.5} fill="url(#fin-rev)" />
                        </AreaChart>
                    </ResponsiveContainer>
                </SectionChart>

                {/* Chart 2: Donut Metode Pembayaran (4 kol) */}
                <SectionChart title="Metode Pembayaran" className="lg:col-span-4" color="purple">
                    <div className="flex flex-col items-center h-[220px]">
                        <ResponsiveContainer width="100%" height={160}>
                            <PieChart>
                                <Pie data={paymentData} cx="50%" cy="50%" innerRadius={45} outerRadius={70} paddingAngle={4} dataKey="value">
                                    {paymentData.map((e, i) => <Cell key={i} fill={e.color} />)}
                                </Pie>
                                <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} formatter={(v) => [`Rp ${(Number(v) / 1e6).toFixed(1)}M`]} />
                            </PieChart>
                        </ResponsiveContainer>
                        <div className="flex gap-4 mt-1">
                            {paymentData.map((item, i) => (
                                <div key={i} className="flex flex-col items-center gap-1">
                                    <div className="flex items-center gap-1">
                                        <div className="h-1.5 w-1.5 rounded-full" style={{ backgroundColor: item.color }} />
                                        <span className="text-[10px] text-zinc-500">{item.name}</span>
                                    </div>
                                    <span className="text-[10px] font-bold text-zinc-300">{totalPayment > 0 ? Math.round(item.realValue / totalPayment * 100) : 0}%</span>
                                </div>
                            ))}
                        </div>
                    </div>
                </SectionChart>

                {/* Chart 3: Invoice Paid vs Unpaid (6 kol) */}
                <SectionChart title="Invoice Paid vs Unpaid Per Bulan" className="lg:col-span-6" color="sky">
                    <ResponsiveContainer width="100%" height={200}>
                        <BarChart data={invoiceStatsData}>
                            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <YAxis axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} tickFormatter={v => `${(v / 1e6).toFixed(0)}M`} />
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} formatter={(v) => [`Rp ${(Number(v) / 1e6).toFixed(1)}M`]} />
                            <Bar dataKey="paid" name="Lunas" fill={COLORS.emerald} radius={[4, 4, 0, 0]} />
                            <Bar dataKey="unpaid" name="Belum Bayar" fill={COLORS.rose} radius={[4, 4, 0, 0]} />
                            <Legend iconType="circle" iconSize={8} formatter={v => <span style={{ color: '#a1a1aa', fontSize: 11 }}>{v}</span>} />
                        </BarChart>
                    </ResponsiveContainer>
                </SectionChart>

                {/* Chart 4: Piutang Trend (6 kol) */}
                <SectionChart title="Trend Piutang (YTD)" className="lg:col-span-6" color="rose">
                    <ResponsiveContainer width="100%" height={200}>
                        <AreaChart data={revenueData}>
                            <defs>
                                <linearGradient id="fin-debt" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="5%" stopColor={COLORS.rose} stopOpacity={0.25} />
                                    <stop offset="95%" stopColor={COLORS.rose} stopOpacity={0} />
                                </linearGradient>
                            </defs>
                            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <YAxis axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} tickFormatter={v => `${(v / 1e6).toFixed(0)}M`} />
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} itemStyle={{ color: COLORS.rose }} formatter={(v) => [`Rp ${(Number(v) / 1e6).toFixed(1)}M`, 'Piutang']} />
                            <Area type="monotone" dataKey="value" stroke={COLORS.rose} strokeWidth={2} fill="url(#fin-debt)" strokeDasharray="4 2" />
                        </AreaChart>
                    </ResponsiveContainer>
                </SectionChart>
            </div>
        </div>
    );
}
