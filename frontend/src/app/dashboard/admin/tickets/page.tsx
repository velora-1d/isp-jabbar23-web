"use client";

import { useState } from "react";
import { useTickets, type Ticket } from "@/hooks/use-tickets";
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
  Plus, 
  Filter, 
  Ticket as TicketIcon, 
  Clock, 
  CheckCircle2, 
  AlertCircle,
  MoreHorizontal,
  Eye
} from "lucide-react";
import Link from "next/link";
import { format } from "date-fns";
import { id as idLocale } from "date-fns/locale";

export default function TicketsPage() {
  const [filters, setFilters] = useState({
    search: "",
    status: "all",
    priority: "all",
    page: 1,
  });

  const { data, isLoading } = useTickets({
    ...filters,
    status: filters.status === "all" ? "" : filters.status,
    priority: filters.priority === "all" ? "" : filters.priority,
  });

  const getStatusBadge = (status: string) => {
    switch (status) {
      case "open":
        return <Badge className="bg-blue-500/10 text-blue-500 border-blue-500/20">Open</Badge>;
      case "in_progress":
        return <Badge className="bg-yellow-500/10 text-yellow-500 border-yellow-500/20">In Progress</Badge>;
      case "resolved":
        return <Badge className="bg-green-500/10 text-green-500 border-green-500/20">Resolved</Badge>;
      case "closed":
        return <Badge variant="secondary">Closed</Badge>;
      default:
        return <Badge variant="outline">{status}</Badge>;
    }
  };

  const getPriorityBadge = (priority: string) => {
    switch (priority) {
      case "low":
        return <Badge variant="outline" className="text-slate-500">Low</Badge>;
      case "medium":
        return <Badge variant="outline" className="text-blue-500">Medium</Badge>;
      case "high":
        return <Badge variant="outline" className="text-orange-500">High</Badge>;
      case "critical":
        return <Badge variant="destructive">Critical</Badge>;
      default:
        return <Badge variant="outline">{priority}</Badge>;
    }
  };

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col gap-2">
        <h1 className="text-3xl font-bold tracking-tight">Manajemen Tiket</h1>
        <p className="text-muted-foreground">
          Kelola laporan gangguan dan permintaan layanan dari pelanggan.
        </p>
      </div>

      {/* Stats Grid */}
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card className="border-none shadow-sm bg-slate-50/50 dark:bg-slate-900/50">
          <CardContent className="p-6">
            <div className="flex items-center gap-4">
              <div className="p-2 bg-blue-500/10 rounded-lg text-blue-600">
                <TicketIcon className="w-6 h-6" />
              </div>
              <div>
                <p className="text-sm font-medium text-muted-foreground">Total Tiket</p>
                <h3 className="text-2xl font-bold">{data?.stats?.total || 0}</h3>
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
                <p className="text-sm font-medium text-muted-foreground">Open</p>
                <h3 className="text-2xl font-bold">{data?.stats?.open || 0}</h3>
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
                <p className="text-sm font-medium text-muted-foreground">In Progress</p>
                <h3 className="text-2xl font-bold">{data?.stats?.in_progress || 0}</h3>
              </div>
            </div>
          </CardContent>
        </Card>
        <Card className="border-none shadow-sm bg-green-50/50 dark:bg-green-900/10">
          <CardContent className="p-6">
            <div className="flex items-center gap-4">
              <div className="p-2 bg-green-500/10 rounded-lg text-green-600">
                <CheckCircle2 className="w-6 h-6" />
              </div>
              <div>
                <p className="text-sm font-medium text-muted-foreground">Resolved</p>
                <h3 className="text-2xl font-bold">{data?.stats?.resolved || 0}</h3>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <Card className="border-none shadow-md overflow-hidden bg-white/50 backdrop-blur-sm dark:bg-slate-950/50">
        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-7">
          <div className="flex flex-col gap-1">
            <CardTitle className="text-xl font-bold">Daftar Tiket</CardTitle>
            <CardDescription>Menampilkan semua tiket gangguan pelanggan</CardDescription>
          </div>
          <div className="flex items-center gap-2">
            <div className="relative w-64">
              <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
              <Input
                placeholder="Cari nomor tiket atau nama..."
                className="pl-9 h-10 border-slate-200"
                value={filters.search}
                onChange={(e) => setFilters({ ...filters, search: e.target.value })}
              />
            </div>
            <Select 
              value={filters.status || ""} 
              onValueChange={(v) => setFilters({ ...filters, status: v })}
            >
              <SelectTrigger className="w-[140px] h-10 border-slate-200">
                <Filter className="w-4 h-4 mr-2" />
                <SelectValue placeholder="Status" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Semua Status</SelectItem>
                <SelectItem value="open">Open</SelectItem>
                <SelectItem value="in_progress">In Progress</SelectItem>
                <SelectItem value="resolved">Resolved</SelectItem>
                <SelectItem value="closed">Closed</SelectItem>
              </SelectContent>
            </Select>
            <Button className="bg-blue-600 hover:bg-blue-700 text-white h-10">
              <Plus className="w-4 h-4 mr-2" />
              Buat Tiket
            </Button>
          </div>
        </CardHeader>
        <CardContent className="p-0">
          <div className="rounded-none border-t border-slate-100 dark:border-slate-800">
            <Table>
              <TableHeader className="bg-slate-50/50 dark:bg-slate-900/50">
                <TableRow className="hover:bg-transparent">
                  <TableHead className="py-4 pl-6 font-semibold">Tiket #</TableHead>
                  <TableHead className="font-semibold">Pelanggan</TableHead>
                  <TableHead className="font-semibold">Subjek</TableHead>
                  <TableHead className="font-semibold text-center">Prioritas</TableHead>
                  <TableHead className="font-semibold text-center">Status</TableHead>
                  <TableHead className="font-semibold">Tgl Masuk</TableHead>
                  <TableHead className="pr-6 text-right font-semibold">Aksi</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {isLoading ? (
                  <TableRow>
                    <TableCell colSpan={7} className="h-24 text-center">Memuat data...</TableCell>
                  </TableRow>
                ) : data?.tickets?.data.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={7} className="h-24 text-center">Tidak ada tiket ditemukan.</TableCell>
                  </TableRow>
                ) : (
                  data?.tickets?.data.map((ticket: Ticket) => (
                    <TableRow key={ticket.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors border-slate-100 dark:border-slate-800">
                      <TableCell className="py-4 pl-6 font-medium text-blue-600 dark:text-blue-400">
                        {ticket.ticket_number}
                      </TableCell>
                      <TableCell>
                        <div className="flex flex-col">
                          <span className="font-medium">{ticket.customer?.name}</span>
                          <span className="text-xs text-muted-foreground">{ticket.customer?.customer_id}</span>
                        </div>
                      </TableCell>
                      <TableCell className="max-w-[200px] truncate" title={ticket.subject}>
                        {ticket.subject}
                      </TableCell>
                      <TableCell className="text-center">
                        {getPriorityBadge(ticket.priority)}
                      </TableCell>
                      <TableCell className="text-center">
                        {getStatusBadge(ticket.status)}
                      </TableCell>
                      <TableCell className="text-sm">
                        {ticket.created_at ? format(new Date(ticket.created_at), "dd MMM yyyy, HH:mm", { locale: idLocale }) : "-"}
                      </TableCell>
                      <TableCell className="pr-6 text-right">
                        <Button variant="ghost" size="icon" asChild className="h-8 w-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50">
                          <Link href={`/dashboard/admin/tickets/${ticket.id}`}>
                            <Eye className="h-4 w-4" />
                          </Link>
                        </Button>
                        <Button variant="ghost" size="icon" className="h-8 w-8 text-slate-400">
                          <MoreHorizontal className="h-4 w-4" />
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
              Menampilkan <span className="font-medium text-foreground">{data?.tickets?.data.length || 0}</span> dari <span className="font-medium text-foreground">{data?.tickets?.total || 0}</span> tiket
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
                disabled={filters.page === (data?.tickets?.last_page || 1)}
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
