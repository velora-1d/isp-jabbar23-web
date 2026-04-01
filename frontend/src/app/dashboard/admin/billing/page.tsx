"use client";

import { useState } from "react";
import { useInvoices } from "@/hooks/use-invoices";
import { Invoice } from "@/types/finance";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from "@/components/ui/select";
import { 
  Search, 
  FileText, 
  CreditCard, 
  Clock, 
  AlertCircle,
  Eye,
  Calendar,
  Filter,
  Download,
  Plus,
  ArrowUpRight
} from "lucide-react";
import Link from "next/link";
import { format } from "date-fns";
import { id as idLocale } from "date-fns/locale";
import { DashboardPageShell } from "@/components/dashboard/page-shell";
import { cn } from "@/lib/utils";

export default function BillingPage() {
  const [filters, setFilters] = useState({
    search: "",
    status: "all",
    year: new Date().getFullYear().toString(),
    month: (new Date().getMonth() + 1).toString(),
    page: 1,
  });

  const { invoices: data, isLoading } = useInvoices(filters);

  const getStatusBadge = (status: string) => {
    switch (status) {
      case "paid":
        return <Badge className="bg-emerald-500/10 text-emerald-400 border-emerald-500/20">Terbayar</Badge>;
      case "unpaid":
        return <Badge className="bg-red-500/10 text-red-500 border-red-500/20">Belum Bayar</Badge>;
      case "overdue":
        return <Badge className="bg-orange-500/10 text-orange-500 border-orange-500/20">Jatuh Tempo</Badge>;
      case "pending_approval":
        return <Badge className="bg-emerald-500/10 text-emerald-400 border-emerald-500/20 animate-pulse">Menunggu Verifikasi</Badge>;
      default:
        return <Badge variant="outline">{status}</Badge>;
    }
  };

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0,
    }).format(amount);
  };

  return (
    <DashboardPageShell
      title="Manajemen Billing"
      description="Kelola tagihan pelanggan, konfirmasi pembayaran, dan pantau piutang real-time."
      actions={
        <div className="flex items-center gap-2">
          <Button variant="outline" className="h-10 border-white/10 bg-white/[0.03] text-zinc-400 hover:text-white">
            <Download className="w-4 h-4 mr-2" />
            Export Data
          </Button>
          <Button className="bg-emerald-600 hover:bg-emerald-500 text-white h-10 shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
            <Plus className="w-4 h-4 mr-2" />
            Generate Invoice
          </Button>
        </div>
      }
    >
      <div className="space-y-8">
        {/* Stats Grid - Futuristic Glass Style */}
        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
          {[
            { 
              label: "Total Piutang", 
              value: formatCurrency(data?.stats?.total_billing || 0), 
              icon: CreditCard, 
              color: "text-emerald-400",
              bgColor: "bg-emerald-500/10",
              glowColor: "group-hover:shadow-emerald-500/10"
            },
            { 
              label: "Antrian Bayar", 
              value: `${data?.stats?.unpaid_count || 0} Invoice`, 
              icon: AlertCircle, 
              color: "text-red-400",
              bgColor: "bg-red-500/10",
              glowColor: "group-hover:shadow-red-500/10"
            },
            { 
              label: "Lunas Bulan Ini", 
              value: formatCurrency(data?.stats?.paid_this_month || 0), 
              icon: FileText, 
              color: "text-emerald-400",
              bgColor: "bg-emerald-500/10",
              glowColor: "group-hover:shadow-emerald-500/10"
            },
            { 
              label: "Overdue", 
              value: `${data?.stats?.overdue_count || 0} Invoice`, 
              icon: Clock, 
              color: "text-orange-400",
              bgColor: "bg-orange-500/10",
              glowColor: "group-hover:shadow-orange-500/10"
            }
          ].map((stat, i) => (
            <div 
              key={i} 
              className={cn(
                "group relative p-6 rounded-2xl border border-white/5 bg-zinc-900/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:border-white/10 shadow-xl",
                stat.glowColor
              )}
            >
              <div className="flex items-center gap-4">
                <div className={cn("p-3 rounded-xl transition-colors duration-300", stat.bgColor, stat.color)}>
                  <stat.icon className="w-6 h-6" />
                </div>
                <div>
                  <p className="text-xs font-semibold text-zinc-500 uppercase tracking-widest">{stat.label}</p>
                  <h3 className="text-2xl font-bold font-heading text-white mt-0.5 tracking-tight">
                    {stat.value}
                  </h3>
                </div>
              </div>
              <ArrowUpRight className="absolute top-4 right-4 w-4 h-4 text-zinc-700 group-hover:text-zinc-400 transition-colors" />
            </div>
          ))}
        </div>

        {/* Filters & Table Section */}
        <div className="space-y-4">
          <div className="flex flex-col md:flex-row items-center justify-between gap-4">
            <div className="relative w-full md:w-96">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
              <Input
                placeholder="Cari nomor invoice atau nama..."
                className="pl-10 h-11 border-white/5 bg-zinc-900/40 text-sm focus:ring-emerald-500/50 transition-all rounded-xl"
                value={filters.search}
                onChange={(e) => setFilters({ ...filters, search: e.target.value })}
              />
            </div>
            <div className="flex items-center gap-3 w-full md:w-auto">
              <Select 
                value={filters.status} 
                onValueChange={(v) => setFilters({ ...filters, status: v || "all" })}
              >
                <SelectTrigger className="w-full md:w-[180px] h-11 border-white/5 bg-zinc-900/40 rounded-xl focus:ring-emerald-500/50">
                  <div className="flex items-center gap-2 text-zinc-300">
                    <Filter className="w-4 h-4 text-zinc-500" />
                    <SelectValue placeholder="Status" />
                  </div>
                </SelectTrigger>
                <SelectContent className="bg-[#0b1218] border-white/10 text-zinc-300">
                  <SelectItem value="all">Semua Status</SelectItem>
                  <SelectItem value="unpaid">Belum Bayar</SelectItem>
                  <SelectItem value="paid">Terbayar</SelectItem>
                  <SelectItem value="overdue">Overdue</SelectItem>
                  <SelectItem value="pending_approval">Pending Approval</SelectItem>
                </SelectContent>
              </Select>
              <Button variant="outline" className="h-11 border-white/5 bg-zinc-900/40 rounded-xl px-4 text-zinc-400 hover:text-white">
                <Calendar className="w-4 h-4 mr-2" />
                Periode
              </Button>
            </div>
          </div>

          <div className="rounded-2xl border border-white/5 bg-zinc-900/10 overflow-hidden backdrop-blur-sm shadow-2xl">
            <Table>
              <TableHeader className="bg-white/[0.02]">
                <TableRow className="hover:bg-transparent border-white/5">
                  <TableHead className="py-5 pl-8 font-bold text-zinc-400 uppercase tracking-tighter text-xs">Invoice #</TableHead>
                  <TableHead className="font-bold text-zinc-400 uppercase tracking-tighter text-xs">Pelanggan</TableHead>
                  <TableHead className="font-bold text-zinc-400 uppercase tracking-tighter text-xs">Periode</TableHead>
                  <TableHead className="font-bold text-zinc-400 uppercase tracking-tighter text-xs text-right">Total Tagihan</TableHead>
                  <TableHead className="font-bold text-zinc-400 uppercase tracking-tighter text-xs text-center">Status</TableHead>
                  <TableHead className="font-bold text-zinc-400 uppercase tracking-tighter text-xs text-center">Jatuh Tempo</TableHead>
                  <TableHead className="pr-8 text-right font-bold text-zinc-400 uppercase tracking-tighter text-xs">Aksi</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {isLoading ? (
                  <TableRow className="border-white/5">
                    <TableCell colSpan={7} className="h-32 text-center text-zinc-500 animate-pulse">
                      Menghubungkan ke database satelit...
                    </TableCell>
                  </TableRow>
                ) : data?.invoices?.data.length === 0 ? (
                  <TableRow className="border-white/5">
                    <TableCell colSpan={7} className="h-32 text-center text-zinc-500">
                      Tidak ada transmisi invoice ditemukan.
                    </TableCell>
                  </TableRow>
                ) : (
                  data?.invoices?.data.map((invoice: Invoice) => (
                    <TableRow key={invoice.id} className="hover:bg-white/[0.03] transition-colors border-white/5 transition-all">
                      <TableCell className="py-5 pl-8 font-heading font-bold text-emerald-400">
                        {invoice.invoice_number}
                      </TableCell>
                      <TableCell>
                        <div className="flex flex-col">
                          <span className="font-semibold text-white">{invoice.customer?.name}</span>
                          <span className="text-[10px] text-zinc-500 font-mono tracking-wider uppercase">{invoice.customer?.customer_id}</span>
                        </div>
                      </TableCell>
                      <TableCell className="text-sm font-medium text-zinc-300">
                        {invoice.period_start ? format(new Date(invoice.period_start as string), "MMMM yyyy", { locale: idLocale }) : "-"}
                      </TableCell>
                      <TableCell className="text-right font-bold text-white font-heading">
                        {formatCurrency(invoice.total_after_tax)}
                      </TableCell>
                      <TableCell className="text-center">
                        {getStatusBadge(invoice.status)}
                      </TableCell>
                      <TableCell className="text-center text-sm font-medium text-zinc-400">
                         {invoice.due_date ? format(new Date(invoice.due_date as string), "dd MMM yyyy", { locale: idLocale }) : "-"}
                      </TableCell>
                      <TableCell className="pr-8 text-right">
                        <div className="flex items-center justify-end gap-1">
                          <Button variant="ghost" size="icon" asChild className="h-9 w-9 text-zinc-400 hover:text-emerald-400 hover:bg-emerald-500/10 rounded-lg">
                            <Link href={`/dashboard/admin/billing/${invoice.id}`}>
                              <Eye className="h-4.5 w-4.5" />
                            </Link>
                          </Button>
                          <Button variant="ghost" size="icon" className="h-9 w-9 text-zinc-400 hover:text-white hover:bg-white/5 rounded-lg">
                            <Download className="h-4.5 w-4.5" />
                          </Button>
                        </div>
                      </TableCell>
                    </TableRow>
                  ))
                )}
              </TableBody>
            </Table>
            
            {/* Pagination Glass Style */}
            <div className="flex flex-col sm:flex-row items-center justify-between gap-4 p-6 bg-white/[0.02] border-t border-white/5">
              <p className="text-xs text-zinc-500 tracking-wider font-medium uppercase font-heading">
                TRANSMITTING <span className="text-white">{data?.invoices?.data.length || 0}</span> / <span className="text-white">{data?.invoices?.total || 0}</span> INVOICE PACKETS
              </p>
              <div className="flex items-center gap-3">
                <Button 
                  variant="outline" 
                  size="sm" 
                  disabled={filters.page === 1}
                  onClick={() => setFilters({ ...filters, page: filters.page - 1 })}
                  className="h-9 px-5 border-white/5 bg-zinc-900/40 text-xs font-bold uppercase tracking-widest hover:text-emerald-400 transition-all"
                >
                  Prev
                </Button>
                <div className="flex items-center gap-1.5 px-3">
                  <span className="h-7 w-7 flex items-center justify-center rounded bg-emerald-500 text-black text-[10px] font-bold shadow-lg shadow-emerald-500/20">{filters.page}</span>
                  <span className="text-[10px] text-zinc-500 font-bold uppercase tracking-tighter">PAGE OF {data?.invoices?.last_page || 1}</span>
                </div>
                <Button 
                  variant="outline" 
                  size="sm"
                  disabled={filters.page === (data?.invoices?.last_page || 1)}
                  onClick={() => setFilters({ ...filters, page: filters.page + 1 })}
                  className="h-9 px-5 border-white/5 bg-zinc-900/40 text-xs font-bold uppercase tracking-widest hover:text-emerald-400 transition-all"
                >
                  Next
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardPageShell>
  );
}
