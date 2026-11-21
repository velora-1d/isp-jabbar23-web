"use client";

import { useState } from "react";
import { useLeads } from "@/hooks/use-leads";
import { 
  Card, 
  CardContent, 
  CardHeader, 
  CardTitle,
  CardDescription 
} from "@/components/ui/card";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { 
  Search, 
  Plus, 
  Filter, 
  MoreVertical,
  UserCheck,
  Phone,
  Mail,
  MapPin,
  Calendar,
  Layers,
  TrendingUp,
  UserPlus,
  Zap
} from "lucide-react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { LeadStatus } from "@/types/lead";
import { format } from "date-fns";
import { id } from "date-fns/locale";
import { DashboardPageShell } from "@/components/dashboard/page-shell";
import { motion, AnimatePresence } from "framer-motion";

const statusConfig: Record<LeadStatus, { label: string; color: string; border: string; glow: string }> = {
  new: { 
    label: "Baru", 
    color: "text-blue-400 bg-blue-500/10", 
    border: "border-blue-500/20",
    glow: "shadow-[0_0_15px_rgba(59,130,246,0.1)]"
  },
  contacted: { 
    label: "Dihubungi", 
    color: "text-yellow-400 bg-yellow-500/10", 
    border: "border-yellow-500/20",
    glow: "shadow-[0_0_15px_rgba(234,179,8,0.1)]"
  },
  qualified: { 
    label: "Prospek", 
    color: "text-purple-400 bg-purple-500/10", 
    border: "border-purple-500/20",
    glow: "shadow-[0_0_15px_rgba(168,85,247,0.1)]"
  },
  proposal: { 
    label: "Proposal", 
    color: "text-orange-400 bg-orange-500/10", 
    border: "border-orange-500/20",
    glow: "shadow-[0_0_15px_rgba(249,115,22,0.1)]"
  },
  negotiation: { 
    label: "Negosiasi", 
    color: "text-cyan-400 bg-cyan-500/10", 
    border: "border-cyan-500/20",
    glow: "shadow-[0_0_15px_rgba(6,182,212,0.1)]"
  },
  won: { 
    label: "Closing", 
    color: "text-emerald-400 bg-emerald-500/10", 
    border: "border-emerald-500/20",
    glow: "shadow-[0_0_15px_rgba(16,185,129,0.1)]"
  },
  lost: { 
    label: "Gagal", 
    color: "text-red-400 bg-red-500/10", 
    border: "border-red-500/20",
    glow: "shadow-[0_0_15px_rgba(239,68,68,0.1)]"
  },
};

