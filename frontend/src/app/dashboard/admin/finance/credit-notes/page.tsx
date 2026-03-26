"use client";

import { useState } from "react";
import { 
  useCreditNotes, 
  useCreditNote,
  useCreateCreditNote, 
  useApplyCreditNote,
  useCancelCreditNote,
  CreditNote
} from "@/hooks/use-credit-note";
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
  Plus, 
  Search, 
  CheckCircle2, 
  Clock,
  Ban,
  Coins,
  ArrowDownCircle,
  Text,
  AlertCircle
} from "lucide-react";
import { toast } from "sonner";
import { format } from "date-fns";
import { id } from "date-fns/locale";

export default function CreditNotesPage() {
  const [search, setSearch] = useState("");
  const [status, setStatus] = useState<string | null>(null);
  const [reason, setReason] = useState<string | null>(null);
  const [page, setPage] = useState(1);
  const [isCreateOpen, setIsCreateOpen] = useState(false);
  const [isApplyOpen, setIsApplyOpen] = useState(false);
  const [selectedCN, setSelectedCN] = useState<number | null>(null);
  const [targetInvoiceId, setTargetInvoiceId] = useState<string>("");

  // Queries
  const { data: creditNotesData, isLoading } = useCreditNotes({ 
    search, 
    status: status === "all" ? undefined : status || undefined,
    reason: reason === "all" ? undefined : reason || undefined,
    page 
  });
  const { data: customers } = useCustomers({ status: 'active' });
  const { data: cnDetail } = useCreditNote(selectedCN);
  
  const createMutation = useCreateCreditNote();
  const applyMutation = useApplyCreditNote();
  const cancelMutation = useCancelCreditNote();

  // Form State
  const [formData, setFormData] = useState({
    customer_id: "",
    amount: "",
    reason: "overpayment",
    notes: ""
  });

  const handleCreate = async () => {
    try {
      await createMutation.mutateAsync(formData);
      toast.success("Credit Note berhasil diterbitkan.");
      setIsCreateOpen(false);
      setFormData({ customer_id: "", amount: "", reason: "overpayment", notes: "" });
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Gagal membuat credit note");
    }
  };

  const handleApply = async () => {
    if (!selectedCN || !targetInvoiceId) return;
    try {
      await applyMutation.mutateAsync({ id: selectedCN, invoice_id: parseInt(targetInvoiceId) });
      toast.success("Credit Note berhasil diterapkan ke Invoice.");
      setIsApplyOpen(false);
      setSelectedCN(null);
      setTargetInvoiceId("");
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Gagal menerapkan credit note");
    }
  };

  const statusBadge = (status: string) => {
    switch (status) {
      case 'pending': return <Badge className="bg-orange-500/10 text-orange-600 border-none gap-1"><Clock className="w-3 h-3" /> Pending</Badge>;
      case 'applied': return <Badge className="bg-emerald-500/10 text-emerald-600 border-none gap-1"><CheckCircle2 className="w-3 h-3" /> Applied</Badge>;
      case 'cancelled': return <Badge variant="outline" className="text-red-500 gap-1"><Ban className="w-3 h-3" /> Cancelled</Badge>;
      default: return <Badge>{status}</Badge>;
    }
  };

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Credit Notes</h1>
          <p className="text-muted-foreground text-sm">Kelola penyesuian saldo, refund, dan diskon untuk pelanggan.</p>
        </div>

        <Dialog open={isCreateOpen} onOpenChange={setIsCreateOpen}>
          <DialogTrigger>
            <Button className="bg-indigo-600 shadow-lg hover:bg-indigo-700">
              <Plus className="w-4 h-4 mr-2" />
              Terbitkan Credit Note
            </Button>
          </DialogTrigger>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Terbitkan Credit Note</DialogTitle>
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
                    <Label>Alasan</Label>
                    <Select value={formData.reason} onValueChange={val => setFormData({...formData, reason: val})}>
                        <SelectTrigger>
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="overpayment">Kelebihan Bayar</SelectItem>
                            <SelectItem value="refund">Refund (Kerusakan/Gangguan)</SelectItem>
                            <SelectItem value="discount">Diskon Khusus</SelectItem>
                            <SelectItem value="adjustment">Penyesuaian Saldo</SelectItem>
                            <SelectItem value="other">Lainnya</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div className="grid gap-2">
                    <Label>Catatan Internal</Label>
                    <Input value={formData.notes} onChange={e => setFormData({...formData, notes: e.target.value})} placeholder="Alasan detail..." />
                </div>
            </div>
            <DialogFooter>
                <Button className="w-full bg-indigo-600" onClick={handleCreate} disabled={createMutation.isPending}>
                    {createMutation.isPending ? "Sedang Memproses..." : "Konfirmasi Terbit"}
                </Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid gap-4 md:grid-cols-4">
        <Card className="border-none shadow-sm bg-orange-50/50 dark:bg-orange-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-orange-600 dark:text-orange-400 font-medium font-mono text-[10px]">TOTAL PENDING</CardDescription>
            <CardTitle className="text-2xl flex items-center justify-between">
                <span>{creditNotesData?.stats.pending_count || 0}</span>
                <Clock className="w-8 h-8 text-orange-200" />
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-slate-50/50 dark:bg-slate-900/50">
          <CardHeader className="pb-2">
            <CardDescription className="font-medium font-mono text-[10px]">VALUE PENDING</CardDescription>
            <CardTitle className="text-2xl flex items-center justify-between">
                <span>Rp {(creditNotesData?.stats.pending_value || 0).toLocaleString('id-ID')}</span>
                <Coins className="w-8 h-8 text-slate-200" />
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-emerald-50/50 dark:bg-emerald-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-emerald-600 dark:text-emerald-400 font-medium font-mono text-[10px]">TOTAL APPLIED</CardDescription>
            <CardTitle className="text-2xl flex items-center justify-between">
                <span>{creditNotesData?.stats.applied_count || 0}</span>
                <CheckCircle2 className="w-8 h-8 text-emerald-200" />
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-emerald-50/50 dark:bg-emerald-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-emerald-600 dark:text-emerald-400 font-medium font-mono text-[10px]">VALUE APPLIED</CardDescription>
            <CardTitle className="text-2xl">
                Rp {(creditNotesData?.stats.applied_value || 0).toLocaleString('id-ID')}
            </CardTitle>
          </CardHeader>
        </Card>
      </div>

      <Card className="border-none shadow-md overflow-hidden bg-white dark:bg-slate-950">
        <CardHeader className="flex flex-row items-center justify-between bg-slate-50/50 dark:bg-slate-900/50 border-b">
          <div className="relative w-full max-w-sm">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <Input 
                placeholder="Cari CN atau nama..." 
                className="pl-9 h-9 bg-white" 
                value={search}
                onChange={e => setSearch(e.target.value)}
            />
          </div>
          <div className="flex items-center gap-4">
            <Select value={reason || "all"} onValueChange={val => setReason(val)}>
                <SelectTrigger className="w-[150px] h-9 bg-white">
                    <SelectValue placeholder="Alasan" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">Semua Alasan</SelectItem>
                    <SelectItem value="overpayment">Kelebihan Bayar</SelectItem>
                    <SelectItem value="refund">Refund</SelectItem>
                    <SelectItem value="discount">Diskon</SelectItem>
                    <SelectItem value="adjustment">Penyesuaian</SelectItem>
                </SelectContent>
            </Select>
            <Select value={status || "all"} onValueChange={val => setStatus(val)}>
                <SelectTrigger className="w-[150px] h-9 bg-white">
                    <SelectValue placeholder="Status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">Semua Status</SelectItem>
                    <SelectItem value="pending">Pending</SelectItem>
                    <SelectItem value="applied">Applied</SelectItem>
                    <SelectItem value="cancelled">Cancelled</SelectItem>
                </SelectContent>
            </Select>
          </div>
        </CardHeader>
        <CardContent className="p-0">
          <Table>
            <TableHeader className="bg-slate-100/50 dark:bg-slate-800/50">
              <TableRow>
                <TableHead className="py-4">Nomor CN</TableHead>
                <TableHead>Customer</TableHead>
                <TableHead>Nominal</TableHead>
                <TableHead>Alasan</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right pr-6">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {isLoading ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10">Memuat credit notes...</TableCell></TableRow>
              ) : creditNotesData?.credit_notes.data.length === 0 ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10 text-muted-foreground font-medium">Belum ada catatan kredit.</TableCell></TableRow>
              ) : (
                creditNotesData?.credit_notes.data.map((cn: CreditNote) => (
                  <TableRow key={cn.id} className="hover:bg-slate-50/50 transition-colors">
                    <TableCell className="font-mono font-bold text-indigo-600 pl-6">{cn.credit_number}</TableCell>
                    <TableCell>
                        <div className="flex flex-col">
                            <span className="font-semibold text-sm">{cn.customer.name}</span>
                            <span className="text-[10px] text-muted-foreground uppercase tracking-widest">{cn.customer.identifier}</span>
                        </div>
                    </TableCell>
                    <TableCell className="font-bold text-emerald-600">Rp {cn.amount.toLocaleString('id-ID')}</TableCell>
                    <TableCell>
                        <Badge variant="outline" className="text-[10px] uppercase tracking-tighter bg-slate-50 border-slate-200">
                           {cn.reason}
                        </Badge>
                    </TableCell>
                    <TableCell>{statusBadge(cn.status)}</TableCell>
                    <TableCell className="text-right pr-6">
                        {cn.status === 'pending' && (
                            <div className="flex items-center justify-end gap-2">
                                <Button 
                                    size="sm" 
                                    className="h-8 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border-none shadow-none"
                                    onClick={() => {
                                        setSelectedCN(cn.id);
                                        setIsApplyOpen(true);
                                    }}
                                >
                                    <ArrowDownCircle className="w-3 h-3 mr-1" /> Terapkan
                                </Button>
                                <Button 
                                    variant="ghost" 
                                    size="icon" 
                                    className="h-8 w-8 text-slate-400 hover:text-red-500 hover:bg-red-50"
                                    onClick={() => {
                                        if(confirm("Batalkan credit note?")) cancelMutation.mutate(cn.id);
                                    }}
                                >
                                    <Ban className="w-4 h-4" />
                                </Button>
                            </div>
                        )}
                        {cn.status === 'applied' && (
                             <Badge variant="secondary" className="bg-slate-100 text-slate-500 font-normal">
                               ID Invoice: {cn.applied_to_invoice_id}
                             </Badge>
                        )}
                    </TableCell>
                  </TableRow>
                ))
              )}
            </TableBody>
          </Table>
        </CardContent>
      </Card>

      {/* Apply CN Dialog */}
      <Dialog open={isApplyOpen} onOpenChange={setIsApplyOpen}>
          <DialogContent>
              <DialogHeader>
                  <DialogTitle>Terapkan ke Tagihan (Invoice)</DialogTitle>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                  {cnDetail && (
                      <div className="p-4 bg-indigo-50 dark:bg-indigo-950/20 rounded-lg flex flex-col gap-2">
                          <div className="flex justify-between items-center">
                              <span className="text-xs text-indigo-600 font-bold uppercase">Nominal Kredit</span>
                              <span className="text-lg font-bold text-indigo-700">Rp {cnDetail.credit_note.amount.toLocaleString('id-ID')}</span>
                          </div>
                          <div className="text-[10px] text-indigo-500 border-t border-indigo-100 pt-2 italic">
                              *Kredit akan mematikan status tagihan menjadi PAID (Lunas) jika nominal cukup.
                          </div>
                      </div>
                  )}
                  
                  <div className="grid gap-2">
                      <Label>Pilih Invoice Belum Lunas</Label>
                      {cnDetail?.unpaid_invoices.length > 0 ? (
                          <Select value={targetInvoiceId} onValueChange={setTargetInvoiceId}>
                              <SelectTrigger>
                                  <SelectValue placeholder="Pilih Nomor Invoice" />
                              </SelectTrigger>
                              <SelectContent>
                                  {cnDetail.unpaid_invoices.map((inv: any) => (
                                      <SelectItem key={inv.id} value={inv.id.toString()}>
                                          {inv.invoice_number} - Rp {inv.amount.toLocaleString('id-ID')}
                                      </SelectItem>
                                  ))}
                              </SelectContent>
                          </Select>
                      ) : (
                          <div className="flex items-center gap-2 p-3 text-sm text-orange-600 bg-orange-50 rounded border border-orange-100">
                              <AlertCircle className="w-4 h-4" />
                              Customer tidak memiliki tagihan (Unpaid) aktif.
                          </div>
                      )}
                  </div>
              </div>
              <DialogFooter>
                  <Button 
                    className="w-full bg-emerald-600 hover:bg-emerald-700" 
                    disabled={!targetInvoiceId || applyMutation.isPending}
                    onClick={handleApply}
                  >
                      {applyMutation.isPending ? "Sedang Menerapkan..." : "Terapkan Kredit Sekarang"}
                  </Button>
              </DialogFooter>
          </DialogContent>
      </Dialog>
    </div>
  );
}
