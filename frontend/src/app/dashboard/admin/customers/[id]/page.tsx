'use client';

import { use, useState } from 'react';
import { useCustomer, useUpdateCustomerStatus, useCustomers, useSyncCustomerToMikrotik } from '@/hooks/use-customers';
import { 
    Card, 
    CardContent, 
    CardHeader, 
    CardTitle,
    CardDescription
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { 
    Dialog, 
    DialogContent, 
    DialogHeader, 
    DialogTitle,
    DialogFooter,
    DialogDescription
} from '@/components/ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { 
    ChevronLeft, 
    User, 
    MapPin, 
    Phone, 
    Mail, 
    CreditCard, 
    Activity, 
    Settings, 
    Router as RouterIcon, 
    Globe,
    Calendar,
    ArrowUpRight,
    Edit2,
    Loader2,
    Clock,
    UserCircle,
    Server,
    Zap,
    FileText as FileIcon
} from 'lucide-react';
import { ContractSection } from './components/ContractSection';
import { ReferralSection } from './components/ReferralSection';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { cn } from '@/lib/utils';
import { format } from 'date-fns';
import { id as idLocale } from 'date-fns/locale';

export default function CustomerDetailPage({ params }: { params: Promise<{ id: string }> }) {
    const { id } = use(params);
    const router = useRouter();
    const { data: customer, isLoading, error } = useCustomer(id);
    const { data: optionsData } = useCustomers({ limit: 1 }); // To get status options
    const updateStatusMutation = useUpdateCustomerStatus();

    const [statusDialogOpen, setStatusDialogOpen] = useState(false);
    const [newStatus, setNewStatus] = useState('');
    const [statusNotes, setStatusNotes] = useState('');
    const syncMikrotikMutation = useSyncCustomerToMikrotik();

    if (isLoading) {
        return (
            <div className="flex flex-col gap-8 animate-pulse">
                <div className="h-10 w-48 bg-zinc-900 rounded-lg"></div>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="h-64 bg-zinc-900 rounded-2xl md:col-span-1"></div>
                    <div className="h-64 bg-zinc-900 rounded-2xl md:col-span-2"></div>
                </div>
            </div>
        );
    }

    if (error || !customer) {
        return (
            <div className="flex flex-col items-center justify-center min-h-[60vh] gap-4">
                <p className="text-zinc-500">Data pelanggan tidak ditemukan atau terjadi kesalahan.</p>
                <Button variant="outline" onClick={() => router.back()}>Kembali</Button>
            </div>
        );
    }

    const handleUpdateStatus = () => {
        if (!newStatus) return;

        updateStatusMutation.mutate({
            id: customer.id,
            status: newStatus,
            notes: statusNotes
        }, {
            onSuccess: () => {
                toast.success('Status pelanggan berhasil diperbarui');
                setStatusDialogOpen(false);
                setNewStatus('');
                setStatusNotes('');
            },
            onError: () => {
                toast.error('Gagal memperbarui status');
            }
        });
    };

    const handleSyncMikrotik = () => {
        syncMikrotikMutation.mutate(customer.id, {
            onSuccess: (data) => {
                toast.success(data.message || 'Sinkronisasi MikroTik berhasil');
            },
            onError: (err: any) => {
                toast.error(err?.response?.data?.message || 'Gagal melakukan sinkronisasi ke MikroTik');
            }
        });
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'active': return 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
            case 'registered': return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
            case 'suspended': return 'bg-red-500/10 text-red-400 border-red-500/20';
            default: return 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20';
        }
    };

    return (
        <div className="space-y-8 max-w-6xl mx-auto">
            {/* Navigation Header */}
            <div className="flex items-center justify-between">
                <Button 
                    variant="ghost" 
                    className="gap-2 text-zinc-400 hover:text-zinc-100 -ml-4"
                    onClick={() => router.back()}
                >
                    <ChevronLeft className="h-5 w-5" />
                    <span>Kembali ke Daftar</span>
                </Button>
                <div className="flex items-center gap-3">
                    <Button variant="outline" className="border-zinc-800 rounded-xl gap-2 h-10">
                        <Edit2 className="h-4 w-4" />
                        Edit Profil
                    </Button>
                    <Button 
                        className="bg-blue-600 hover:bg-blue-700 text-white rounded-xl h-10 px-5 shadow-lg shadow-blue-600/20"
                        onClick={() => {
                            setNewStatus(customer.status);
                            setStatusDialogOpen(true);
                        }}
                    >
                        Update Status
                    </Button>
                </div>
            </div>

            {/* Profile Header Card */}
            <Card className="bg-zinc-900/40 border-zinc-800/50 backdrop-blur-md overflow-hidden relative rounded-2xl shadow-2xl">
                <div className="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 blur-3xl -z-10 rounded-full" />
                <CardContent className="p-8">
                    <div className="flex flex-col md:flex-row items-start md:items-center gap-8">
                        <div className="h-24 w-24 rounded-3xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center text-3xl font-bold text-white shadow-2xl shadow-blue-500/30">
                            {customer.name.substring(0, 2).toUpperCase()}
                        </div>
                        <div className="space-y-2 flex-1">
                            <div className="flex flex-wrap items-center gap-3">
                                <h1 className="text-3xl font-bold tracking-tight">{customer.name}</h1>
                                <Badge className={cn("px-3 py-1 rounded-full text-xs font-bold uppercase", getStatusColor(customer.status))}>
                                    {customer.status_label}
                                </Badge>
                            </div>
                            <div className="flex flex-wrap items-center gap-x-6 gap-y-2 text-zinc-400 text-sm">
                                <div className="flex items-center gap-2">
                                    <Globe className="h-4 w-4" />
                                    <span>{customer.customer_id}</span>
                                </div>
                                <div className="flex items-center gap-2">
                                    <Phone className="h-4 w-4" />
                                    <span>{customer.phone || 'N/A'}</span>
                                </div>
                                <div className="flex items-center gap-2">
                                    <Mail className="h-4 w-4" />
                                    <span>{customer.email || 'N/A'}</span>
                                </div>
                            </div>
                        </div>
                        <div className="bg-zinc-950/50 border border-zinc-800 p-4 rounded-2xl text-center min-w-[140px]">
                            <p className="text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Paket Layanan</p>
                            <p className="text-xl font-bold text-blue-400 mt-1">{customer.package?.name || 'Basic'}</p>
                            <p className="text-xs text-zinc-500 mt-0.5">Rp {customer.package?.price?.toLocaleString() || '0'}/bln</p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            {/* Main Content Tabs */}
            <Tabs defaultValue="overview" className="space-y-6">
                <TabsList className="bg-zinc-900/50 border border-zinc-800 p-1 rounded-xl h-12 w-full sm:w-auto">
                    <TabsTrigger value="overview" className="rounded-lg data-[state=active]:bg-zinc-800 data-[state=active]:text-white">Overview</TabsTrigger>
                    <TabsTrigger value="billing" className="rounded-lg data-[state=active]:bg-zinc-800 data-[state=active]:text-white">Billing</TabsTrigger>
                    <TabsTrigger value="network" className="rounded-lg data-[state=active]:bg-zinc-800 data-[state=active]:text-white">Network</TabsTrigger>
                    <TabsTrigger value="contract" className="rounded-lg data-[state=active]:bg-zinc-800 data-[state=active]:text-white">Contract</TabsTrigger>
                    <TabsTrigger value="referral" className="rounded-lg data-[state=active]:bg-zinc-800 data-[state=active]:text-white">Referral</TabsTrigger>
                    <TabsTrigger value="history" className="rounded-lg data-[state=active]:bg-zinc-800 data-[state=active]:text-white">History</TabsTrigger>
                </TabsList>

                {/* Overview Tab */}
                <TabsContent value="overview" className="space-y-6 animate-in fade-in-50 duration-500">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <Card className="bg-zinc-900/20 border-zinc-800/50 rounded-2xl">
                            <CardHeader>
                                <CardTitle className="text-lg flex items-center gap-2">
                                    <MapPin className="h-5 w-5 text-blue-400" />
                                    Informasi Lokasi
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                                    <div>
                                        <p className="text-[10px] uppercase text-zinc-500 font-bold tracking-widest mb-1">Alamat Lengkap</p>
                                        <p className="text-sm leading-relaxed">{customer.address}</p>
                                    </div>
                                    <div>
                                        <p className="text-[10px] uppercase text-zinc-500 font-bold tracking-widest mb-1">RT / RW</p>
                                        <p className="text-sm">{customer.rt_rw || '---'}</p>
                                    </div>
                                    <div>
                                        <p className="text-[10px] uppercase text-zinc-500 font-bold tracking-widest mb-1">Kelurahan / Desa</p>
                                        <p className="text-sm">{customer.kelurahan}</p>
                                    </div>
                                    <div>
                                        <p className="text-[10px] uppercase text-zinc-500 font-bold tracking-widest mb-1">Kecamatan</p>
                                        <p className="text-sm">{customer.kecamatan}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card className="bg-zinc-900/20 border-zinc-800/50 rounded-2xl">
                            <CardHeader>
                                <CardTitle className="text-lg flex items-center gap-2">
                                    <Activity className="h-5 w-5 text-emerald-400" />
                                    Account Status
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-3">
                                        <Calendar className="h-5 w-5 text-zinc-500" />
                                        <span className="text-sm text-zinc-400">Terdaftar sejak</span>
                                    </div>
                                    <span className="font-semibold">{format(new Date(customer.created_at), 'dd MMMM yyyy', { locale: idLocale })}</span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-3">
                                        <UserCircle className="h-5 w-5 text-zinc-500" />
                                        <span className="text-sm text-zinc-400">Teknisi Pendamping</span>
                                    </div>
                                    <span className="font-semibold text-zinc-200">{customer.technician?.name || 'Belum Ditentukan'}</span>
                                </div>
                                <div className="flex items-center justify-between border-t border-zinc-800 pt-4 mt-4">
                                    <div className="flex items-center gap-3">
                                        <Zap className="h-5 w-5 text-amber-400" />
                                        <span className="text-sm text-zinc-400">Partner Referral</span>
                                    </div>
                                    <span className="font-semibold">{customer.partner?.name || 'Langsung'}</span>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                {/* Billing Tab */}
                <TabsContent value="billing" className="space-y-6 animate-in fade-in-50 duration-500">
                    <Card className="bg-zinc-900/20 border-zinc-800/50 rounded-2xl">
                        <CardHeader className="flex flex-row items-center justify-between">
                            <CardTitle className="text-lg flex items-center gap-2">
                                <CreditCard className="h-5 w-5 text-blue-400" />
                                Riwayat Tagihan
                            </CardTitle>
                            <Button variant="ghost" size="sm" className="text-blue-400 gap-1 hover:bg-blue-400/10">
                                Lihat Semua
                                <ArrowUpRight className="h-4 w-4" />
                            </Button>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-1">
                                {customer.invoices && customer.invoices.length > 0 ? (
                                    customer.invoices.map((invoice) => (
                                        <div key={invoice.id} className="flex items-center justify-between p-4 hover:bg-white/5 rounded-xl transition-colors group border-b border-zinc-800/50 last:border-0">
                                            <div className="flex items-center gap-4">
                                                <div className={cn(
                                                    "h-10 w-10 rounded-full flex items-center justify-center",
                                                    invoice.status === 'paid' ? "bg-emerald-500/10 text-emerald-400" : "bg-amber-500/10 text-amber-400"
                                                )}>
                                                    <CreditCard className="h-5 w-5" />
                                                </div>
                                                <div>
                                                    <p className="font-semibold text-sm">{invoice.invoice_number}</p>
                                                    <p className="text-xs text-zinc-500">Jatuh Tempo: {format(new Date(invoice.due_date), 'dd MMM yyyy')}</p>
                                                </div>
                                            </div>
                                            <div className="text-right">
                                                <p className="font-bold text-sm">Rp {invoice.amount.toLocaleString()}</p>
                                                <Badge variant="outline" className={cn(
                                                    "mt-1 text-[10px] uppercase font-bold tracking-widest px-2 py-0 border-0",
                                                    invoice.status === 'paid' ? "text-emerald-400" : "text-amber-400"
                                                )}>
                                                    {invoice.status === 'paid' ? 'LUNAS' : 'PENDING'}
                                                </Badge>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="py-12 text-center text-zinc-500 italic text-sm">Belum ada tagihan terdaftar.</div>
                                )}
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                {/* Network Tab */}
                <TabsContent value="network" className="space-y-6 animate-in fade-in-50 duration-500">
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Card className="bg-zinc-900/20 border-zinc-800/50 rounded-2xl p-6">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="h-10 w-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
                                    <RouterIcon className="h-5 w-5" />
                                </div>
                                <div className="flex-1">
                                    <h3 className="font-bold">Akses Mikrotik</h3>
                                    <p className="text-[10px] text-zinc-500 uppercase tracking-wider">PPPoE Configuration</p>
                                </div>
                                <Button 
                                    size="sm" 
                                    variant="outline" 
                                    className="border-blue-500/30 bg-blue-500/5 text-blue-400 hover:bg-blue-500/10 h-8 gap-2 rounded-lg"
                                    onClick={handleSyncMikrotik}
                                    disabled={syncMikrotikMutation.isPending}
                                >
                                    {syncMikrotikMutation.isPending ? (
                                        <Loader2 className="h-3 w-3 animate-spin" />
                                    ) : (
                                        <Activity className="h-3 w-3" />
                                    )}
                                    Sync
                                </Button>
                            </div>
                            <div className="space-y-4">
                                <div>
                                    <p className="text-[10px] uppercase text-zinc-500 font-bold tracking-widest mb-1">PPPoE Username</p>
                                    <p className="text-sm font-mono bg-zinc-950 p-2 rounded-lg border border-zinc-800 text-blue-400">
                                        {customer.pppoe_username || 'Not Generated'}
                                    </p>
                                </div>
                                <div>
                                    <p className="text-[10px] uppercase text-zinc-500 font-bold tracking-widest mb-1">Static IP (Optional)</p>
                                    <p className="text-sm font-mono text-zinc-300">
                                        {customer.mikrotik_ip || 'DHCP Dynamic'}
                                    </p>
                                </div>
                            </div>
                        </Card>

                        <Card className="bg-zinc-900/20 border-zinc-800/50 rounded-2xl p-6">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="h-10 w-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                                    <Server className="h-5 w-5" />
                                </div>
                                <h3 className="font-bold">Infrastruktur</h3>
                            </div>
                            <div className="space-y-4">
                                <div className="flex items-center justify-between">
                                    <span className="text-sm text-zinc-400">Router Node</span>
                                    <span className="font-semibold text-zinc-200">{customer.router?.name || 'Unassigned'}</span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <span className="text-sm text-zinc-400">OLT Port</span>
                                    <span className="font-semibold text-zinc-200">{customer.olt?.name || 'Unassigned'}</span>
                                </div>
                            </div>
                        </Card>
                    </div>
                </TabsContent>

                {/* Contract Tab */}
                <TabsContent value="contract" className="space-y-6 animate-in fade-in-50 duration-500">
                    <ContractSection customerId={Number(customer.id)} />
                </TabsContent>

                {/* Referral Tab */}
                <TabsContent value="referral" className="space-y-6 animate-in fade-in-50 duration-500">
                    <ReferralSection customerId={Number(customer.id)} />
                </TabsContent>

                {/* History Tab */}
                <TabsContent value="history" className="space-y-6 animate-in fade-in-50 duration-500">
                    <Card className="bg-zinc-900/20 border-zinc-800/50 rounded-2xl">
                        <CardHeader>
                            <CardTitle className="text-lg flex items-center gap-2">
                                <Clock className="h-5 w-5 text-zinc-400" />
                                Log Status Perjalanan Layanan
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-8 relative before:absolute before:inset-0 before:left-8 before:h-full before:w-px before:bg-zinc-800 ml-4 py-4">
                                {customer.status_logs && customer.status_logs.length > 0 ? (
                                    customer.status_logs.map((log, idx) => (
                                        <div key={log.id} className="relative pl-12">
                                            <div className={cn(
                                                "absolute left-[25px] top-1.5 h-3.5 w-3.5 rounded-full ring-4 ring-zinc-950",
                                                idx === 0 ? "bg-blue-500 animate-pulse" : "bg-zinc-700"
                                            )} />
                                            <div>
                                                <div className="flex flex-wrap items-center gap-3 mb-1">
                                                    <span className="font-bold text-white uppercase text-xs tracking-wider">
                                                        {log.status_label}
                                                    </span>
                                                    <span className="text-[10px] text-zinc-500">
                                                        {format(new Date(log.changed_at), 'dd MMM yyyy, HH:mm')}
                                                    </span>
                                                    {log.changed_by_user && (
                                                        <span className="text-[10px] px-2 py-0.5 rounded-full bg-zinc-800 text-zinc-400 border border-zinc-700">
                                                            oleh {log.changed_by_user.name}
                                                        </span>
                                                    )}
                                                </div>
                                                <p className="text-sm text-zinc-400 italic">
                                                    "{log.notes || 'Perubahan status sistem'}"
                                                </p>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="py-8 text-center text-zinc-500 italic text-sm">Belum ada riwayat aktivitas.</div>
                                )}
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>

            {/* Status Update Dialog */}
            <Dialog open={statusDialogOpen} onOpenChange={setStatusDialogOpen}>
                <DialogContent className="bg-zinc-900 border-zinc-800 text-white sm:max-w-md rounded-2xl">
                    <DialogHeader>
                        <DialogTitle>Update Status Pelanggan</DialogTitle>
                        <DialogDescription className="text-zinc-400">
                            Ubah status layanan untuk <span className="text-white font-semibold">{customer.name}</span>
                        </DialogDescription>
                    </DialogHeader>
                    <div className="space-y-4 py-4">
                        <div className="space-y-2">
                            <label className="text-sm font-medium text-zinc-300">Status Baru</label>
                            <Select value={newStatus} onValueChange={(val) => setNewStatus(val ?? '')}>
                                <SelectTrigger className="bg-zinc-950 border-zinc-800 rounded-xl">
                                    <SelectValue placeholder="Pilih status" />
                                </SelectTrigger>
                                <SelectContent className="bg-zinc-900 border-zinc-800 text-white">
                                    {optionsData?.options.statuses && Object.entries(optionsData.options.statuses).map(([key, label]) => (
                                        <SelectItem key={key} value={key}>{label}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="space-y-2">
                            <label className="text-sm font-medium text-zinc-300">Catatan (Opsional)</label>
                            <Input 
                                placeholder="Alasan perubahan, progres, dll..." 
                                className="bg-zinc-950 border-zinc-800 rounded-xl"
                                value={statusNotes}
                                onChange={(e) => setStatusNotes(e.target.value)}
                                autoFocus
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button 
                            variant="outline" 
                            className="bg-transparent border-zinc-800 hover:bg-zinc-800 rounded-xl px-6"
                            onClick={() => setStatusDialogOpen(false)}
                        >
                            Batal
                        </Button>
                        <Button 
                            className="bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-6 shadow-lg shadow-blue-600/20"
                            onClick={handleUpdateStatus}
                            disabled={updateStatusMutation.isPending}
                        >
                            {updateStatusMutation.isPending ? (
                                <Loader2 className="h-4 w-4 animate-spin mr-2" />
                            ) : null}
                            Simpan Perubahan
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
