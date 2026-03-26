"use client";

import { useInvoice, useMarkAsPaid } from "@/hooks/use-invoices";
import { useParams, useRouter } from "next/navigation";
import { 
  Card, 
  CardContent, 
  CardHeader, 
  CardTitle,
  CardDescription
} from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { 
  ArrowLeft, 
  User, 
  Calendar, 
  CreditCard, 
  Info, 
  CheckCircle2, 
  Save,
  Loader2,
  Download,
  Receipt,
  AlertTriangle
} from "lucide-react";
import Link from "next/link";
import { format } from "date-fns";
import { id as idLocale } from "date-fns/locale";
import { useState } from "react";

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
      <div className="flex items-center justify-center min-h-[400px]">
        <Loader2 className="w-8 h-8 animate-spin text-blue-600" />
      </div>
    );
  }

  if (!invoice) return <div>Invoice tidak ditemukan.</div>;

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

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-4">
          <Button variant="ghost" size="icon" asChild className="rounded-full">
            <Link href="/dashboard/admin/billing">
              <ArrowLeft className="h-5 w-5" />
            </Link>
          </Button>
          <div className="flex flex-col">
            <h1 className="text-2xl font-bold tracking-tight flex items-center gap-2">
              Invoice #{invoice.invoice_number}
              {getStatusBadge(invoice.status)}
            </h1>
            <p className="text-muted-foreground flex items-center gap-2">
              Periode {invoice.period_start ? format(new Date(invoice.period_start as string), "MMMM yyyy", { locale: idLocale }) : "-"}
            </p>
          </div>
        </div>
        <div className="flex items-center gap-2">
           <Button variant="outline" className="border-slate-200">
             <Download className="w-4 h-4 mr-2" /> PDF
           </Button>
           {invoice.status !== "paid" && (
             <Button 
              className="bg-green-600 hover:bg-green-700 text-white shadow-lg shadow-green-500/20"
              onClick={handleConfirmPayment}
              disabled={markAsPaid.isPending}
             >
              {markAsPaid.isPending ? <Loader2 className="w-4 h-4 mr-2 animate-spin" /> : <CheckCircle2 className="w-4 h-4 mr-2" />}
              Konfirmasi Lunas
            </Button>
           )}
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Main Content */}
        <div className="lg:col-span-2 flex flex-col gap-6">
          <Card className="border-none shadow-md overflow-hidden bg-white dark:bg-slate-950">
            <CardHeader className="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
              <CardTitle className="text-lg font-bold flex items-center gap-2">
                <Receipt className="w-5 h-5 text-blue-500" />
                Rincian Tagihan
              </CardTitle>
            </CardHeader>
            <CardContent className="p-0">
               <div className="p-6 space-y-6">
                  <div className="grid grid-cols-2 gap-4 text-sm">
                     <div className="space-y-1">
                        <p className="text-muted-foreground">Tanggal Terbit</p>
                        <p className="font-medium">{format(new Date(invoice.created_at), "dd MMM yyyy", { locale: idLocale })}</p>
                     </div>
                     <div className="space-y-1 text-right">
                        <p className="text-muted-foreground">Batas Pembayaran</p>
                        <p className="font-medium text-red-500">{format(new Date(invoice.due_date), "dd MMM yyyy", { locale: idLocale })}</p>
                     </div>
                  </div>

                  <div className="border rounded-lg overflow-hidden">
                     <table className="w-full text-sm">
                        <thead className="bg-slate-50 dark:bg-slate-900">
                           <tr>
                              <th className="px-4 py-3 text-left font-semibold">Deskripsi Layanan</th>
                              <th className="px-4 py-3 text-right font-semibold">Jumlah</th>
                           </tr>
                        </thead>
                        <tbody className="divide-y">
                           <tr>
                              <td className="px-4 py-4">
                                 Layanan Internet - Bulan {invoice.period_start ? format(new Date(invoice.period_start as string), "MMMM yyyy", { locale: idLocale }) : "-"}
                              </td>
                              <td className="px-4 py-4 text-right">
                                 {formatCurrency(invoice.amount)}
                              </td>
                           </tr>
                        </tbody>
                        <tfoot className="bg-slate-50/50 dark:bg-slate-900/50 font-medium">
                           <tr>
                              <td className="px-4 py-3 text-right text-muted-foreground">PPN (11%)</td>
                              <td className="px-4 py-3 text-right">{formatCurrency(invoice.tax_amount)}</td>
                           </tr>
                           <tr className="text-lg font-bold text-blue-600">
                              <td className="px-4 py-4 text-right">Total Bayar</td>
                              <td className="px-4 py-4 text-right">{formatCurrency(invoice.total_after_tax)}</td>
                           </tr>
                        </tfoot>
                     </table>
                  </div>
               </div>
            </CardContent>
          </Card>

          {invoice.status === "paid" && (
            <Card className="border-green-100 shadow-sm bg-green-50/30">
               <CardContent className="p-6 flex items-center gap-4">
                  <div className="p-2 bg-green-100 rounded-full text-green-600">
                     <CheckCircle2 className="w-6 h-6" />
                  </div>
                  <div>
                     <p className="font-bold text-green-800">Pembayaran Terverifikasi</p>
                     <p className="text-sm text-green-700">
                        Invoice ini telah dibayar pada {format(new Date(invoice.payment_date!), "dd MMMM yyyy", { locale: idLocale })} melalui {invoice.payment_method}.
                     </p>
                  </div>
               </CardContent>
            </Card>
          )}
        </div>

        {/* Sidebar */}
        <div className="flex flex-col gap-6">
          <Card className="border-none shadow-md overflow-hidden bg-white dark:bg-slate-950">
            <CardHeader className="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
              <CardTitle className="text-lg font-bold flex items-center gap-2">
                <User className="w-5 h-5 text-blue-500" />
                Informasi Pelanggan
              </CardTitle>
            </CardHeader>
            <CardContent className="p-6 space-y-4">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 font-bold">
                  {invoice.customer?.name?.[0]}
                </div>
                <div className="flex flex-col">
                   <p className="font-bold">{invoice.customer?.name}</p>
                   <p className="text-xs text-muted-foreground font-mono">{invoice.customer?.customer_id}</p>
                </div>
              </div>
              <Button variant="outline" className="w-full border-slate-200" asChild>
                <Link href={`/dashboard/admin/customers/${invoice.customer_id}`}>
                   Detail Pelanggan
                </Link>
              </Button>
            </CardContent>
          </Card>

          {invoice.status !== "paid" && (
            <Card className="border-none shadow-md overflow-hidden bg-white dark:bg-slate-950">
               <CardHeader className="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
                  <CardTitle className="text-lg font-bold flex items-center gap-2">
                     <CreditCard className="w-5 h-5 text-blue-500" />
                     Data Pembayaran
                  </CardTitle>
               </CardHeader>
               <CardContent className="p-6 space-y-4">
                  <div className="space-y-2">
                     <Label>Metode Pembayaran</Label>
                     <select 
                        className="w-full h-10 px-3 rounded-md border border-slate-200 bg-white dark:bg-slate-900"
                        value={paymentForm.payment_method}
                        onChange={(e) => setPaymentForm({...paymentForm, payment_method: e.target.value})}
                     >
                        <option value="manual_transfer">Transfer Manual (BCA/Mandiri)</option>
                        <option value="cash">Tunai (Kantor)</option>
                        <option value="other">Lainnya</option>
                     </select>
                  </div>
                  <div className="space-y-2">
                     <Label>Tanggal Bayar</Label>
                     <Input 
                        type="date" 
                        value={paymentForm.payment_date}
                        onChange={(e) => setPaymentForm({...paymentForm, payment_date: e.target.value})}
                     />
                  </div>
                  <div className="p-3 bg-amber-50 rounded-lg border border-amber-100 flex gap-2">
                     <AlertTriangle className="w-4 h-4 text-amber-600 shrink-0" />
                     <p className="text-[10px] text-amber-700">
                        Pastikan Anda telah menerima bukti pembayaran yang sah sebelum melakukan konfirmasi lunas.
                     </p>
                  </div>
               </CardContent>
            </Card>
          )}
        </div>
      </div>
    </div>
  );
}
