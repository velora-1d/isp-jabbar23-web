'use client';

import { useState, useMemo } from 'react';
import Link from 'next/link';
import { useCustomers, useUpdateCustomerStatus } from '@/hooks/use-customers';
import { Button, buttonVariants } from '@/components/ui/button';
import { 
    Table, 
    TableBody, 
    TableCell, 
    TableHead, 
    TableHeader, 
    TableRow 
} from '@/components/ui/table';
import { 
    Card, 
    CardContent, 
    CardHeader, 
    CardTitle 
} from '@/components/ui/card';
import { 
    Select, 
    SelectContent, 
    SelectItem, 
    SelectTrigger, 
    SelectValue 
} from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { 
    Dialog, 
    DialogContent, 
    DialogHeader, 
    DialogTitle,
    DialogFooter,
    DialogDescription
} from '@/components/ui/dialog';
import { 
    Search, 
    Filter, 
    Plus, 
    Users, 
    CheckCircle2, 
    Clock, 
    AlertCircle,
    Eye,
    Edit2,
    Loader2,
    ChevronLeft,
    ChevronRight,
    ArrowUpRight,
    Globe,
    Zap,
    MapPin
} from 'lucide-react';
import { toast } from 'sonner';
import { cn } from '@/lib/utils';
import { DashboardPageShell } from '@/components/dashboard/page-shell';

// StatCard Component for consistent look
function StatCard({ label, value, icon: Icon, color = "emerald", description }: any) {
    const colorMap = {
        emerald: "from-emerald-500/20 to-emerald-500/5 text-emerald-400 border-emerald-500/20",
        blue: "from-blue-500/20 to-blue-500/5 text-blue-400 border-blue-500/20",
        amber: "from-amber-500/20 to-amber-500/5 text-amber-400 border-amber-500/20",
        red: "from-red-500/20 to-red-500/5 text-red-400 border-red-500/20",
    } as any;

    const glowMap = {
        emerald: "bg-emerald-500/10",
        blue: "bg-blue-500/10",
        amber: "bg-amber-500/10",
        red: "bg-red-500/10",
    } as any;

    return (
        <div className={cn(
            "relative group overflow-hidden rounded-2xl border bg-gradient-to-br p-px transition-all duration-300 hover:shadow-2xl hover:shadow-black/50",
            colorMap[color]
        )}>
            <div className="relative h-full w-full rounded-[15px] bg-zinc-950/80 p-5 backdrop-blur-xl transition-all group-hover:bg-zinc-950/40">
                <div className={cn("absolute -right-6 -top-6 h-24 w-24 rounded-full blur-3xl transition-opacity group-hover:opacity-100 opacity-20", glowMap[color])} />
                
                <div className="flex items-center justify-between mb-3">
                    <div className={cn("p-2 rounded-lg bg-white/5 border border-white/10 group-hover:scale-110 transition-transform duration-500")}>
                        <Icon className="h-5 w-5" />
                    </div>
                    {description && (
                         <div className="flex items-center gap-1 text-[10px] uppercase tracking-tighter opacity-70">
                            <ArrowUpRight className="h-3 w-3" />
                            {description}
                         </div>
                    )}
                </div>

                <div className="space-y-1">
                    <p className="text-sm font-medium text-zinc-400 group-hover:text-zinc-300 transition-colors">{label}</p>
                    <h3 className="text-2xl font-bold tracking-tight text-white font-heading">{value}</h3>
                </div>
            </div>
        </div>
    );
}

