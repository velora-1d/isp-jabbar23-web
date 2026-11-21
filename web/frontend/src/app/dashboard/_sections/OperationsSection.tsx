'use client';

import React from 'react';
import {
    AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip,
    ResponsiveContainer, BarChart, Bar, PieChart, Pie, Cell, Legend,
} from 'recharts';
import { Ticket, ClipboardList, CheckCircle, AlertOctagon, Clock, Shield, UserCheck, Activity, ArrowUpRight } from 'lucide-react';
import { StatCard } from './StatCard';
import { SectionChart } from './SectionChart';
import type { AnalyticsResponse } from '@/hooks/use-analytics';

type Data = AnalyticsResponse['data'];
interface SectionProps { data: Data; }

const COLORS = {
    emerald: '#10b981', sky: '#0ea5e9', amber: '#f59e0b',
    rose: '#f43f5e', red: '#ef4444', indigo: '#6366f1', purple: '#8b5cf6',
};

export function OperationsSection({ data }: SectionProps) {
    const tk = data.tickets;
    const wo = data.work_orders;
    const st = data.staff;

    const closedThisMonth = tk?.closed_this_month ?? 0;
    const slaBreached = tk?.sla_breached_count ?? 0;
    const inProgressWo = wo?.in_progress_count ?? 0;

    // Ticket status donut
    const ticketData = [
        { name: 'Open', value: tk?.open_count ?? 0, color: COLORS.rose },
        { name: 'In Progress', value: tk?.in_progress_count ?? 0, color: COLORS.sky },
        { name: 'Closed', value: closedThisMonth, color: COLORS.emerald },
    ].filter(d => d.value > 0);

    // WO status bar
    const woData = [
        { name: 'Pending', value: wo?.pending_count ?? 0, fill: COLORS.amber },
        { name: 'In Progress', value: inProgressWo, fill: COLORS.sky },
        { name: 'Selesai', value: wo?.completed_this_month ?? 0, fill: COLORS.emerald },
    ];

    // Ticket trend (fallback kosong jika backend belum ready)
    const ticketTrend = tk?.monthly_trend?.map(t => ({ name: t.month_name, total: t.total }))
        ?? Array.from({ length: 6 }, (_, i) => ({ name: `M${i + 1}`, total: 0 }));

    // Attendance last 7 days (placeholder — backend menyusul)
    const attendanceDays = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    const attendanceData = attendanceDays.map((d, i) => ({
        name: d,
        hadir: i === 6 ? 0 : (st?.staff_online ?? 0),
    }));

    return (
        <div className="space-y-6">
            {/* 9 KPI Cards */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatCard title="Open Tickets" value={tk?.open_count ?? 0} sub="Belum direspon" icon={Ticket} color="rose" up={false} />
                <StatCard title="In Progress" value={tk?.in_progress_count ?? 0} sub="Sedang dikerjakan" icon={Clock} color="sky" />
                <StatCard title="Closed Bulan Ini" value={closedThisMonth} sub="Ticket selesai" icon={CheckCircle} color="emerald" up={closedThisMonth > 0} />
                <StatCard title="SLA Breached" value={slaBreached} sub="Melebihi SLA" icon={AlertOctagon} color="red" up={slaBreached === 0} />
                <StatCard title="Pending WO" value={wo?.pending_count ?? 0} sub="Antrian work order" icon={ClipboardList} color="amber" />
                <StatCard title="WO In Progress" value={inProgressWo} sub="WO dikerjakan" icon={Activity} color="sky" />
                <StatCard title="WO Selesai Bulan Ini" value={wo?.completed_this_month ?? 0} sub="WO completed" icon={CheckCircle} color="emerald" up={(wo?.completed_this_month ?? 0) > 0} />
                <StatCard title="Staff Hadir" value={`${st?.staff_online ?? 0}/${st?.total_staff ?? 0}`} sub="Hari ini" icon={UserCheck} color="indigo" />
                <StatCard title="Attendance Rate" value={`${st?.attendance_rate ?? 0}%`} sub="Kehadiran" icon={Shield} color="purple" up={(st?.attendance_rate ?? 0) >= 80} />
            </div>

            {/* 4 Charts */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-4">
                {/* Chart 1: Ticket Status Donut (5 kol) */}
                <SectionChart title="Distribusi Status Ticket" className="lg:col-span-5" color="rose">
                    <ResponsiveContainer width="100%" height={220}>
                        <PieChart>
                            <Pie data={ticketData.length ? ticketData : [{ name: 'Tidak ada', value: 1, color: '#27272a' }]}
                                cx="50%" cy="45%" innerRadius={55} outerRadius={80} paddingAngle={4} dataKey="value">
                                {(ticketData.length ? ticketData : [{ name: 'Tidak ada', value: 1, color: '#27272a' }])
                                    .map((e, i) => <Cell key={i} fill={e.color} />)}
                            </Pie>
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} />
                            <Legend iconType="circle" iconSize={8} formatter={v => <span style={{ color: '#a1a1aa', fontSize: 11 }}>{v}</span>} />
                        </PieChart>
                    </ResponsiveContainer>
                </SectionChart>

                {/* Chart 2: WO Status Horizontal Bar (7 kol) */}
                <SectionChart title="Status Work Order" className="lg:col-span-7" color="amber">
                    <div className="space-y-4 py-4 px-2">
                        {woData.map((w, i) => {
                            const total = woData.reduce((a, b) => a + b.value, 0) || 1;
                            const pct = Math.round((w.value / total) * 100);
                            return (
                                <div key={i} className="space-y-1.5">
                                    <div className="flex items-center justify-between">
                                        <span className="text-xs text-zinc-400 font-medium">{w.name}</span>
                                        <div className="flex items-center gap-2">
                                            <span className="text-xs font-bold text-white">{w.value}</span>
                                            <span className="text-[10px] text-zinc-600">{pct}%</span>
                                        </div>
                                    </div>
                                    <div className="h-2 bg-white/5 rounded-full overflow-hidden">
                                        <div
                                            className="h-full rounded-full transition-all duration-1000"
                                            style={{ width: `${pct}%`, backgroundColor: w.fill }}
                                        />
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </SectionChart>

                {/* Chart 3: Ticket Trend Area (7 kol) */}
                <SectionChart title="Trend Ticket (6 Bulan)" className="lg:col-span-7" color="sky">
                    <ResponsiveContainer width="100%" height={200}>
                        <AreaChart data={ticketTrend}>
                            <defs>
                                <linearGradient id="ops-ticket" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="5%" stopColor={COLORS.sky} stopOpacity={0.3} />
                                    <stop offset="95%" stopColor={COLORS.sky} stopOpacity={0} />
                                </linearGradient>
                            </defs>
                            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <YAxis axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} itemStyle={{ color: COLORS.sky }} />
                            <Area type="monotone" dataKey="total" name="Ticket" stroke={COLORS.sky} strokeWidth={2.5} fill="url(#ops-ticket)" />
                        </AreaChart>
                    </ResponsiveContainer>
                </SectionChart>

                {/* Chart 4: Kehadiran Harian (5 kol) */}
                <SectionChart title="Kehadiran Tim (7 Hari)" className="lg:col-span-5" color="indigo">
                    <ResponsiveContainer width="100%" height={200}>
                        <BarChart data={attendanceData}>
                            <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} />
                            <YAxis axisLine={false} tickLine={false} tick={{ fill: '#52525b', fontSize: 10 }} domain={[0, st?.total_staff || 10]} />
                            <Tooltip contentStyle={{ background: '#09090b', border: '1px solid #27272a', borderRadius: 12 }} itemStyle={{ color: COLORS.indigo }} />
                            <Bar dataKey="hadir" name="Hadir" fill={COLORS.indigo} radius={[4, 4, 0, 0]} />
                        </BarChart>
                    </ResponsiveContainer>
                </SectionChart>
            </div>
        </div>
    );
}
