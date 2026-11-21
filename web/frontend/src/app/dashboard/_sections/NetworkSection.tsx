'use client';

import React from 'react';
import {
    AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip,
    ResponsiveContainer, BarChart, Bar, PieChart, Pie, Cell, Legend
} from 'recharts';
import { Activity, Wifi, Server, Globe, TrendingUp, Users, Cpu, Radio, ArrowUpRight } from 'lucide-react';
import { StatCard } from './StatCard';
import { SectionChart } from './SectionChart';
import type { AnalyticsResponse } from '@/hooks/use-analytics';
import { cn } from '@/lib/utils';

type Data = AnalyticsResponse['data'];
interface SectionProps { data: Data; }

const COLORS = {
    emerald: '#10b981', sky: '#0ea5e9', purple: '#8b5cf6',
    rose: '#f43f5e', amber: '#f59e0b', zinc: '#52525b', indigo: '#6366f1',
};

export function NetworkSection({ data }: SectionProps) {
    const n = data.network;
    const growth = data.customer_growth ?? [];

    const onlineRate = n.total_customers > 0
        ? Math.round((n.total_online / n.total_customers) * 100) : 0;

    const routerOnline = n.routers_online ?? n.routers?.filter(r => r.is_up).length ?? 0;
    const totalRouters = n.total_routers ?? n.routers?.length ?? 0;

    const onlineOfflineData = [
        { name: 'PPPoE Online', value: n.active_pppoe ?? 0, color: COLORS.emerald },
        { name: 'Hotspot', value: n.hotspot_active ?? 0, color: COLORS.sky },
        { name: 'Offline', value: n.total_offline ?? 0, color: COLORS.zinc },
    ];

    const growthData = growth.map(g => ({ name: g.month_name, total: g.total }));

    const routerBarData = n.routers?.map(r => ({
        name: r.name,
        status: r.is_up ? 1 : 0,
        statusLabel: r.is_up ? 'Online' : 'Offline',
    })) ?? [];

    const sessionTypeData = [
        { name: 'PPPoE', value: n.active_pppoe ?? 0, color: COLORS.emerald },
        { name: 'Hotspot', value: n.hotspot_active ?? 0, color: COLORS.sky },
    ];

    return (
        <div className="space-y-6">
            {/* 9 KPI Cards */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatCard title="PPPoE Active" value={n.active_pppoe ?? 0} sub="Sesi PPPoE aktif" icon={Activity} color="emerald" />
                <StatCard title="Total Online" value={n.total_online ?? 0} sub="Pelanggan terhubung" icon={Radio} color="sky" />
                <StatCard title="Total Offline" value={n.total_offline ?? 0} sub="Pelanggan terputus" icon={Wifi} color="rose" up={false} />
                <StatCard title="Hotspot Active" value={n.hotspot_active ?? 0} sub="User hotspot" icon={Wifi} color="purple" />
                <StatCard title="Router Online" value={`${routerOnline}/${totalRouters}`} sub="Router terkoneksi" icon={Server} color="emerald" up={routerOnline === totalRouters} />
                <StatCard title="Total Router" value={totalRouters} sub="Seluruh perangkat" icon={Cpu} color="zinc_neutral" />
                <StatCard title="Total Pelanggan" value={(n.total_customers ?? 0).toLocaleString()} sub="Semua subscriber" icon={Users} color="sky" />
                <StatCard title="% Online" value={`${onlineRate}%`} sub="Persentase online" icon={TrendingUp} color="amber" up={onlineRate > 90} />
                <StatCard title="Pelanggan Baru" value={n.new_customers_month ?? 0} sub="Bulan ini" icon={ArrowUpRight} color="indigo" />
            </div>

            {/* 4 Charts */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-4">
                {/* Chart 1: Pie — Komposisi Koneksi */}
                <SectionChart title="Komposisi Koneksi" className="lg:col-span-4" color="emerald">
                    <ResponsiveContainer width="100%" height={220}>
                        <PieChart>
                            <Pie data={onlineOfflineData} cx="50%" cy="45%" innerRadius={55} outerRadius={80} paddingAngle={4} dataKey="value">
                                {onlineOfflineData.map((e, i) => <Cell key={i} fill={e.color} />)}
                            </Pie>
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} />
                            <Legend iconType="circle" iconSize={8} formatter={v => <span style={{ color: '#a1a1aa', fontSize: 11 }}>{v}</span>} />
                        </PieChart>
                    </ResponsiveContainer>
                </SectionChart>

                {/* Chart 2: Status Router (Card List) */}
                <SectionChart title="Status Router / Node" className="lg:col-span-8" color="sky">
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-[220px] overflow-y-auto pr-1">
                        {n.routers?.length ? n.routers.map((r, i) => (
                            <div key={i} className="flex items-center justify-between p-3 rounded-xl bg-white/[0.03] border border-white/5 hover:border-white/10 transition-all">
                                <div className="flex items-center gap-2.5">
                                    <div className={cn('h-2 w-2 rounded-full', r.is_up ? 'bg-emerald-500 shadow-[0_0_6px_#10b981]' : 'bg-rose-500 shadow-[0_0_6px_#f43f5e]')} />
                                    <span className="text-xs font-semibold text-zinc-300 truncate max-w-[120px]">{r.name}</span>
                                </div>
                                <span className={cn('text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md', r.is_up ? 'text-emerald-400 bg-emerald-400/10' : 'text-rose-400 bg-rose-400/10')}>
                                    {r.is_up ? 'Online' : 'Offline'}
                                </span>
                            </div>
                        )) : (
                            <p className="text-zinc-600 text-xs col-span-2 text-center py-8">Belum ada data router</p>
                        )}
                    </div>
                </SectionChart>

                {/* Chart 3: PPPoE vs Hotspot Donut */}
                <SectionChart title="PPPoE vs Hotspot" className="lg:col-span-5" color="purple">
                    <div className="grid grid-cols-2 gap-4 h-[200px]">
                        {/* min-w-0 wajib agar ResponsiveContainer bisa kalkulasi width di grid */}
                        <div className="min-w-0">
                            <ResponsiveContainer width="100%" height={200}>
                                <PieChart>
                                    <Pie data={sessionTypeData} cx="50%" cy="50%" innerRadius={45} outerRadius={70} paddingAngle={4} dataKey="value">
                                        {sessionTypeData.map((e, i) => <Cell key={i} fill={e.color} />)}
                                    </Pie>
                                    <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} />
                                </PieChart>
                            </ResponsiveContainer>
                        </div>
                        <div className="flex flex-col justify-center gap-4">
                            {sessionTypeData.map((item, i) => (
                                <div key={i}>
                                    <div className="flex items-center justify-between mb-1">
                                        <div className="flex items-center gap-2">
                                            <div className="h-2 w-2 rounded-full" style={{ backgroundColor: item.color }} />
                                            <span className="text-xs text-zinc-400">{item.name}</span>
                                        </div>
                                        <span className="text-xs font-bold text-white">{item.value}</span>
                                    </div>
                                    <div className="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                        <div
                                            className="h-full rounded-full transition-all duration-1000"
                                            style={{
                                                width: `${(item.value / (n.active_pppoe + n.hotspot_active || 1)) * 100}%`,
                                                backgroundColor: item.color,
                                            }}
                                        />
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </SectionChart>

                {/* Chart 4: Customer Growth */}
                <SectionChart title="Pertumbuhan Pelanggan (6 Bulan)" className="lg:col-span-7" color="indigo">
                    <ResponsiveContainer width="100%" height={200}>
                        <AreaChart data={growthData}>
                            <defs>
                                <linearGradient id="net-growth" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="5%" stopColor={COLORS.indigo} stopOpacity={0.3} />
                                    <stop offset="95%" stopColor={COLORS.indigo} stopOpacity={0} />
                                </linearGradient>
                            </defs>
                            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <YAxis axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} itemStyle={{ color: COLORS.indigo }} />
                            <Area type="monotone" dataKey="total" stroke={COLORS.indigo} strokeWidth={2.5} fill="url(#net-growth)" />
                        </AreaChart>
                    </ResponsiveContainer>
                </SectionChart>
            </div>
        </div>
    );
}
