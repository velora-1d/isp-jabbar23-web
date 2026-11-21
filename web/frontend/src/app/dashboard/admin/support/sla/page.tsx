'use client';

import { 
    Shield, 
    Plus, 
    Clock, 
    AlertCircle,
    CheckCircle2,
    MoreVertical,
    Edit2,
    Trash2,
    History,
    Activity
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

import { useSlaPolicies, useDeleteSlaPolicy } from '@/hooks/use-support';

export default function SlaPage() {
    const { data: policiesData, isLoading } = useSlaPolicies();
    const deleteMutation = useDeleteSlaPolicy();

    const policies = policiesData?.data || [];

    const stats = {
        total: policies.length,
        active: policies.filter(p => p.is_active).length,
        critical: policies.filter(p => p.priority === 'critical').length
    };

    const priorityStyles = {
        low: 'text-zinc-400 bg-zinc-500/10 border-zinc-500/20',
        medium: 'text-blue-400 bg-blue-500/10 border-blue-500/20',
        high: 'text-amber-400 bg-amber-500/10 border-amber-500/20',
        critical: 'text-red-400 bg-red-500/10 border-red-500/20',
    };

    return (
        <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            {/* Header Section */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-3xl font-bold tracking-tight bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent">
                        SLA Management
                    </h1>
                    <p className="text-zinc-500 mt-1">
                        Atur standar waktu respon dan resolusi layanan (Service Level Agreement).
                    </p>
                </div>
                <div className="flex items-center gap-3">
                    <Button className="bg-blue-600 hover:bg-blue-500 text-white rounded-xl gap-2 shadow-lg shadow-blue-500/20">
                        <Plus className="h-4 w-4" />
                        Tambah Kebijakan
                    </Button>
                </div>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Card className="bg-zinc-900/50 border-zinc-800/50 relative overflow-hidden group">
                    <CardHeader className="pb-2">
                        <CardDescription className="text-zinc-500 text-xs font-semibold uppercase tracking-wider">Total Kebijakan</CardDescription>
                        <CardTitle className="text-3xl font-black text-white">{stats.total}</CardTitle>
                    </CardHeader>
                    <Shield className="absolute top-6 right-6 h-8 w-8 text-zinc-800 group-hover:text-blue-500/20 transition-colors" />
                </Card>

                <Card className="bg-zinc-900/50 border-zinc-800/50 relative overflow-hidden group">
                    <CardHeader className="pb-2">
                        <CardDescription className="text-zinc-500 text-xs font-semibold uppercase tracking-wider">Kebijakan Aktif</CardDescription>
                        <CardTitle className="text-3xl font-black text-emerald-400">{stats.active}</CardTitle>
                    </CardHeader>
                    <Activity className="absolute top-6 right-6 h-8 w-8 text-zinc-800 group-hover:text-emerald-500/20 transition-colors" />
                </Card>

                <Card className="bg-zinc-900/50 border-zinc-800/50 relative overflow-hidden group">
                    <CardHeader className="pb-2">
                        <CardDescription className="text-zinc-500 text-xs font-semibold uppercase tracking-wider">Prioritas Kritis</CardDescription>
                        <CardTitle className="text-3xl font-black text-red-500">{stats.critical}</CardTitle>
                    </CardHeader>
                    <AlertCircle className="absolute top-6 right-6 h-8 w-8 text-zinc-800 group-hover:text-red-500/20 transition-colors" />
                </Card>
            </div>

            {/* SLA Policies List */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {isLoading ? (
                    <div className="col-span-full text-center py-20 text-zinc-500">Memuat kebijakan...</div>
                ) : policies.length === 0 ? (
                    <div className="col-span-full text-center py-20 bg-zinc-900/20 border border-dashed border-zinc-800 rounded-3xl">
                        <Shield className="h-12 w-12 text-zinc-800 mx-auto mb-4" />
                        <h3 className="text-white font-medium">Belum ada kebijakan SLA</h3>
                        <p className="text-zinc-500 text-sm">Standarisasi waktu layanan Anda sekarang.</p>
                    </div>
                ) : (
                    policies.map((policy) => (
                        <Card key={policy.id} className="bg-zinc-900/30 border-zinc-800/50 hover:border-zinc-700 hover:bg-zinc-900/50 transition-all group">
                            <CardHeader className="flex flex-row items-start justify-between pb-2">
                                <div className="space-y-1">
                                    <div className="flex items-center gap-2">
                                        <CardTitle className="text-white group-hover:text-blue-400 transition-colors">{policy.name}</CardTitle>
                                        {!policy.is_active && (
                                            <Badge variant="outline" className="text-[10px] uppercase border-zinc-700 text-zinc-500">Non-Aktif</Badge>
                                        )}
                                    </div>
                                    <CardDescription className="text-zinc-500 line-clamp-1">{policy.description || 'Tidak ada deskripsi'}</CardDescription>
                                </div>
                                <DropdownMenu>
                                    <DropdownMenuTrigger asChild>
                                        <Button variant="ghost" size="icon" className="text-zinc-500 hover:text-white rounded-xl">
                                            <MoreVertical className="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end" className="bg-zinc-900 border-zinc-800 text-zinc-400">
                                        <DropdownMenuItem className="focus:bg-zinc-800 focus:text-white gap-2">
                                            <Edit2 className="h-4 w-4" /> Edit Policy
                                        </DropdownMenuItem>
                                        <DropdownMenuItem className="focus:bg-zinc-800 focus:text-white gap-2">
                                            <History className="h-4 w-4" /> Lihat Riwayat
                                        </DropdownMenuItem>
                                        <DropdownMenuItem 
                                            className="focus:bg-red-500/10 focus:text-red-400 gap-2"
                                            onClick={() => deleteMutation.mutate(policy.id)}
                                        >
                                            <Trash2 className="h-4 w-4" /> Hapus
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </CardHeader>
                            <CardContent>
                                <div className="grid grid-cols-2 gap-4 mt-2">
                                    <div className="bg-zinc-950/50 rounded-2xl p-4 border border-zinc-800/50 space-y-1">
                                        <p className="text-[10px] uppercase tracking-wider text-zinc-500 font-bold">Respon Pertama</p>
                                        <div className="flex items-center gap-2">
                                            <Clock className="h-4 w-4 text-blue-400" />
                                            <span className="text-xl font-bold text-white">{policy.first_response_hours} Jam</span>
                                        </div>
                                    </div>
                                    <div className="bg-zinc-950/50 rounded-2xl p-4 border border-zinc-800/50 space-y-1">
                                        <p className="text-[10px] uppercase tracking-wider text-zinc-500 font-bold">Target Resolusi</p>
                                        <div className="flex items-center gap-2">
                                            <CheckCircle2 className="h-4 w-4 text-emerald-400" />
                                            <span className="text-xl font-bold text-white">{policy.resolution_hours} Jam</span>
                                        </div>
                                    </div>
                                </div>
                                <div className="mt-4 flex items-center justify-between">
                                    <Badge className={`rounded-lg px-2 py-0.5 text-[10px] font-bold uppercase border ${priorityStyles[policy.priority]}`}>
                                        {policy.priority_label}
                                    </Badge>
                                    <span className="text-[10px] text-zinc-600 font-medium">Terakhir diupdate: {new Date(policy.updated_at).toLocaleDateString()}</span>
                                </div>
                            </CardContent>
                        </Card>
                    ))
                )}
            </div>
        </div>
    );
}
