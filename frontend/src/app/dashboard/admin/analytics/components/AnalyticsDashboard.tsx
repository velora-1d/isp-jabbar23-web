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
  Waves,
  PackageCheck,
  AlertCircle,
  Wrench,
  Search,
  MapPin,
  TrendingDown,
  UserCheck
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
import { motion, AnimatePresence } from 'framer-motion';
import {
  Tabs,
  TabsContent,
  TabsList,
  TabsTrigger
} from '@/components/ui/tabs';

// ─── Sub-component: Active Tab Indicator (Framer Motion) ───────────────────
const TabsTriggerOverlay = ({ tabId }: { tabId: string }) => {
  return (
    <div className="absolute inset-0 z-0 pointer-events-none">
      <AnimatePresence mode="wait">
        <motion.div
          key={`indicator-${tabId}`}
          layoutId="activeTab"
          className="absolute inset-0 bg-gradient-to-tr from-emerald-500/20 via-emerald-500/10 to-transparent border border-white/20 backdrop-blur-md rounded-[20px] shadow-[0_0_30px_rgba(16,185,129,0.2)]"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          transition={{
            type: "spring",
            stiffness: 400,
            damping: 30,
            mass: 1
          }}
        />
        <motion.div
          layoutId="activeTabGlow"
          className="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2/3 h-[2px] bg-emerald-400 blur-[2px] rounded-full"
          transition={{ type: "spring", stiffness: 400, damping: 30 }}
        />
      </AnimatePresence>
    </div>
  );
};

