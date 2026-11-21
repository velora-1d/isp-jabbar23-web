"use client";

import { useInvoice, useMarkAsPaid } from "@/hooks/use-invoices";
import { Invoice } from "@/types/finance";
import { useParams, useRouter } from "next/navigation";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { 
  ArrowLeft, 
  User, 
  Calendar, 
  CreditCard, 
  CheckCircle2, 
  Loader2,
  Download,
  Receipt,
  AlertTriangle,
  ExternalLink,
  ShieldCheck,
  Zap
} from "lucide-react";
import Link from "next/link";
import { format } from "date-fns";
import { id as idLocale } from "date-fns/locale";
import { useState } from "react";
import { DashboardPageShell } from "@/components/dashboard/page-shell";
import { cn } from "@/lib/utils";

export default function BillingDetailPage() {
  const { id } = useParams() as { id: string };
  const router = useRouter();
  const { data: invoice, isLoading } = useInvoice(id);
  const markAsPaid = useMarkAsPaid();

  const [paymentForm, setPaymentForm] = useState({
    payment_method: "manual_transfer",
    payment_date: new Date().toISOString().split("T")[0],
  });

  if (isLoading) {
    return (
      <div className="flex h-[60vh] items-center justify-center">
        <div className="flex flex-col items-center gap-4">
          <Loader2 className="w-10 h-10 animate-spin text-emerald-500" />
          <p className="text-zinc-500 font-medium animate-pulse">Mengambil data transmisi invoice...</p>
        </div>
      </div>
    );
  }

  if (!invoice) return (
    <div className="p-8 text-center text-zinc-500 bg-red-500/5 border border-red-500/10 rounded-2xl">
      Transmisi Data Terputus: Invoice tidak ditemukan.
    </div>
  );

  const handleConfirmPayment = () => {
    markAsPaid.mutate({
      id,
      data: paymentForm,
    });
  };

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0,
    }).format(amount);
  };

  const getStatusBadge = (status: string) => {
    switch (status) {
      case "paid":
        return <Badge className="bg-emerald-500/10 text-emerald-400 border-emerald-500/20">Terbayar</Badge>;
      case "unpaid":
        return <Badge className="bg-red-500/10 text-red-500 border-red-500/20">Belum Bayar</Badge>;
      case "overdue":
        return <Badge className="bg-orange-500/10 text-orange-500 border-orange-500/20 shadow-[0_0_10px_rgba(249,115,22,0.1)]">Jatuh Tempo</Badge>;
      case "pending_approval":
        return <Badge className="bg-emerald-500/10 text-emerald-400 border-emerald-500/20 animate-pulse">Menunggu Verifikasi</Badge>;
      default:
        return <Badge variant="outline">{status}</Badge>;
    }
  };

  return (
    <DashboardPageShell
      title={`Invoice #${invoice.invoice_number}`}
      description={`Rincian tagihan layanan untuk periode ${invoice.period_start ? format(new Date(invoice.period_start as string), "MMMM yyyy", { locale: idLocale }) : "-"}`}
      actions={
        <div className="flex items-center gap-2">
           <Button variant="outline" className="h-10 border-white/10 bg-white/[0.03] text-zinc-400 hover:text-white">
             <Download className="w-4 h-4 mr-2" />
             Download PDF
           </Button>
           {invoice.status !== "paid" && (
             <Button 
              className="bg-emerald-600 hover:bg-emerald-500 text-white h-10 shadow-lg shadow-emerald-500/20 transition-all active:scale-95"
              onClick={handleConfirmPayment}
              disabled={markAsPaid.isPending}
             >
              {markAsPaid.isPending ? <Loader2 className="w-4 h-4 mr-2 animate-spin" /> : <CheckCircle2 className="w-4 h-4 mr-2" />}
              Konfirmasi Lunas
            </Button>
           )}
        </div>
      }
    >
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Main Content Area */}
        <div className="lg:col-span-2 space-y-6">
          {/* Main Invoice Card */}
          <div className="rounded-2xl border border-white/5 bg-zinc-900/10 overflow-hidden backdrop-blur-sm shadow-2xl relative">
             <div className="p-8 space-y-8">
                {/* Header Info */}
                <div className="flex justify-between items-start">
                   <div className="space-y-1">
                      <p className="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Status Tagihan</p>
                      <div className="pt-1">{getStatusBadge(invoice.status)}</div>
                   </div>
                   <div className="text-right space-y-1">
                      <p className="text-[10px] font-bold text-zinc-500 uppercase tracking-widest underline decoration-emerald-500/30">Metode Verifikasi</p>
                      <p className="text-xs font-medium text-zinc-400 mt-1 flex items-center justify-end gap-1.5">
                         <ShieldCheck className="w-3.5 h-3.5 text-emerald-500" />
                         Auto-Match Billing
                      </p>
                   </div>
                </div>

                {/* Table Breakdown */}
                <div className="rounded-xl border border-white/5 bg-white/[0.02] overflow-hidden">
                   <table className="w-full text-sm">
                      <thead className="bg-white/[0.03]">
                         <tr>
                            <th className="px-6 py-4 text-left font-bold text-zinc-400 uppercase tracking-tighter text-[10px]">Deskripsi Layanan</th>
                            <th className="px-6 py-4 text-right font-bold text-zinc-400 uppercase tracking-tighter text-[10px]">Jumlah (IDR)</th>
                         </tr>
                      </thead>
                      <tbody className="divide-y divide-white/5">
                         <tr className="hover:bg-white/[0.01] transition-colors">
                            <td className="px-6 py-6 flex items-start gap-3">
                               <div className="p-2 rounded-lg bg-emerald-500/10 text-emerald-500 mt-0.5">
                                  <Zap className="w-4 h-4" />
                               </div>
                               <div>
                                  <p className="font-semibold text-white">Layanan Internet Broadband</p>
                                  <p className="text-xs text-zinc-500 mt-1">Bulan {invoice.period_start ? format(new Date(invoice.period_start as string), "MMMM yyyy", { locale: idLocale }) : "-"}</p>
                               </div>
                            </td>
                            <td className="px-6 py-6 text-right font-bold text-white font-heading">
                               {formatCurrency(invoice.amount)}
                            </td>
                         </tr>
                      </tbody>
                      <tfoot className="bg-emerald-500/5 font-medium border-t border-white/5">
                         <tr>
                            <td className="px-6 py-4 text-right text-zinc-400">Pajak Pertambahan Nilai (PPN 11%)</td>
                            <td className="px-6 py-4 text-right text-zinc-300 font-bold">{formatCurrency(invoice.tax_amount)}</td>
                         </tr>
                         <tr className="text-xl font-bold bg-emerald-500/10">
                            <td className="px-6 py-5 text-right text-white">TOTAL PEMBAYARAN</td>
                            <td className="px-6 py-5 text-right text-emerald-400 font-heading tracking-tight">{formatCurrency(invoice.total_after_tax)}</td>
                         </tr>
                      </tfoot>
                   </table>
                </div>

                {/* Dates Info */}
                <div className="grid grid-cols-2 gap-4">
                  <div className="p-4 rounded-xl border border-white/5 bg-zinc-900/40">
                    <p className="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">Diterbitkan</p>
                    <p className="text-sm font-semibold text-white">{format(new Date(invoice.created_at), "dd MMMM yyyy", { locale: idLocale })}</p>
                  </div>
                  <div className="p-4 rounded-xl border border-red-500/5 bg-red-500/5">
                    <p className="text-[10px] font-bold text-red-500/50 uppercase tracking-widest mb-1">Batas Waktu</p>
                    <p className="text-sm font-semibold text-red-400">{format(new Date(invoice.due_date), "dd MMMM yyyy", { locale: idLocale })}</p>
                  </div>
                </div>
             </div>

             {/* Bottom Decoration */}
             <div className="h-1 bg-gradient-to-r from-emerald-500/0 via-emerald-500/50 to-emerald-500/0 w-full" />
          </div>

          {/* Payment Status Message */}
          {invoice.status === "paid" && (
            <div className="rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-6 flex items-center gap-4 animate-in fade-in zoom-in duration-500">
               <div className="h-12 w-12 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 border border-emerald-500/20">
                  <CheckCircle2 className="w-6 h-6 shadow-[0_0_15px_rgba(16,185,129,0.3)]" />
               </div>
               <div>
                  <p className="font-bold text-white uppercase tracking-tight">Pembayaran Terverifikasi</p>
                  <p className="text-sm text-zinc-400">
                     Sistem telah memproses pelunasan pada <span className="text-emerald-400 font-semibold">{format(new Date(invoice.payment_date!), "dd MMMM yyyy", { locale: idLocale })}</span> melalui {invoice.payment_method}.
                  </p>
               </div>
            </div>
          )}
        </div>

        {/* Sidebar Actions */}
        <div className="space-y-6">
          {/* Customer Context Card */}
          <div className="rounded-2xl border border-white/5 bg-zinc-900/50 backdrop-blur-md p-6 space-y-6 relative overflow-hidden group">
             <div className="flex items-center justify-between">
                <h4 className="text-xs font-bold text-zinc-500 uppercase tracking-widest">Konteks Pelanggan</h4>
                <div className="h-2 w-2 rounded-full bg-emerald-500 animate-pulse" />
             </div>
             
             <div className="flex items-center gap-4">
                <div className="h-12 w-12 rounded-xl bg-zinc-800 border border-white/5 flex items-center justify-center text-emerald-500 font-bold text-xl font-heading group-hover:border-emerald-500/30 transition-colors">
                  {invoice.customer?.name?.[0]}
                </div>
                <div className="flex-1 min-w-0">
                   <p className="font-bold text-white truncate">{invoice.customer?.name}</p>
                   <p className="text-xs text-zinc-500 font-mono tracking-tighter uppercase">{invoice.customer?.customer_id}</p>
                </div>
             </div>

             <Button variant="outline" className="w-full h-11 border-white/5 bg-white/[0.03] text-zinc-400 hover:text-white rounded-xl group-hover:border-emerald-500/20 transition-all font-semibold" asChild>
                <Link href={`/dashboard/admin/customers/${invoice.customer_id}`}>
                   Profil Pelanggan
                   <ExternalLink className="w-3.5 h-3.5 ml-2 opacity-50" />
                </Link>
             </Button>
          </div>

          {/* Payment Execution Card */}
          {invoice.status !== "paid" && (
            <div className="rounded-2xl border border-white/5 bg-zinc-900/50 backdrop-blur-md p-6 space-y-6">
               <h4 className="text-xs font-bold text-zinc-500 uppercase tracking-widest flex items-center gap-2">
                  <CreditCard className="w-3.5 h-3.5" />
                  Terminal Pembayaran
               </h4>

               <div className="space-y-4">
                  <div className="space-y-2">
                     <Label className="text-[10px] font-bold text-zinc-400 uppercase px-1">Metode Capture</Label>
                     <select 
                        className="w-full h-11 px-4 rounded-xl border border-white/5 bg-zinc-950 text-sm text-zinc-300 focus:ring-1 focus:ring-emerald-500/50 outline-none transition-all appearance-none cursor-pointer"
                        value={paymentForm.payment_method}
                        onChange={(e) => setPaymentForm({...paymentForm, payment_method: e.target.value})}
                     >
                        <option value="manual_transfer">Transfer Manual (BCA/Mandiri)</option>
                        <option value="cash">Tunai (Kantor)</option>
                        <option value="other">Penyesuaian Manual</option>
                     </select>
                  </div>
                  <div className="space-y-2">
                     <Label className="text-[10px] font-bold text-zinc-400 uppercase px-1">Waktu Transaksi</Label>
                     <Input 
                        type="date" 
                        value={paymentForm.payment_date}
                        className="h-11 border-white/5 bg-zinc-950 rounded-xl focus:ring-emerald-500/50"
                        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setPaymentForm({...paymentForm, payment_date: e.target.value})}
                     />
                  </div>
               </div>

               <div className="p-4 rounded-xl bg-amber-500/5 border border-amber-500/10 flex gap-3">
                  <AlertTriangle className="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
                  <p className="text-[10px] text-amber-200/60 leading-relaxed font-medium">
                     TINDAKAN MANUAL: Pastikan Anda telah melihat fisik bukti bayar sebelum mengeksekusi konfirmasi lunas pada sistem pusat JABBAR23.
                  </p>
               </div>
            </div>
          )}
        </div>
      </div>
    </DashboardPageShell>
  );
}
