'use client';

import React from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useRouter } from 'next/navigation';
import { 
  Form, 
  FormControl, 
  FormField, 
  FormItem, 
  FormLabel, 
  FormMessage
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from '@/components/ui/select';
import { Card, CardContent } from '@/components/ui/card';
import { 
  User, 
  MapPin, 
  Activity, 
  Network, 
  Loader2,
  CheckCircle2,
  Calendar,
  Wallet,
  Cpu,
  BadgeInfo,
  ShieldCheck,
  PackageCheck,
  Globe,
  Settings,
  HardDrive,
  Users
} from 'lucide-react';
import { useCustomerFormData } from '@/hooks/use-customers';
import { toast } from 'sonner';
import { cn } from '@/lib/utils';
import { Separator } from '@/components/ui/separator';

const customerSchema = z.object({
  // Identitas
  name: z.string().min(1, 'Nama wajib diisi'),
  ktp_number: z.string().min(16, 'KTP wajib 16 digit').max(20),
  phone: z.string().min(10, 'No HP wajib diisi'),
  email: z.string().email('Email tidak valid').min(1, 'Email wajib diisi'),
  
  // Alamat
  address: z.string().min(1, 'Alamat wajib diisi'),
  rt_rw: z.string().min(1, 'RT/RW wajib diisi'),
  kelurahan: z.string().min(1, 'Kelurahan wajib diisi'),
  kecamatan: z.string().min(1, 'Kecamatan wajib diisi'),
  kabupaten: z.string().min(1, 'Kabupaten wajib diisi'),
  provinsi: z.string().min(1, 'Provinsi wajib diisi'),
  kode_pos: z.string().min(1, 'Kode Pos wajib diisi'),
  
  // GPS
  latitude: z.coerce.number().min(-90).max(90),
  longitude: z.coerce.number().min(-180).max(180),
  
  // Paket & Detail Pemasangan
  package_id: z.string().min(1, 'Pilih paket'),
  installation_date: z.string().min(1, 'Tgl Instalasi wajib diisi'),
  billing_date: z.string().min(1, 'Tgl Tagihan wajib diisi'),
  
  // Teknis Jaringan (OLT/ODP)
  olt_id: z.string().min(1, 'Pilih OLT'),
  odp_port: z.string().min(1, 'Port ODP wajib diisi'),
  onu_index: z.string().min(1, 'ONU Index wajib diisi'),
  
  // Mikrotik
  router_id: z.string().min(1, 'Pilih Router'),
  pppoe_profile: z.string().min(1, 'Pilih Profile PPPoE'),
  pppoe_username: z.string().min(1, 'PPPoE Username wajib diisi'),
  pppoe_password: z.string().min(1, 'PPPoE Password wajib diisi'),
  mikrotik_ip: z.string().min(7, 'IP MikroTik wajib diisi'),
  
  // Manajemen
  partner_id: z.string().min(1, 'Pilih Partner'),
  assigned_to: z.string().min(1, 'Pilih Teknisi'),
  team_size: z.coerce.number().min(1, 'Jumlah tim wajib diisi'),
  status: z.string().min(1, 'Pilih status'),
  notes: z.string().min(1, 'Catatan wajib diisi'),
});

type CustomerFormValues = z.infer<typeof customerSchema>;

interface CustomerFormProps {
  initialData?: any;
  onSubmit: (data: CustomerFormValues) => Promise<void>;
  isLoading?: boolean;
}

export function CustomerForm({ initialData, onSubmit, isLoading }: CustomerFormProps) {
  const { data: formData, isLoading: isLoadingOptions } = useCustomerFormData();
  
  const form = useForm<CustomerFormValues>({
    resolver: zodResolver(customerSchema),
    defaultValues: initialData || {
      name: '',
      ktp_number: '',
      phone: '',
      email: '',
      address: '',
      rt_rw: '',
      kelurahan: '',
      kecamatan: '',
      kabupaten: '',
      provinsi: 'Jawa Barat',
      kode_pos: '',
      latitude: -6.1,
      longitude: 106.8,
      package_id: '',
      installation_date: new Date().toISOString().split('T')[0],
      billing_date: new Date().toISOString().split('T')[0],
      olt_id: '',
      odp_port: '1',
      onu_index: '1/1/1',
      router_id: '',
      pppoe_profile: 'default',
      pppoe_username: '',
      pppoe_password: '123',
      mikrotik_ip: '10.88.x.x',
      partner_id: '',
      assigned_to: '',
      team_size: 1,
      status: 'registered',
      notes: '-',
    },
  });

  const selectedPackageId = useWatch({ control: form.control, name: 'package_id' });
  const selectedPackage = formData?.packages.find(p => p.id.toString() === selectedPackageId);

  const handleSubmit = async (values: CustomerFormValues) => {
    try {
      await onSubmit(values);
    } catch (error: any) {
      console.error(error);
      toast.error(error.response?.data?.message || 'Gagal menyimpan data.');
    }
  };

  if (isLoadingOptions) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[400px] space-y-6 text-emerald-500">
        <Loader2 className="w-12 h-12 animate-spin text-emerald-500" />
        <p className="text-xs font-bold uppercase tracking-widest animate-pulse">Syncing Database Structure...</p>
      </div>
    );
  }

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(handleSubmit)} className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {/* ── Left Column: Form Sections ─────────────────────────── */}
        <div className="lg:col-span-8 space-y-6">
          
          {/* Section 1: Customer Identity */}
          <SectionCard title="Identitas Pelanggan" subtitle="Data Kependudukan (Wajib)" icon={User} accentColor="emerald">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormFieldInput name="name" label="Nama Lengkap" placeholder="Masukkan nama" control={form.control} />
              <FormFieldInput name="ktp_number" label="Nomor KTP" placeholder="317xxxxxxxxxxxxx" control={form.control} />
              <FormFieldInput name="phone" label="No WhatsApp" placeholder="0812xxxx" control={form.control} />
              <FormFieldInput name="email" label="Email" placeholder="user@jabbar.com" control={form.control} />
            </div>
          </SectionCard>

          {/* Section 2: Address Detail */}
          <SectionCard title="Alamat Pemasangan" subtitle="Lokasi Fisik Jaringan" icon={MapPin} accentColor="blue">
            <div className="space-y-6">
              <FormFieldTextarea name="address" label="Alamat / Blok / No" placeholder="Jl. Raya Keadilan No. 23..." control={form.control} />
              <div className="grid grid-cols-2 md:grid-cols-3 gap-6">
                <FormFieldInput name="rt_rw" label="RT / RW" placeholder="001/001" control={form.control} />
                <FormFieldInput name="kelurahan" label="Kelurahan" placeholder="Kelurahan" control={form.control} />
                <FormFieldInput name="kecamatan" label="Kecamatan" placeholder="Kecamatan" control={form.control} />
                <FormFieldInput name="kabupaten" label="Kabupaten / Kota" placeholder="Kota" control={form.control} />
                <FormFieldInput name="provinsi" label="Provinsi" placeholder="Provinsi" control={form.control} />
                <FormFieldInput name="kode_pos" label="Kode Pos" placeholder="12345" control={form.control} />
              </div>
            </div>
          </SectionCard>

          {/* Section 3: GPS Coordinates */}
          <SectionCard title="Titik Lokasi (GPS)" subtitle="Koordinat Geospasial" icon={Globe} accentColor="indigo">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormFieldInput name="latitude" label="Latitude" placeholder="-6.123456" control={form.control} type="number" />
              <FormFieldInput name="longitude" label="Longitude" placeholder="106.123456" control={form.control} type="number" />
            </div>
          </SectionCard>

          {/* Section 4: Infrastructure - OLT/ODP */}
          <SectionCard title="Infrastruktur GPON" subtitle="Distribusi Jaringan" icon={Cpu} accentColor="amber">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div className="md:col-span-1">
                <FormFieldSelect 
                  name="olt_id" 
                  label="OLT Tujuan" 
                  options={formData?.olts.map(o => ({ id: o.id.toString(), name: o.name })) || []} 
                  control={form.control} 
                />
              </div>
              <FormFieldInput name="odp_port" label="Port ODP" placeholder="1" control={form.control} />
              <FormFieldInput name="onu_index" label="ONU Index" placeholder="1/1/1" control={form.control} />
            </div>
          </SectionCard>

          {/* Section 5: Mikrotik Config */}
          <SectionCard title="Konfigurasi MikroTik" subtitle="Akses PPPoE & IP Management" icon={Activity} accentColor="rose">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormFieldSelect 
                name="router_id" 
                label="Gateway Router" 
                options={formData?.routers.map(r => ({ id: r.id.toString(), name: r.name })) || []} 
                control={form.control} 
              />
              <FormFieldSelect 
                name="pppoe_profile" 
                label="Profile PPPoE" 
                options={formData?.pppoe_profiles || []} 
                control={form.control} 
              />
              <FormFieldInput name="pppoe_username" label="PPPoE User / Secret" placeholder="user.jabbar" control={form.control} />
              <FormFieldInput name="pppoe_password" label="PPPoE Pass" placeholder="***" control={form.control} />
              <div className="md:col-span-2">
                <FormFieldInput name="mikrotik_ip" label="Management IP (Static / Remote)" placeholder="10.88.x.x" control={form.control} />
              </div>
            </div>
          </SectionCard>

          {/* Section 6: Management */}
          <SectionCard title="Administrasi & PIC" subtitle="Delegasi Tugas" icon={Settings} accentColor="slate">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormFieldSelect 
                name="partner_id" 
                label="Partner / Agen" 
                options={formData?.partners.map(p => ({ id: p.id.toString(), name: p.name })) || []} 
                control={form.control} 
              />
              <FormFieldSelect 
                name="assigned_to" 
                label="Teknisi" 
                options={formData?.technicians.map(t => ({ id: t.id.toString(), name: t.name })) || []} 
                control={form.control} 
              />
              <FormFieldInput name="team_size" label="Jumlah Tim" placeholder="1" control={form.control} type="number" />
              <FormFieldSelect 
                name="status" 
                label="Status" 
                options={Object.entries(formData?.statuses || {}).map(([id, name]) => ({ id, name }))} 
                control={form.control} 
              />
              <div className="md:col-span-2">
                <FormFieldTextarea name="notes" label="Catatan" placeholder="Instruksi khusus..." control={form.control} />
              </div>
            </div>
          </SectionCard>

        </div>

        {/* ── Right Column: Registry Summary ─────────────────────── */}
        <div className="lg:col-span-4 space-y-6">
          <Card className="bg-slate-900 border-emerald-500/20 backdrop-blur-3xl sticky top-24 overflow-hidden rounded-3xl border-t-8 border-t-emerald-500">
            <CardContent className="p-8 space-y-8">
              <div className="flex items-center gap-3">
                <PackageCheck className="w-6 h-6 text-emerald-500" />
                <h3 className="text-xl font-black text-white uppercase tracking-tight">Paket & Billing</h3>
              </div>

              <div className="space-y-6">
                <FormFieldSelect 
                  name="package_id" 
                  label="Layanan Internet" 
                  options={formData?.packages.map(p => ({ id: p.id.toString(), name: `${p.name} - Rp${p.price.toLocaleString()}` })) || []} 
                  control={form.control} 
                />
                
                <FormFieldInput name="installation_date" label="Tgl Pemasangan" control={form.control} type="date" />
                <FormFieldInput name="billing_date" label="Tgl Tagihan" control={form.control} type="date" />
              </div>

              <Separator className="bg-slate-800" />

              <div className="space-y-3">
                <div className="flex justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                  <span>Biaya Bulanan:</span>
                  <span className="text-white">Rp {(selectedPackage?.price || 0).toLocaleString()}</span>
                </div>
                <div className="flex justify-between text-base font-black text-emerald-400">
                  <span className="uppercase tracking-tight">Total Tagihan:</span>
                  <span>Rp {(selectedPackage?.price || 0).toLocaleString()}</span>
                </div>
              </div>

              <Button 
                type="submit" 
                disabled={isLoading}
                className="w-full h-14 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/20 transition-all disabled:opacity-50"
              >
                {isLoading ? <Loader2 className="w-5 h-5 animate-spin" /> : 'Kirim Registrasi'}
              </Button>

              <p className="text-[9px] text-center text-slate-500 font-medium leading-relaxed">
                * Dengan mengirim form ini, data akan langsung terintegrasi dengan sistem RADIUS dan monitoring jaringan PT Fakta Jabbar Industri.
              </p>
            </CardContent>
          </Card>
        </div>
      </form>
    </Form>
  );
}

