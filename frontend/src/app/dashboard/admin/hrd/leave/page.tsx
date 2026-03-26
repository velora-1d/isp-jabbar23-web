"use client";

import { useState } from "react";
import { 
  useLeaves, 
  useCreateLeave, 
  useApproveLeave, 
  useRejectLeave,
  useDeleteLeave,
  LeaveRequest
} from "@/hooks/use-leave";
import { useUsers } from "@/hooks/use-users";
import { 
  Card, 
  CardContent, 
  CardHeader, 
  CardTitle,
  CardDescription
} from "@/components/ui/card";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/components/ui/table";
import { 
  Dialog, 
  DialogContent, 
  DialogHeader, 
  DialogTitle, 
  DialogTrigger,
  DialogFooter
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from "@/components/ui/select";
import { 
  PlaneTakeoff, 
  Plus, 
  Search, 
  CheckCircle2, 
  XCircle,
  Clock,
  Calendar,
  AlertCircle,
  FileText,
  UserCheck
} from "lucide-react";
import { toast } from "sonner";
import { format } from "date-fns";
import { id } from "date-fns/locale";

export default function LeavePage() {
  const [search, setSearch] = useState("");
  const [status, setStatus] = useState<string | null>(null);
  const [type, setType] = useState<string | null>(null);
  const [isCreateOpen, setIsCreateOpen] = useState(false);
  const [isRejectOpen, setIsRejectOpen] = useState(false);
  const [selectedLeave, setSelectedLeave] = useState<number | null>(null);
  const [rejectionReason, setRejectionReason] = useState("");

  // Queries
  const { data: leavesData, isLoading } = useLeaves({ 
    search, 
    status: status === "all" ? undefined : status || undefined,
    type: type === "all" ? undefined : type || undefined
  });
  const { data: employees } = useUsers();
  
  const createMutation = useCreateLeave();
  const approveMutation = useApproveLeave();
  const rejectMutation = useRejectLeave();
  const deleteMutation = useDeleteLeave();

  // Form State
  const [formData, setFormData] = useState({
    user_id: "",
    type: "annual",
    start_date: "",
    end_date: "",
    reason: ""
  });

  const handleCreate = async () => {
    try {
      await createMutation.mutateAsync(formData);
      toast.success("Pengajuan cuti berhasil dikirim.");
      setIsCreateOpen(false);
      setFormData({ user_id: "", type: "annual", start_date: "", end_date: "", reason: "" });
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Gagal membuat pengajuan");
    }
  };

  const handleApprove = async (id: number) => {
    if(!confirm("Setujui pengajuan cuti ini?")) return;
    try {
      await approveMutation.mutateAsync(id);
      toast.success("Cuti disetujui.");
    } catch (e: any) {
      toast.error("Gagal menyetujui");
    }
  };

  const handleReject = async () => {
    if(!selectedLeave || !rejectionReason) return;
    try {
      await rejectMutation.mutateAsync({ id: selectedLeave, rejection_reason: rejectionReason });
      toast.success("Cuti ditolak.");
      setIsRejectOpen(false);
      setSelectedLeave(null);
      setRejectionReason("");
    } catch (e: any) {
      toast.error("Gagal menolak");
    }
  };

  const statusBadge = (status: string) => {
    switch (status) {
      case 'pending': return <Badge className="bg-orange-500/10 text-orange-600 border-none gap-1"><Clock className="w-3 h-3" /> Pending</Badge>;
      case 'approved': return <Badge className="bg-emerald-500 hover:bg-emerald-600 gap-1"><CheckCircle2 className="w-3 h-3" /> Approved</Badge>;
      case 'rejected': return <Badge variant="destructive" className="gap-1"><XCircle className="w-3 h-3" /> Rejected</Badge>;
      default: return <Badge variant="secondary">{status}</Badge>;
    }
  };

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Leave Management</h1>
          <p className="text-muted-foreground text-sm">Kelola pengajuan cuti, sakit, dan izin karyawan JABBAR23.</p>
        </div>

        <Dialog open={isCreateOpen} onOpenChange={setIsCreateOpen}>
          <DialogTrigger>
            <Button className="bg-indigo-600 shadow-lg hover:bg-indigo-700">
              <Plus className="w-4 h-4 mr-2" />
              Buat Pengajuan
            </Button>
          </DialogTrigger>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Form Pengajuan Cuti</DialogTitle>
            </DialogHeader>
            <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                    <Label>Karyawan</Label>
                    <Select value={formData.user_id} onValueChange={val => setFormData({...formData, user_id: val})}>
                        <SelectTrigger>
                            <SelectValue placeholder="Pilih Karyawan" />
                        </SelectTrigger>
                        <SelectContent>
                            {employees?.data?.map((u: any) => (
                                <SelectItem key={u.id} value={u.id.toString()}>{u.name}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>
                <div className="grid gap-2">
                    <Label>Tipe Izin</Label>
                    <Select value={formData.type} onValueChange={val => setFormData({...formData, type: val})}>
                        <SelectTrigger>
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="annual">Cuti Tahunan</SelectItem>
                            <SelectItem value="sick">Sakit</SelectItem>
                            <SelectItem value="personal">Izin Pribadi</SelectItem>
                            <SelectItem value="maternity">Melahirkan</SelectItem>
                            <SelectItem value="unpaid">Cuti Diluar Tanggungan</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div className="grid grid-cols-2 gap-4">
                    <div className="grid gap-2">
                        <Label>Tanggal Mulai</Label>
                        <Input type="date" value={formData.start_date} onChange={e => setFormData({...formData, start_date: e.target.value})} />
                    </div>
                    <div className="grid gap-2">
                        <Label>Tanggal Selesai</Label>
                        <Input type="date" value={formData.end_date} onChange={e => setFormData({...formData, end_date: e.target.value})} />
                    </div>
                </div>
                <div className="grid gap-2">
                    <Label>Keterangan / Alasan</Label>
                    <Input value={formData.reason} onChange={e => setFormData({...formData, reason: e.target.value})} placeholder="Misal: Acara Keluarga" />
                </div>
            </div>
            <DialogFooter>
                <Button className="w-full bg-indigo-600" onClick={handleCreate} disabled={createMutation.isPending}>
                    {createMutation.isPending ? "Sedang Mengirim..." : "Kirim Pengajuan"}
                </Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid gap-4 md:grid-cols-4">
        <Card className="border-none shadow-sm bg-indigo-50/50 dark:bg-indigo-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-indigo-600 font-medium font-mono text-[10px]">TOTAL REQUESTS</CardDescription>
            <CardTitle className="text-2xl flex items-center justify-between">
                <span>{leavesData?.stats.total || 0}</span>
                <PlaneTakeoff className="w-8 h-8 text-indigo-200" />
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-orange-50/50 dark:bg-orange-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-orange-600 font-medium font-mono text-[10px]">WAITING REVIEW</CardDescription>
            <CardTitle className="text-2xl flex items-center justify-between">
                <span>{leavesData?.stats.pending || 0}</span>
                <Clock className="w-8 h-8 text-orange-200" />
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-emerald-50/50 dark:bg-emerald-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-emerald-600 font-medium font-mono text-[10px]">APPROVED</CardDescription>
            <CardTitle className="text-2xl flex items-center justify-between">
                <span>{leavesData?.stats.approved || 0}</span>
                <CheckCircle2 className="w-8 h-8 text-emerald-200" />
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-red-50/50 dark:bg-red-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-red-600 font-medium font-mono text-[10px]">REJECTED</CardDescription>
            <CardTitle className="text-2xl flex items-center justify-between">
                <span>{leavesData?.stats.rejected || 0}</span>
                <XCircle className="w-8 h-8 text-red-200" />
            </CardTitle>
          </CardHeader>
        </Card>
      </div>

      <Card className="border-none shadow-md overflow-hidden">
        <CardHeader className="flex flex-row items-center justify-between bg-white dark:bg-slate-950 border-b pb-4">
          <div className="relative w-full max-w-sm">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <Input 
                placeholder="Cari karyawan atau alasan..." 
                className="pl-9 h-9" 
                value={search}
                onChange={e => setSearch(e.target.value)}
            />
          </div>
          <div className="flex items-center gap-3">
            <Select value={type || "all"} onValueChange={setType}>
                <SelectTrigger className="w-[140px] h-9">
                    <SelectValue placeholder="Tipe Izin" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">Semua Tipe</SelectItem>
                    <SelectItem value="annual">Cuti Tahunan</SelectItem>
                    <SelectItem value="sick">Sakit</SelectItem>
                    <SelectItem value="personal">Izin Pribadi</SelectItem>
                </SelectContent>
            </Select>
            <Select value={status || "all"} onValueChange={setStatus}>
                <SelectTrigger className="w-[140px] h-9">
                    <SelectValue placeholder="Status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">Semua Status</SelectItem>
                    <SelectItem value="pending">Pending</SelectItem>
                    <SelectItem value="approved">Approved</SelectItem>
                    <SelectItem value="rejected">Rejected</SelectItem>
                </SelectContent>
            </Select>
          </div>
        </CardHeader>
        <CardContent className="p-0">
          <Table>
            <TableHeader className="bg-slate-50 opacity-80">
              <TableRow>
                <TableHead className="pl-6 py-4">Karyawan</TableHead>
                <TableHead>Masa Cuti</TableHead>
                <TableHead>Durasi</TableHead>
                <TableHead>Tipe</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right pr-6">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {isLoading ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10">Memuat pengajuan...</TableCell></TableRow>
              ) : leavesData?.leaves.data.length === 0 ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10 text-muted-foreground">Tidak ada riwayat pengajuan cuti.</TableCell></TableRow>
              ) : (
                leavesData?.leaves.data.map((l: LeaveRequest) => (
                  <TableRow key={l.id} className="hover:bg-slate-50 transition-colors">
                    <TableCell className="pl-6">
                        <div className="flex flex-col">
                            <span className="font-bold text-sm">{l.user.name}</span>
                            <span className="text-[10px] text-muted-foreground italic line-clamp-1">{l.reason}</span>
                        </div>
                    </TableCell>
                    <TableCell className="text-xs font-medium">
                        <div className="flex items-center gap-2">
                            <Calendar className="w-3 h-3 text-indigo-500" />
                            {format(new Date(l.start_date), 'dd MMM')} - {format(new Date(l.end_date), 'dd MMM yyyy')}
                        </div>
                    </TableCell>
                    <TableCell>
                        <Badge variant="outline" className="font-bold border-indigo-100 text-indigo-700 bg-indigo-50/30">
                            {l.days} Hari
                        </Badge>
                    </TableCell>
                    <TableCell>
                        <span className="text-[10px] uppercase font-bold tracking-widest text-slate-500">{l.type}</span>
                    </TableCell>
                    <TableCell>{statusBadge(l.status)}</TableCell>
                    <TableCell className="text-right pr-6">
                        {l.status === 'pending' && (
                            <div className="flex items-center justify-end gap-2">
                                <Button 
                                    size="sm" 
                                    className="h-8 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border-none px-3"
                                    onClick={() => handleApprove(l.id)}
                                >
                                    <CheckCircle2 className="w-3 h-3 mr-1" /> Setujui
                                </Button>
                                <Button 
                                    size="sm" 
                                    variant="outline"
                                    className="h-8 text-red-500 border-red-100 hover:bg-red-50"
                                    onClick={() => {
                                        setSelectedLeave(l.id);
                                        setIsRejectOpen(true);
                                    }}
                                >
                                    <XCircle className="w-3 h-3 mr-1" /> Tolak
                                </Button>
                            </div>
                        )}
                        {l.status !== 'pending' && (
                            <div className="flex items-center justify-end gap-2 text-xs text-muted-foreground italic">
                                <UserCheck className="w-3 h-3" /> {l.approver?.name || 'System'}
                            </div>
                        )}
                    </TableCell>
                  </TableRow>
                ))
              )}
            </TableBody>
          </Table>
        </CardContent>
      </Card>

      {/* Reject Dialog */}
      <Dialog open={isRejectOpen} onOpenChange={setIsRejectOpen}>
          <DialogContent>
              <DialogHeader>
                  <DialogTitle>Tolak Pengajuan Cuti</DialogTitle>
                  <DialogDescription>Berikan alasan mengapa pengajuan ini ditolak agar karyawan mengetahui kekurangannya.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-2 py-4">
                  <Label>Alasan Penolakan</Label>
                  <Input 
                    value={rejectionReason} 
                    onChange={e => setRejectionReason(e.target.value)} 
                    placeholder="Misal: Kuota cuti bulanan sudah penuh" 
                  />
              </div>
              <DialogFooter>
                  <Button variant="outline" onClick={() => setIsRejectOpen(false)}>Batal</Button>
                  <Button variant="destructive" onClick={handleReject} disabled={!rejectionReason || rejectMutation.isPending}>
                      Konfirmasi Tolak
                  </Button>
              </DialogFooter>
          </DialogContent>
      </Dialog>
    </div>
  );
}
