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
    Select, 
    SelectContent, 
    SelectItem, 
    SelectTrigger, 
    SelectValue 
} from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { 
    Search, 
    Plus, 
    Users, 
    CheckCircle2, 
    AlertCircle,
    Eye,
    Edit2,
    Loader2,
    ChevronLeft,
    ChevronRight,
    Globe,
    MapPin,
    Clock,
    Filter
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { DashboardPageShell } from '@/components/dashboard/page-shell';

// StatCard Component matching Screenshot 02 style
function StatCard({ label, value, icon: Icon, colorClass, borderClass, bgClass, iconBgClass }: any) {
    return (
        <div className={cn("relative overflow-hidden rounded-2xl border p-6 backdrop-blur-xl transition-all duration-300 hover:shadow-2xl", borderClass, bgClass)}>
            <div className="flex items-center justify-between">
                <div className="space-y-1">
                    <p className="text-xs font-semibold text-slate-500 uppercase tracking-widest">{label}</p>
                    <h3 className="text-3xl font-black text-white font-heading">{value}</h3>
                </div>
                <div className={cn("p-3 rounded-2xl", iconBgClass)}>
                    <Icon className={cn("h-6 w-6", colorClass)} />
                </div>
            </div>
            {/* Subtle progress indicator or trend could go here if in screenshot */}
            <div className="mt-4 flex items-center space-x-2">
                <div className="h-1 flex-1 bg-slate-800 rounded-full overflow-hidden">
                    <div className={cn("h-full w-2/3 rounded-full", colorClass.replace('text', 'bg'))} />
                </div>
                <span className="text-[10px] text-slate-500 font-bold">65%</span>
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

    const getStatusBadge = (status: string, label: string) => {
        switch (status) {
            case 'active': 
                return <Badge className="bg-emerald-500/10 text-emerald-400 border-none rounded-lg px-2 text-[10px] font-bold uppercase tracking-widest">● {label}</Badge>;
            case 'suspended': 
                return <Badge className="bg-red-500/10 text-red-400 border-none rounded-lg px-2 text-[10px] font-bold uppercase tracking-widest">● {label}</Badge>;
            case 'registered': 
                return <Badge className="bg-blue-500/10 text-blue-400 border-none rounded-lg px-2 text-[10px] font-bold uppercase tracking-widest">● {label}</Badge>;
            default: 
                return <Badge className="bg-slate-500/10 text-slate-400 border-none rounded-lg px-2 text-[10px] font-bold uppercase tracking-widest">● {label}</Badge>;
        }
    };

    const actions = (
        <Link href="/dashboard/admin/customers/create">
            <Button className="bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl h-12 px-6 shadow-xl shadow-emerald-500/20 gap-2 font-bold uppercase tracking-widest text-[11px]">
                <Plus className="h-4 w-4" />
                Tambah Pelanggan
            </Button>
        </Link>
    );

    return (
        <DashboardPageShell
            title="Database Pelanggan"
            description="Manajemen data pelanggan, monitoring status layanan, dan penagihan billing."
            actions={actions}
        >
            {/* Summary Statistics */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <StatCard 
                    label="Total Pelanggan" 
                    value={data?.stats?.total || 0} 
                    icon={Users} 
                    bgClass="bg-slate-900/40" 
                    borderClass="border-slate-800"
                    colorClass="text-blue-400"
                    iconBgClass="bg-blue-500/10"
                />
                <StatCard 
                    label="Pelanggan Aktif" 
                    value={data?.stats?.active || 0} 
                    icon={CheckCircle2} 
                    bgClass="bg-slate-900/40" 
                    borderClass="border-slate-800"
                    colorClass="text-emerald-400"
                    iconBgClass="bg-emerald-500/10"
                />
                <StatCard 
                    label="Pelanggan Suspend" 
                    value={data?.stats?.suspended || 0} 
                    icon={AlertCircle} 
                    bgClass="bg-slate-900/40" 
                    borderClass="border-slate-800"
                    colorClass="text-red-400"
                    iconBgClass="bg-red-500/10"
                />
                <StatCard 
                    label="Menunggu Registrasi" 
                    value={data?.stats?.pending || 0} 
                    icon={Clock} 
                    bgClass="bg-slate-900/40" 
                    borderClass="border-slate-800"
                    colorClass="text-amber-400"
                    iconBgClass="bg-amber-500/10"
                />
            </div>

            {/* Filter & Search Bar */}
            <div className="flex flex-col xl:flex-row gap-4 mb-8 bg-slate-900/40 border border-slate-800 p-4 rounded-2xl backdrop-blur-xl">
                <div className="relative flex-1 group">
                    <Search className="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500 group-focus-within:text-emerald-400 transition-colors" />
                    <Input 
                        placeholder="Cari nama, CID, atau nomor telepon..." 
                        className="bg-slate-950/50 border-slate-800 pl-12 h-12 rounded-xl text-sm focus:border-emerald-500/50 focus:ring-emerald-500/10 transition-all placeholder:text-slate-600"
                        value={filters.search}
                        onChange={(e) => setFilters({...filters, search: e.target.value, page: 1})}
                    />
                </div>
                
                <div className="grid grid-cols-2 lg:grid-cols-3 gap-3">
                    <Select value={filters.status} onValueChange={(val) => setFilters({...filters, status: val || 'all', page: 1})}>
                        <SelectTrigger className="h-12 bg-slate-950/50 border-slate-800 rounded-xl text-slate-300 w-full min-w-[140px]">
                            <Filter className="w-3.5 h-3.5 mr-2 text-emerald-500" />
                            <SelectValue placeholder="Status" />
                        </SelectTrigger>
                        <SelectContent className="bg-slate-900 border-slate-800 text-slate-200">
                            <SelectItem value="all">Semua Status</SelectItem>
                            {data?.options.statuses && Object.entries(data.options.statuses).map(([key, label]) => (
                                <SelectItem key={key} value={key}>{label as string}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>

                    <Select value={filters.kelurahan} onValueChange={(val) => setFilters({...filters, kelurahan: val || 'all', page: 1})}>
                        <SelectTrigger className="h-12 bg-slate-950/50 border-slate-800 rounded-xl text-slate-300 w-full min-w-[140px]">
                            <MapPin className="w-3.5 h-3.5 mr-2 text-emerald-500" />
                            <SelectValue placeholder="Wilayah" />
                        </SelectTrigger>
                        <SelectContent className="bg-slate-900 border-slate-800 text-slate-200">
                            <SelectItem value="all">Semua Wilayah</SelectItem>
                            {data?.options.locations.kelurahan.map((loc: string) => (
                                <SelectItem key={loc} value={loc}>{loc}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>

                    <Select value={filters.package_id} onValueChange={(val) => setFilters({...filters, package_id: val || 'all', page: 1})}>
                        <SelectTrigger className="h-12 bg-slate-950/50 border-slate-800 rounded-xl text-slate-300 w-full min-w-[140px] hidden lg:flex">
                            <Globe className="w-3.5 h-3.5 mr-2 text-emerald-500" />
                            <SelectValue placeholder="Paket" />
                        </SelectTrigger>
                        <SelectContent className="bg-slate-900 border-slate-800 text-slate-200">
                            <SelectItem value="all">Semua Paket</SelectItem>
                            {data?.options.packages.map((pkg: any) => (
                                <SelectItem key={pkg.id} value={pkg.id.toString()}>{pkg.name}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>
            </div>

            {/* Table Section */}
            <div className="relative bg-slate-900/20 border border-slate-800/60 rounded-3xl overflow-hidden backdrop-blur-sm shadow-2xl">
                {isLoading && (
                    <div className="absolute inset-0 bg-slate-950/40 backdrop-blur-md z-10 flex items-center justify-center">
                        <div className="flex flex-col items-center space-y-4">
                            <Loader2 className="w-10 h-10 text-emerald-500 animate-spin" />
                            <p className="text-slate-400 font-bold text-sm animate-pulse tracking-widest uppercase">Sinkronisasi Database...</p>
                        </div>
                    </div>
                )}

                <Table>
                    <TableHeader className="bg-slate-800/30">
                        <TableRow className="border-slate-800 hover:bg-transparent">
                            <TableHead className="w-12 px-4 py-5 text-center text-slate-500 font-bold uppercase tracking-widest text-[10px]">#</TableHead>
                            <TableHead className="px-6 py-5 text-slate-500 font-bold uppercase tracking-widest text-[10px]">Data Pelanggan</TableHead>
                            <TableHead className="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Layanan & Paket</TableHead>
                            <TableHead className="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Status Akun</TableHead>
                            <TableHead className="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Lokasi / Alamat</TableHead>
                            <TableHead className="px-8 py-5 text-right text-slate-500 font-bold uppercase tracking-widest text-[10px]">Tindakan</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {!isLoading && data?.customers.data.map((customer: any, index: number) => (
                            <TableRow key={customer.id} className="border-slate-800/50 hover:bg-white/[0.02] transition-colors group">
                                <TableCell className="w-12 px-4 py-5 text-center font-mono text-[10px] text-slate-600 font-bold">
                                    {((filters.page - 1) * 10) + index + 1}
                                </TableCell>
                                <TableCell className="px-6 py-5">
                                    <div className="flex items-center space-x-4">
                                        <div className="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 font-black text-lg border border-emerald-500/20 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                            {customer.name.substring(0, 1).toUpperCase()}
                                        </div>
                                        <div>
                                            <Link href={`/dashboard/admin/customers/${customer.id}`} className="block text-sm font-bold text-slate-200 hover:text-emerald-400 transition-colors">
                                                {customer.name}
                                            </Link>
                                            <span className="text-[10px] font-mono text-slate-500 tracking-wider">CID: {customer.customer_id}</span>
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div className="space-y-1">
                                        <p className="text-xs font-bold text-slate-300">{customer.package?.name || '---'}</p>
                                        <p className="text-[10px] text-slate-500 uppercase font-medium">{customer.package?.speed_limit || 'UNLIMITED'}</p>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    {getStatusBadge(customer.status, customer.status_label)}
                                </TableCell>
                                <TableCell>
                                    <div className="flex items-start space-x-2">
                                        <MapPin className="w-3 h-3 text-slate-500 mt-0.5 shrink-0" />
                                        <p className="text-xs text-slate-400 leading-normal line-clamp-2 max-w-[200px]">{customer.address}</p>
                                    </div>
                                </TableCell>
                                <TableCell className="px-8 py-5 text-right">
                                    <div className="flex items-center justify-end space-x-2">
                                        <Link 
                                            href={`/dashboard/admin/customers/${customer.id}`}
                                            className={cn(buttonVariants({ variant: 'ghost', size: 'icon' }), "h-10 w-10 bg-blue-500/5 text-blue-400 hover:bg-blue-500/10 hover:text-blue-300 border border-blue-500/10 rounded-xl")}
                                        >
                                            <Eye className="h-4 w-4" />
                                        </Link>
                                        <Link 
                                            href={`/dashboard/admin/customers/${customer.id}/edit`}
                                            className={cn(buttonVariants({ variant: 'ghost', size: 'icon' }), "h-10 w-10 bg-slate-800/50 text-slate-400 hover:bg-slate-800 hover:text-white border border-slate-700/50 rounded-xl")}
                                        >
                                            <Edit2 className="h-4 w-4" />
                                        </Link>
                                    </div>
                                </TableCell>
                            </TableRow>
                        ))}

                        {!isLoading && data?.customers.data.length === 0 && (
                            <TableRow>
                                <TableCell colSpan={5} className="py-24 text-center">
                                    <div className="flex flex-col items-center justify-center opacity-30 grayscale grayscale-100">
                                        <Users className="w-16 h-16 text-slate-400 mb-4" />
                                        <p className="text-xl font-bold text-white mb-1">Database Tidak Ditemukan</p>
                                        <p className="text-sm text-slate-500">Gunakan filter atau pencarian lain.</p>
                                    </div>
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>

                {/* Footer / Pagination */}
                {!isLoading && data && data.customers.last_page > 1 && (
                    <div className="p-8 flex items-center justify-between border-t border-slate-800 bg-slate-800/10">
                        <div className="text-[10px] items-center space-x-1.5 hidden md:flex">
                            <span className="text-slate-500 font-bold uppercase tracking-widest">Menampilkan</span>
                            <span className="bg-slate-800 px-2 py-0.5 rounded-lg text-emerald-400 font-mono text-xs">{data.customers.data.length}</span>
                            <span className="text-slate-500 font-bold uppercase tracking-widest">DR</span>
                            <span className="text-white font-mono text-xs font-bold">{data.customers.total}</span>
                            <span className="text-slate-500 font-bold uppercase tracking-widest text-[9px] ml-2">Identitas Unik</span>
                        </div>
                        
                        <div className="flex items-center space-x-3">
                            <Button 
                                variant="outline" 
                                size="sm" 
                                className="h-10 bg-slate-900 border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl px-5 text-[10px] font-bold uppercase tracking-widest transition-all disabled:opacity-30"
                                disabled={filters.page === 1}
                                onClick={() => setFilters({...filters, page: filters.page - 1})}
                            >
                                <ChevronLeft className="h-4 w-4 mr-2" />
                                Kembali
                            </Button>

                            <div className="h-10 flex items-center justify-center space-x-2 px-6 rounded-xl bg-slate-950 border border-slate-800">
                                <span className="text-emerald-500 font-black text-sm">{filters.page}</span>
                                <div className="w-px h-3 bg-slate-800 mx-2" />
                                <span className="text-slate-600 font-bold text-xs uppercase tracking-widest">{data.customers.last_page}</span>
                            </div>

                            <Button 
                                variant="outline" 
                                size="sm" 
                                className="h-10 bg-slate-900 border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl px-5 text-[10px] font-bold uppercase tracking-widest transition-all disabled:opacity-30"
                                disabled={filters.page >= data.customers.last_page}
                                onClick={() => setFilters({...filters, page: filters.page + 1})}
                            >
                                Lanjut
                                <ChevronRight className="h-4 w-4 ml-2" />
                            </Button>
                        </div>
                    </div>
                )}
            </div>
        </DashboardPageShell>
    );
}
