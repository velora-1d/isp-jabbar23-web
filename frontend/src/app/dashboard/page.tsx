'use client';

import React from 'react';
import { useAuth } from '@/hooks/use-auth';
import { DashboardPageShell } from '@/components/dashboard/page-shell';
import { 
    Users, 
    CreditCard, 
    Ticket, 
    Activity, 
    ArrowUpRight, 
    ArrowDownRight,
    TrendingUp,
    Zap,
    Globe,
    ShieldCheck
} from 'lucide-react';
import { 
    AreaChart, 
    Area, 
    XAxis, 
    YAxis, 
    CartesianGrid, 
    Tooltip, 
    ResponsiveContainer,
    BarChart,
    Bar,
    Cell
} from 'recharts';
import { cn } from '@/lib/utils';

// Dummy Data untuk Chart
const revenueData = [
    { name: 'Jan', value: 45000000 },
    { name: 'Feb', value: 52000000 },
    { name: 'Mar', value: 48000000 },
    { name: 'Apr', value: 61000000 },
    { name: 'May', value: 55000000 },
    { name: 'Jun', value: 67000000 },
];

const customerGrowth = [
    { name: 'W1', value: 12 },
    { name: 'W2', value: 18 },
    { name: 'W3', value: 15 },
    { name: 'W4', value: 25 },
];

export default function DashboardPage() {
    const { user, isLoading } = useAuth();

    if (isLoading) {
        return (
            <div className="flex h-[80vh] items-center justify-center">
                <div className="flex flex-col items-center gap-4">
                    <div className="h-10 w-10 animate-spin rounded-full border-2 border-emerald-500 border-t-transparent shadow-[0_0_15px_rgba(34,197,94,0.3)]"></div>
                    <p className="text-zinc-400 animate-pulse text-sm">Menghubungkan ke Uplink...</p>
                </div>
            </div>
        );
    }

    if (!user) return null;

    return (
        <DashboardPageShell
            title={`Dashboard Overview`}
            description={`Selamat datang di Mission Control, ${user.name}. Berikut ringkasan operasional JABBAR23 hari ini.`}
        >
            <div className="space-y-8">
                {/* 1. Quick Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <StatCard 
                        title="Total Pelanggan" 
                        value="1,284" 
                        change="+12.5%" 
                        isUp={true} 
                        icon={Users} 
                        color="emerald"
                    />
                    <StatCard 
                        title="Pendapatan (Mar)" 
                        value="Rp 67.2M" 
                        change="+8.2%" 
                        isUp={true} 
                        icon={CreditCard} 
                        color="sky"
                    />
                    <StatCard 
                        title="Tiket Terbuka" 
                        value="24" 
                        change="-4" 
                        isUp={false} 
                        icon={Ticket} 
                        color="amber"
                    />
                    <StatCard 
                        title="Uptime Jaringan" 
                        value="99.98%" 
                        change="Stable" 
                        isUp={true} 
                        icon={Activity} 
                        color="rose"
                    />
                </div>

                {/* 2. Main Analytics Section */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Revenue Trend Chart */}
                    <div className="lg:col-span-2 rounded-2xl border border-white/5 bg-white/[0.02] p-6">
                        <div className="flex items-center justify-between mb-8">
                            <div>
                                <h3 className="text-lg font-heading font-bold text-white flex items-center gap-2">
                                    <TrendingUp className="h-4 w-4 text-emerald-400" />
                                    Tren Pendapatan Bulanan
                                </h3>
                                <p className="text-xs text-zinc-500 mt-1">Akumulasi pembayaran invoice lunas</p>
                            </div>
                            <div className="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-tight">
                                High Growth
                            </div>
                        </div>
                        
                        <div className="h-[300px] w-full">
                            <ResponsiveContainer width="100%" height="100%">
                                <AreaChart data={revenueData}>
                                    <defs>
                                        <linearGradient id="colorValue" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="5%" stopColor="#10b981" stopOpacity={0.3}/>
                                            <stop offset="95%" stopColor="#10b981" stopOpacity={0}/>
                                        </linearGradient>
                                    </defs>
                                    <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                                    <XAxis 
                                        dataKey="name" 
                                        axisLine={false} 
                                        tickLine={false} 
                                        tick={{fill: '#71717a', fontSize: 10}}
                                        dy={10}
                                    />
                                    <YAxis 
                                        hide 
                                    />
                                    <Tooltip 
                                        contentStyle={{ backgroundColor: '#09090b', borderColor: '#27272a', color: '#fff' }}
                                        itemStyle={{ color: '#10b981' }}
                                        formatter={(value: any) => [`Rp ${value.toLocaleString()}`, 'Revenue']}
                                    />
                                    <Area 
                                        type="monotone" 
                                        dataKey="value" 
                                        stroke="#10b981" 
                                        strokeWidth={2}
                                        fillOpacity={1} 
                                        fill="url(#colorValue)" 
                                    />
                                </AreaChart>
                            </ResponsiveContainer>
                        </div>
                    </div>

                    {/* Side Widgets */}
                    <div className="space-y-6">
                        {/* Quick Actions / Integration Status */}
                        <div className="rounded-2xl border border-white/5 bg-white/[0.02] p-5">
                            <h3 className="text-sm font-bold text-zinc-300 mb-4 uppercase tracking-widest flex items-center gap-2">
                                <Zap className="h-4 w-4 text-emerald-400" />
                                System Health
                            </h3>
                            <div className="space-y-4">
                                <StatusRow label="Mikrotik Core" status="Online" active={true} />
                                <StatusRow label="OLT ZTE PR01" status="Online" active={true} />
                                <StatusRow label="Radius Server" status="Warning" active={false} isWarning={true} />
                                <StatusRow label="Fonnte API" status="Online" active={true} />
                            </div>
                        </div>

                        {/* Recent Activity */}
                        <div className="rounded-2xl border border-white/5 bg-white/[0.02] p-5 flex-1">
                            <h3 className="text-sm font-bold text-zinc-300 mb-4 uppercase tracking-widest flex items-center gap-2">
                                <Activity className="h-4 w-4 text-blue-400" />
                                Activity Feed
                            </h3>
                            <div className="space-y-4">
                                <ActivityItem 
                                    label="Invoice Paid" 
                                    desc="Pelanggan #1024 lunas" 
                                    time="2m ago" 
                                    icon={ShieldCheck} 
                                    iconColor="text-emerald-500" 
                                />
                                <ActivityItem 
                                    label="New Lead" 
                                    desc="Inquiry dari website" 
                                    time="15m ago" 
                                    icon={Globe} 
                                    iconColor="text-blue-500" 
                                />
                                <ActivityItem 
                                    label="Ticket Closed" 
                                    desc="Gangguan ODP-02 selesai" 
                                    time="1h ago" 
                                    icon={Ticket} 
                                    iconColor="text-purple-500" 
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </DashboardPageShell>
    );
}

