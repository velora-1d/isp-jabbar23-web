'use client';

import { use, useState } from 'react';
import { useCustomer, useUpdateCustomerStatus, useCustomers, useSyncCustomerToMikrotik } from '@/hooks/use-customers';
import { 
    Card, 
    CardContent
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { 
    ChevronLeft, 
    User, 
    MapPin, 
    Phone, 
    Mail, 
    CreditCard, 
    Activity, 
    Router as RouterIcon, 
    Globe,
    Edit2,
    Loader2,
    Clock,
    Server,
    Zap,
    ShieldCheck,
    RefreshCw,
    Ban,
    MessageSquare,
    ClipboardList
} from 'lucide-react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { cn } from '@/lib/utils';
import { format } from 'date-fns';

export default function CustomerDetailPage({ params }: { params: Promise<{ id: string }> }) {
    const { id } = use(params);
    const router = useRouter();
    const { data: customer, isLoading, error } = useCustomer(id);
    const syncMikrotikMutation = useSyncCustomerToMikrotik();
    const updateStatusMutation = useUpdateCustomerStatus();

    if (isLoading) {
        return (
            <div className="flex flex-col items-center justify-center min-h-[400px] space-y-4">
                <Loader2 className="w-8 h-8 animate-spin text-emerald-500" />
                <p className="text-slate-400 animate-pulse text-sm">Memuat data pelanggan...</p>
            </div>
        );
    }

    if (error || !customer) {
        return (
            <div className="flex flex-col items-center justify-center min-h-[60vh] gap-4">
                <p className="text-slate-500">Data pelanggan tidak ditemukan.</p>
                <Button variant="outline" onClick={() => router.back()}>Kembali</Button>
            </div>
        );
    }

    const handleSyncMikrotik = () => {
        syncMikrotikMutation.mutate(customer.id, {
            onSuccess: () => toast.success('Sinkronisasi MikroTik berhasil'),
            onError: (err: any) => toast.error(err?.response?.data?.message || 'Gagal sinkronisasi')
        });
    };

    const handleSuspend = () => {
        const isCurrentlyActive = customer.status === 'active';
        updateStatusMutation.mutate({
            id: customer.id,
            status: isCurrentlyActive ? 'suspended' : 'active',
            notes: isCurrentlyActive ? 'Ditangguhkan secara manual oleh Admin' : 'Diaktifkan kembali oleh Admin'
        }, {
            onSuccess: () => toast.success(`Pelanggan berhasil ${isCurrentlyActive ? 'ditangguhkan' : 'diaktifkan'}`),
            onError: () => toast.error('Gagal memperbarui status')
        });
    };

    return (
        <div className="space-y-6">
            {/* Header / Breadcrumb */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div className="space-y-1">
                    <div className="flex items-center text-xs text-slate-500 space-x-2">
                        <Link href="/dashboard/admin/customers" className="hover:text-emerald-500 transition-colors">Pelanggan</Link>
                        <ChevronLeft className="w-3 h-3 rotate-180" />
                        <span className="text-slate-300">Detail Pelanggan</span>
                    </div>
                    <h1 className="text-2xl font-bold text-white">Detail Pelanggan: {customer.name}</h1>
                </div>
                <div className="flex items-center gap-3">
                    <Button 
                        variant="outline" 
                        onClick={handleSyncMikrotik}
                        disabled={syncMikrotikMutation.isPending}
                        className="bg-slate-900/50 border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 rounded-xl gap-2"
                    >
                        {syncMikrotikMutation.isPending ? <Loader2 className="w-4 h-4 animate-spin" /> : <RefreshCw className="w-4 h-4" />}
                        Sync MikroTik
                    </Button>
                    <Button 
                        variant="outline" 
                        onClick={handleSuspend}
                        disabled={updateStatusMutation.isPending}
                        className={cn(
                            "rounded-xl gap-2 border-slate-800",
                            customer.status === 'active' ? "text-red-400 hover:bg-red-500/10 hover:text-red-300" : "text-emerald-400 hover:bg-emerald-500/10 hover:text-emerald-300"
                        )}
                    >
                        {customer.status === 'active' ? <Ban className="w-4 h-4" /> : <ShieldCheck className="w-4 h-4" />}
                        {customer.status === 'active' ? 'Suspend Customer' : 'Activate Customer'}
                    </Button>
                    <Link href={`/dashboard/admin/customers/${customer.id}/edit`}>
                        <Button className="bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl gap-2 font-medium px-6">
                            <Edit2 className="w-4 h-4" />
                            Edit Profil
                        </Button>
                    </Link>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {/* Left Sidebar: Vertical Tabs (2 columns) */}
                <div className="lg:col-span-2">
                    <div className="bg-slate-900/40 border border-slate-800 rounded-2xl p-2 space-y-1 backdrop-blur-xl">
                        <button className="w-full flex items-center space-x-3 px-4 py-3 bg-emerald-500/10 text-emerald-400 rounded-xl font-medium text-sm transition-all">
                            <User className="w-4 h-4" />
                            <span>Informasi</span>
                        </button>
                        <button className="w-full flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 rounded-xl font-medium text-sm transition-all">
                            <CreditCard className="w-4 h-4" />
                            <span>Tagihan</span>
                        </button>
                        <button className="w-full flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 rounded-xl font-medium text-sm transition-all">
                            <Activity className="w-4 h-4" />
                            <span>Status OLT</span>
                        </button>
                        <button className="w-full flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 rounded-xl font-medium text-sm transition-all">
                            <ClipboardList className="w-4 h-4" />
                            <span>Aktivitas</span>
                        </button>
                        <button className="w-full flex items-center space-x-3 px-4 py-3 text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 rounded-xl font-medium text-sm transition-all">
                            <MessageSquare className="w-4 h-4" />
                            <span>Komplain</span>
                        </button>
                    </div>
                </div>

                {/* Center Content: Profile Data (6 columns) */}
                <div className="lg:col-span-6 space-y-6">
                    <Card className="bg-slate-900/40 border-slate-800 backdrop-blur-xl shadow-2xl overflow-hidden rounded-2xl group">
                        <div className="h-2 bg-gradient-to-r from-emerald-500 to-teal-500" />
                        <CardContent className="p-8">
                            <div className="flex flex-col md:flex-row items-center md:items-start gap-8">
                                <div className="relative">
                                    <div className="w-32 h-32 rounded-3xl bg-emerald-500 flex items-center justify-center text-4xl font-black text-white shadow-2xl shadow-emerald-500/30 group-hover:scale-105 transition-transform duration-500">
                                        {customer.name.substring(0, 1).toUpperCase()}
                                    </div>
                                    <div className="absolute -bottom-2 -right-2 bg-slate-900 p-1.5 rounded-xl border border-slate-700">
                                        <Badge className={cn(
                                            "rounded-lg px-2 text-[10px] uppercase font-bold",
                                            customer.status === 'active' ? "bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/20" : "bg-red-500/20 text-red-400 hover:bg-red-500/20"
                                        )}>
                                            {customer.status_label}
                                        </Badge>
                                    </div>
                                </div>
                                <div className="flex-1 space-y-6 text-center md:text-left">
                                    <div>
                                        <h2 className="text-3xl font-bold text-white">{customer.name}</h2>
                                        <p className="text-emerald-400 font-mono text-sm tracking-wider mt-1">{customer.customer_id}</p>
                                    </div>
                                    
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div className="flex items-center space-x-3 text-slate-300">
                                            <div className="p-2 bg-slate-800/50 rounded-lg"><Phone className="w-4 h-4 text-slate-400" /></div>
                                            <span className="text-sm">{customer.phone || 'N/A'}</span>
                                        </div>
                                        <div className="flex items-center space-x-3 text-slate-300">
                                            <div className="p-2 bg-slate-800/50 rounded-lg"><Mail className="w-4 h-4 text-slate-400" /></div>
                                            <span className="text-sm truncate max-w-[150px]">{customer.email || 'N/A'}</span>
                                        </div>
                                        <div className="flex items-center space-x-3 text-slate-300">
                                            <div className="p-2 bg-slate-800/50 rounded-lg"><Globe className="w-4 h-4 text-slate-400" /></div>
                                            <span className="text-sm uppercase font-semibold">KTP: {customer.ktp_number || 'N/A'}</span>
                                        </div>
                                        <div className="flex items-center space-x-3 text-slate-300">
                                            <div className="p-2 bg-slate-800/50 rounded-lg"><MapPin className="w-4 h-4 text-slate-400" /></div>
                                            <span className="text-sm">Area: {customer.kelurahan || 'N/A'}</span>
                                        </div>
                                    </div>

                                    <div className="pt-4 border-t border-slate-800 flex items-start space-x-3 text-slate-400">
                                        <MapPin className="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" />
                                        <p className="text-sm leading-relaxed">{customer.address}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Bottom Stats Summary */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Card className="bg-slate-900/40 border-slate-800 backdrop-blur-xl p-6 space-y-2">
                            <p className="text-[10px] uppercase text-slate-500 font-bold tracking-widest">Tagihan Estimasi</p>
                            <h4 className="text-xl font-bold text-white">Rp {customer.package?.price?.toLocaleString('id-ID') || '0'}</h4>
                            <div className="flex items-center text-xs text-emerald-400 font-medium">Bulan Ini <ChevronLeft className="w-3 h-3 rotate-180 ml-1" /></div>
                        </Card>
                        <Card className="bg-slate-900/40 border-slate-800 backdrop-blur-xl p-6 space-y-2">
                            <p className="text-[10px] uppercase text-slate-500 font-bold tracking-widest">Pembayaran Terakhir</p>
                            <h4 className="text-xl font-bold text-white">Lunas</h4>
                            <div className="text-xs text-slate-400">24/03/2026 via Transfer</div>
                        </Card>
                        <Card className="bg-slate-900/40 border-slate-800 backdrop-blur-xl p-6 space-y-2">
                            <p className="text-[10px] uppercase text-slate-500 font-bold tracking-widest">Tiket Komplain</p>
                            <h4 className="text-xl font-bold text-slate-500">Nol (0)</h4>
                            <div className="text-xs text-emerald-500">Layanan Bagus</div>
                        </Card>
                    </div>
                </div>

                {/* Right Sidebar: Technical Info (4 columns) */}
                <div className="lg:col-span-4 space-y-6">
                    <Card className="bg-slate-900/40 border-slate-800 backdrop-blur-xl shadow-2xl rounded-2xl overflow-hidden">
                        <div className="bg-slate-800/30 p-4 border-b border-slate-800">
                            <h3 className="text-sm font-bold text-slate-300 flex items-center gap-2">
                                <Server className="w-4 h-4 text-blue-400" />
                                Detail Teknis & Jaringan
                            </h3>
                        </div>
                        <CardContent className="p-6 space-y-6">
                            <div className="space-y-4">
                                <div className="group space-y-1.5">
                                    <label className="text-[10px] uppercase text-slate-500 font-bold tracking-widest">Router / Mikrotik</label>
                                    <div className="bg-blue-500/5 border border-blue-500/20 p-3 rounded-xl flex items-center justify-between">
                                        <div className="flex items-center space-x-3">
                                            <RouterIcon className="w-5 h-5 text-blue-400" />
                                            <span className="text-sm font-semibold text-white">{customer.router?.name || 'BELUM TERDAFTAR'}</span>
                                        </div>
                                        <Badge className="bg-blue-500/10 text-blue-400 border-none px-2 h-5 text-[9px]">ONLINE</Badge>
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div className="space-y-1.5">
                                        <label className="text-[10px] uppercase text-slate-500 font-bold tracking-widest">ODP / Box</label>
                                        <div className="bg-slate-950/50 border border-slate-800 p-3 rounded-xl text-sm font-semibold text-white truncate">
                                            {customer.olt?.name || 'N/A'}
                                        </div>
                                    </div>
                                    <div className="space-y-1.5">
                                        <label className="text-[10px] uppercase text-slate-500 font-bold tracking-widest">Port</label>
                                        <div className="bg-slate-950/50 border border-slate-800 p-3 rounded-xl text-sm font-semibold text-white">
                                            PORT #{customer.odp_port || '??'}
                                        </div>
                                    </div>
                                </div>

                                <div className="space-y-1.5 pt-2">
                                    <label className="text-[10px] uppercase text-slate-500 font-bold tracking-widest">Paket Langganan</label>
                                    <div className="bg-emerald-500/10 border border-emerald-500/20 p-4 rounded-xl flex items-center justify-between">
                                        <div>
                                            <p className="text-lg font-bold text-emerald-400">{customer.package?.name || 'GABUNG'}</p>
                                            <p className="text-[10px] text-emerald-500/70 font-semibold tracking-tighter">UNLIMITED ACCESS</p>
                                        </div>
                                        <Zap className="w-8 h-8 text-emerald-500 animate-pulse" />
                                    </div>
                                </div>
                            </div>
                            
                            <hr className="border-slate-800" />

                            <div className="space-y-3">
                                <div className="flex items-center justify-between text-xs">
                                    <span className="text-slate-500">Service Status</span>
                                    <span className="text-emerald-400 font-bold">STABLE</span>
                                </div>
                                <div className="flex items-center justify-between text-xs">
                                    <span className="text-slate-500">IP Address</span>
                                    <span className="text-white font-mono">{customer.mikrotik_ip || 'Dynamic DHCP'}</span>
                                </div>
                                <div className="flex items-center justify-between text-xs">
                                    <span className="text-slate-500">PPPoE User</span>
                                    <span className="text-blue-400 font-mono">{customer.pppoe_username || 'n/a'}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="bg-slate-900/40 border-slate-800 backdrop-blur-xl p-6 space-y-4">
                        <h3 className="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <Clock className="w-4 h-4" />
                            Log Aktivitas Terakhir
                        </h3>
                        <div className="space-y-6 relative ml-3 border-l border-slate-800 pl-6 py-2">
                            {customer.status_logs?.slice(0, 3).map((log) => (
                                <div key={log.id} className="relative">
                                    <div className="absolute -left-[31px] top-1 w-2.5 h-2.5 rounded-full bg-slate-700 ring-4 ring-slate-900" />
                                    <p className="text-xs font-bold text-white uppercase">{log.status_label}</p>
                                    <p className="text-[10px] text-slate-500">{format(new Date(log.changed_at), 'dd MMM yyyy, HH:mm')}</p>
                                    <p className="text-[10px] text-slate-400 mt-1 italic leading-relaxed">"{log.notes || 'Sistem Update'}"</p>
                                </div>
                            ))}
                        </div>
                    </Card>
                </div>

            </div>
        </div>
    );
}
