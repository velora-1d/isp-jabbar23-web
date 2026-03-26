'use client';

import { useQuery } from '@tanstack/react-query';
import api from '@/lib/axios';
import { useWorkOrders } from '@/hooks/use-work-orders';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Loader2, 
    Calendar, 
    MapPin, 
    User, 
    ClipboardList,
    CheckCircle2,
    Clock,
    Wrench,
    Navigation,
    Camera
} from 'lucide-react';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';
import { useState } from 'react';
import { toast } from 'sonner';

export default function TechnicianDashboard() {
    const { updateStatus } = useWorkOrders();
    const [isUpdating, setIsUpdating] = useState(false);

    const { data: jobs, isLoading, refetch } = useQuery({
        queryKey: ['technician', 'work-orders'],
        queryFn: async () => {
            const { data } = await api.get('/technician/work-orders');
            return data;
        }
    });

    const statusColors: Record<string, string> = {
        pending: 'bg-zinc-500',
        scheduled: 'bg-blue-500',
        on_way: 'bg-amber-500',
        in_progress: 'bg-indigo-500',
        completed: 'bg-green-500',
        cancelled: 'bg-red-500',
    };

    const handleStatusUpdate = async (jobId: string, newStatus: string) => {
        setIsUpdating(true);
        try {
            await api.put(`/technician/work-orders/${jobId}/status`, { status: newStatus });
            toast.success(`Status diperbarui ke ${newStatus}`);
            refetch();
        } catch (error) {
            toast.error('Gagal memperbarui status');
        } finally {
            setIsUpdating(false);
        }
    };

    if (isLoading) {
        return (
            <div className="flex items-center justify-center min-h-[60vh]">
                <Loader2 className="h-8 w-8 animate-spin text-primary" />
            </div>
        );
    }

    const activeJobs = jobs?.data?.filter((j: any) => j.status !== 'completed' && j.status !== 'cancelled') || [];

    return (
        <div className="space-y-6 max-w-md mx-auto pb-20">
            <div className="flex flex-col gap-1">
                <h1 className="text-2xl font-bold">Tugas Saya</h1>
                <p className="text-sm text-muted-foreground">Halo, Teknisi! Berikut jadwal kerja Anda hari ini.</p>
            </div>

            {activeJobs.length === 0 ? (
                <div className="flex flex-col items-center justify-center py-12 text-center space-y-4">
                    <div className="p-4 bg-zinc-900 rounded-full">
                        <CheckCircle2 className="h-12 w-12 text-zinc-500" />
                    </div>
                    <div>
                        <p className="font-bold">Tidak ada tugas aktif</p>
                        <p className="text-sm text-muted-foreground">Semua tiket telah selesai. Selamat istirahat!</p>
                    </div>
                </div>
            ) : (
                <div className="space-y-4">
                    {activeJobs.map((job: any) => (
                        <Card key={job.id} className="glass border-white/10 overflow-hidden">
                            <CardHeader className="p-4 bg-white/5 border-b border-white/5">
                                <div className="flex justify-between items-start">
                                    <div className="space-y-1">
                                        <div className="text-xs font-mono font-bold text-blue-400">{job.ticket_number}</div>
                                        <CardTitle className="text-lg">{job.customer?.name}</CardTitle>
                                    </div>
                                    <Badge className={statusColors[job.status]}>
                                        {job.status.toUpperCase()}
                                    </Badge>
                                </div>
                            </CardHeader>
                            <CardContent className="p-4 space-y-4">
                                <div className="space-y-2 text-sm">
                                    <div className="flex items-start gap-3">
                                        <MapPin className="h-4 w-4 text-zinc-500 mt-1" />
                                        <span className="text-zinc-300 leading-tight">{job.customer?.address || 'Alamat tidak tersedia'}</span>
                                    </div>
                                    <div className="flex items-center gap-3">
                                        <Calendar className="h-4 w-4 text-zinc-500" />
                                        <span className="text-zinc-300">
                                            {job.scheduled_date ? format(new Date(job.scheduled_date), 'dd MMMM - HH:mm', { locale: id }) : 'Belum dijadwalkan'}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-3">
                                        <Wrench className="h-4 w-4 text-zinc-500" />
                                        <span className="capitalize">{job.type} • {job.priority} Priority</span>
                                    </div>
                                </div>

                                <div className="pt-4 border-t border-white/5 grid grid-cols-1 gap-2">
                                    {job.status === 'scheduled' && (
                                        <Button className="w-full bg-amber-600 hover:bg-amber-700 h-12" onClick={() => handleStatusUpdate(job.id, 'on_way')} disabled={isUpdating}>
                                            <Navigation className="mr-2 h-5 w-5" /> Mulai Jalan
                                        </Button>
                                    )}
                                    {job.status === 'on_way' && (
                                        <Button className="w-full bg-indigo-600 hover:bg-indigo-700 h-12" onClick={() => handleStatusUpdate(job.id, 'in_progress')} disabled={isUpdating}>
                                            <Wrench className="mr-2 h-5 w-5" /> Tiba & Mulai Kerja
                                        </Button>
                                    )}
                                    {job.status === 'in_progress' && (
                                        <div className="space-y-2">
                                            <Button variant="outline" className="w-full h-12 border-white/10">
                                                <Camera className="mr-2 h-5 w-5" /> Upload Foto Bukti
                                            </Button>
                                            <Button className="w-full bg-green-600 hover:bg-green-700 h-12" onClick={() => handleStatusUpdate(job.id, 'completed')} disabled={isUpdating}>
                                                <CheckCircle2 className="mr-2 h-5 w-5" /> Selesaikan Tugas
                                            </Button>
                                        </div>
                                    )}
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            )}
        </div>
    );
}