export default function LeadsPage() {
  const [search, setSearch] = useState("");
  const { leadsQuery, updateStatusMutation } = useLeads();

  const leads = leadsQuery.data?.data || [];

  const handleUpdateStatus = (leadId: number, status: LeadStatus) => {
    updateStatusMutation.mutate({ id: leadId, status });
  };

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

  return (
    <DashboardPageShell
      title="Leads CRM"
      description="Kelola prospek dan pantau pipeline penjualan Anda dengan sistem pelacakan modern."
      icon={Layers}
      actions={
        <Button className="gap-2 bg-primary/20 hover:bg-primary/30 text-primary border border-primary/30 backdrop-blur-md">
          <Plus className="h-4 w-4" />
          Tambah Lead
        </Button>
      }
    >
      <motion.div 
        variants={containerVariants}
        initial="hidden"
        animate="visible"
        className="space-y-8"
      >
        {/* Statistics Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          {[
            { label: "Total Leads", value: leadsQuery.data?.total || 0, icon: Layers, color: "blue" },
            { 
              label: "Pipeline Aktif", 
              value: leads.filter((l: any) => ['new', 'contacted', 'qualified'].includes(l.status)).length, 
              icon: Zap, 
              color: "yellow" 
            },
            { 
              label: "Closed (Won)", 
              value: leads.filter((l: any) => l.status === 'won').length, 
              icon: UserCheck, 
              color: "emerald" 
            },
            { 
              label: "Conversion", 
              value: `${leadsQuery.data?.total > 0 
                ? Math.round((leads.filter((l: any) => l.status === 'won').length / leadsQuery.data.total) * 100) 
                : 0}%`, 
              icon: TrendingUp, 
              color: "purple" 
            },
          ].map((stat, i) => (
            <motion.div key={i} variants={itemVariants}>
              <Card className="bg-zinc-900/40 border-white/5 backdrop-blur-xl relative overflow-hidden group">
                <div className={`absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity`}>
                   <stat.icon className="h-12 w-12" />
                </div>
                <CardHeader className="pb-2">
                  <CardDescription className="text-zinc-400">{stat.label}</CardDescription>
                  <CardTitle className="text-3xl font-bold font-mono">
                    {leadsQuery.isLoading ? (
                      <div className="h-9 w-16 bg-white/5 animate-pulse rounded" />
                    ) : (
                      stat.value
                    )}
                  </CardTitle>
                </CardHeader>
              </Card>
            </motion.div>
          ))}
        </div>

        {/* Filters & Table */}
        <motion.div variants={itemVariants}>
          <Card className="bg-zinc-900/40 border-white/10 backdrop-blur-xl overflow-hidden">
            <CardHeader className="border-b border-white/5 bg-white/5">
              <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                  <CardTitle className="text-xl">Daftar Prospek</CardTitle>
                  <CardDescription>Menampilkan semua data calon pelanggan potensial.</CardDescription>
                </div>
                <div className="flex items-center gap-2">
                  <div className="relative w-full md:w-64">
                    <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                    <Input
                      placeholder="Cari prospek..."
                      className="pl-9 bg-zinc-950/50 border-white/10 focus:border-primary/50"
                      value={search}
                      onChange={(e) => setSearch(e.target.value)}
                    />
                  </div>
                  <Button variant="outline" size="icon" className="border-white/10 bg-zinc-950/50">
                    <Filter className="h-4 w-4" />
                  </Button>
                </div>
              </div>
            </CardHeader>
            <CardContent className="p-0">
              <div className="overflow-x-auto">
                <Table>
                  <TableHeader className="bg-white/5">
                    <TableRow className="border-white/5 hover:bg-transparent">
                      <TableHead className="text-zinc-400 font-medium">Prospek</TableHead>
                      <TableHead className="text-zinc-400 font-medium">Kontak</TableHead>
                      <TableHead className="text-zinc-400 font-medium">Sumber</TableHead>
                      <TableHead className="text-zinc-400 font-medium">Sales</TableHead>
                      <TableHead className="text-zinc-400 font-medium">Status</TableHead>
                      <TableHead className="text-zinc-400 font-medium">Dibuat</TableHead>
                      <TableHead className="text-right text-zinc-400 font-medium">Aksi</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    <AnimatePresence mode="popLayout">
                      {leadsQuery.isLoading ? (
                        Array.from({ length: 5 }).map((_, i) => (
                          <TableRow key={`skeleton-${i}`} className="border-white/5">
                            <TableCell colSpan={7}>
                              <div className="h-10 w-full bg-white/5 animate-pulse rounded" />
                            </TableCell>
                          </TableRow>
                        ))
                      ) : leads.length === 0 ? (
                        <TableRow className="border-white/5">
                          <TableCell colSpan={7} className="h-32 text-center text-zinc-500 italic">
                            Tidak ada data prospek ditemukan.
                          </TableCell>
                        </TableRow>
                      ) : (
                        leads.map((lead: any) => (
                          <motion.tr
                            key={lead.id}
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            className="group border-white/5 hover:bg-white/5 transition-colors"
                          >
                            <TableCell>
                              <div className="flex flex-col">
                                <span className="font-semibold text-zinc-100 group-hover:text-primary transition-colors">
                                  {lead.name}
                                </span>
                                <span className="text-xs font-mono text-zinc-500">{lead.lead_number}</span>
                              </div>
                            </TableCell>
                            <TableCell>
                              <div className="flex flex-col gap-1 text-xs text-zinc-400">
                                <div className="flex items-center gap-2">
                                  <Phone className="h-3 w-3 text-primary/60" />
                                  {lead.phone || '-'}
                                </div>
                                <div className="flex items-center gap-2">
                                  <Mail className="h-3 w-3 text-primary/60" />
                                  {lead.email || '-'}
                                </div>
                              </div>
                            </TableCell>
                            <TableCell>
                              <Badge variant="outline" className="bg-zinc-950/50 border-white/10 text-zinc-400 capitalize">
                                {lead.source}
                              </Badge>
                            </TableCell>
                            <TableCell>
                              <div className="flex items-center gap-2">
                                <div className="h-7 w-7 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center">
                                  <UserCheck className="h-3.5 w-3.5 text-primary" />
                                </div>
                                <span className="text-sm font-medium text-zinc-300">
                                  {lead.assigned_sales?.name || 'Belum Ditugaskan'}
                                </span>
                              </div>
                            </TableCell>
                            <TableCell>
                              <Badge 
                                variant="outline" 
                                className={`px-2 py-0.5 rounded-md border text-[10px] font-bold uppercase tracking-wider ${statusConfig[lead.status as LeadStatus].color} ${statusConfig[lead.status as LeadStatus].border} ${statusConfig[lead.status as LeadStatus].glow}`}
                              >
                                {statusConfig[lead.status as LeadStatus].label}
                              </Badge>
                            </TableCell>
                            <TableCell>
                              <div className="flex items-center gap-2 text-xs text-zinc-400">
                                <Calendar className="h-3 w-3" />
                                {format(new Date(lead.created_at), "dd MMM yyyy", { locale: id })}
                              </div>
                            </TableCell>
                            <TableCell className="text-right">
                              <DropdownMenu>
                                <DropdownMenuTrigger asChild>
                                  <Button variant="ghost" size="icon" className="h-8 w-8 text-zinc-500 hover:text-white hover:bg-white/10">
                                    <MoreVertical className="h-4 w-4" />
                                  </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end" className="w-56 bg-zinc-900 border-white/10 text-zinc-300">
                                  <DropdownMenuLabel className="text-zinc-500 text-xs px-2 py-1.5 uppercase tracking-widest font-bold">Update Status</DropdownMenuLabel>
                                  <DropdownMenuSeparator className="bg-white/5" />
                                  {[
                                    { status: 'contacted', label: '📞 Tandai Dihubungi' },
                                    { status: 'qualified', label: '✅ Kualifikasi Prospek' },
                                    { status: 'proposal', label: '📄 Kirim Proposal' },
                                    { status: 'negotiation', label: '🤝 Negosiasi' },
                                    { status: 'won', label: '🏆 Closing (Menang)' },
                                    { status: 'lost', label: '❌ Gagal (Tutup)' },
                                  ].map((opt) => (
                                    <DropdownMenuItem 
                                      key={opt.status}
                                      onClick={() => handleUpdateStatus(lead.id, opt.status as LeadStatus)}
                                      className="hover:bg-primary/10 hover:text-primary transition-colors cursor-pointer"
                                    >
                                      {opt.label}
                                    </DropdownMenuItem>
                                  ))}
                                  <DropdownMenuSeparator className="bg-white/5" />
                                  <DropdownMenuItem className="cursor-pointer">👁️ Lihat Detail</DropdownMenuItem>
                                  <DropdownMenuItem className="text-red-500 hover:bg-red-500/10 hover:text-red-400 cursor-pointer">🗑️ Hapus</DropdownMenuItem>
                                </DropdownMenuContent>
                              </DropdownMenu>
                            </TableCell>
                          </motion.tr>
                        ))
                      )}
                    </AnimatePresence>
                  </TableBody>
                </Table>
              </div>
            </CardContent>
          </Card>
        </motion.div>
      </motion.div>
    </DashboardPageShell>
  );
}
