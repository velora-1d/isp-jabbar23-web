'use client';

import React from 'react';
import {
    AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip,
    ResponsiveContainer, BarChart, Bar, PieChart, Pie, Cell, Legend
} from 'recharts';
import {
    Users, CreditCard, Ticket, Activity, Package,
    ClipboardList, Zap, Wallet, TrendingUp, UserCheck,
    MessageSquare,
    Router as RouterIcon,
    Server,
    Globe
} from 'lucide-react';
import { StatCard } from './StatCard';
import { SectionChart } from './SectionChart';
import type { AnalyticsResponse } from '@/hooks/use-analytics';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

type Data = AnalyticsResponse['data'];

const COLORS = {
    emerald: '#10b981', sky: '#3b82f6', purple: '#8b5cf6',
    rose: '#f43f5e', amber: '#f59e0b', orange: '#f97316',
    indigo: '#6366f1', zinc: '#71717a', red: '#ef4444',
};

interface SectionProps { data: Data; }

export function OverviewSection({ data }: SectionProps) {
    const revenueData = data.finance?.monthly_revenue?.map(item => ({
        name: item.month_name || `M${item.month}`,
        value: Number(item.total),
    })) ?? [];

    const totalCustomers = data.network?.total_customers || 0;
    const activeCustomers = data.network?.total_online || 0;
    const monthRevenue = data.finance?.month_revenue || 0;
    const openTickets = data.tickets?.open_count || 0;
    const totalStaff = data.staff?.total_staff || 0;

    return (
        <div className="space-y-8 animate-in fade-in duration-700">
            {/* Top 4 Stats Row (Screenshot 01 Style) */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard 
                    title="Total Pelanggan" 
                    value={totalCustomers.toLocaleString()} 
                    sub={`${activeCustomers} Online Saat Ini`} 
                    icon={Users} 
                    color="sky" 
                />
                <StatCard 
                    title="Tagihan Masuk" 
                    value={`Rp ${(monthRevenue / 1e6).toFixed(1)}M`} 
                    sub="Pendapatan Bulan Ini" 
                    icon={Wallet} 
                    color="emerald" 
                />
                <StatCard 
                    title="Tiket Komplain" 
                    value={openTickets} 
                    sub="Membutuhkan Respon" 
                    icon={MessageSquare} 
                    color="rose" 
                />
                <StatCard 
                    title="Partner Aktif" 
                    value={totalStaff} 
                    sub="Petugas Lapangan" 
                    icon={UserCheck} 
                    color="amber" 
                />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {/* Finance / Package Distribution (Left - 5 columns) */}
                <div className="lg:col-span-5 space-y-6">
                    <Card className="bg-slate-900/40 border-slate-800 backdrop-blur-xl rounded-3xl overflow-hidden">
                        <div className="p-6 border-b border-slate-800 flex items-center justify-between">
                            <h3 className="text-sm font-bold text-slate-300 uppercase tracking-widest flex items-center gap-2">
                                <Activity className="w-4 h-4 text-emerald-400" />
                                Penjualan Paket
                            </h3>
                            <Badge className="bg-emerald-500/10 text-emerald-400 border-none">ACTIVE</Badge>
                        </div>
                        <CardContent className="p-8 space-y-8">
                            <div className="space-y-6">
                                {/* Simulated bar data for package distribution */}
                                <div className="space-y-2">
                                    <div className="flex justify-between text-xs font-bold uppercase">
                                        <span className="text-slate-400">Lite 10 Mbps</span>
                                        <span className="text-white">45%</span>
                                    </div>
                                    <div className="h-2 bg-slate-800 rounded-full overflow-hidden">
                                        <div className="h-full bg-emerald-500 rounded-full w-[45%]" />
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <div className="flex justify-between text-xs font-bold uppercase">
                                        <span className="text-slate-400">Regular 20 Mbps</span>
                                        <span className="text-white">30%</span>
                                    </div>
                                    <div className="h-2 bg-slate-800 rounded-full overflow-hidden">
                                        <div className="h-full bg-blue-500 rounded-full w-[30%]" />
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <div className="flex justify-between text-xs font-bold uppercase">
                                        <span className="text-slate-400">Premium 50 Mbps</span>
                                        <span className="text-white">15%</span>
                                    </div>
                                    <div className="h-2 bg-slate-800 rounded-full overflow-hidden">
                                        <div className="h-full bg-indigo-500 rounded-full w-[15%]" />
                                    </div>
                                </div>
                            </div>

                            <div className="pt-6 border-t border-slate-800 space-y-4">
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center space-x-3">
                                        <div className="p-2 bg-emerald-500/10 rounded-lg"><TrendingUp className="w-4 h-4 text-emerald-400" /></div>
                                        <div>
                                            <p className="text-xs font-bold text-white tracking-widest">Growth</p>
                                            <p className="text-[10px] text-slate-500 uppercase">+12% Bulan Ini</p>
                                        </div>
                                    </div>
                                    <span className="text-emerald-400 font-bold">+128 Users</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Network Status (Right - 7 columns) */}
                <div className="lg:col-span-7 space-y-6">
                    <Card className="bg-slate-900/40 border-slate-800 backdrop-blur-xl rounded-3xl overflow-hidden">
                        <div className="p-6 border-b border-slate-800 flex items-center justify-between">
                            <h3 className="text-sm font-bold text-slate-300 uppercase tracking-widest flex items-center gap-2">
                                <Globe className="w-4 h-4 text-blue-400" />
                                Monitoring Jaringan
                            </h3>
                            <p className="text-[10px] text-slate-500 font-bold uppercase cursor-pointer hover:text-white transition-colors">See Details</p>
                        </div>
                        <CardContent className="p-8">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div className="space-y-6">
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center space-x-3">
                                            <div className="p-2.5 bg-blue-500/10 rounded-2xl"><RouterIcon className="w-5 h-5 text-blue-400" /></div>
                                            <div>
                                                <p className="text-xs font-black text-white uppercase tracking-widest">Router Utama</p>
                                                <p className="text-[10px] text-emerald-400 font-bold">ONLINE · 24ms</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center space-x-3">
                                            <div className="p-2.5 bg-slate-800 rounded-2xl"><Server className="w-5 h-5 text-slate-400" /></div>
                                            <div>
                                                <p className="text-xs font-black text-white uppercase tracking-widest">OLT Core 01</p>
                                                <p className="text-[10px] text-emerald-400 font-bold">STABLE</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center space-x-3">
                                            <div className="p-2.5 bg-slate-800 rounded-2xl"><Server className="w-5 h-5 text-slate-400" /></div>
                                            <div>
                                                <p className="text-xs font-black text-white uppercase tracking-widest">OLT Core 02</p>
                                                <p className="text-[10px] text-emerald-400 font-bold">ONLINE</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div className="relative group">
                                    <div className="absolute inset-0 bg-emerald-500/5 blur-3xl rounded-full animate-pulse opacity-50" />
                                    <div className="relative p-6 bg-slate-900 border border-slate-800 rounded-3xl h-full flex flex-col items-center justify-center text-center">
                                        <p className="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Network Load</p>
                                        <div className="relative w-28 h-28 flex items-center justify-center">
                                            {/* Simulated loading indicator */}
                                            <div className="absolute inset-0 border-4 border-slate-800 rounded-full" />
                                            <div className="absolute inset-0 border-4 border-emerald-500 rounded-full border-t-transparent animate-[spin_3s_linear_infinite]" />
                                            <span className="text-2xl font-black text-white">42<span className="text-xs text-slate-500">%</span></span>
                                        </div>
                                        <p className="text-[11px] text-emerald-400 font-bold mt-4 uppercase tracking-widest">Operational Optimal</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Card className="bg-slate-900/40 border-slate-800 p-6 flex items-center gap-4">
                            <div className="p-3 bg-indigo-500/10 rounded-2xl"><Zap className="w-5 h-5 text-indigo-400" /></div>
                            <div>
                                <p className="text-xs font-bold text-white uppercase tracking-widest">Bandwidth Used</p>
                                <p className="text-lg font-black text-white">840 <span className="text-xs text-slate-500">Mbps</span></p>
                            </div>
                        </Card>
                        <Card className="bg-slate-900/40 border-slate-800 p-6 flex items-center gap-4">
                            <div className="p-3 bg-emerald-500/10 rounded-2xl"><TrendingUp className="w-5 h-5 text-emerald-400" /></div>
                            <div>
                                <p className="text-xs font-bold text-white uppercase tracking-widest">Avg Uptime</p>
                                <p className="text-lg font-black text-white">99.98<span className="text-xs text-slate-500">%</span></p>
                            </div>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    );
}
