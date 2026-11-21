"use client";

import React from "react";
import { 
  Building2, 
  Users, 
  ArrowUpRight, 
  DollarSign, 
  UserPlus, 
  Search, 
  Filter, 
  MoreVertical,
  Award,
  BarChart3,
  ExternalLink,
  ChevronRight
} from "lucide-react";
import { motion } from "framer-motion";
import { DashboardPageShell } from "@/components/dashboard/page-shell";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";

const partners = [
  { id: 1, name: "Toko Berkah Jaya", type: "Retailer", referrals: 45, earned: "Rp 4.500.000", status: "Active" },
  { id: 2, name: "Warnet Cyber", type: "Internet Cafe", referrals: 28, earned: "Rp 2.800.000", status: "Active" },
  { id: 3, name: "CV. Media Solusi", type: "IT Consultant", referrals: 15, earned: "Rp 1.500.000", status: "Pending" },
  { id: 4, name: "Bapak Ahmad", type: "Individual", referrals: 32, earned: "Rp 3.200.000", status: "Active" },
  { id: 5, name: "Ibu Siti", type: "Individual", referrals: 12, earned: "Rp 1.200.000", status: "Inactive" },
];

const containerVariants = {
  hidden: { opacity: 0 },
  visible: {
    opacity: 1,
    transition: {
      staggerChildren: 0.1
    }
  }
};

const itemVariants = {
  hidden: { y: 20, opacity: 0 },
  visible: {
    y: 0,
    opacity: 1
  }
};