// ── Shared Internal Components ─────────────────────────────────────

function SectionCard({ title, subtitle, icon: Icon, accentColor, children }: any) {
  const accentClasses: any = {
    emerald: "bg-emerald-500/10 border-emerald-500/20 text-emerald-400",
    blue: "bg-blue-500/10 border-blue-500/20 text-blue-400",
    amber: "bg-amber-500/10 border-amber-500/20 text-amber-400",
    indigo: "bg-indigo-500/10 border-indigo-500/20 text-indigo-400",
    rose: "bg-rose-500/10 border-rose-500/20 text-rose-400",
    slate: "bg-slate-500/10 border-slate-500/20 text-slate-400",
  };

  return (
    <Card className="bg-slate-900/40 border-slate-800 backdrop-blur-xl rounded-3xl overflow-hidden shadow-2xl transition-all hover:bg-slate-900/60">
      <div className="p-5 border-b border-slate-800/50 flex items-center gap-4">
        <div className={cn("p-3 rounded-xl border shrink-0", accentClasses[accentColor])}>
          <Icon className="w-5 h-5" />
        </div>
        <div>
          <h3 className="text-lg font-black text-white tracking-tight leading-none mb-1">{title}</h3>
          <p className="text-[9px] text-slate-500 font-bold uppercase tracking-[0.2em]">{subtitle}</p>
        </div>
      </div>
      <CardContent className="p-6">{children}</CardContent>
    </Card>
  );
}