// --- Subcomponents ---

function StatCard({ title, value, change, isUp, icon: Icon, color }: any) {
    const colorMap: any = {
        emerald: "bg-emerald-500/10 text-emerald-500 border-emerald-500/20 shadow-emerald-500/10",
        sky: "bg-sky-500/10 text-sky-500 border-sky-500/20 shadow-sky-500/10",
        amber: "bg-amber-500/10 text-amber-500 border-amber-500/20 shadow-amber-500/10",
        rose: "bg-rose-500/10 text-rose-500 border-rose-500/20 shadow-rose-500/10",
    };

    return (
        <div className="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/[0.02] p-5 transition-all hover:border-white/10 hover:bg-white/[0.04]">
            <div className="flex items-start justify-between">
                <div className={cn("p-2 rounded-lg border", colorMap[color])}>
                    <Icon className="h-5 w-5" />
                </div>
                <div className={cn(
                    "flex items-center gap-1 text-[10px] font-bold px-1.5 py-0.5 rounded",
                    isUp ? "text-emerald-400 bg-emerald-400/10" : "text-rose-400 bg-rose-400/10"
                )}>
                    {isUp ? <ArrowUpRight className="h-3 w-3" /> : <ArrowDownRight className="h-3 w-3" />}
                    {change}
                </div>
            </div>
            
            <div className="mt-4">
                <p className="text-xs font-medium text-zinc-500 uppercase tracking-wider">{title}</p>
                <p className="text-2xl font-heading font-bold text-white mt-1">{value}</p>
            </div>
            
            {/* Visual Flare */}
            <div className={cn(
                "absolute -bottom-6 -right-6 w-16 h-16 rounded-full blur-2xl opacity-0 group-hover:opacity-20 transition-opacity duration-500",
                color === 'emerald' ? 'bg-emerald-500' : 
                color === 'sky' ? 'bg-sky-500' : 
                color === 'amber' ? 'bg-amber-500' : 'bg-rose-500'
            )} />
        </div>
    );
}

function StatusRow({ label, status, active, isWarning }: any) {
    return (
        <div className="flex items-center justify-between group">
            <span className="text-xs text-zinc-400 group-hover:text-zinc-200 transition-colors">{label}</span>
            <div className="flex items-center gap-2">
                <span className={cn(
                    "text-[10px] font-bold uppercase tracking-tight",
                    active ? "text-emerald-400" : isWarning ? "text-amber-400" : "text-rose-400"
                )}>
                    {status}
                </span>
                <div className={cn(
                    "h-1.5 w-1.5 rounded-full animate-pulse",
                    active ? "bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]" : 
                    isWarning ? "bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]" : 
                    "bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]"
                )} />
            </div>
        </div>
    );
}

function ActivityItem({ label, desc, time, icon: Icon, iconColor }: any) {
    return (
        <div className="flex gap-3">
            <div className={cn("mt-1 p-1.5 rounded-md bg-white/[0.03] border border-white/5", iconColor)}>
                <Icon className="h-3 w-3" />
            </div>
            <div className="flex-1 min-w-0">
                <div className="flex items-center justify-between gap-2">
                    <p className="text-xs font-semibold text-zinc-200 truncate">{label}</p>
                    <span className="text-[10px] text-zinc-600 whitespace-nowrap">{time}</span>
                </div>
                <p className="text-[10px] text-zinc-500 truncate">{desc}</p>
            </div>
        </div>
    );
}
