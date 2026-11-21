'use client';

import React, { use } from 'react';
import { useRouter } from 'next/navigation';
import { CustomerForm } from '@/components/customers/customer-form';
import { useCustomer, useUpdateCustomer } from '@/hooks/use-customers';
import { toast } from 'sonner';
import { ChevronLeft, Loader2, User, ShieldCheck, Zap } from 'lucide-react';
import { Button } from '@/components/ui/button';
import Link from 'next/link';
import { Badge } from '@/components/ui/badge';

export default function EditCustomerPage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = use(params);
  const router = useRouter();
  const { data: customer, isLoading } = useCustomer(id);
  const updateCustomer = useUpdateCustomer();

  const handleSubmit = async (data: any) => {
    // Normalisasi data untuk API sesuai 33-column list
    const payload = { ...data };
    
    // Pastikan tipe data numerik sesuai
    payload.package_id = Number(payload.package_id);
    payload.olt_id = Number(payload.olt_id);
    payload.router_id = Number(payload.router_id);
    payload.partner_id = Number(payload.partner_id);
    payload.assigned_to = Number(payload.assigned_to);
    payload.team_size = Number(payload.team_size);
    payload.latitude = Number(payload.latitude);
    payload.longitude = Number(payload.longitude);
    payload.odp_port = String(payload.odp_port);

    try {
      await updateCustomer.mutateAsync({ id, data: payload });
      toast.success('Data pelanggan berhasil diperbarui!');
      router.push(`/dashboard/admin/customers/${id}`);
    } catch (error: any) {
      console.error('Update error:', error);
    }
  };

  if (isLoading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-screen bg-[#020617] space-y-6">
        <Loader2 className="w-12 h-12 animate-spin text-emerald-500" />
        <p className="text-xs font-black uppercase tracking-widest text-slate-500 animate-pulse">Memuat Data Jaringan...</p>
      </div>
    );
  }

  if (!customer) {
    return (
      <div className="min-h-screen bg-[#020617] flex items-center justify-center">
        <div className="text-center space-y-4">
          <h2 className="text-2xl font-black text-white uppercase tracking-tighter">ERROR 404</h2>
          <p className="text-slate-500 text-sm">Pelanggan tidak ditemukan di database.</p>
          <Button variant="outline" onClick={() => router.back()} className="border-slate-800 text-slate-400">Kembali</Button>
        </div>
      </div>
    );
  }

  // Pre-process data for the form (All fields from 33-column structure)
  const initialData = {
    ...customer,
    package_id: customer.package_id?.toString() || '',
    partner_id: customer.partner_id?.toString() || '',
    assigned_to: customer.assigned_to?.toString() || '',
    router_id: customer.router_id?.toString() || '',
    olt_id: customer.olt_id?.toString() || '',
    // Format dates (YYYY-MM-DD)
    installation_date: customer.installation_date ? customer.installation_date.split('T')[0] : '',
    billing_date: customer.billing_date ? customer.billing_date.split('T')[0] : '',
  };

  return (
    <div className="min-h-screen bg-[#020617] text-slate-200">
      {/* ── Visual Backdrop ────────────────────────────────────────── */}
      <div className="fixed inset-0 pointer-events-none overflow-hidden">
        <div className="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-500/5 blur-[120px] rounded-full" />
        <div className="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] bg-emerald-500/5 blur-[120px] rounded-full" />
      </div>

      <div className="relative z-10 max-w-[1600px] mx-auto p-4 md:p-6">
        
        {/* ── Tool Header ───────────────────────────────────────────── */}
        <div className="flex items-center justify-between mb-8">
          <button 
            onClick={() => router.back()}
            className="group flex items-center gap-3 text-slate-400 hover:text-white transition-all overflow-hidden"
          >
            <div className="p-2.5 bg-slate-900/50 border border-slate-800 rounded-xl group-hover:scale-110 transition-transform">
              <ChevronLeft className="w-4 h-4 text-emerald-500" />
            </div>
            <div className="text-left">
              <p className="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500">Back</p>
              <p className="text-xs font-bold">{customer.customer_id}</p>
            </div>
          </button>

          <div className="flex gap-2">
            <Badge className="bg-emerald-500/10 text-emerald-400 border-none px-3 py-1 text-[10px] font-black tracking-widest uppercase rounded-lg">
              EDIT UNIT
            </Badge>
          </div>
        </div>

        {/* ── Page Intro ────────────────────────────────────────────── */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-10">
          <div className="lg:col-span-8 space-y-2">
            <div className="flex items-center gap-2 text-blue-400 font-bold tracking-widest text-[10px] uppercase">
              <ShieldCheck className="w-3 h-3" />
              <span>Network Parameter Sync</span>
            </div>
            <h1 className="text-3xl md:text-5xl font-black tracking-tight text-white leading-tight">
              Update <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">Parameter Jaringan</span>
            </h1>
            <p className="text-sm text-slate-400 font-medium max-w-xl">
              Edit data pelanggan <span className="text-white font-bold">{customer.name}</span>. 
              Pastikan koordinat GPS dan PPPoE akurat.
            </p>
          </div>
        </div>

        {/* ── Form Container ────────────────────────────────────────── */}
        <div className="relative">
          <CustomerForm 
            initialData={initialData}
            onSubmit={handleSubmit} 
            isLoading={updateCustomer.isPending} 
          />
        </div>
      </div>
    </div>
  );
}
