'use client';

import { useState } from 'react';
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
    MoreVertical,
    MoreHorizontal,
    ChevronLeft,
    ChevronRight,
    Loader2
} from 'lucide-react';
import { toast } from 'sonner';
import { cn } from '@/lib/utils';

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

    return (
        <div className="space-y-8 max-w-7xl mx-auto">
            {/* Header */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-3xl font-bold tracking-tight bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent">
                        Manajemen Pelanggan
                    </h1>
                    <p className="text-zinc-400 mt-1">Kelola data pelanggan dan status layanan internet.</p>
                </div>
                <Link href="/dashboard/admin/customers/create">
                    <Button className="bg-blue-600 hover:bg-blue-700 text-white rounded-xl h-11 px-6 shadow-xl shadow-blue-600/20 gap-2 w-full md:w-auto">
                        <Plus className="h-5 w-5" />
                        <span>Tambah Pelanggan</span>
                    </Button>
                </Link>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {[
                    { label: 'Total Pelanggan', value: data?.stats.total || 0, icon: Users, color: 'blue' },
                    { label: 'Status Aktif', value: data?.stats.active || 0, icon: CheckCircle2, color: 'emerald' },
                    { label: 'Pending / Progress', value: data?.stats.pending || 0, icon: Clock, color: 'amber' },
                    { label: 'Suspended', value: data?.stats.suspended || 0, icon: AlertCircle, color: 'red' },
                ].map((stat, i) => (
                    <Card key={i} className="bg-zinc-900/40 border-zinc-800/50 backdrop-blur-sm hover:bg-zinc-900/60 transition-all duration-300 group overflow-hidden relative">
                        <div className={cn(
                            "absolute top-0 right-0 w-24 h-24 blur-3xl rounded-full opacity-10 transition-opacity group-hover:opacity-20",
                            stat.color === 'blue' ? "bg-blue-500" : 
                            stat.color === 'emerald' ? "bg-emerald-500" :
                            stat.color === 'amber' ? "bg-amber-500" : "bg-red-500"
                        )} />
                        <CardHeader className="flex flex-row items-center justify-between pb-2 space-y-0">
                            <CardTitle className="text-sm font-medium text-zinc-400">{stat.label}</CardTitle>
                            <stat.icon className={cn(
                                "h-5 w-5 transition-transform group-hover:scale-110",
                                stat.color === 'blue' ? "text-blue-400" : 
                                stat.color === 'emerald' ? "text-emerald-400" :
                                stat.color === 'amber' ? "text-amber-400" : "text-red-400"
                            )} />
                        </CardHeader>
                        <CardContent>
                            <div className="text-3xl font-bold tracking-tight">{stat.value}</div>
                            <p className="text-xs text-zinc-500 mt-1">Update terakhir hari ini</p>
                        </CardContent>
                    </Card>
                ))}
            </div>

            {/* Filters & Table Card */}
            <Card className="bg-zinc-900/40 border-zinc-800/50 backdrop-blur-sm overflow-hidden rounded-2xl shadow-2xl">
                <div className="p-6 border-b border-zinc-800/50 bg-zinc-900/20">
                    <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div className="relative flex-1 max-w-md">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                            <Input 
                                placeholder="Cari nama, CID, atau telepon..." 
                                className="pl-10 h-11 bg-zinc-950/50 border-zinc-800 rounded-xl focus:ring-blue-500/50"
                                value={filters.search}
                                onChange={(e) => setFilters({...filters, search: e.target.value, page: 1})}
                            />
                        </div>

                        <div className="flex flex-wrap items-center gap-4">
                            <Select 
                                value={filters.status} 
                                onValueChange={(val) => setFilters({...filters, status: val ?? 'all', page: 1})}
                            >
                                <SelectTrigger className="w-[160px] h-11 bg-zinc-950/50 border-zinc-800 rounded-xl">
                                    <div className="flex items-center gap-2">
                                        <Filter className="h-4 w-4 text-zinc-500" />
                                        <SelectValue placeholder="Status" />
                                    </div>
                                </SelectTrigger>
                                <SelectContent className="bg-zinc-900 border-zinc-800 text-white">
                                    <SelectItem value="all">Semua Status</SelectItem>
                                    {data?.options.statuses && Object.entries(data.options.statuses).map(([key, label]) => (
                                        <SelectItem key={key} value={key}>{label}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>

                            <Select 
                                value={filters.package_id} 
                                onValueChange={(val) => setFilters({...filters, package_id: val ?? 'all', page: 1})}
                            >
                                <SelectTrigger className="w-[180px] h-11 bg-zinc-950/50 border-zinc-800 rounded-xl">
                                    <SelectValue placeholder="Semua Paket" />
                                </SelectTrigger>
                                <SelectContent className="bg-zinc-900 border-zinc-800 text-white">
                                    <SelectItem value="all">Semua Paket</SelectItem>
                                    {data?.options.packages.map(pkg => (
                                        <SelectItem key={pkg.id} value={pkg.id.toString()}>{pkg.name}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>

                            <Select 
                                value={filters.kelurahan} 
                                onValueChange={(val) => setFilters({...filters, kelurahan: val ?? 'all', page: 1})}
                            >
                                <SelectTrigger className="w-[160px] h-11 bg-zinc-950/50 border-zinc-800 rounded-xl">
                                    <SelectValue placeholder="Lokasi Desa" />
                                </SelectTrigger>
                                <SelectContent className="bg-zinc-900 border-zinc-800 text-white">
                                    <SelectItem value="all">Semua Desa</SelectItem>
                                    {data?.options.locations.kelurahan.map(loc => (
                                        <SelectItem key={loc} value={loc}>{loc}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </div>

                <div className="relative">
                    {isLoading && (
                        <div className="absolute inset-0 bg-zinc-950/20 backdrop-blur-[1px] z-10 flex items-center justify-center">
                            <Loader2 className="h-10 w-10 text-blue-500 animate-spin" />
                        </div>
                    )}
                    
                    <Table>
                        <TableHeader className="bg-zinc-900/50 border-b border-zinc-800/50">
                            <TableRow className="border-zinc-800/50 hover:bg-transparent">
                                <TableHead className="text-zinc-500 font-bold uppercase text-[10px] tracking-widest px-6 h-12">Pelanggan</TableHead>
                                <TableHead className="text-zinc-500 font-bold uppercase text-[10px] tracking-widest h-12">CID</TableHead>
                                <TableHead className="text-zinc-500 font-bold uppercase text-[10px] tracking-widest h-12">Paket</TableHead>
                                <TableHead className="text-zinc-500 font-bold uppercase text-[10px] tracking-widest h-12">Status</TableHead>
                                <TableHead className="text-zinc-500 font-bold uppercase text-[10px] tracking-widest h-12">Tgl Register</TableHead>
                                <TableHead className="text-right text-zinc-500 font-bold uppercase text-[10px] tracking-widest px-6 h-12">Aksi</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {!isLoading && data?.customers.data.map((customer) => (
                                <TableRow key={customer.id} className="border-zinc-800/50 hover:bg-white/5 transition-colors group">
                                    <TableCell className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="h-10 w-10 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-zinc-100 font-bold shadow-inner group-hover:scale-110 transition-transform">
                                                {customer.name.substring(0, 2).toUpperCase()}
                                            </div>
                                            <div>
                                                <Link 
                                                    href={`/dashboard/admin/customers/${customer.id}`}
                                                    className="font-semibold text-zinc-100 hover:text-blue-400 transition-colors"
                                                >
                                                    {customer.name}
                                                </Link>
                                                <p className="text-xs text-zinc-500">{customer.phone || 'N/A'}</p>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell className="font-mono text-[11px] text-zinc-400">
                                        {customer.customer_id}
                                    </TableCell>
                                    <TableCell>
                                        <p className="text-sm text-zinc-300">{customer.package?.name || '---'}</p>
                                    </TableCell>
                                    <TableCell>
                                        <Badge 
                                            variant="outline" 
                                            className={cn("px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider cursor-pointer hover:opacity-80 transition-opacity", getStatusColor(customer.status))}
                                            onClick={() => {
                                                setSelectedCustomer(customer);
                                                setNewStatus(customer.status);
                                                setStatusDialogOpen(true);
                                            }}
                                        >
                                            {customer.status_label}
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="text-sm text-zinc-500">
                                        {new Date(customer.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}
                                    </TableCell>
                                    <TableCell className="px-6 py-4">
                                        <div className="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <Link 
                                                href={`/dashboard/admin/customers/${customer.id}`}
                                                className={cn(buttonVariants({ variant: 'ghost', size: 'icon' }), "h-8 w-8 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg")}
                                            >
                                                <Eye className="h-4 w-4" />
                                            </Link>
                                            <Link 
                                                href={`/dashboard/admin/customers/${customer.id}/edit`}
                                                className={cn(buttonVariants({ variant: 'ghost', size: 'icon' }), "h-8 w-8 text-zinc-400 hover:text-blue-400 hover:bg-blue-400/10 rounded-lg")}
                                            >
                                                <Edit2 className="h-4 w-4" />
                                            </Link>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            ))}
                            {data?.customers.data.length === 0 && !isLoading && (
                                <TableRow>
                                    <TableCell colSpan={6} className="h-64 text-center">
                                        <div className="flex flex-col items-center gap-2 opacity-40">
                                            <Search className="h-10 w-10 mb-2" />
                                            <p className="font-medium">Data pelanggan tidak ditemukan</p>
                                            <p className="text-sm">Coba sesuaikan filter atau kata kunci pencarian</p>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </div>

                {/* Pagination */}
                {!isLoading && data && data.customers.last_page > 1 && (
                    <div className="p-6 border-t border-zinc-800/50 bg-zinc-900/10 flex items-center justify-between">
                        <p className="text-sm text-zinc-500">
                            Menampilkan <span className="text-white font-medium">{data.customers.data.length}</span> dari <span className="text-white font-medium">{data.customers.total}</span> pelanggan
                        </p>
                        <div className="flex items-center gap-2">
                            <Button 
                                variant="outline" 
                                size="sm" 
                                className="bg-zinc-900 border-zinc-800 hover:bg-zinc-800 disabled:opacity-30"
                                disabled={filters.page === 1}
                                onClick={() => setFilters({...filters, page: filters.page - 1})}
                            >
                                <ChevronLeft className="h-4 w-4 mr-2" />
                                Kembali
                            </Button>
                            <div className="flex items-center gap-1 mx-2">
                                <span className="text-sm font-medium text-white">{filters.page}</span>
                                <span className="text-sm text-zinc-500">/</span>
                                <span className="text-sm text-zinc-500">{data.customers.last_page}</span>
                            </div>
                            <Button 
                                variant="outline" 
                                size="sm" 
                                className="bg-zinc-900 border-zinc-800 hover:bg-zinc-800 disabled:opacity-30"
                                disabled={filters.page >= data.customers.last_page}
                                onClick={() => setFilters({...filters, page: filters.page + 1})}
                            >
                                Lanjut
                                <ChevronRight className="h-4 w-4 ml-2" />
                            </Button>
                        </div>
                    </div>
                )}
            </Card>

            {/* Status Update Dialog */}
            <Dialog open={statusDialogOpen} onOpenChange={setStatusDialogOpen}>
                <DialogContent className="bg-zinc-900 border-zinc-800 text-white sm:max-w-md rounded-2xl">
                    <DialogHeader>
                        <DialogTitle>Update Status Pelanggan</DialogTitle>
                        <DialogDescription className="text-zinc-400">
                            Ubah status layanan untuk <span className="text-white font-semibold">{selectedCustomer?.name}</span>
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
                                    {data?.options.statuses && Object.entries(data.options.statuses).map(([key, label]) => (
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
