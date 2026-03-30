'use client';

import React from 'react';
import { useAuth } from '@/hooks/use-auth';
import { useAnalytics } from '@/hooks/use-analytics';
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
    ShieldCheck,
    Cpu,
    Server,
    Wifi,
    BarChart3
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
import { motion } from 'framer-motion';

export default function DashboardPage() {
    const { user, isLoading: authLoading } = useAuth();
    const { data: analytics, isLoading: statsLoading } = useAnalytics();

    const isLoading = authLoading || statsLoading;

    if (isLoading) {
        return (
            <div className="flex h-[80vh] items-center justify-center">
                <div className="flex flex-col items-center gap-4">
                    <div className="relative">
                        <div className="h-16 w-16 animate-spin rounded-full border-2 border-emerald-500/20 border-t-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.2)]"></div>
                        <div className="absolute inset-0 flex items-center justify-center">
                            <Zap className="h-6 w-6 text-emerald-500 animate-pulse" />
                        </div>
                    </div>
                    <p className="text-zinc-400 font-medium tracking-widest text-[10px] uppercase animate-pulse">Initializing Neural Link...</p>
                </div>
            </div>
        );
    }

    if (!user) return null;

    // Format data for chart
    const revenueData = analytics?.data.finance.monthly_revenue.map(item => ({
        name: item.month_name || `M${item.month}`,
        value: Number(item.total)
    })) || [];

    // Summary Stats
    const totalCustomers = analytics?.data.network.total_customers || 0;
    const onlineCustomers = analytics?.data.network.total_online || 0;
    const onlineRate = totalCustomers > 0 ? Math.round((onlineCustomers / totalCustomers) * 100) : 0;
    const collectionRate = analytics?.data.finance.collection_rate || 0;

    return (
        <DashboardPageShell
            title={`Mission Control`}
            description={`Operasional Terpadu JABBAR23 • Status: Nominal`}
            className="pb-10"
        >
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                {/* Bento Row 1: Key Metrics */}
                <StatCard 
                    title="Total Customers" 
                    value={totalCustomers.toLocaleString()} 
                    subValue={`${onlineCustomers} Active Sessions`}
                    change={`${onlineRate}% Online`} 
                    isUp={onlineRate > 90} 
                    icon={Users} 
                    color="emerald"
                    delay={0.1}
                />
                <StatCard 
                    title="Revenue (YTD)" 
                    value={`Rp ${(Number(analytics?.data.finance.total_ytd || 0) / 1000000).toFixed(1)}M`} 
                    subValue="Total Penagihan Lunas"
                    change={`Rate: ${collectionRate}%`} 
                    isUp={collectionRate > 80} 
                    icon={CreditCard} 
                    color="sky"
                    delay={0.2}
                />
                <StatCard 
                    title="Staff Performance" 
                    value={`${analytics?.data.staff.staff_online ?? 0}/${analytics?.data.staff.total_staff ?? 0}`} 
                    subValue="Staff Check-in Hari Ini"
                    change={`${analytics?.data.staff.attendance_rate ?? 0}% Rate`} 
                    isUp={(analytics?.data.staff.attendance_rate ?? 0) > 70} 
                    icon={Activity} 
                    color="purple"
                    delay={0.3}
                />
                <StatCard 
                    title="Network Status" 
                    value="99.9%" 
                    subValue="Core & Distribution"
                    change="Stable" 
                    isUp={true} 
                    icon={Zap} 
                    color="amber"
                    delay={0.4}
                />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {/* Main Bento Area: Analytics Chart */}
                <motion.div 
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.5 }}
                    className="lg:col-span-8 rounded-3xl border border-white/5 bg-white/[0.02] p-8 relative overflow-hidden group"
                >
                    <div className="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                        <BarChart3 className="h-32 w-32 text-emerald-500" />
                    </div>
                    
                    <div className="flex items-center justify-between mb-10 relative z-10">
                        <div>
                            <h3 className="text-xl font-heading font-bold text-white flex items-center gap-2">
                                <TrendingUp className="h-5 w-5 text-emerald-400" />
                                Tren Pendapatan 2026
                            </h3>
                            <p className="text-sm text-zinc-500 mt-1">Data akumulasi berdasarkan tanggal pelunasan invoice</p>
                        </div>
                        <div className="hidden sm:flex items-center gap-4">
                            <div className="flex items-center gap-2">
                                <div className="h-2 w-2 rounded-full bg-emerald-500"></div>
                                <span className="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Paid Invoices</span>
                            </div>
                        </div>
                    </div>
                    
                    <div className="h-[350px] w-full relative z-10">
                        <ResponsiveContainer width="100%" height="100%">
                            <AreaChart data={revenueData}>
                                <defs>
                                    <linearGradient id="revenueGradient" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="5%" stopColor="#10b981" stopOpacity={0.3}/>
                                        <stop offset="95%" stopColor="#10b981" stopOpacity={0}/>
                                    </linearGradient>
                                </defs>
                                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                                <XAxis 
                                    dataKey="name" 
                                    axisLine={false} 
                                    tickLine={false} 
                                    tick={{fill: '#71717a', fontSize: 10, fontWeight: 600}}
                                    dy={10}
                                />
                                <YAxis 
                                    axisLine={false}
                                    tickLine={false}
                                    tick={{fill: '#71717a', fontSize: 10}}
                                    tickFormatter={(val) => `Rp${(val/1000000).toFixed(0)}M`}
                                />
                                <Tooltip 
                                    contentStyle={{ 
                                        backgroundColor: 'rgba(9, 9, 11, 0.9)', 
                                        border: '1px solid rgba(255, 255, 255, 0.05)',
                                        borderRadius: '12px',
                                        backdropFilter: 'blur(10px)',
                                        fontSize: '12px'
                                    }}
                                    itemStyle={{ color: '#10b981' }}
                                    formatter={(value: any) => [`Rp ${Number(value).toLocaleString()}`, 'Revenue']}
                                />
                                <Area 
                                    type="monotone" 
                                    dataKey="value" 
                                    stroke="#10b981" 
                                    strokeWidth={3}
                                    fillOpacity={1} 
                                    fill="url(#revenueGradient)" 
                                    animationDuration={2000}
                                />
                            </AreaChart>
                        </ResponsiveContainer>
                    </div>
                </motion.div>

                {/* Right Bento Column: System Health & Nodes */}
                <div className="lg:col-span-4 space-y-6">
                    <motion.div 
                        initial={{ opacity: 0, x: 20 }}
                        animate={{ opacity: 1, x: 0 }}
                        transition={{ delay: 0.6 }}
                        className="rounded-3xl border border-white/5 bg-white/[0.02] p-6 relative overflow-hidden"
                    >
                        <h3 className="text-xs font-bold text-emerald-500/80 mb-6 uppercase tracking-[0.2em] flex items-center gap-2">
                            <Server className="h-4 w-4" />
                            Core Nodes Status
                        </h3>
                        <div className="space-y-6">
                            {analytics?.data.network.routers.map((router, i) => (
                                <StatusRow 
                                    key={i}
                                    label={router.name} 
                                    status={router.is_up ? "Normal" : "Link Down"} 
                                    active={router.is_up} 
                                />
                            ))}
                            <StatusRow label="Radius Link" status="Warning" active={false} isWarning={true} />
                            <StatusRow label="Fonnte Gateway" status="Operational" active={true} />
                        </div>
                    </motion.div>

                    <motion.div 
                        initial={{ opacity: 0, x: 20 }}
                        animate={{ opacity: 1, x: 0 }}
                        transition={{ delay: 0.7 }}
                        className="rounded-3xl border border-emerald-500/10 bg-emerald-500/[0.03] p-6 relative overflow-hidden group hover:bg-emerald-500/[0.05] transition-colors"
                    >
                        <div className="flex items-start justify-between">
                            <div>
                                <p className="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1">Billing Summary</p>
                                <h4 className="text-xl font-bold text-white">Rp {Number(analytics?.data.finance.unpaid_receivables || 0).toLocaleString()}</h4>
                                <p className="text-[10px] text-zinc-500 mt-1">Pending Receivables (Current Cycle)</p>
                            </div>
                            <div className="p-2 rounded-lg bg-emerald-500/10 text-emerald-500">
                                <BarChart3 className="h-4 w-4" />
                            </div>
                        </div>
                        <div className="mt-6 pt-6 border-t border-white/5">
                            <button className="w-full py-2.5 rounded-xl bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-emerald-500 transition-colors shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                                View Finance Records
                            </button>
                        </div>
                    </motion.div>
                </div>
            </div>
        </DashboardPageShell>
    );
}