export default function ReferralsPage() {
  return (
    <DashboardPageShell
      title="Mitra & Program Referral"
      description="Kelola kemitraan strategis dan pantau performa program rujukan pelanggan Anda."
      icon={Building2}
      actions={
        <div className="flex items-center gap-2">
          <Button variant="outline" className="border-white/10 bg-zinc-950/50 hover:bg-zinc-900">
            <BarChart3 className="h-4 w-4 mr-2" />
            Laporan
          </Button>
          <Button className="bg-primary/20 hover:bg-primary/30 text-primary border border-primary/30 backdrop-blur-md">
            <UserPlus className="h-4 w-4 mr-2" />
            Daftar Mitra Baru
          </Button>
        </div>
      }
    >
      <motion.div 
        variants={containerVariants}
        initial="hidden"
        animate="visible"
        className="space-y-8"
      >
        {/* Top Summary Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {[
            { label: "Total Mitra Aktif", value: "124", icon: Building2, color: "emerald", growth: "+12%" },
            { label: "Total Referral (Bulan Ini)", value: "85", icon: Users, color: "blue", growth: "+5%" },
            { label: "Komisi Terbayar", value: "Rp 12.4M", icon: Award, color: "purple", growth: "+18%" },
          ].map((stat, i) => (
            <motion.div key={i} variants={itemVariants}>
              <Card className="bg-zinc-900/40 border-white/5 backdrop-blur-xl relative overflow-hidden group">
                <div className="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                  <stat.icon className="h-24 w-24" />
                </div>
                <CardHeader className="pb-2">
                  <div className="flex items-center justify-between">
                    <CardDescription className="text-zinc-400">{stat.label}</CardDescription>
                    <Badge variant="outline" className="bg-emerald-500/10 text-emerald-400 border-emerald-500/20 text-[10px]">
                      {stat.growth}
                    </Badge>
                  </div>
                  <CardTitle className="text-3xl font-bold font-mono tracking-tight text-white flex items-baseline gap-2">
                    {stat.value}
                  </CardTitle>
                </CardHeader>
              </Card>
            </motion.div>
          ))}
        </div>

        {/* Main Section: Distribution & Top Partners */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Top Partners List */}
          <motion.div variants={itemVariants} className="lg:col-span-2">
            <Card className="bg-zinc-900/40 border-white/10 backdrop-blur-xl h-full">
              <CardHeader className="flex flex-row items-center justify-between border-b border-white/5 bg-white/5">
                <div>
                  <CardTitle className="text-xl">Daftar Mitra Strategis</CardTitle>
                  <CardDescription>Ringkasan performa 5 mitra teratas bulan ini.</CardDescription>
                </div>
                <div className="flex gap-2">
                  <div className="relative">
                    <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-zinc-500" />
                    <Input 
                      placeholder="Cari mitra..." 
                      className="pl-8 bg-zinc-950/50 border-white/10 w-48 focus:border-primary/50"
                    />
                  </div>
                  <Button variant="outline" size="icon" className="border-white/10 bg-zinc-950/50">
                    <Filter className="h-4 w-4" />
                  </Button>
                </div>
              </CardHeader>
              <CardContent className="p-0">
                <Table>
                  <TableHeader className="bg-white/5">
                    <TableRow className="border-white/5 hover:bg-transparent">
                      <TableHead className="text-zinc-400">Nama Mitra</TableHead>
                      <TableHead className="text-zinc-400">Tipe</TableHead>
                      <TableHead className="text-zinc-400">Referrals</TableHead>
                      <TableHead className="text-zinc-400">Pendapatan</TableHead>
                      <TableHead className="text-zinc-400">Status</TableHead>
                      <TableHead className="text-right text-zinc-400">Aksi</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {partners.map((partner) => (
                      <TableRow key={partner.id} className="border-white/5 hover:bg-white/5 transition-colors group">
                        <TableCell className="font-medium text-zinc-200 group-hover:text-primary transition-colors">
                          {partner.name}
                        </TableCell>
                        <TableCell className="text-zinc-400">{partner.type}</TableCell>
                        <TableCell className="font-mono text-zinc-300">{partner.referrals}</TableCell>
                        <TableCell className="font-mono text-emerald-400">{partner.earned}</TableCell>
                        <TableCell>
                          <Badge 
                            variant="outline" 
                            className={
                              partner.status === "Active" 
                                ? "bg-emerald-500/10 text-emerald-400 border-emerald-500/20" 
                                : partner.status === "Pending"
                                ? "bg-yellow-500/10 text-yellow-400 border-yellow-500/20"
                                : "bg-zinc-500/10 text-zinc-400 border-zinc-500/20"
                            }
                          >
                            {partner.status}
                          </Badge>
                        </TableCell>
                        <TableCell className="text-right">
                          <Button variant="ghost" size="icon" className="h-8 w-8 hover:bg-white/10">
                            <MoreVertical className="h-4 w-4 text-zinc-500" />
                          </Button>
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
                <div className="p-4 border-t border-white/5 flex justify-center">
                  <Button variant="link" className="text-primary text-sm gap-1 hover:gap-2 transition-all">
                    Lihat Semua Mitra <ChevronRight className="h-4 w-4" />
                  </Button>
                </div>
              </CardContent>
            </Card>
          </motion.div>

          {/* Quick Actions / Campaign Card */}
          <motion.div variants={itemVariants}>
            <Card className="bg-gradient-to-br from-zinc-900/80 to-zinc-900 border-white/10 backdrop-blur-xl h-full flex flex-col">
              <CardHeader>
                <CardTitle className="text-xl">Program Referral Aktif</CardTitle>
                <CardDescription>Kampanye yang sedang berjalan untuk meningkatkan sales.</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4 flex-1">
                {[
                  { title: "Promo Ramadhan 2026", desc: "Bonus 50rb per referral", progress: 75, deadline: "10 April" },
                  { title: "Program Retailer+", desc: "Komisi berjenjang s.d 15%", progress: 40, deadline: "Ongoing" },
                ].map((camp, i) => (
                  <div key={i} className="p-4 rounded-xl bg-white/5 border border-white/5 space-y-3 group hover:border-primary/30 transition-colors">
                    <div className="flex justify-between items-start">
                      <h4 className="font-bold text-zinc-100 group-hover:text-primary transition-colors">{camp.title}</h4>
                      <Badge variant="outline" className="text-[10px] border-white/10 capitalize">{camp.deadline}</Badge>
                    </div>
                    <p className="text-xs text-zinc-400">{camp.desc}</p>
                    <div className="w-full bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                      <div 
                        className="bg-primary h-full rounded-full group-hover:shadow-[0_0_10px_rgba(16,185,129,0.5)] transition-all" 
                        style={{ width: `${camp.progress}%` }} 
                      />
                    </div>
                  </div>
                ))}
                
                {/* Promo Banner Style */}
                <div className="mt-4 p-4 rounded-2xl bg-primary/20 border border-primary/30 flex items-center justify-between group cursor-pointer overflow-hidden relative">
                  <div className="relative z-10">
                    <p className="text-[10px] uppercase font-bold text-primary tracking-widest mb-1">Coming Soon</p>
                    <h5 className="font-bold text-white text-sm">Tiered Incentives v2</h5>
                  </div>
                  <div className="p-2 bg-primary/20 rounded-lg relative z-10">
                    <ArrowUpRight className="h-4 w-4 text-primary group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
                  </div>
                  {/* Decorative Glow */}
                  <div className="absolute top-0 right-0 w-16 h-16 bg-primary/20 rounded-full blur-2xl group-hover:bg-primary/40 transition-colors" />
                </div>
              </CardContent>
              <div className="p-6 border-t border-white/5">
                <Button className="w-full bg-primary text-zinc-950 font-bold hover:bg-primary/80 transition-all">
                  Buat Kampanye Baru
                </Button>
              </div>
            </Card>
          </motion.div>
        </div>

        {/* Recent Activity Section */}
        <motion.div variants={itemVariants}>
          <Card className="bg-zinc-900/40 border-white/10 backdrop-blur-xl">
            <CardHeader>
              <CardTitle className="text-xl">Aktivitas Terbaru</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-6">
                {[
                  { user: "Toko Berkah Jaya", action: "berhasil mendaftarkan 3 pelanggan baru", time: "2 jam yang lalu", icon: UserPlus },
                  { user: "CV. Media Solusi", action: "mengajukan penarikan komisi (Pending)", time: "5 jam yang lalu", icon: DollarSign },
                  { user: "Warnet Cyber", action: "bergabung sebagai Mitra Internet Cafe", time: "1 hari yang lalu", icon: Building2 },
                ].map((act, i) => (
                  <div key={i} className="flex gap-4 items-start relative group">
                    {i !== 2 && <div className="absolute left-[13px] top-6 w-[2px] h-12 bg-white/5" />}
                    <div className="h-7 w-7 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center relative z-10">
                      <act.icon className="h-3.5 w-3.5 text-primary" />
                    </div>
                    <div className="flex-1">
                      <p className="text-sm text-zinc-200">
                        <span className="font-bold text-white group-hover:text-primary transition-colors cursor-pointer">{act.user}</span> {act.action}
                      </p>
                      <p className="text-xs text-zinc-500 mt-0.5 font-mono">{act.time}</p>
                    </div>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>
        </motion.div>
      </motion.div>
    </DashboardPageShell>
  );
}
