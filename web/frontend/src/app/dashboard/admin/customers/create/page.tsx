'use client';

import React from 'react';
import { useRouter } from 'next/navigation';
import { CustomerForm } from '@/components/customers/customer-form';
import { useCreateCustomer } from '@/hooks/use-customers';
import { toast } from 'sonner';
import { UserPlus, Zap, ShieldCheck, Globe, ArrowLeft } from 'lucide-react';
import Link from 'next/link';
import { Badge } from '@/components/ui/badge';

export default function CreateCustomerPage() {
  const router = useRouter();
  const createCustomer = useCreateCustomer();

  const handleSubmit = async (data: any) => {
    // Normalisasi data untuk API
    // Kita kirimkan semua data dari form langsung karena sudah divalidasi Zod
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
    // Port dikirim sebagai string sesuai migration terbaru
    payload.odp_port = String(payload.odp_port);

    try {
      await createCustomer.mutateAsync(payload);
      toast.success('Pelanggan berhasil didaftarkan!');
      router.push('/dashboard/admin/customers');
    } catch (error: any) {
      console.error('Registration error:', error);
      // Toast error detail ditangani di CustomerForm atau global interceptor
    }
  };

  return (
    <div className="min-h-screen bg-[#020617] text-slate-200">
      {/* ── Background Elements ────────────────────────────────────── */}
      <div className="fixed inset-0 overflow-hidden pointer-events-none">
        <div className="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-emerald-500/10 blur-[120px] rounded-full animate-pulse" />
        <div className="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-500/10 blur-[120px] rounded-full" />
      </div>

      <div className="relative z-10 max-w-[1600px] mx-auto p-4 md:p-6">
        
        {/* ── Top Navigation ────────────────────────────────────────── */}
        <div className="flex items-center justify-between mb-6">
          <Link 
            href="/dashboard/admin/customers" 
            className="group flex items-center gap-2 text-slate-400 hover:text-white transition-colors"
          >
            <div className="p-2 bg-slate-900 border border-slate-800 rounded-xl group-hover:border-slate-700 transition-all">
              <ArrowLeft className="w-4 h-4" />
            </div>
            <span className="text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-slate-300">Kembali</span>
          </Link>

          <div className="flex items-center gap-2">
            <Badge className="bg-emerald-500/10 text-emerald-400 border-none px-3 py-1 text-[10px] font-black tracking-widest uppercase">
              REGISTRATION UNIT
            </Badge>
          </div>
        </div>

        {/* ── Page Header ────────────────────────────────────────────── */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
          <div className="lg:col-span-7 space-y-2">
            <div className="flex items-center gap-2 text-emerald-500 font-bold tracking-widest text-[10px] uppercase">
              <Zap className="w-3 h-3 animate-pulse" />
              <span>Sistem Aktivasi Jaringan</span>
            </div>
            <h1 className="text-3xl md:text-4xl font-black tracking-tight text-white leading-tight">
              Registrasi <span className="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-blue-500 font-black">Data Lengkap</span>
            </h1>
            <p className="text-sm text-slate-400 font-medium max-w-xl">
              Seluruh field wajib diisi untuk validasi infrastruktur (RADIUS/GPON).
            </p>
          </div>

          <div className="lg:col-span-5 grid grid-cols-2 gap-4">
            <div className="p-5 bg-slate-900/40 border border-slate-800 backdrop-blur-xl rounded-2xl space-y-2">
              <ShieldCheck className="w-6 h-6 text-emerald-400" />
              <div>
                <p className="text-[10px] font-black text-white uppercase tracking-widest">Full Validation</p>
                <p className="text-[9px] text-slate-500 font-bold">Field Wajib</p>
              </div>
            </div>
            <div className="p-5 bg-slate-900/40 border border-slate-800 backdrop-blur-xl rounded-2xl space-y-2">
              <Globe className="w-6 h-6 text-blue-400" />
              <div>
                <p className="text-[10px] font-black text-white uppercase tracking-widest">Auto Sync</p>
                <p className="text-[9px] text-slate-500 font-bold">OLT & ODP Integration</p>
              </div>
            </div>
          </div>
        </div>

        {/* ── Form Section ──────────────────────────────────────────── */}
        <div className="relative">
          <CustomerForm 
            onSubmit={handleSubmit} 
            isLoading={createCustomer.isPending} 
          />
        </div>
      </div>
    </div>
  );
}