// --- High-End Subcomponents ---

function StatCard({ title, value, subValue, change, isUp, icon: Icon, color, delay = 0 }: any) {
    const colorStyles: any = {
        emerald: {
            icon: "text-emerald-400 bg-emerald-500/10 border-emerald-500/20",
            glow: "bg-emerald-500/20",
            change: "text-emerald-400 bg-emerald-400/10"
        },
        sky: {
            icon: "text-sky-400 bg-sky-500/10 border-sky-500/20",
            glow: "bg-sky-500/20",
            change: "text-sky-400 bg-sky-400/10"
        },
        purple: {
            icon: "text-purple-400 bg-purple-500/10 border-purple-500/20",
            glow: "bg-purple-500/20",
            change: "text-purple-400 bg-purple-400/10"
        },
        amber: {
            icon: "text-amber-400 bg-amber-500/10 border-amber-500/20",
            glow: "bg-amber-500/20",
            change: "text-amber-400 bg-amber-400/10"
        },
    };

    const style = colorStyles[color] || colorStyles.emerald;

    return (
        <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay }}
            className="group relative overflow-hidden rounded-3xl border border-white/5 bg-white/[0.02] p-6 transition-all hover:border-emerald-500/20 hover:bg-white/[0.04]"
        >
            <div className="flex items-start justify-between relative z-10">
                <div className={cn("p-3 rounded-2xl border backdrop-blur-md", style.icon)}>
                    <Icon className="h-6 w-6" />
                </div>
                <div className={cn(
                    "flex items-center gap-1 text-[10px] font-bold px-2 py-1 rounded-full",
                    isUp ? "text-emerald-400 bg-emerald-400/10" : "text-rose-400 bg-rose-400/10"
                )}>
                    {isUp ? <ArrowUpRight className="h-3 w-3" /> : <ArrowDownRight className="h-3 w-3" />}
                    {change}
                </div>
            </div>
            
            <div className="mt-6 relative z-10">
                <p className="text-[10px] font-bold text-zinc-500 uppercase tracking-[0.2em]">{title}</p>
                <div className="flex items-baseline gap-2 mt-1">
                    <p className="text-3xl font-heading font-black text-white tracking-tight">{value}</p>
                </div>
                {subValue && <p className="text-[10px] text-zinc-600 mt-1 font-medium italic">{subValue}</p>}
            </div>
            
            {/* Ambient Glow */}
            <div className={cn(
                "absolute -bottom-10 -right-10 w-32 h-32 rounded-full blur-[60px] opacity-0 group-hover:opacity-40 transition-opacity duration-700",
                style.glow
            )} />
        </motion.div>
    );
}

function StatusRow({ label, status, active, isWarning }: any) {
    return (
        <div className="flex items-center justify-between group p-3 rounded-2xl hover:bg-white/[0.02] transition-colors border border-transparent hover:border-white/5">
            <div className="flex items-center gap-3">
                <div className={cn(
                    "h-2 w-2 rounded-full shadow-[0_0_8px]",
                    active ? "bg-emerald-500 shadow-emerald-500/50" : 
                    isWarning ? "bg-amber-500 shadow-amber-500/50" : 
                    "bg-rose-500 shadow-rose-500/50"
                )} />
                <span className="text-xs font-semibold text-zinc-400 group-hover:text-zinc-200 transition-colors uppercase tracking-wider">{label}</span>
            </div>
            <span className={cn(
                "text-[10px] font-black uppercase tracking-tighter",
                active ? "text-emerald-400/60" : isWarning ? "text-amber-400/60" : "text-rose-400/60"
            )}>
                {status}
            </span>
        </div>
    );
}
