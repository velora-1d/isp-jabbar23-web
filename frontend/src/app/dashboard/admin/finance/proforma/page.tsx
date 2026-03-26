"use client";

import { useState } from "react";
import { 
  useProformas, 
  useCreateProforma, 
  useConvertProforma, 
  useCancelProforma,
  ProformaInvoice
} from "@/hooks/use-proforma";
import { useCustomers } from "@/hooks/use-customers";
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
  TableRow 
} from "@/components/ui/table";
import { 
  Dialog, 
  DialogContent, 
  DialogHeader, 
  DialogTitle, 
  DialogTrigger,
  DialogFooter
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from "@/components/ui/select";
import { 
  FileText, 
  Plus, 
  Search, 
  ArrowRight, 
  Ban, 
  CheckCircle2, 
  Clock,
  Briefcase,
  TrendingUp,
  XCircle
} from "lucide-react";
import { toast } from "sonner";
import { format } from "date-fns";
import { id } from "date-fns/locale";

export default function ProformaPage() {
  const [search, setSearch] = useState("");
  const [status, setStatus] = useState<string | null>(null);
  const [page, setPage] = useState(1);
  const [isCreateOpen, setIsCreateOpen] = useState(false);

  // Queries
  const { data: proformasData, isLoading } = useProformas({ search, status: status === "all" ? undefined : status || undefined, page });
  const { data: customers } = useCustomers({ status: 'active' });
  const createMutation = useCreateProforma();
  const convertMutation = useConvertProforma();
  const cancelMutation = useCancelProforma();

  // Form State
  const [formData, setFormData] = useState({
    customer_id: "",
    amount: "",
    valid_days: "30",
    notes: ""
  });

  const handleCreate = async () => {
    try {
      await createMutation.mutateAsync(formData);
      toast.success("Proforma Invoice berhasil dibuat.");
      setIsCreateOpen(false);
      setFormData({ customer_id: "", amount: "", valid_days: "30", notes: "" });
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Gagal membuat proforma");
    }
  };

  const handleConvert = async (id: number) => {
    if (!confirm("Konversi proforma ini menjadi invoice resmi?")) return;
    try {
      await convertMutation.mutateAsync(id);
      toast.success("Berhasil dikonversi ke Invoice.");
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Gagal konversi");
    }
  };

  const statusBadge = (status: string) => {
    switch (status) {
      case 'pending': return <Badge className="bg-orange-500/10 text-orange-600 border-none gap-1"><Clock className="w-3 h-3" /> Pending</Badge>;
      case 'converted': return <Badge className="bg-emerald-500/10 text-emerald-600 border-none gap-1"><CheckCircle2 className="w-3 h-3" /> Converted</Badge>;
      case 'cancelled': return <Badge variant="outline" className="text-red-500 gap-1"><XCircle className="w-3 h-3" /> Cancelled</Badge>;
      case 'expired': return <Badge variant="secondary" className="gap-1"><Ban className="w-3 h-3" /> Expired</Badge>;
      default: return <Badge>{status}</Badge>;
    }
  };

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Proforma Invoices</h1>
          <p className="text-muted-foreground text-sm">Kelola tagihan sementara dan konversi ke invoice resmi untuk pelanggan korporat.</p>
        </div>

        <Dialog open={isCreateOpen} onOpenChange={setIsCreateOpen}>
          <DialogTrigger>
            <Button className="bg-blue-600 shadow-lg">
              <Plus className="w-4 h-4 mr-2" />
              Buat Proforma
            </Button>
          </DialogTrigger>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Buat Proforma Invoice</DialogTitle>
            </DialogHeader>
            <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                    <Label>Pilih Customer</Label>
                    <Select value={formData.customer_id} onValueChange={val => setFormData({...formData, customer_id: val})}>
                        <SelectTrigger>
                            <SelectValue placeholder="Pilih Pelanggan" />
                        </SelectTrigger>
                        <SelectContent>
                            {customers?.data?.map((c: any) => (
                                <SelectItem key={c.id} value={c.id.toString()}>{c.name} ({c.identifier})</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>
                <div className="grid gap-2">
                    <Label>Nominal (Rp)</Label>
                    <Input type="number" value={formData.amount} onChange={e => setFormData({...formData, amount: e.target.value})} placeholder="0" />
                </div>
                <div className="grid gap-2">
                    <Label>Masa Berlaku (Hari)</Label>
                    <Input type="number" value={formData.valid_days} onChange={e => setFormData({...formData, valid_days: e.target.value})} />
                </div>
                <div className="grid gap-2">
                    <Label>Catatan</Label>
                    <Input value={formData.notes} onChange={e => setFormData({...formData, notes: e.target.value})} placeholder="Keterangan tambahan..." />
                </div>
            </div>
            <DialogFooter>
                <Button className="w-full bg-blue-600" onClick={handleCreate} disabled={createMutation.isPending}>
                    {createMutation.isPending ? "Sedang Membuat..." : "Terbitkan Proforma"}
                </Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid gap-4 md:grid-cols-4">
        <Card className="border-none shadow-sm bg-blue-50/50 dark:bg-blue-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-blue-600 dark:text-blue-400 font-medium">Pending Count</CardDescription>
            <CardTitle className="text-2xl flex items-center gap-2">
                <Clock className="w-5 h-5 text-orange-500" />
                {proformasData?.stats.pending_count || 0}
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-slate-50/50 dark:bg-slate-900/50">
          <CardHeader className="pb-2">
            <CardDescription className="font-medium">Potential Value</CardDescription>
            <CardTitle className="text-2xl flex items-center gap-2">
                <TrendingUp className="w-5 h-5 text-blue-600" />
                Rp {(proformasData?.stats.pending_value || 0).toLocaleString('id-ID')}
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-emerald-50/50 dark:bg-emerald-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-emerald-600 dark:text-emerald-400 font-medium">Converted</CardDescription>
            <CardTitle className="text-2xl">{proformasData?.stats.converted_count || 0}</CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-slate-50/50 dark:bg-slate-900/50">
          <CardHeader className="pb-2">
            <CardDescription className="font-medium">Expired</CardDescription>
            <CardTitle className="text-2xl text-muted-foreground">{proformasData?.stats.expired_count || 0}</CardTitle>
          </CardHeader>
        </Card>
      </div>

      <Card className="border-none shadow-md">
        <CardHeader className="flex flex-row items-center justify-between">
          <div className="relative w-full max-w-sm">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <Input 
                placeholder="Cari nomor proforma atau nama..." 
                className="pl-9 h-9" 
                value={search}
                onChange={e => setSearch(e.target.value)}
            />
          </div>
          <div className="flex items-center gap-2">
            <Select value={status || "all"} onValueChange={val => setStatus(val)}>
                <SelectTrigger className="w-[150px] h-9">
                    <SelectValue placeholder="Filter Status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">Semua Status</SelectItem>
                    <SelectItem value="pending">Pending</SelectItem>
                    <SelectItem value="converted">Converted</SelectItem>
                    <SelectItem value="cancelled">Cancelled</SelectItem>
                    <SelectItem value="expired">Expired</SelectItem>
                </SelectContent>
            </Select>
          </div>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Nomor Proforma</TableHead>
                <TableHead>Customer</TableHead>
                <TableHead>Nominal</TableHead>
                <TableHead>Masa Berlaku</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {isLoading ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10">Memuat proforma...</TableCell></TableRow>
              ) : proformasData?.proformas.data.length === 0 ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10 text-muted-foreground">Tidak ada data proforma.</TableCell></TableRow>
              ) : (
                proformasData?.proformas.data.map((p: ProformaInvoice) => (
                  <TableRow key={p.id}>
                    <TableCell className="font-mono font-bold text-blue-600">{p.proforma_number}</TableCell>
                    <TableCell>
                        <div className="flex flex-col">
                            <span className="font-medium text-sm">{p.customer.name}</span>
                            <span className="text-[10px] text-muted-foreground">{p.customer.identifier}</span>
                        </div>
                    </TableCell>
                    <TableCell className="font-bold">Rp {p.amount.toLocaleString('id-ID')}</TableCell>
                    <TableCell className="text-xs">
                        <div className="flex flex-col gap-0.5">
                            <span>S/D: {format(new Date(p.valid_until), 'dd MMM yyyy')}</span>
                            <span className="text-[10px] text-muted-foreground">Terbit: {format(new Date(p.issue_date), 'dd/MM/yy')}</span>
                        </div>
                    </TableCell>
                    <TableCell>{statusBadge(p.status)}</TableCell>
                    <TableCell className="text-right">
                        {p.status === 'pending' && (
                            <div className="flex items-center justify-end gap-2">
                                <Button 
                                    variant="outline" 
                                    size="sm" 
                                    className="h-8 border-emerald-200 text-emerald-600 hover:bg-emerald-50"
                                    onClick={() => handleConvert(p.id)}
                                >
                                    <ArrowRight className="w-3 h-3 mr-1" /> Konversi
                                </Button>
                                <Button 
                                    variant="ghost" 
                                    size="icon" 
                                    className="h-8 w-8 text-red-500"
                                    onClick={() => {
                                        if(confirm("Batalkan proforma?")) cancelMutation.mutate(p.id);
                                    }}
                                >
                                    <Ban className="w-4 h-4" />
                                </Button>
                            </div>
                        )}
                        {p.status === 'converted' && (
                            <Button variant="ghost" size="sm" className="h-8 text-blue-600" onClick={() => toast.info(`Invoice ID: ${p.converted_invoice_id}`)}>
                                <FileText className="w-3 h-3 mr-1" /> Lihat Invoice
                            </Button>
                        )}
                    </TableCell>
                  </TableRow>
                ))
              )}
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  );
}
