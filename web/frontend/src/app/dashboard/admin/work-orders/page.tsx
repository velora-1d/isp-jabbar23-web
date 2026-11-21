'use client';

import { useState } from 'react';
import { useWorkOrders } from '@/hooks/use-work-orders';
import { WorkOrder } from '@/types/work-order';
import { Button } from '@/components/ui/button';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { 
  Loader2, 
  Plus, 
  ClipboardList, 
  Clock, 
  CheckCircle2, 
  XCircle,
  Wrench,
  User,
  MapPin,
  Calendar,
  Box
} from 'lucide-react';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { format } from 'date-fns';
import { id } from 'date-fns/locale';

export default function WorkOrderPage() {
    const { workOrders, updateStatus } = useWorkOrders();
    const [selectedWO, setSelectedWO] = useState<WorkOrder | null>(null);
    const [isDetailOpen, setIsDetailOpen] = useState(false);
    const [statusNotes, setStatusNotes] = useState('');

    const statusColors: Record<string, string> = {
        pending: 'bg-zinc-500',
        scheduled: 'bg-blue-500',
        on_way: 'bg-amber-500',
        in_progress: 'bg-indigo-500',
        completed: 'bg-green-500',
        cancelled: 'bg-red-500',
    };

    const typeLabels: Record<string, string> = {
        installation: 'Pasang Baru',
        repair: 'Perbaikan',
        dismantling: 'Bongkar Relokasi',
        survey: 'Survey',
        maintenance: 'Maintenance',
    };

    const handleUpdateStatus = async (newStatus: string) => {
        if (!selectedWO) return;
        await updateStatus.mutateAsync({
            id: selectedWO.id,
            status: newStatus,
            notes: statusNotes
        });
        setIsDetailOpen(false);
        setStatusNotes('');
    };

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h1 className="text-3xl font-bold tracking-tight">Work Orders (SPK)</h1>
                    <p className="text-muted-foreground"> Kelola perintah kerja teknisi lapangan. </p>
                </div>
                <Button className="gap-2">
                    <Plus className="h-4 w-4" /> Buat SPK Baru
                </Button>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                <Card className="glass border-white/10">
                    <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle className="text-sm font-medium">Pending</CardTitle>
                        <Clock className="h-4 w-4 text-zinc-400" />
                    </CardHeader>
                    <CardContent>
                        <div className="text-2xl font-bold">{workOrders.data?.data?.filter((w: any) => w.status === 'pending').length || 0}</div>
                    </CardContent>
                </Card>
                <Card className="glass border-white/10">
                    <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle className="text-sm font-medium">In Progress</CardTitle>
                        <Wrench className="h-4 w-4 text-indigo-400" />
                    </CardHeader>
                    <CardContent>
                        <div className="text-2xl font-bold text-indigo-400">{workOrders.data?.data?.filter((w: any) => w.status === 'in_progress').length || 0}</div>
                    </CardContent>
                </Card>
                <Card className="glass border-white/10">
                    <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle className="text-sm font-medium">Selesai (Bulan Ini)</CardTitle>
                        <CheckCircle2 className="h-4 w-4 text-green-400" />
                    </CardHeader>
                    <CardContent>
                        <div className="text-2xl font-bold text-green-400">{workOrders.data?.data?.filter((w: any) => w.status === 'completed').length || 0}</div>
                    </CardContent>
                </Card>
            </div>

            <Card className="glass border-white/10">
                <CardContent className="p-0">
                    {workOrders.isLoading ? (
                        <div className="flex justify-center py-8"><Loader2 className="h-8 w-8 animate-spin text-primary" /></div>
                    ) : (
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No. Tiket</TableHead>
                                    <TableHead>Tipe</TableHead>
                                    <TableHead>Pelanggan</TableHead>
                                    <TableHead>Teknisi</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Prioritas</TableHead>
                                    <TableHead className="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {workOrders.data?.data?.map((wo: WorkOrder) => (
                                    <TableRow key={wo.id}>
                                        <TableCell className="font-mono font-bold text-blue-400">{wo.ticket_number}</TableCell>
                                        <TableCell>{typeLabels[wo.type]}</TableCell>
                                        <TableCell>
                                            <div className="font-medium">{wo.customer?.name || 'N/A'}</div>
                                        </TableCell>
                                        <TableCell>
                                            <div className="flex items-center gap-2 text-xs">
                                                <User className="h-3 w-3" />
                                                {wo.technician?.name || 'Belum Ditugaskan'}
                                            </div>
                                        </TableCell>
                                        <TableCell>
                                            <Badge className={statusColors[wo.status]}>
                                                {wo.status.replace('_', ' ').toUpperCase()}
                                            </Badge>
                                        </TableCell>
                                        <TableCell>
                                            <Badge variant={wo.priority === 'critical' ? 'destructive' : 'outline'}>
                                                {wo.priority.toUpperCase()}
                                            </Badge>
                                        </TableCell>
                                        <TableCell className="text-right">
                                            <Button variant="ghost" size="sm" onClick={() => { setSelectedWO(wo); setIsDetailOpen(true); }}>
                                                Detail
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    )}
                </CardContent>
            </Card>

            {/* MODAL DETAIL & UPDATE STATUS */}
            <Dialog open={isDetailOpen} onOpenChange={setIsDetailOpen}>
                <DialogContent className="glass border-white/20 max-w-2xl">
                    {selectedWO && (
                        <div className="space-y-6">
                            <DialogHeader>
                                <DialogTitle className="flex items-center gap-2 text-2xl">
                                    <ClipboardList className="h-6 w-6 text-blue-400" />
                                    {selectedWO.ticket_number}
                                </DialogTitle>
                                <DialogDescription>
                                    Dibuat pada {format(new Date(selectedWO.created_at || new Date()), 'dd MMMM yyyy HH:mm', { locale: id })}
                                </DialogDescription>
                            </DialogHeader>

                            <div className="grid grid-cols-2 gap-6">
                                <div className="space-y-4 text-sm">
                                    <div className="flex items-center gap-3">
                                        <div className="p-2 bg-white/5 rounded-lg"><User className="h-4 w-4 text-zinc-400" /></div>
                                        <div>
                                            <p className="text-zinc-500 text-[10px] uppercase font-bold tracking-wider">Pelanggan</p>
                                            <p className="font-medium">{selectedWO.customer?.name}</p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-3">
                                        <div className="p-2 bg-white/5 rounded-lg"><Calendar className="h-4 w-4 text-zinc-400" /></div>
                                        <div>
                                            <p className="text-zinc-500 text-[10px] uppercase font-bold tracking-wider">Jadwal</p>
                                            <p className="font-medium">{selectedWO.scheduled_date ? format(new Date(selectedWO.scheduled_date), 'dd/MM/yyyy HH:mm') : '-'}</p>
                                        </div>
                                    </div>
                                </div>
                                <div className="space-y-4 text-sm">
                                    <div className="flex items-center gap-3">
                                        <div className="p-2 bg-white/5 rounded-lg"><MapPin className="h-4 w-4 text-zinc-400" /></div>
                                        <div>
                                            <p className="text-zinc-500 text-[10px] uppercase font-bold tracking-wider">Tipe Pekerjaan</p>
                                            <p className="font-medium">{typeLabels[selectedWO.type]}</p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-3">
                                        <div className="p-2 bg-white/5 rounded-lg"><Badge className={statusColors[selectedWO.status]}>{selectedWO.status.toUpperCase()}</Badge></div>
                                        <div>
                                            <p className="text-zinc-500 text-[10px] uppercase font-bold tracking-wider">Status Saat Ini</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="space-y-2">
                                <h4 className="text-sm font-bold flex items-center gap-2">
                                    <Box className="h-4 w-4" /> Material Digunakan
                                </h4>
                                <div className="bg-zinc-950/50 rounded-lg border border-white/5 overflow-hidden">
                                    <Table>
                                        <TableBody>
                                            {selectedWO.items?.map(item => (
                                                <TableRow key={item.id} className="border-white/5">
                                                    <TableCell className="text-xs">{item.inventory_item?.name}</TableCell>
                                                    <TableCell className="text-right text-xs font-bold">{item.quantity} {item.unit}</TableCell>
                                                </TableRow>
                                            ))}
                                            {(!selectedWO.items || selectedWO.items.length === 0) && (
                                                <TableRow><TableCell className="text-center text-zinc-500 text-xs py-4">Tidak ada material yang dicatat</TableCell></TableRow>
                                            )}
                                        </TableBody>
                                    </Table>
                                </div>
                            </div>

                            <div className="space-y-4 border-t border-white/5 pt-4">
                                <h4 className="text-sm font-bold">Update Progres</h4>
                                <div className="grid grid-cols-2 gap-4">
                                    <Button variant="outline" className="border-amber-500/50 text-amber-500 bg-amber-500/5" onClick={() => handleUpdateStatus('on_way')}>
                                        Mulai Jalan
                                    </Button>
                                    <Button variant="outline" className="border-indigo-500/50 text-indigo-500 bg-indigo-500/5" onClick={() => handleUpdateStatus('in_progress')}>
                                        Sedang Kerja
                                    </Button>
                                    <Button className="col-span-2 bg-green-600 hover:bg-green-700" onClick={() => handleUpdateStatus('completed')}>
                                        <CheckCircle2 className="mr-2 h-4 w-4" /> Selesaikan & Update Stok
                                    </Button>
                                </div>
                            </div>
                        </div>
                    )}
                </DialogContent>
            </Dialog>
        </div>
    );
}
