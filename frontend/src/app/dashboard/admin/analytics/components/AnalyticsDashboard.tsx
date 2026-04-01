'use client';

import React from 'react';
import { useAnalytics } from '@/hooks/use-analytics';
import { 
  Activity, 
  Users, 
  TrendingUp, 
  Clock, 
  Server, 
  Signal,
  CreditCard,
  Target,
  ArrowUpRight,
  ArrowDownRight,
  Zap,
  Cpu,
  Globe,
  Waves
} from 'lucide-react';
import { 
  BarChart, 
  Bar, 
  XAxis, 
  YAxis, 
  CartesianGrid, 
  Tooltip, 
  ResponsiveContainer,
  AreaChart,
  Area,
  PieChart,
  Pie,
  Cell
} from 'recharts';
import { cn } from '@/lib/utils';
import { motion } from 'framer-motion';

export function AnalyticsDashboard() {
  const { data: response, isLoading } = useAnalytics();
  const data = response?.data;

  if (isLoading) {
    return (
      <div className="space-y-8 animate-in fade-in duration-500">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          {[1, 2, 3, 4].map((i) => (
            <div key={i} className="h-32 bg-white/[0.02] rounded-2xl border border-white/5 animate-pulse" />
          ))}
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div className="md:col-span-2 h-[400px] bg-white/[0.02] rounded-2xl border border-white/5 animate-pulse" />
            <div className="h-[400px] bg-white/[0.02] rounded-2xl border border-white/5 animate-pulse" />
        </div>
      </div>
    );
  }

  const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];
  const revenueData = data?.finance?.monthly_revenue?.map((item) => ({
    name: monthNames[parseInt(String(item.month)) - 1],
    total: parseFloat(String(item.total))
  })) || [];

  const containerVariants = {
    hidden: { opacity: 0 },
    show: {
      opacity: 1,
      transition: {
        staggerChildren: 0.1
      }
    }
  };

  const itemVariants = {
    hidden: { opacity: 0, y: 20 },
    show: { opacity: 1, y: 0 }
  };

  return (
    <motion.div 
      variants={containerVariants}
      initial="hidden"
      animate="show"
      className="space-y-8"
    >
      {/* 1. Global Stat Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <motion.div variants={itemVariants}>
          <StatCard 
            title="Sistem & Network" 
            value={`${data?.network?.total_online} / ${data?.network?.total_customers}`} 
            desc="Kapasitas Jaringan Aktif"
            icon={Signal} 
            color="emerald"
            trend="+2.4%"
            isUp={true}
          />
        </motion.div>
        <motion.div variants={itemVariants}>
          <StatCard 
            title="Total Revenue YTD" 
            value={`Rp ${data?.finance?.total_ytd?.toLocaleString()}`} 
            desc="Pendapatan Tahun Berjalan"
            icon={TrendingUp} 
            color="sky"
            trend="+12%"
            isUp={true}
          />
        </motion.div>
        <motion.div variants={itemVariants}>
          <StatCard 
            title="Efisiensi Penagihan" 
            value={`${data?.finance?.collection_rate}%`} 
            desc="Rasio Keberhasilan Invoice"
            icon={CreditCard} 
            color="amber"
            trend="+1.2%"
            isUp={true}
          />
        </motion.div>
        <motion.div variants={itemVariants}>
          <StatCard 
            title="Tim Operasional" 
            value={`${data?.staff?.staff_online} / ${data?.staff?.total_staff}`} 
            desc="Teknisi Field Online"
            icon={Users} 
            color="rose"
          />
        </motion.div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* 2. Revenue Performance Chart */}
        <motion.div 
          variants={itemVariants}
          className="lg:col-span-2 rounded-3xl border border-white/5 bg-gradient-to-b from-white/[0.04] to-transparent p-1 backdrop-blur-xl group"
        >
          <div className="bg-zinc-950/40 rounded-[calc(1.5rem-1px)] p-6 h-full relative overflow-hidden">
            <div className="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none group-hover:bg-emerald-500/20 transition-colors duration-700" />
            
            <div className="flex items-center justify-between mb-8 relative z-10">
              <div>
                <h3 className="text-xl font-heading font-bold text-white flex items-center gap-3">
                  <div className="p-2 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <Activity className="h-5 w-5" />
                  </div>
                  Proyeksi Keuangan
                </h3>
                <p className="text-xs text-zinc-500 mt-2 font-medium">Monitoring akumulasi pendapatan real-time per bulan</p>
              </div>
              <div className="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center gap-2">
                  <div className="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]" />
                  <span className="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Live System</span>
              </div>
            </div>

            <div className="h-[350px] w-full relative z-10">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={revenueData}>
                  <defs>
                    <linearGradient id="colorRevenue" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#10b981" stopOpacity={0.4}/>
                      <stop offset="95%" stopColor="#10b981" stopOpacity={0}/>
                    </linearGradient>
                    <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                      <feGaussianBlur stdDeviation="3" result="blur" />
                      <feComposite in="SourceGraphic" in2="blur" operator="over" />
                    </filter>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                  <XAxis 
                      dataKey="name" 
                      axisLine={false} 
                      tickLine={false} 
                      tick={{fill: '#52525b', fontSize: 11, fontWeight: 500}}
                      dy={15}
                  />
                  <YAxis 
                      axisLine={false} 
                      tickLine={false} 
                      tick={{fill: '#52525b', fontSize: 11, fontWeight: 500}}
                      tickFormatter={(value: number) => `Rp${value/1000000}M`}
                  />
                  <Tooltip 
                    contentStyle={{ 
                      backgroundColor: 'rgba(9, 9, 11, 0.95)', 
                      borderColor: 'rgba(16, 185, 129, 0.2)', 
                      borderRadius: '16px',
                      backdropFilter: 'blur(12px)',
                      boxShadow: '0 10px 15px -3px rgba(0, 0, 0, 0.5)',
                      padding: '12px'
                    }}
                    labelStyle={{ color: '#71717a', fontSize: '10px', marginBottom: '4px', textTransform: 'uppercase', fontWeight: 700 }}
                    itemStyle={{ color: '#10b981', fontSize: '14px', fontWeight: 700 }}
                    cursor={{ stroke: '#10b981', strokeWidth: 2, strokeDasharray: '6 6' }}
                  />
                  <Area 
                      type="monotone" 
                      dataKey="total" 
                      stroke="#10b981" 
                      strokeWidth={4}
                      fillOpacity={1} 
                      fill="url(#colorRevenue)" 
                      animationDuration={3000}
                      filter="url(#glow)"
                  />
                </AreaChart>
              </ResponsiveContainer>
            </div>
          </div>
        </motion.div>

        {/* 3. Node & Infrastructure Health */}
        <motion.div 
          variants={itemVariants}
          className="rounded-3xl border border-white/5 bg-white/[0.02] p-6 backdrop-blur-sm shadow-2xl relative overflow-hidden"
        >
          <div className="absolute top-0 right-0 p-4 opacity-10 rotate-12">
            <Globe className="w-32 h-32 text-blue-500" />
          </div>

          <h3 className="text-sm font-bold text-zinc-300 mb-8 uppercase tracking-[0.2em] flex items-center gap-3">
            <div className="p-2 rounded-lg bg-blue-500/10 text-blue-400">
              <Zap className="h-4 w-4" />
            </div>
            Core Infrastructure
          </h3>
          
          <div className="space-y-8 relative z-10">
            {data?.network?.routers?.map((router: any, idx: number) => (
              <div key={router.name} className="group cursor-default">
                <div className="flex justify-between items-end mb-3">
                  <div className="flex flex-col">
                    <span className="text-[13px] font-bold text-zinc-100 flex items-center gap-2 group-hover:text-blue-400 transition-colors">
                      <Cpu className="h-3.5 w-3.5 text-zinc-500" />
                      {router.name}
                    </span>
                    <span className="text-[10px] text-zinc-500 font-medium mt-1">Status: Stable Performance</span>
                  </div>
                  <div className="flex flex-col items-end">
                    <div className="flex items-center gap-1.5 mb-1">
                      <span className="text-xs font-bold text-blue-400">{router.online}</span>
                      <span className="text-[10px] text-zinc-600 font-bold uppercase">Peers</span>
                    </div>
                    <span className={cn(
                        "text-[9px] font-bold uppercase tracking-tighter px-1.5 py-0.5 rounded border leading-none",
                        router.online > 0 
                          ? "text-emerald-400 bg-emerald-400/10 border-emerald-400/20" 
                          : "text-rose-400 bg-rose-400/10 border-rose-400/20"
                    )}>
                        {router.online > 0 ? "Linked" : "Disconnected"}
                    </span>
                  </div>
                </div>
                <div className="h-2 bg-white/[0.03] rounded-full overflow-hidden p-[1px] border border-white/5 shadow-inner">
                  <motion.div 
                    initial={{ width: 0 }}
                    animate={{ width: `${Math.min(100, (router.online / 250) * 100)}%` }}
                    transition={{ duration: 2, delay: idx * 0.2, ease: "easeOut" }}
                    className={cn(
                        "h-full rounded-full transition-all relative overflow-hidden",
                        router.online > 200 
                          ? "bg-gradient-to-r from-emerald-600 to-emerald-400" 
                          : "bg-gradient-to-r from-blue-600 to-blue-400"
                    )}
                  >
                    <div className="absolute inset-0 bg-[linear-gradient(90deg,transparent_0%,rgba(255,255,255,0.4)_50%,transparent_100%)] w-20 animate-[shimmer_2s_infinite]" />
                  </motion.div>
                </div>
              </div>
            ))}
          </div>

          <div className="mt-10 pt-6 border-t border-white/5 relative z-10">
                <div className="flex items-center justify-between text-[11px] text-zinc-500 mb-4 px-1">
                    <span className="font-bold uppercase tracking-widest">Global Asset Overview</span>
                    <span className="text-blue-400 font-bold bg-blue-400/10 px-2 py-0.5 rounded">89% Optimal</span>
                </div>
                <div className="grid grid-cols-2 gap-3">
                    <div className="p-3 rounded-2xl bg-zinc-900/50 border border-white/5 flex flex-col gap-1 relative overflow-hidden group/card hover:border-emerald-500/20 transition-all">
                        <Waves className="absolute -bottom-2 -right-2 w-8 h-8 text-emerald-500/5 rotate-12 group-hover/card:scale-125 transition-transform" />
                        <span className="text-[9px] text-zinc-500 uppercase font-black tracking-tighter">ODP Units</span>
                        <span className="text-lg font-heading font-bold text-emerald-400 leading-none">{data?.network?.total_customers || 412}</span>
                    </div>
                    <div className="p-3 rounded-2xl bg-zinc-900/50 border border-white/5 flex flex-col gap-1 relative overflow-hidden group/card hover:border-blue-500/20 transition-all">
                        <Globe className="absolute -bottom-2 -right-2 w-8 h-8 text-blue-500/5 -rotate-12 group-hover/card:scale-125 transition-transform" />
                        <span className="text-[9px] text-zinc-500 uppercase font-black tracking-tighter">Backbone FO</span>
                        <div className="flex items-baseline gap-1">
                            <span className="text-lg font-heading font-bold text-blue-400 leading-none">12.8</span>
                            <span className="text-[9px] font-bold text-zinc-600">KM</span>
                        </div>
                    </div>
                </div>
          </div>
        </motion.div>
      </div>
    </motion.div>
  );
}

