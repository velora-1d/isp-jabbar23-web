"use client";

import { useState } from "react";
import { useInvoices } from "@/hooks/use-invoices";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/components/ui/table";
import { 
  Card, 
  CardContent, 
  CardHeader, 
  CardTitle,
  CardDescription
} from "@/components/ui/card";
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
  Download
} from "lucide-react";
import Link from "next/link";
import { format } from "date-fns";
import { id as idLocale } from "date-fns/locale";

export default function BillingPage() {
  const [filters, setFilters] = useState({
    search: "",
    status: "all",
    year: new Date().getFullYear().toString(),
    month: (new Date().getMonth() + 1).toString(),
    page: 1,
  });

  const { data, isLoading } = useInvoices(filters);

  const getStatusBadge = (status: string) => {
    switch (status) {
      case "paid":
        return <Badge className="bg-green-500/10 text-green-500 border-green-500/20">Terbayar</Badge>;
      case "unpaid":
        return <Badge className="bg-red-500/10 text-red-500 border-red-500/20">Belum Bayar</Badge>;
      case "overdue":
        return <Badge className="bg-orange-500/10 text-orange-500 border-orange-500/20">Jatuh Tempo</Badge>;
      case "pending_approval":
        return <Badge className="bg-blue-500/10 text-blue-500 border-blue-500/20">Menunggu Verifikasi</Badge>;
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
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col gap-2">
        <h1 className="text-3xl font-bold tracking-tight">Manajemen Billing</h1>
        <p className="text-muted-foreground">
          Kelola tagihan pelanggan, konfirmasi pembayaran, dan pantau piutang.
        </p>
      </div>

      {/* Stats Grid */}
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card className="border-none shadow-sm bg-blue-50/50 dark:bg-blue-900/10">
          <CardContent className="p-6">
            <div className="flex items-center gap-4">
              <div className="p-2 bg-blue-500/10 rounded-lg text-blue-600">
                <CreditCard className="w-6 h-6" />
              </div>
              <div>
                <p className="text-sm font-medium text-muted-foreground">Total Piutang</p>
                <h3 className="text-xl font-bold">{formatCurrency(data?.stats?.total_billing || 0)}</h3>
              </div>
            </div>
          </CardContent>
        </Card>
        <Card className="border-none shadow-sm bg-red-50/50 dark:bg-red-900/10">
          <CardContent className="p-6">
            <div className="flex items-center gap-4">
              <div className="p-2 bg-red-500/10 rounded-lg text-red-600">
                <AlertCircle className="w-6 h-6" />
              </div>
              <div>
                <p className="text-sm font-medium text-muted-foreground">Antrian Bayar</p>
                <h3 className="text-xl font-bold">{data?.stats?.unpaid_count || 0} Invoice</h3>
              </div>
            </div>
          </CardContent>
        </Card>
        <Card className="border-none shadow-sm bg-green-50/50 dark:bg-green-900/10">
          <CardContent className="p-6">
            <div className="flex items-center gap-4">
              <div className="p-2 bg-green-500/10 rounded-lg text-green-600">
                <FileText className="w-6 h-6" />
              </div>
              <div>
                <p className="text-sm font-medium text-muted-foreground">Lunas Bulan Ini</p>
                <h3 className="text-xl font-bold">{formatCurrency(data?.stats?.paid_this_month || 0)}</h3>
              </div>
            </div>
          </CardContent>
        </Card>
        <Card className="border-none shadow-sm bg-orange-50/50 dark:bg-orange-900/10">
          <CardContent className="p-6">
            <div className="flex items-center gap-4">
              <div className="p-2 bg-orange-500/10 rounded-lg text-orange-600">
                <Clock className="w-6 h-6" />
              </div>
              <div>
                <p className="text-sm font-medium text-muted-foreground">Overdue</p>
                <h3 className="text-xl font-bold">{data?.stats?.overdue_count || 0} Invoice</h3>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <Card className="border-none shadow-md overflow-hidden bg-white/50 backdrop-blur-sm dark:bg-slate-950/50">
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-7">
          <div className="flex flex-col gap-1">
            <CardTitle className="text-xl font-bold">Daftar Tagihan</CardTitle>
            <CardDescription>Menampilkan invoice pelanggan berdasarkan filter</CardDescription>
          </div>
          <div className="flex items-center gap-2">
            <div className="relative w-64">
              <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
              <Input
                placeholder="Cari nomor invoice atau nama..."
                className="pl-9 h-10 border-slate-200"
                value={filters.search}
                onChange={(e) => setFilters({ ...filters, search: e.target.value })}
              />
            </div>
            <Select 
              value={filters.status} 
              onValueChange={(v) => setFilters({ ...filters, status: v })}
            >
              <SelectTrigger className="w-[150px] h-10 border-slate-200">
                <Filter className="w-4 h-4 mr-2" />
                <SelectValue placeholder="Status" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Semua Status</SelectItem>
                <SelectItem value="unpaid">Belum Bayar</SelectItem>
                <SelectItem value="paid">Terbayar</SelectItem>
                <SelectItem value="overdue">Overdue</SelectItem>
                <SelectItem value="pending_approval">Pending Approval</SelectItem>
              </SelectContent>
            </Select>
            <Button variant="outline" className="h-10 border-slate-200">
              <Calendar className="w-4 h-4 mr-2" />
              Periode
            </Button>
            <Button className="bg-blue-600 hover:bg-blue-700 text-white h-10">
              Generate Invoice
            </Button>
          </div>
        </CardHeader>
        <CardContent className="p-0">
          <div className="rounded-none border-t border-slate-100 dark:border-slate-800">
            <Table>
              <TableHeader className="bg-slate-50/50 dark:bg-slate-900/50">
                <TableRow className="hover:bg-transparent">
                  <TableHead className="py-4 pl-6 font-semibold">Invoice #</TableHead>
                  <TableHead className="font-semibold">Pelanggan</TableHead>
                  <TableHead className="font-semibold">Periode</TableHead>
                  <TableHead className="font-semibold text-right">Total Tagihan</TableHead>
                  <TableHead className="font-semibold text-center">Status</TableHead>
                  <TableHead className="font-semibold text-center">Jatuh Tempo</TableHead>
                  <TableHead className="pr-6 text-right font-semibold">Aksi</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {isLoading ? (
                  <TableRow>
                    <TableCell colSpan={7} className="h-24 text-center">Memuat data...</TableCell>
                  </TableRow>
                ) : data?.invoices?.data.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={7} className="h-24 text-center">Tidak ada invoice ditemukan.</TableCell>
                  </TableRow>
                ) : (
                  data?.invoices?.data.map((invoice) => (
                    <TableRow key={invoice.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors border-slate-100 dark:border-slate-800">
                      <TableCell className="py-4 pl-6 font-medium text-blue-600 dark:text-blue-400">
                        {invoice.invoice_number}
                      </TableCell>
                      <TableCell>
                        <div className="flex flex-col">
                          <span className="font-medium">{invoice.customer?.name}</span>
                          <span className="text-xs text-muted-foreground">{invoice.customer?.customer_id}</span>
                        </div>
                      </TableCell>
                      <TableCell className="text-sm">
                        {invoice.period_start ? format(new Date(invoice.period_start as string), "MMMM yyyy", { locale: idLocale }) : "-"}
                      </TableCell>
                      <TableCell className="text-right font-bold">
                        {formatCurrency(invoice.total_after_tax)}
                      </TableCell>
                      <TableCell className="text-center">
                        {getStatusBadge(invoice.status)}
                      </TableCell>
                      <TableCell className="text-center text-sm">
                         {invoice.due_date ? format(new Date(invoice.due_date as string), "dd MMM yyyy", { locale: idLocale }) : "-"}
                      </TableCell>
                      <TableCell className="pr-6 text-right">
                        <Button variant="ghost" size="icon" asChild className="h-8 w-8 text-blue-600">
                          <Link href={`/dashboard/admin/billing/${invoice.id}`}>
                            <Eye className="h-4 w-4" />
                          </Link>
                        </Button>
                        <Button variant="ghost" size="icon" className="h-8 w-8 text-slate-400">
                          <Download className="h-4 w-4" />
                        </Button>
                      </TableCell>
                    </TableRow>
                  ))
                )}
              </TableBody>
            </Table>
          </div>
          
          {/* Pagination */}
          <div className="flex items-center justify-between p-6 bg-slate-50/30 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-800">
            <p className="text-sm text-muted-foreground">
              Menampilkan <span className="font-medium text-foreground">{data?.invoices?.data.length || 0}</span> dari <span className="font-medium text-foreground">{data?.invoices?.total || 0}</span> invoice
            </p>
            <div className="flex items-center gap-2">
              <Button 
                variant="outline" 
                size="sm" 
                disabled={filters.page === 1}
                onClick={() => setFilters({ ...filters, page: filters.page - 1 })}
                className="h-9 px-4 border-slate-200"
              >
                Sebelumnya
              </Button>
              <Button 
                variant="outline" 
                size="sm"
                disabled={filters.page === (data?.invoices?.last_page || 1)}
                onClick={() => setFilters({ ...filters, page: filters.page + 1 })}
                className="h-9 px-4 border-slate-200"
              >
                Berikutnya
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
