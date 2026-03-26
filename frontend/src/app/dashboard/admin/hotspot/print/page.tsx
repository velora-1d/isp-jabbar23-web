"use client";

import { useSearchParams } from "next/navigation";
import { useVouchers } from "@/hooks/use-hotspot";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Printer, ArrowLeft } from "lucide-react";
import Link from "next/link";
import { useEffect } from "react";

export default function VoucherPrintPage() {
  const searchParams = useSearchParams();
  const ids = searchParams.get("ids")?.split(",") || [];
  const { data: vouchersData, isLoading } = useVouchers({ ids });

  useEffect(() => {
    if (!isLoading && vouchersData?.vouchers.data.length) {
       // Auto trigger print if needed? Maybe better manual
    }
  }, [isLoading, vouchersData]);

  if (isLoading) return <div className="p-10 text-center">Loading vouchers for print...</div>;

  return (
    <div className="min-h-screen bg-slate-50 dark:bg-slate-950 p-4 md:p-8">
      <div className="max-w-4xl mx-auto no-print mb-8 flex items-center justify-between bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border">
        <div className="flex items-center gap-4">
            <Button variant="ghost" size="icon" asChild>
                <Link href="/dashboard/admin/hotspot">
                    <ArrowLeft className="w-5 h-5" />
                </Link>
            </Button>
            <div>
                <h1 className="text-lg font-bold tracking-tight">Voucher Print Preview</h1>
                <p className="text-xs text-muted-foreground">Total {vouchersData?.vouchers.data.length} voucher siap cetak.</p>
            </div>
        </div>
        <Button onClick={() => window.print()} className="bg-emerald-600 hover:bg-emerald-700 shadow-lg">
          <Printer className="w-4 h-4 mr-2" />
          Cetak Sekarang
        </Button>
      </div>

      <div className="print-grid max-w-[210mm] mx-auto bg-white p-[10mm] shadow-lg print:shadow-none print:p-0">
        <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
          {vouchersData?.vouchers.data.map((v: any) => (
            <div key={v.id} className="border-2 border-dashed border-slate-200 p-4 rounded-lg relative overflow-hidden h-[45mm] flex flex-col justify-between print:border-slate-800">
               <div className="flex items-center justify-between border-b pb-2 mb-2">
                 <span className="text-[10px] font-black italic text-slate-400">JABBAR23<span className="text-blue-500">HOTSPOT</span></span>
                 <span className="text-[9px] font-bold text-slate-500 uppercase">{v.profile.display_name}</span>
               </div>
               
               <div className="bg-slate-100 print:bg-slate-50 border border-slate-200 py-2 rounded text-center my-1">
                 <span className="font-mono text-xl font-black tracking-[3px] text-slate-800">{v.code}</span>
               </div>

               <div className="flex items-center justify-between text-[8px] text-slate-400 mt-2 font-medium">
                  <div className="flex flex-col">
                    <span>Connect: <strong>@JABBAR23_FREE</strong></span>
                    <span>Validity: <strong>{v.profile.validity_hours} Hours</strong></span>
                  </div>
                  <div className="text-right">
                    <span className="text-[12px] font-black text-slate-900">Rp {v.profile.price.toLocaleString('id-ID')}</span>
                  </div>
               </div>
            </div>
          ))}
        </div>
      </div>

      <style jsx global>{`
        @media print {
          .no-print { display: none !important; }
          body { background: white !important; padding: 0 !important; }
          .print-grid { 
            box-shadow: none !important; 
            width: 100% !important; 
            max-width: none !important;
            padding: 0 !important;
          }
          @page {
            size: A4;
            margin: 10mm;
          }
        }
      `}</style>
    </div>
  );
}