// =============================================================================
export function AnalyticsDashboard() {
  const { data: response, isLoading } = useAnalytics();
  const data = response?.data;
  const [activeTab, setActiveTab] = React.useState('performa');

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

  const inventoryMutationData = data?.inventory?.mutasi_stok_bulanan?.map((item) => ({
    name: item.name,
    masuk: item.masuk,
    keluar: item.keluar
  })) || [];

  const ticketTrendData = data?.tickets?.monthly_trend?.map((item) => ({
    name: monthNames[parseInt(String(item.month)) - 1],
    total: item.total
  })) || [];

  const regionalData = data?.finance?.regional_performance?.map((item) => ({
    name: item.region,
    lunas: parseFloat(String(item.paid)),
    terhutang: parseFloat(String(item.unpaid))
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
            trend={data?.network?.trend || "+0.0%"}
            isUp={(data?.network?.trend || "").startsWith('+')}
          />
        </motion.div>
        <motion.div variants={itemVariants}>
          <StatCard
            title="Total Revenue YTD"
            value={`Rp ${data?.finance?.total_ytd?.toLocaleString()}`}
            desc="Pendapatan Tahun Berjalan"
            icon={TrendingUp}
            color="sky"
            trend={data?.finance?.revenue_trend || "+0.0%"}
            isUp={(data?.finance?.revenue_trend || "").startsWith('+')}
          />
        </motion.div>
        <motion.div variants={itemVariants}>
          <StatCard
            title="Efisiensi Penagihan"
            value={`${data?.finance?.collection_rate}%`}
            desc="Rasio Keberhasilan Invoice"
            icon={CreditCard}
            color="amber"
            trend={data?.finance?.billing_trend || "+0.0%"}
            isUp={(data?.finance?.billing_trend || "").startsWith('+')}
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

      <Tabs defaultValue="performa" onValueChange={setActiveTab} className="w-full space-y-12">
        {/* ── Dashboard Control Bar (Mission Control Style Alignment) ────────────────────── */}
        <div className="flex flex-col xl:flex-row xl:items-stretch justify-between gap-2 p-1.5 rounded-2xl bg-white/[0.025] border border-white/5 shadow-2xl backdrop-blur-md">
          <TabsList className="flex flex-1 items-center gap-[15%] bg-transparent border-none p-0">
            {[
              { id: 'performa', label: 'Monitor Performa', icon: Activity, color: 'emerald' },
              { id: 'operasional', label: 'Layanan & Support', icon: Wrench, color: 'blue' },
              { id: 'geografis', label: 'Analisis Wilayah', icon: MapPin, color: 'amber' },
            ].map((tab) => (
              <TabsTrigger
                key={tab.id}
                value={tab.id}
                className="relative flex-1 xl:flex-none flex items-center justify-center gap-2.5 px-6 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-[0.12em] transition-all duration-200 whitespace-nowrap text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] data-active:bg-emerald-500 data-active:text-white data-active:shadow-lg data-active:shadow-emerald-500/20 group outline-none overflow-hidden"
              >
                <tab.icon className="h-3.5 w-3.5 relative z-10 transition-transform duration-300 group-data-active:scale-110" />
                <span className="relative z-10">{tab.label}</span>
              </TabsTrigger>
            ))}
          </TabsList>

          <div className="flex items-center justify-between xl:justify-end gap-6 px-6 py-4 xl:py-0 border-t xl:border-t-0 xl:border-l border-white/5 bg-white/[0.015] xl:bg-transparent rounded-b-2xl xl:rounded-none">
            <div className="flex items-center gap-3">
              <div className="relative">
                <div className="h-2 w-2 rounded-full bg-emerald-500" />
                <div className="absolute inset-0 h-2 w-2 rounded-full bg-emerald-500 animate-ping opacity-75" />
              </div>
              <span className="text-[10px] font-black text-zinc-300 uppercase tracking-[0.25em]">Live Telemetry</span>
            </div>
            <div className="h-4 w-px bg-white/5 hidden xl:block" />
            <div className="flex items-center gap-3">
              <div className="p-1.5 rounded-lg bg-white/[0.03] border border-white/5">
                <Clock className="h-3.5 w-3.5 text-emerald-400" />
              </div>
              <div className="flex flex-col">
                <span className="text-[9px] font-bold text-zinc-500 uppercase tracking-tighter">Last Sync</span>
                <span className="text-[11px] font-mono font-bold text-zinc-300">
                  {new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })}
                </span>
              </div>
            </div>
          </div>
        </div>

        <TabsContent value="performa" className="space-y-10 mt-0 outline-none">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {/* 2. Revenue Performance Chart */}
            <motion.div
              variants={itemVariants}
              whileHover={{ scale: 1.002, translateY: -2 }}
              className="lg:col-span-2 rounded-[32px] border border-white/10 bg-white/[0.03] p-1 backdrop-blur-2xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] group cursor-default"
              aria-label="Grafik Proyeksi Keuangan bulanan"
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
                          <stop offset="5%" stopColor="#10b981" stopOpacity={0.4} />
                          <stop offset="95%" stopColor="#10b981" stopOpacity={0} />
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
                        tick={{ fill: '#52525b', fontSize: 11, fontWeight: 500 }}
                        dy={15}
                      />
                      <YAxis
                        axisLine={false}
                        tickLine={false}
                        tick={{ fill: '#52525b', fontSize: 11, fontWeight: 500 }}
                        tickFormatter={(value: number) => `Rp${value / 1000000}M`}
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
              whileHover={{ scale: 1.01, translateY: -4 }}
              className="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur-2xl shadow-[0_20px_50px_rgba(0,0,0,0.2)] relative overflow-hidden group cursor-pointer"
              aria-label="Status Kesehatan Infrastruktur Inti"
            >
              <div className="absolute top-0 right-0 p-4 opacity-10 rotate-12 group-hover:rotate-45 group-hover:scale-125 transition-transform duration-700">
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
                        <span className={cn(
                          "text-[13px] font-bold flex items-center gap-2 transition-colors",
                          router.is_up ? (router.cpu_load > 80 ? "text-rose-400" : "text-zinc-100 group-hover:text-blue-400") : "text-zinc-500"
                        )}>
                          <Cpu className={cn("h-3.5 w-3.5", router.is_up ? (router.cpu_load > 80 ? "text-rose-500 animate-pulse" : "text-zinc-500") : "text-zinc-700")} />
                          {router.name}
                        </span>
                        <span className={cn("text-[10px] font-medium mt-1", router.is_up ? (router.cpu_load > 80 ? "text-rose-400/80" : "text-zinc-500") : "text-zinc-600")}>
                          {router.is_up ? `CPU: ${router.cpu_load}% | ${router.status_label || 'Stable'}` : "Status: Offline / No Signal"}
                        </span>
                      </div>
                      <div className="flex flex-col items-end">
                        <div className="flex items-center gap-1.5 mb-1">
                          <span className={cn("text-xs font-bold", router.is_up ? "text-blue-400" : "text-zinc-600")}>{router.online}</span>
                          <span className="text-[10px] text-zinc-600 font-bold uppercase">Peers</span>
                        </div>
                        <span className={cn(
                          "text-[9px] font-bold uppercase tracking-tighter px-1.5 py-0.5 rounded border leading-none",
                          router.is_up
                            ? (router.cpu_load > 80 ? "text-rose-400 bg-rose-400/10 border-rose-400/20" : "text-emerald-400 bg-emerald-400/10 border-emerald-400/20")
                            : "text-zinc-500 bg-zinc-800 border-zinc-700"
                        )}>
                          {router.is_up ? "Linked" : "Disconnected"}
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
                          !router.is_up ? "bg-zinc-800" : (router.cpu_load > 80 ? "bg-gradient-to-r from-rose-600 to-rose-400" : "bg-gradient-to-r from-emerald-600 to-emerald-400")
                        )}
                      >
                        {router.is_up && <div className="absolute inset-0 bg-[linear-gradient(90deg,transparent_0%,rgba(255,255,255,0.4)_50%,transparent_100%)] w-20 animate-[shimmer_2s_infinite]" />}
                      </motion.div>
                    </div>
                  </div>
                ))}
              </div>

              <div className="mt-10 pt-6 border-t border-white/5 relative z-10">
                <div className="flex items-center justify-between text-[11px] text-zinc-500 mb-4 px-1">
                  <span className="font-bold uppercase tracking-widest">Global Asset Overview</span>
                  <span className="text-blue-400 font-bold bg-blue-400/10 px-2 py-0.5 rounded">{data?.network?.network_health}% Optimal</span>
                </div>
                <div className="grid grid-cols-2 gap-3">
                  <div className="p-3 rounded-2xl bg-zinc-900/50 border border-white/5 flex flex-col gap-1 relative overflow-hidden group/card hover:border-emerald-500/20 transition-all">
                    <Waves className="absolute -bottom-2 -right-2 w-8 h-8 text-emerald-500/5 rotate-12 group-hover/card:scale-125 transition-transform" />
                    <span className="text-[9px] text-zinc-500 uppercase font-black tracking-tighter">ODP Units</span>
                    <span className="text-lg font-heading font-bold text-emerald-400 leading-none">{data?.network?.odp_count || 0}</span>
                  </div>
                  <div className="p-3 rounded-2xl bg-zinc-900/50 border border-white/5 flex flex-col gap-1 relative overflow-hidden group/card hover:border-blue-500/20 transition-all">
                    <Globe className="absolute -bottom-2 -right-2 w-8 h-8 text-blue-500/5 -rotate-12 group-hover/card:scale-125 transition-transform" />
                    <span className="text-[9px] text-zinc-500 uppercase font-black tracking-tighter">OLT Devices</span>
                    <div className="flex items-baseline gap-1">
                      <span className="text-lg font-heading font-bold text-blue-400 leading-none">{data?.network?.olt_count || 0}</span>
                      <span className="text-[9px] font-bold text-zinc-600 uppercase">Nodes</span>
                    </div>
                  </div>
                </div>
              </div>
            </motion.div>
          </div>
        </TabsContent>

        <TabsContent value="operasional" className="space-y-8 mt-0 outline-none">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {/* 4a. Staff Role Breakdown (KPI Style) */}
            <motion.div
              variants={itemVariants}
              whileHover={{ scale: 1.01, translateY: -4 }}
              className="lg:col-span-2 rounded-3xl border border-white/10 bg-white/[0.03] p-8 backdrop-blur-2xl relative overflow-hidden group/main cursor-default shadow-[0_20px_50px_rgba(0,0,0,0.3)] hover:border-emerald-500/20 transition-all duration-500"
              aria-label="Statistik Komposisi Tim berdasarkan Role"
            >
              {/* Background Glow Effect */}
              <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent" />
              <div className="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-[100px] pointer-events-none group-hover/main:bg-emerald-500/10 transition-colors duration-700" />

              <div className="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4 relative z-10">
                <div className="flex items-center gap-5">
                  <div className="p-4 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-[0_0_20px_rgba(16,185,129,0.1)] group-hover/main:scale-110 transition-transform duration-500">
                    <Users className="h-6 w-6" />
                  </div>
                  <div>
                    <h3 className="text-2xl font-heading font-bold text-white tracking-tight flex items-center gap-2">
                      Komposisi Tim & Role
                      <div className="h-2 w-2 rounded-full bg-emerald-500 animate-pulse" />
                    </h3>
                    <p className="text-sm text-zinc-500 mt-1 font-medium tracking-wide">Distribusi beban kerja berdasarkan klasifikasi role fungsional</p>
                  </div>
                </div>
                <div className="bg-white/[0.03] border border-white/10 px-6 py-4 rounded-2xl flex items-center gap-4 hover:bg-white/[0.06] transition-colors cursor-help group/total">
                  <div className="text-right">
                    <div className="text-[10px] text-zinc-500 font-black uppercase tracking-[0.2em] mb-1">Status Kepegawaian</div>
                    <div className="text-3xl font-heading font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">
                      {data?.staff?.total_staff || 0} 
                      <span className="text-xs text-zinc-500 ml-2 font-bold tracking-normal uppercase">Aktif</span>
                    </div>
                  </div>
                  <div className="p-2 rounded-xl bg-emerald-500/5 border border-emerald-500/10 group-hover/total:rotate-12 transition-transform">
                     <UserCheck className="w-5 h-5 text-emerald-500/50" />
                  </div>
                </div>
              </div>

              <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 relative z-10">
                {data?.staff?.by_role?.map((role: any, idx: number) => (
                  <motion.div
                    key={role.name}
                    initial={{ opacity: 0, scale: 0.95 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ delay: 0.2 + idx * 0.05, type: 'spring', stiffness: 100 }}
                    whileHover={{ translateY: -8, backgroundColor: 'rgba(16, 185, 129, 0.04)' }}
                    className="p-5 rounded-3xl bg-white/[0.02] border border-white/5 hover:border-emerald-500/40 transition-all duration-300 group/role cursor-pointer"
                    role="listitem"
                    aria-label={`${role.name}: ${role.total} user`}
                  >
                    <div className="flex justify-between items-start mb-4">
                      <span className="text-[10px] font-black text-zinc-500 uppercase tracking-widest group-hover/role:text-emerald-400 transition-colors">
                        {role.name.replace('_', ' ')}
                      </span>
                      <div className="p-2 rounded-xl bg-white/5 text-zinc-400 group-hover/role:bg-emerald-500/10 group-hover/role:text-emerald-400 transition-all duration-300">
                        <UserCheck className="h-4 w-4" />
                      </div>
                    </div>
                    
                    <div className="flex items-end gap-2 mb-4">
                      <span className="text-3xl font-heading font-bold text-white group-hover/role:scale-110 transition-transform origin-left">{role.total}</span>
                      <span className="text-[10px] font-bold text-zinc-600 mb-1.5 uppercase">Anggota</span>
                    </div>

                    <div className="space-y-2">
                        <div className="flex justify-between text-[9px] font-bold uppercase tracking-tighter">
                            <span className="text-zinc-500 group-hover/role:text-emerald-500/80 transition-colors">Kapasitas</span>
                            <span className="text-zinc-400">{Math.round((role.total / (data?.staff?.total_staff || 1)) * 100)}%</span>
                        </div>
                        <div className="h-1.5 bg-white/[0.03] rounded-full overflow-hidden p-[1px] border border-white/5">
                            <motion.div
                                initial={{ width: 0 }}
                                animate={{ width: `${(role.total / (data?.staff?.total_staff || 1)) * 100}%` }}
                                transition={{ duration: 1.5, delay: 0.5 + idx * 0.1, ease: 'circOut' }}
                                className="h-full bg-gradient-to-r from-emerald-600 to-teal-400 rounded-full relative"
                            >
                                <div className="absolute inset-0 bg-white/20 blur-sm animate-pulse" />
                            </motion.div>
                        </div>
                    </div>
                  </motion.div>
                ))}
              </div>
            </motion.div>

            {/* Inventory Mutation Chart */}
            <motion.div
              variants={itemVariants}
              className="rounded-3xl border border-white/5 bg-zinc-950/40 p-6 backdrop-blur-xl relative overflow-hidden group"
            >
              <div className="flex items-center justify-between mb-8">
                <div>
                  <h3 className="text-xl font-heading font-bold text-white flex items-center gap-3">
                    <div className="p-2 rounded-xl bg-sky-500/10 text-sky-400 border border-sky-500/20">
                      <PackageCheck className="h-5 w-5" />
                    </div>
                    Arus Keluar Masuk Barang
                  </h3>
                  <p className="text-xs text-zinc-500 mt-2 font-medium">Monitoring pergerakan stok hardware & material</p>
                </div>
                <div className="flex items-center gap-4">
                  <div className="flex items-center gap-2">
                    <div className="h-2 w-2 rounded-full bg-emerald-500" />
                    <span className="text-[10px] text-zinc-400 font-bold uppercase">Masuk</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <div className="h-2 w-2 rounded-full bg-rose-500" />
                    <span className="text-[10px] text-zinc-400 font-bold uppercase">Keluar</span>
                  </div>
                </div>
              </div>

              <div className="h-[300px] w-full">
                <ResponsiveContainer width="100%" height="100%">
                  <BarChart data={inventoryMutationData} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
                    <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                    <XAxis
                      dataKey="name"
                      axisLine={false}
                      tickLine={false}
                      tick={{ fill: '#52525b', fontSize: 10, fontWeight: 600 }}
                    />
                    <YAxis
                      axisLine={false}
                      tickLine={false}
                      tick={{ fill: '#52525b', fontSize: 10 }}
                    />
                    <Tooltip
                      cursor={{ fill: 'rgba(255,255,255,0.03)' }}
                      contentStyle={{
                        backgroundColor: 'rgba(9, 9, 11, 0.95)',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderRadius: '12px',
                        padding: '10px'
                      }}
                      itemStyle={{ fontSize: '12px', fontWeight: 600 }}
                    />
                    <Bar dataKey="masuk" fill="#10b981" radius={[4, 4, 0, 0]} barSize={12} />
                    <Bar dataKey="keluar" fill="#f43f5e" radius={[4, 4, 0, 0]} barSize={12} />
                  </BarChart>
                </ResponsiveContainer>
              </div>
            </motion.div>

            {/* Support Ticket Volume Trend */}
            <motion.div
              variants={itemVariants}
              className="rounded-3xl border border-white/5 bg-zinc-950/40 p-6 backdrop-blur-xl relative overflow-hidden group"
            >
              <div className="flex items-center justify-between mb-8">
                <div>
                  <h3 className="text-xl font-heading font-bold text-white flex items-center gap-3">
                    <div className="p-2 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                      <AlertCircle className="h-5 w-5" />
                    </div>
                    Volume Tiket Gangguan
                  </h3>
                  <p className="text-xs text-zinc-500 mt-2 font-medium">Tren pengaduan pelanggan (6 Bulan Terakhir)</p>
                </div>
                <div className="text-right">
                  <div className="text-2xl font-heading font-bold text-amber-500">{data?.tickets?.open_count || 0}</div>
                  <div className="text-[10px] text-zinc-500 font-bold uppercase tracking-widest">Tiket Open</div>
                </div>
              </div>

              <div className="h-[300px] w-full">
                <ResponsiveContainer width="100%" height="100%">
                  <AreaChart data={ticketTrendData} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
                    <defs>
                      <linearGradient id="colorTickets" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="5%" stopColor="#f59e0b" stopOpacity={0.3} />
                        <stop offset="95%" stopColor="#f59e0b" stopOpacity={0} />
                      </linearGradient>
                    </defs>
                    <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#ffffff05" />
                    <XAxis
                      dataKey="name"
                      axisLine={false}
                      tickLine={false}
                      tick={{ fill: '#52525b', fontSize: 10, fontWeight: 600 }}
                    />
                    <YAxis
                      axisLine={false}
                      tickLine={false}
                      tick={{ fill: '#52525b', fontSize: 10 }}
                    />
                    <Tooltip
                      contentStyle={{
                        backgroundColor: 'rgba(9, 9, 11, 0.95)',
                        borderColor: 'rgba(245, 158, 11, 0.2)',
                        borderRadius: '12px',
                        padding: '10px'
                      }}
                      itemStyle={{ color: '#f59e0b', fontSize: '12px', fontWeight: 600 }}
                    />
                    <Area
                      type="monotone"
                      dataKey="total"
                      stroke="#f59e0b"
                      strokeWidth={3}
                      fillOpacity={1}
                      fill="url(#colorTickets)"
                      animationDuration={2000}
                    />
                  </AreaChart>
                </ResponsiveContainer>
              </div>
            </motion.div>

            {/* 4b. Secondary Metrics (Moved from bottom) */}
            <div className="lg:col-span-2 grid grid-cols-1 md:grid-cols-1 xl:grid-cols-3 gap-6 auto-rows-min">
              <motion.div variants={itemVariants} className="p-6 rounded-3xl border border-white/5 bg-white/[0.01] flex items-center gap-5 group hover:bg-white/[0.03] transition-colors h-fit">
                <div className="p-4 rounded-2xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 group-hover:scale-110 transition-transform">
                  <Wrench className="h-6 w-6" />
                </div>
                <div>
                  <p className="text-[10px] font-black text-zinc-500 uppercase tracking-widest">Maintenance Done</p>
                  <p className="text-2xl font-heading font-bold text-white mt-1">{data?.work_orders?.completed_this_month || 0}</p>
                  <p className="text-[10px] text-emerald-500/60 font-medium mt-1">Bulan ini</p>
                </div>
              </motion.div>

              <motion.div variants={itemVariants} className="p-6 rounded-3xl border border-white/5 bg-white/[0.01] flex items-center gap-5 group hover:bg-white/[0.03] transition-colors h-fit">
                <div className="p-4 rounded-2xl bg-blue-500/10 text-blue-500 border border-blue-500/20 group-hover:scale-110 transition-transform">
                  <Search className="h-6 w-6" />
                </div>
                <div>
                  <p className="text-[10px] font-black text-zinc-500 uppercase tracking-widest">Aset Inventori</p>
                  <p className="text-2xl font-heading font-bold text-white mt-1">Rp {data?.inventory?.total_value?.toLocaleString() || 0}</p>
                  <p className="text-[10px] text-blue-500/60 font-medium mt-1">Estimasi Nilai Total</p>
                </div>
              </motion.div>

              <motion.div variants={itemVariants} className="p-6 rounded-3xl border border-white/5 bg-white/[0.01] flex items-center gap-5 group hover:bg-white/[0.03] transition-colors h-fit">
                <div className="p-4 rounded-2xl bg-rose-500/10 text-rose-500 border border-rose-500/20 group-hover:scale-110 transition-transform">
                  <TrendingUp className="h-6 w-6" />
                </div>
                <div>
                  <p className="text-[10px] font-black text-zinc-500 uppercase tracking-widest">Customer Growth</p>
                  <p className="text-2xl font-heading font-bold text-white mt-1">+{data?.customer_growth?.slice(-1)[0]?.total || 0}</p>
                  <p className="text-[10px] text-rose-500/60 font-medium mt-1">Pelanggan Baru</p>
                </div>
              </motion.div>
            </div>
          </div>
        </TabsContent>

        <TabsContent value="geografis" className="space-y-8 mt-0 outline-none">
          <motion.div
            variants={itemVariants}
            className="rounded-3xl border border-white/5 bg-zinc-950/40 p-8 backdrop-blur-xl relative overflow-hidden group"
          >
            <div className="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
              <div className="flex items-center gap-5">
                <div className="p-3.5 rounded-2xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shadow-[0_0_20px_rgba(99,102,241,0.1)]">
                  <MapPin className="h-6 w-6" />
                </div>
                <div>
                  <h3 className="text-2xl font-heading font-bold text-white tracking-tight">Performa Penagihan per Wilayah</h3>
                  <p className="text-sm text-zinc-500 mt-1 font-medium">Distribusi pelunasan invoice berdasarkan Kecamatan</p>
                </div>
              </div>

              <div className="flex items-center gap-8 bg-white/[0.02] border border-white/5 p-4 rounded-2xl">
                <div className="flex flex-col items-end">
                  <span className="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Piutang</span>
                  <span className="text-lg font-heading font-bold text-rose-400">Rp {data?.finance?.unpaid_receivables?.toLocaleString()}</span>
                </div>
                <div className="h-10 w-px bg-white/5" />
                <div className="flex flex-col items-end">
                  <span className="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Collection Rate</span>
                  <span className="text-lg font-heading font-bold text-emerald-400">{data?.finance?.collection_rate}%</span>
                </div>
              </div>
            </div>

            <div className="h-[400px] w-full mt-4">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart
                  data={regionalData}
                  layout="vertical"
                  margin={{ top: 0, right: 30, left: 40, bottom: 0 }}
                  barGap={8}
                >
                  <XAxis type="number" hide />
                  <YAxis
                    dataKey="name"
                    type="category"
                    axisLine={false}
                    tickLine={false}
                    tick={{ fill: '#a1a1aa', fontSize: 12, fontWeight: 700 }}
                    width={120}
                  />
                  <Tooltip
                    cursor={{ fill: 'rgba(255,255,255,0.03)' }}
                    contentStyle={{
                      backgroundColor: 'rgba(9, 9, 11, 0.98)',
                      borderColor: 'rgba(255, 255, 255, 0.1)',
                      borderRadius: '16px',
                      boxShadow: '0 20px 40px rgba(0,0,0,0.4)',
                      padding: '15px'
                    }}
                    formatter={(value: any) => [`Rp ${Number(value).toLocaleString()}`, '']}
                  />
                  <Bar
                    dataKey="lunas"
                    name="Lunas"
                    fill="#10b981"
                    radius={[0, 4, 4, 0]}
                    barSize={16}
                    background={{ fill: 'rgba(255,255,255,0.02)', radius: 4 }}
                  />
                  <Bar
                    dataKey="terhutang"
                    name="Terhutang"
                    fill="#f43f5e"
                    radius={[0, 4, 4, 0]}
                    barSize={16}
                    background={{ fill: 'rgba(255,255,255,0.02)', radius: 4 }}
                  />
                </BarChart>
              </ResponsiveContainer>
            </div>

            {/* Legend Custom */}
            <div className="mt-8 flex items-center justify-center gap-10 border-t border-white/5 pt-8">
              <div className="flex items-center gap-3">
                <div className="h-3 w-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.4)]" />
                <span className="text-xs font-bold text-zinc-300 uppercase tracking-widest">Invoices Paid</span>
              </div>
              <div className="flex items-center gap-3">
                <div className="h-3 w-3 rounded-full bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.4)]" />
                <span className="text-xs font-bold text-zinc-300 uppercase tracking-widest">Outstanding Invoices</span>
              </div>
            </div>
          </motion.div>
        </TabsContent>
      </Tabs>
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
