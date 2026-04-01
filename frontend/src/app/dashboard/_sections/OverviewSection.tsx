'use client';

import React from 'react';
import {
    AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip,
    ResponsiveContainer, BarChart, Bar, PieChart, Pie, Cell, Legend
} from 'recharts';
import {
    Users, CreditCard, Ticket, Activity, Package,
    ClipboardList, Zap, Wallet, TrendingUp, UserCheck
} from 'lucide-react';
import { StatCard } from './StatCard';
import { SectionChart } from './SectionChart';
import type { AnalyticsResponse } from '@/hooks/use-analytics';

type Data = AnalyticsResponse['data'];

const COLORS = {
    emerald: '#10b981', sky: '#0ea5e9', purple: '#8b5cf6',
    rose: '#f43f5e', amber: '#f59e0b', orange: '#f97316',
    indigo: '#6366f1', zinc: '#71717a', red: '#ef4444',
};

interface SectionProps { data: Data; }

// ─────────────────────────────────────────────
// OVERVIEW SECTION
// ─────────────────────────────────────────────
export function OverviewSection({ data }: SectionProps) {
    const revenueData = data.finance?.monthly_revenue?.map(item => ({
        name: item.month_name || `M${item.month}`,
        value: Number(item.total),
    })) ?? [];

    const growthData = data.customer_growth?.map(item => ({
        name: item.month_name,
        total: item.total,
    })) ?? [];

    const totalPayment = (data.payment_dist?.cash ?? 0) + (data.payment_dist?.manual_transfer ?? 0) + (data.payment_dist?.payment_gateway ?? 0);
    const paymentData = totalPayment > 0 ? [
        { name: 'Cash', value: data.payment_dist?.cash ?? 0, color: COLORS.emerald },
        { name: 'Transfer', value: data.payment_dist?.manual_transfer ?? 0, color: COLORS.sky },
        { name: 'Gateway', value: data.payment_dist?.payment_gateway ?? 0, color: COLORS.purple },
    ] : [{ name: 'Belum ada data', value: 1, color: '#3f3f46' }];

    const onlineRate = data.network?.total_customers > 0
        ? Math.round((data.network.total_online / data.network.total_customers) * 100)
        : 0;

    const onlineOfflineData = (data.network?.total_online ?? 0) + (data.network?.total_offline ?? 0) > 0 ? [
        { name: 'Online', value: data.network?.total_online ?? 0, color: COLORS.emerald },
        { name: 'Offline', value: data.network?.total_offline ?? 0, color: COLORS.zinc },
    ] : [{ name: 'Belum ada data', value: 1, color: '#3f3f46' }];

    return (
        <div className="space-y-6">
            {/* 9 KPI Cards — 3×3 grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatCard title="Total Pelanggan" value={(data.network?.total_customers ?? 0).toLocaleString()} sub={`${data.network?.total_online ?? 0} online`} icon={Users} color="emerald" />
                <StatCard title="Revenue YTD" value={`Rp ${((data.finance?.total_ytd ?? 0) / 1e6).toFixed(1)}M`} sub="Total penagihan lunas" icon={TrendingUp} color="sky" />
                <StatCard title="Collection Rate" value={`${data.finance?.collection_rate ?? 0}%`} sub="Rasio invoice terbayar" icon={CreditCard} color="purple" up={(data.finance?.collection_rate ?? 0) > 80} />
                <StatCard title="PPPoE Active" value={(data.network?.active_pppoe ?? 0).toLocaleString()} sub="Sesi aktif" icon={Activity} color="emerald" />
                <StatCard title="Open Tickets" value={data.tickets?.open_count ?? 0} sub="Belum direspon" icon={Ticket} color="rose" />
                <StatCard title="Pending WO" value={data.work_orders?.pending_count ?? 0} sub="Work order antrian" icon={ClipboardList} color="amber" />
                <StatCard title="Revenue Bulan Ini" value={`Rp ${((data.finance?.month_revenue ?? 0) / 1e6).toFixed(1)}M`} sub="MTD" icon={Wallet} color="sky" />
                <StatCard title="Staff Hadir" value={`${data.staff?.staff_online ?? 0}/${data.staff?.total_staff ?? 0}`} sub={`${data.staff?.attendance_rate ?? 0}% attendance`} icon={UserCheck} color="indigo" />
                <StatCard title="Low Stock Alert" value={data.inventory?.low_stock_count ?? 0} sub="Item stok hampir habis" icon={Package} color="orange" up={(data.inventory?.low_stock_count ?? 0) === 0} />
            </div>

            {/* 4 Charts */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <SectionChart title="Revenue Trend YTD" className="lg:col-span-8" color="emerald">
                    <ResponsiveContainer width="100%" height={220}>
                        <AreaChart data={revenueData}>
                            <defs>
                                <linearGradient id="ov-rev" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="5%" stopColor={COLORS.emerald} stopOpacity={0.3} />
                                    <stop offset="95%" stopColor={COLORS.emerald} stopOpacity={0} />
                                </linearGradient>
                            </defs>
                            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <YAxis axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} tickFormatter={v => `${(v / 1e6).toFixed(0)}M`} />
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} itemStyle={{ color: COLORS.emerald }} formatter={(v) => [`Rp ${(Number(v) / 1e6).toFixed(1)}M`, 'Revenue']} />
                            <Area type="monotone" dataKey="value" stroke={COLORS.emerald} strokeWidth={2.5} fill="url(#ov-rev)" />
                        </AreaChart>
                    </ResponsiveContainer>
                </SectionChart>

                <SectionChart title="Rasio Pelanggan" className="lg:col-span-4" color="sky">
                    <ResponsiveContainer width="100%" height={220}>
                        <PieChart>
                            <Pie data={onlineOfflineData} cx="50%" cy="45%" innerRadius={55} outerRadius={80} paddingAngle={4} dataKey="value">
                                {onlineOfflineData.map((e, i) => <Cell key={i} fill={e.color} />)}
                            </Pie>
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} />
                            <Legend iconType="circle" iconSize={8} formatter={(v) => <span style={{ color: '#a1a1aa', fontSize: 11 }}>{v}</span>} />
                        </PieChart>
                    </ResponsiveContainer>
                </SectionChart>

                <SectionChart title="Customer Growth (6 Bulan)" className="lg:col-span-5" color="sky">
                    <ResponsiveContainer width="100%" height={200}>
                        <BarChart data={growthData}>
                            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <YAxis axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} />
                            <Bar dataKey="total" fill={COLORS.sky} radius={[4, 4, 0, 0]} />
                        </BarChart>
                    </ResponsiveContainer>
                </SectionChart>

                <SectionChart title="Distribusi Pembayaran" className="lg:col-span-7" color="purple">
                    <div className="grid grid-cols-2 gap-4 h-[200px]">
                        {/* Donut chart — harus dalam div dengan min-w-0 agar ResponsiveContainer bisa kalkulasi width */}
                        <div className="min-w-0">
                            <ResponsiveContainer width="100%" height={200}>
                                <PieChart>
                                    <Pie data={paymentData} cx="50%" cy="50%" innerRadius={50} outerRadius={75} paddingAngle={4} dataKey="value">
                                        {paymentData.map((e, i) => <Cell key={i} fill={e.color} />)}
                                    </Pie>
                                    <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} formatter={(v) => [`Rp ${(Number(v) / 1e6).toFixed(1)}M`]} />
                                </PieChart>
                            </ResponsiveContainer>
                        </div>
                        <div className="flex flex-col justify-center gap-3 pr-2">
                            {paymentData.map((item, i) => (
                                <div key={i} className="flex items-center justify-between">
                                    <div className="flex items-center gap-2">
                                        <div className="h-2 w-2 rounded-full" style={{ backgroundColor: item.color }} />
                                        <span className="text-xs text-zinc-400">{item.name}</span>
                                    </div>
                                    <span className="text-xs font-bold text-white">Rp {(item.value / 1e6).toFixed(1)}M</span>
                                </div>
                            ))}
                        </div>
                    </div>
                </SectionChart>
            </div>
        </div>
    );
}
