'use client';

import { useState } from 'react';
import { useInvoices } from '@/hooks/use-invoices';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { toast } from 'sonner';
import { 
  FileText, 
  Send, 
  Download, 
  Search, 
  Filter, 
  MoreVertical,
  Calendar,
  Layers,
  CheckCircle2,
  AlertCircle,
  Zap
} from 'lucide-react';
import { format } from 'date-fns';

export function InvoiceDashboard() {
  const [params, setParams] = useState({ page: 1, month: new Date().getMonth() + 1, year: new Date().getFullYear() });
  const { invoices, isLoading, generateInvoices, getSnapToken } = useInvoices(params);

  const handleGenerate = () => {
    if (confirm(`Generate invoice untuk bulan ${params.month}/${params.year}?`)) {
      generateInvoices.mutate({ month: params.month, year: params.year });
    }
  };

  if (isLoading) return <div className="p-8 text-center text-zinc-500 italic">Memuat data penagihan...</div>;

  return (
    <div className="space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold text-white tracking-tight">Invoice Management</h2>
          <p className="text-zinc-400 text-sm">Kelola tagihan pelanggan dan otomasi bulanan.</p>
        </div>
        <div className="flex items-center gap-3">
          <div className="flex bg-zinc-900 border border-zinc-800 rounded-lg p-1">
             <Input 
               type="number" 
               className="w-16 h-8 bg-transparent border-none text-xs focus-visible:ring-0" 
               value={params.month}
               onChange={(e) => setParams(p => ({ ...p, month: parseInt(e.target.value) }))}
             />
             <div className="w-px h-4 bg-zinc-800 self-center mx-1" />
             <Input 
               type="number" 
               className="w-20 h-8 bg-transparent border-none text-xs focus-visible:ring-0" 
               value={params.year}
               onChange={(e) => setParams(p => ({ ...p, year: parseInt(e.target.value) }))}
             />
          </div>
          <Button onClick={handleGenerate} disabled={generateInvoices.isPending} variant="secondary" size="sm">
            <Layers className="w-4 h-4 mr-2" />
            Generate Bulk
          </Button>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          <StatCard title="Total Unpaid" value={invoices?.meta?.total_unpaid || 0} icon={<AlertCircle className="text-red-400" />} />
          <StatCard title="Total Paid" value={invoices?.meta?.total_paid || 0} icon={<CheckCircle2 className="text-emerald-400" />} />
          <StatCard title="Total Revenue" value={`Rp ${invoices?.meta?.total_revenue?.toLocaleString() || 0}`} icon={<FileText className="text-blue-400" />} />
          <StatCard title="Collection Rate" value="92%" icon={<Calendar className="text-purple-400" />} />
      </div>

      <Card className="bg-zinc-900/20 border-zinc-800/50 backdrop-blur-sm overflow-hidden">
        <div className="p-0">
          <table className="w-full text-left text-sm">
            <thead>
              <tr className="bg-zinc-900/50 border-b border-zinc-800 text-zinc-400 uppercase text-[10px] font-bold">
                <th className="px-6 py-4">Nomor Invoice</th>
                <th className="px-6 py-4">Pelanggan</th>
                <th className="px-6 py-4">Periode</th>
                <th className="px-6 py-4">Total</th>
                <th className="px-6 py-4">Status</th>
                <th className="px-6 py-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-zinc-800/50">
              {invoices?.data?.map((invoice: any) => (
                <tr key={invoice.id} className="hover:bg-zinc-800/20 transition-colors group">
                  <td className="px-6 py-4 font-mono text-xs">{invoice.invoice_number}</td>
                  <td className="px-6 py-4">
                    <div className="font-medium text-zinc-200">{invoice.customer?.name}</div>
                    <div className="text-[10px] text-zinc-500 uppercase">{invoice.customer?.package?.name}</div>
                  </td>
                  <td className="px-6 py-4 font-medium">{format(new Date(invoice.period_start), 'MMM yyyy')}</td>
                  <td className="px-6 py-4 font-bold text-white">Rp {parseFloat(invoice.total_after_tax).toLocaleString()}</td>
                  <td className="px-6 py-4">
                    <Badge variant={invoice.status === 'paid' ? 'default' : 'outline'} className={
                      invoice.status === 'paid' 
                        ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 shadow-[0_0_15px_-5px_rgba(16,185,129,0.3)]' 
                        : 'bg-amber-500/10 text-amber-400 border-amber-500/20'
                    }>
                      {invoice.status === 'unpaid' ? 'BELUM BAYAR' : invoice.status.toUpperCase()}
                    </Badge>
                  </td>
                  <td className="px-6 py-4 text-right">
                    <div className="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      {invoice.status === 'unpaid' && (
                        <Button 
                          size="sm" 
                          variant="secondary" 
                          className="h-8 bg-blue-600 hover:bg-blue-700 text-white border-none"
                          onClick={async () => {
                            try {
                              const response = await getSnapToken.mutateAsync(invoice.id);
                              // @ts-ignore
                              window.snap.pay(response.snap_token, {
                                onSuccess: () => {
                                  toast.success("Pembayaran berhasil!");
                                },
                                onPending: () => toast.info("Menunggu pembayaran..."),
                                onError: () => toast.error("Pembayaran gagal!")
                              });
                            } catch (e) {
                              toast.error("Gagal mendapatkan kode pembayaran");
                            }
                          }}
                        >
                          <Zap className="w-4 h-4 mr-2" />
                          Bayar
                        </Button>
                      )}
                      <Button size="icon" variant="ghost" className="h-8 w-8 text-zinc-400 hover:text-white">
                        <Download className="w-4 h-4" />
                      </Button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          {(!invoices?.data || invoices.data.length === 0) && (
            <div className="py-20 text-center text-zinc-500 italic">Belum ada invoice untuk periode ini.</div>
          )}
        </div>
      </Card>
    </div>
  );
}

function StatCard({ title, value, icon }: { title: string, value: any, icon: React.ReactNode }) {
  return (
    <Card className="bg-zinc-900/40 border-zinc-800 p-4 flex items-center gap-4">
      <div className="h-10 w-10 rounded-xl bg-zinc-950 flex items-center justify-center border border-zinc-800 shadow-inner">
        {icon}
      </div>
      <div>
        <p className="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">{title}</p>
        <p className="text-lg font-bold text-white leading-none mt-1">{value}</p>
      </div>
    </Card>
  );
}