function FormFieldInput({ name, label, control, ...props }: any) {
  return (
    <FormField
      control={control}
      name={name}
      render={({ field }) => (
        <FormItem>
          <FormLabel className="text-[10px] font-black uppercase tracking-widest text-slate-500">{label}</FormLabel>
          <FormControl>
            <Input 
              {...field} 
              {...props} 
              value={field.value ?? ''}
              className={cn(
                "bg-slate-950/40 border-slate-800 h-12 rounded-xl text-white px-4 focus:ring-emerald-500/20 placeholder:text-slate-700 text-sm",
                props.className
              )} 
            />
          </FormControl>
          <FormMessage className="text-[10px] font-bold uppercase text-rose-500" />
        </FormItem>
      )}
    />
  );
}

function FormFieldTextarea({ name, label, control, ...props }: any) {
  return (
    <FormField
      control={control}
      name={name}
      render={({ field }) => (
        <FormItem>
          <FormLabel className="text-[10px] font-black uppercase tracking-widest text-slate-500">{label}</FormLabel>
          <FormControl>
            <Textarea 
              {...field} 
              {...props} 
              value={field.value ?? ''}
              className="bg-slate-950/40 border-slate-800 min-h-[100px] rounded-2xl p-5 text-white placeholder:text-slate-700" 
            />
          </FormControl>
          <FormMessage className="text-[10px] font-bold uppercase text-rose-500" />
        </FormItem>
      )}
    />
  );
}

function FormFieldSelect({ name, label, options, control }: any) {
  return (
    <FormField
      control={control}
      name={name}
      render={({ field }) => (
        <FormItem>
          <FormLabel className="text-[10px] font-black uppercase tracking-widest text-slate-500">{label}</FormLabel>
          <Select onValueChange={field.onChange} value={field.value?.toString()}>
            <FormControl>
              <SelectTrigger className="bg-slate-950/40 border-slate-800 h-12 rounded-xl px-4 text-white text-sm">
                <SelectValue placeholder={`Pilih ${label}`} />
              </SelectTrigger>
            </FormControl>
            <SelectContent className="bg-slate-900 border-slate-800 text-slate-200 rounded-xl">
              {options.map((opt: any) => (
                <SelectItem key={opt.id} value={opt.id.toString()} className="focus:bg-emerald-500/10 focus:text-emerald-400 text-xs">
                  {opt.name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
          <FormMessage className="text-[10px] font-bold uppercase text-rose-500" />
        </FormItem>
      )}
    />
  );
}