export default function AdminCustomersPage() {
    const [filters, setFilters] = useState({
        search: '',
        status: 'all',
        package_id: 'all',
        kelurahan: 'all',
        page: 1,
    });

    const { data, isLoading } = useCustomers(filters);
    const updateStatusMutation = useUpdateCustomerStatus();
    
    const [statusDialogOpen, setStatusDialogOpen] = useState(false);
    const [selectedCustomer, setSelectedCustomer] = useState<any>(null);
    const [newStatus, setNewStatus] = useState('');
    const [statusNotes, setStatusNotes] = useState('');

    const handleUpdateStatus = () => {
        if (!selectedCustomer || !newStatus) return;

        updateStatusMutation.mutate({
            id: selectedCustomer.id,
            status: newStatus,
            notes: statusNotes
        }, {
            onSuccess: () => {
                toast.success('Status pelanggan berhasil diperbarui');
                setStatusDialogOpen(false);
                setSelectedCustomer(null);
                setNewStatus('');
                setStatusNotes('');
            },
            onError: () => {
                toast.error('Gagal memperbarui status');
            }
        });
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'active': return 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
            case 'registered': return 'bg-blue-500/10 text-blue-400 border-blue-500/20';
            case 'survey': return 'bg-sky-500/10 text-sky-400 border-sky-500/20';
            case 'approved': return 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
            case 'scheduled': return 'bg-teal-500/10 text-teal-400 border-teal-500/20';
            case 'installing': return 'bg-amber-500/10 text-amber-400 border-amber-500/20';
            case 'suspended': return 'bg-red-500/10 text-red-400 border-red-500/20';
            case 'terminated': return 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20';
            default: return 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20';
        }
    };

    const actions = (
        <Link href="/dashboard/admin/customers/create">
            <Button className="bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl h-10 px-5 shadow-lg shadow-emerald-600/20 gap-2 border border-emerald-500/50">
                <Plus className="h-4 w-4" />
                <span className="font-semibold text-sm">Pelanggan Baru</span>
            </Button>
        </Link>
    );

    return (
        <DashboardPageShell
            title="Manajemen Pelanggan"
            description="Katalog data pelanggan, status layanan, dan manajemen paket internet JABBAR23."
            actions={actions}
        >
            {/* Stats Row */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <StatCard 
                    label="Total Pelanggan" 
                    value={data?.stats?.total || 0} 
                    icon={Users} 
                    color="blue" 
                    description="SEMUA BASIS DATA"
                />
                <StatCard 
                    label="Aktif / Online" 
                    value={data?.stats?.active || 0} 
                    icon={CheckCircle2} 
                    color="emerald" 
                    description="LAYANAN AKTIF"
                />
                <StatCard 
                    label="Proses Aktivasi" 
                    value={data?.stats?.pending || 0} 
                    icon={Clock} 
                    color="amber" 
                    description="ANTRIAN SURVEY"
                />
                <StatCard 
                    label="Isolir / Suspended" 
                    value={data?.stats?.suspended || 0} 
                    icon={AlertCircle} 
                    color="red" 
                    description="TUNGGAKAN"
                />
            </div>

            {/* Filter Hub */}
            <div className="mb-6 flex flex-col md:flex-row gap-4">
                <div className="relative flex-1 group">
                    <div className="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <Search className="h-4 w-4 text-zinc-500 group-focus-within:text-emerald-400 transition-colors" />
                    </div>
                    <Input 
                        placeholder="Cari Nama, CID, atau No. Telepon..." 
                        className="pl-10 h-11 bg-zinc-950/50 border-white/5 rounded-xl focus:border-emerald-500/50 focus:ring-emerald-500/20 transition-all backdrop-blur-md"
                        value={filters.search}
                        onChange={(e) => setFilters({...filters, search: e.target.value, page: 1})}
                    />
                </div>
                
                <div className="flex flex-wrap items-center gap-3">
                    <Select 
                        value={filters.status} 
                        onValueChange={(val) => setFilters({...filters, status: val ?? 'all', page: 1})}
                    >
                        <SelectTrigger className="w-[150px] h-11 bg-zinc-950/50 border-white/5 rounded-xl text-zinc-300">
                            <div className="flex items-center gap-2">
                                <Filter className="h-3.5 w-3.5 text-emerald-500" />
                                <SelectValue placeholder="Status" />
                            </div>
                        </SelectTrigger>
                        <SelectContent className="bg-zinc-900 border-white/10 text-white">
                            <SelectItem value="all">Semua Status</SelectItem>
                            {data?.options.statuses && Object.entries(data.options.statuses).map(([key, label]) => (
                                <SelectItem key={key} value={key}>{label as string}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>

                    <Select 
                        value={filters.package_id} 
                        onValueChange={(val) => setFilters({...filters, package_id: val ?? 'all', page: 1})}
                    >
                        <SelectTrigger className="w-[180px] h-11 bg-zinc-950/50 border-white/5 rounded-xl text-zinc-300">
                            <div className="flex items-center gap-2">
                                <Zap className="h-4 w-4 text-emerald-500" />
                                <SelectValue placeholder="Paket" />
                            </div>
                        </SelectTrigger>
                        <SelectContent className="bg-zinc-900 border-white/10 text-white">
                            <SelectItem value="all">Semua Paket</SelectItem>
                            {data?.options.packages.map((pkg: any) => (
                                <SelectItem key={pkg.id} value={pkg.id.toString()}>{pkg.name}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>

                    <Select 
                        value={filters.kelurahan} 
                        onValueChange={(val) => setFilters({...filters, kelurahan: val ?? 'all', page: 1})}
                    >
                        <SelectTrigger className="w-[150px] h-11 bg-zinc-950/50 border-white/5 rounded-xl text-zinc-300">
                            <div className="flex items-center gap-2">
                                <MapPin className="h-4 w-4 text-emerald-500" />
                                <SelectValue placeholder="Wilayah" />
                            </div>
                        </SelectTrigger>
                        <SelectContent className="bg-zinc-900 border-white/10 text-white">
                            <SelectItem value="all">Semua Desa</SelectItem>
                            {data?.options.locations.kelurahan.map((loc: string) => (
                                <SelectItem key={loc} value={loc}>{loc}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>
            </div>

            {/* Table Area */}
            <div className="relative overflow-hidden rounded-2xl border border-white/5 bg-zinc-950/30 backdrop-blur-xl">
                {isLoading && (
                    <div className="absolute inset-0 bg-black/40 backdrop-blur-sm z-20 flex items-center justify-center">
                        <div className="flex flex-col items-center gap-3">
                            <Loader2 className="h-10 w-10 text-emerald-500 animate-spin" />
                            <p className="text-zinc-400 animate-pulse text-sm">Menarik data dari server Emerald...</p>
                        </div>
                    </div>
                )}

                <Table>
                    <TableHeader className="bg-white/[0.02] border-b border-white/5">
                        <TableRow className="border-white/5 hover:bg-transparent uppercase tracking-widest text-[10px] font-bold text-zinc-500">
                            <TableHead className="px-6 py-4">Informasi Pelanggan</TableHead>
                            <TableHead>CID</TableHead>
                            <TableHead>Layanan</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Terdaftar</TableHead>
                            <TableHead className="text-right px-6">Navigasi</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {!isLoading && data?.customers.data.map((customer: any) => (
                            <TableRow key={customer.id} className="border-white/5 hover:bg-emerald-500/[0.02] transition-colors group">
                                <TableCell className="px-6 py-4">
                                    <div className="flex items-center gap-4">
                                        <div className="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500/20 to-zinc-800 border border-emerald-500/10 flex items-center justify-center text-emerald-400 font-bold shadow-lg transition-transform group-hover:scale-105">
                                            {customer.name.substring(0, 1).toUpperCase()}
                                        </div>
                                        <div className="flex flex-col">
                                            <Link 
                                                href={`/dashboard/admin/customers/${customer.id}`}
                                                className="font-bold text-zinc-100 hover:text-emerald-400 transition-colors"
                                            >
                                                {customer.name}
                                            </Link>
                                            <span className="text-[11px] text-zinc-500 flex items-center gap-1">
                                                <Globe className="h-3 w-3" />
                                                {customer.phone || '0xx-xxxx-xxxx'}
                                            </span>
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell className="font-mono text-[11px] text-zinc-400 font-medium">
                                    {customer.customer_id}
                                </TableCell>
                                <TableCell>
                                    <div className="flex flex-col">
                                        <span className="text-xs font-semibold text-zinc-300">{customer.package?.name || '---'}</span>
                                        <span className="text-[10px] text-zinc-600 uppercase tracking-tight">{customer.package?.speed_limit || 'Best Effort'}</span>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Badge 
                                        variant="outline" 
                                        className={cn(
                                            "px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider cursor-pointer hover:brightness-125 transition-all border-none ring-1 ring-inset",
                                            getStatusColor(customer.status)
                                        )}
                                        onClick={() => {
                                            setSelectedCustomer(customer);
                                            setNewStatus(customer.status);
                                            setStatusDialogOpen(true);
                                        }}
                                    >
                                        <div className="flex items-center gap-1.5 font-heading">
                                            <div className="h-1 w-1 rounded-full bg-current animate-pulse" />
                                            {customer.status_label}
                                        </div>
                                    </Badge>
                                </TableCell>
                                <TableCell className="text-xs text-zinc-500 font-medium">
                                    {new Date(customer.created_at).toLocaleDateString('id-ID', { 
                                        day: '2-digit', 
                                        month: 'short', 
                                        year: 'numeric' 
                                    })}
                                </TableCell>
                                <TableCell className="px-6 py-4 text-right">
                                    <div className="flex items-center justify-end gap-2 pr-0">
                                        <Link 
                                            href={`/dashboard/admin/customers/${customer.id}`}
                                            className={cn(buttonVariants({ variant: 'ghost', size: 'icon' }), "h-9 w-9 text-zinc-500 hover:text-emerald-400 hover:bg-emerald-400/10 rounded-xl transition-all")}
                                        >
                                            <Eye className="h-4 w-4" />
                                        </Link>
                                        <Link 
                                            href={`/dashboard/admin/customers/${customer.id}/edit`}
                                            className={cn(buttonVariants({ variant: 'ghost', size: 'icon' }), "h-9 w-9 text-zinc-500 hover:text-blue-400 hover:bg-blue-400/10 rounded-xl transition-all")}
                                        >
                                            <Edit2 className="h-4 w-4" />
                                        </Link>
                                    </div>
                                </TableCell>
                            </TableRow>
                        ))}
                        
                        {!isLoading && data?.customers.data.length === 0 && (
                            <TableRow>
                                <TableCell colSpan={6} className="h-72 text-center">
                                    <div className="flex flex-col items-center justify-center gap-4 opacity-30 grayscale">
                                        <div className="p-6 rounded-full bg-zinc-900 border border-zinc-800">
                                            <Users className="h-12 w-12 text-zinc-400" />
                                        </div>
                                        <div className="space-y-1">
                                            <p className="text-lg font-heading font-medium text-white">Database Kosong</p>
                                            <p className="text-sm">Gunakan pencarian lain atau buat pelanggan baru untuk ditampilkan disini.</p>
                                        </div>
                                    </div>
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
                
                {/* Pagination Hub */}
                {!isLoading && data && data.customers.last_page > 1 && (
                    <div className="p-6 border-t border-white/5 bg-white/[0.01] flex items-center justify-between">
                        <div className="flex items-center gap-4">
                            <p className="text-xs text-zinc-500 uppercase tracking-widest font-bold">
                                Entry: <span className="text-emerald-400">{data.customers.data.length}</span> / {data.customers.total}
                            </p>
                        </div>
                        <div className="flex items-center gap-3">
                            <Button 
                                variant="ghost" 
                                size="sm" 
                                className="h-10 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl px-4 text-xs font-bold uppercase tracking-widest"
                                disabled={filters.page === 1}
                                onClick={() => setFilters({...filters, page: filters.page - 1})}
                            >
                                <ChevronLeft className="h-4 w-4 mr-2" />
                                Prev
                            </Button>
                            
                            <div className="flex items-center justify-center h-10 w-24 rounded-xl bg-zinc-950/50 border border-white/5 text-[11px] font-mono font-bold tracking-tighter">
                                <span className="text-emerald-400 text-sm">{filters.page}</span>
                                <span className="mx-2 text-zinc-700">OF</span>
                                <span className="text-zinc-500">{data.customers.last_page}</span>
                            </div>

                            <Button 
                                variant="ghost" 
                                size="sm" 
                                className="h-10 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl px-4 text-xs font-bold uppercase tracking-widest"
                                disabled={filters.page >= data.customers.last_page}
                                onClick={() => setFilters({...filters, page: filters.page + 1})}
                            >
                                Next
                                <ChevronRight className="h-4 w-4 ml-2" />
                            </Button>
                        </div>
                    </div>
                )}
            </div>

            {/* Status Update Dialog - Emerald Style */}
            <Dialog open={statusDialogOpen} onOpenChange={setStatusDialogOpen}>
                <DialogContent className="bg-zinc-950 border-white/10 text-white sm:max-w-md rounded-3xl backdrop-blur-2xl p-0 overflow-hidden">
                    <div className="h-2 w-full bg-gradient-to-r from-emerald-500/50 via-emerald-400 to-emerald-500/50" />
                    
                    <div className="p-6">
                        <DialogHeader className="mb-4">
                            <DialogTitle className="text-xl font-heading font-bold text-emerald-400">Update Progres Layanan</DialogTitle>
                            <DialogDescription className="text-zinc-400">
                                Mengubah status untuk identitas: <span className="text-zinc-100 font-mono text-[10px] bg-white/5 px-1.5 py-0.5 rounded">{selectedCustomer?.customer_id}</span>
                            </DialogDescription>
                        </DialogHeader>
                        
                        <div className="space-y-6 py-2">
                            <div className="space-y-2">
                                <label className="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Status Operasional</label>
                                <Select value={newStatus} onValueChange={(val) => setNewStatus(val ?? '')}>
                                    <SelectTrigger className="h-12 bg-white/5 border-white/10 rounded-2xl transition-all focus:ring-emerald-500/20">
                                        <SelectValue placeholder="Pilih status baru" />
                                    </SelectTrigger>
                                    <SelectContent className="bg-zinc-900 border-white/10 text-white rounded-xl">
                                        {data?.options.statuses && Object.entries(data.options.statuses).map(([key, label]) => (
                                            <SelectItem key={key} value={key} className="focus:bg-emerald-500/20 focus:text-emerald-400 transition-colors">{label as string}</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            
                            <div className="space-y-2">
                                <label className="text-[10px] font-bold uppercase tracking-widest text-zinc-500">Internal Memo</label>
                                <textarea 
                                    placeholder="Tulis alasan perubahan status atau detail instruksi lapangan..." 
                                    className="min-h-[100px] w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-sm focus:outline-none focus:border-emerald-500/50 transition-all resize-none"
                                    value={statusNotes}
                                    onChange={(e) => setStatusNotes(e.target.value)}
                                />
                            </div>
                        </div>
                        
                        <DialogFooter className="mt-8 gap-3 sm:gap-0">
                            <Button 
                                variant="ghost" 
                                className="flex-1 h-12 bg-transparent text-zinc-500 hover:text-white hover:bg-white/5 rounded-2xl font-bold uppercase tracking-widest text-xs"
                                onClick={() => setStatusDialogOpen(false)}
                            >
                                Batal
                            </Button>
                            <Button 
                                className="flex-1 h-12 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl font-bold uppercase tracking-widest text-xs shadow-lg shadow-emerald-600/20"
                                onClick={handleUpdateStatus}
                                disabled={updateStatusMutation.isPending}
                            >
                                {updateStatusMutation.isPending ? (
                                    <Loader2 className="h-4 w-4 animate-spin mr-2" />
                                ) : (
                                    <CheckCircle2 className="h-4 w-4 mr-2" />
                                )}
                                Konfirmasi
                            </Button>
                        </DialogFooter>
                    </div>
                </DialogContent>
            </Dialog>
        </DashboardPageShell>
    );
}