function StatCard({ title, value, desc, icon: Icon, color, trend, isUp }: any) {
  const colorMap: any = {
    emerald: {
      bg: "bg-emerald-500/10 text-emerald-400 border-emerald-500/20",
      glow: "bg-emerald-500/20",
      icon: "text-emerald-400"
    },
    sky: {
      bg: "bg-sky-500/10 text-sky-400 border-sky-500/20",
      glow: "bg-sky-500/20",
      icon: "text-sky-400"
    },
    amber: {
      bg: "bg-amber-500/10 text-amber-400 border-amber-500/20",
      glow: "bg-amber-500/20",
      icon: "text-amber-400"
    },
    rose: {
      bg: "bg-rose-500/10 text-rose-400 border-rose-500/20",
      glow: "bg-rose-500/20",
      icon: "text-rose-400"
    },
  };

  const style = colorMap[color] || colorMap.emerald;

  return (
    <div className="group relative overflow-hidden rounded-3xl border border-white/5 bg-[#0A0F14] p-5 transition-all hover:border-emerald-500/30 hover:scale-[1.02] duration-300">
      {/* Glow Effect */}
      <div className={cn(
        "absolute -top-12 -right-12 w-24 h-24 blur-[60px] opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none",
        style.glow
      )} />

      <div className="flex items-start justify-between relative z-10">
        <div className={cn("p-2.5 rounded-xl border-t border-l border-white/10 shadow-xl", style.bg)}>
          <Icon className={cn("h-5 w-5", style.icon)} />
        </div>
        {trend && (
            <motion.div 
              initial={{ scale: 0.9, opacity: 0 }}
              animate={{ scale: 1, opacity: 1 }}
              className={cn(
                "flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full border",
                isUp ? "text-emerald-400 bg-emerald-400/10 border-emerald-400/20" : "text-rose-400 bg-rose-400/10 border-rose-400/20"
            )}>
                {isUp ? <ArrowUpRight className="h-3 w-3" /> : <ArrowDownRight className="h-3 w-3" />}
                {trend}
            </motion.div>
        )}
      </div>
      
      <div className="mt-5 relative z-10">
        <p className="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">{title}</p>
        <p className="text-3xl font-heading font-bold text-white mt-2 tracking-tighter tabular-nums">{value}</p>
        <div className="flex items-center gap-2 mt-2">
           <div className={cn("h-1 w-1 rounded-full", isUp ? "bg-emerald-500" : "bg-zinc-700")} />
           <p className="text-[11px] text-zinc-500 font-medium">{desc}</p>
        </div>
      </div>
    </div>
  );
}
