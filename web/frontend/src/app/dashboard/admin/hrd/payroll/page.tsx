"use client";

import { useState } from "react";
import { 
  usePayrolls, 
  useCreatePayroll, 
  useUpdatePayroll,
  useApprovePayroll,
  useMarkPayrollPaid,
  useDeletePayroll,
  Payroll
} from "@/hooks/use-payroll";
import { useUsers } from "@/hooks/use-users"; // Assuming this exists or using useCustomers as base
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
  Calculator, 
  Plus, 
  Search, 
  Calendar,
  CheckCircle2, 
  Clock,
  Wallet,
  Users,
  DollarSign,
  MoreVertical,
  Trash2,
  FileText
} from "lucide-react";
import { 
  DropdownMenu, 
  DropdownMenuContent, 
  DropdownMenuItem, 
  DropdownMenuTrigger 
} from "@/components/ui/dropdown-menu";
import { toast } from "sonner";
import { format } from "date-fns";
import { id } from "date-fns/locale";

export default function PayrollPage() {
  const [period, setPeriod] = useState(format(new Date(), "yyyy-MM"));
  const [search, setSearch] = useState("");
  const [status, setStatus] = useState<string>("all");
  const [isCreateOpen, setIsCreateOpen] = useState(false);

  // Queries
  const { data: payrollsData, isLoading } = usePayrolls({ period, search, status: status === "all" ? undefined : status || undefined });
  const { data: employees } = useUsers(); // Need to ensure this provides employees
  
  const createMutation = useCreatePayroll();
  const approveMutation = useApprovePayroll();
  const payMutation = useMarkPayrollPaid();
  const deleteMutation = useDeletePayroll();

  // Form State
  const [formData, setFormData] = useState({
    user_id: "",
    period: period,
    basic_salary: "0",
    allowances: "0",
    overtime: "0",
    bonus: "0",
    deductions: "0",
    tax: "0",
    notes: ""
  });

  const handleCreate = async () => {
    try {
      await createMutation.mutateAsync(formData);
      toast.success("Payroll berhasil di-generate.");
      setIsCreateOpen(false);
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Gagal generate payroll");
    }
  };

  const statusBadge = (status: string) => {
    switch (status) {
      case 'draft': return <Badge variant="outline" className="text-orange-600 border-orange-200 bg-orange-50">Draft</Badge>;
      case 'approved': return <Badge className="bg-emerald-500 hover:bg-emerald-600">Approved</Badge>;
      case 'paid': return <Badge className="bg-blue-600 hover:bg-blue-700">Paid</Badge>;
      default: return <Badge>{status}</Badge>;
    }
  };

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Employee Payroll</h1>
          <p className="text-muted-foreground text-sm">Kelola penggajian bulanan, tunjangan, dan pajak karyawan.</p>
        </div>

        <div className="flex items-center gap-3">
            <Input 
                type="month" 
                value={period} 
                onChange={e => setPeriod(e.target.value)}
                className="w-[180px] shadow-sm"
            />
            <Dialog open={isCreateOpen} onOpenChange={setIsCreateOpen}>
                <DialogTrigger>
                    <Button className="bg-blue-600 hover:bg-blue-700 shadow-lg">
                        <Plus className="w-4 h-4 mr-2" />
                        Generate Payroll
                    </Button>
                </DialogTrigger>
                <DialogContent className="sm:max-w-[500px]">
                    <DialogHeader>
                        <DialogTitle>Generate Payroll Karyawan</DialogTitle>
                    </DialogHeader>
                    <div className="grid grid-cols-2 gap-4 py-4">
                        <div className="col-span-2 grid gap-2">
                            <Label>Karyawan</Label>
                            <Select value={formData.user_id} onValueChange={val => setFormData({...formData, user_id: val ?? ""}) /* fixed: force TSC */}>
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
                            <Label>Gaji Pokok</Label>
                            <Input type="number" value={formData.basic_salary} onChange={e => setFormData({...formData, basic_salary: e.target.value})} />
                        </div>
                        <div className="grid gap-2">
                            <Label>Tunjangan</Label>
                            <Input type="number" value={formData.allowances} onChange={e => setFormData({...formData, allowances: e.target.value})} />
                        </div>
                        <div className="grid gap-2">
                            <Label>Lembur (Overtime)</Label>
                            <Input type="number" value={formData.overtime} onChange={e => setFormData({...formData, overtime: e.target.value})} />
                        </div>
                        <div className="grid gap-2">
                            <Label>Bonus</Label>
                            <Input type="number" value={formData.bonus} onChange={e => setFormData({...formData, bonus: e.target.value})} />
                        </div>
                        <div className="grid gap-2">
                            <Label>Potongan</Label>
                            <Input type="number" value={formData.deductions} onChange={e => setFormData({...formData, deductions: e.target.value})} />
                        </div>
                        <div className="grid gap-2">
                            <Label>Pajak (PPh)</Label>
                            <Input type="number" value={formData.tax} onChange={e => setFormData({...formData, tax: e.target.value})} />
                        </div>
                        <div className="col-span-2 grid gap-2">
                            <Label>Catatan</Label>
                            <Input value={formData.notes} onChange={e => setFormData({...formData, notes: e.target.value})} placeholder="Catatan tambahan (opsional)" />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button className="w-full bg-blue-600" onClick={handleCreate} disabled={createMutation.isPending}>
                            {createMutation.isPending ? "Sedang Memproses..." : "Konfirmasi Generate"}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
      </div>

      <div className="grid gap-4 md:grid-cols-4">
        <Card className="border-none shadow-sm bg-blue-50/50 dark:bg-blue-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-blue-600 font-medium">Total Employees</CardDescription>
            <CardTitle className="text-2xl flex items-center gap-2">
                <Users className="w-5 h-5" />
                {payrollsData?.stats.total || 0}
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-orange-50/50 dark:bg-orange-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-orange-600 font-medium">Draft/Pending</CardDescription>
            <CardTitle className="text-2xl flex items-center gap-2">
                <Clock className="w-5 h-5" />
                {payrollsData?.stats.draft || 0}
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-emerald-50/50 dark:bg-emerald-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-emerald-600 font-medium">Total Paid</CardDescription>
            <CardTitle className="text-2xl">
                {payrollsData?.stats.paid || 0}
            </CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-slate-100/50 dark:bg-slate-900/50">
          <CardHeader className="pb-2">
            <CardDescription className="font-medium">Total Payout ({period})</CardDescription>
            <CardTitle className="text-2xl flex items-center gap-2">
                <Wallet className="w-5 h-5 text-blue-600" />
                Rp {(payrollsData?.stats.total_amount || 0).toLocaleString('id-ID')}
            </CardTitle>
          </CardHeader>
        </Card>
      </div>

      <Card className="border-none shadow-md overflow-hidden">
        <CardHeader className="flex flex-row items-center justify-between border-b pb-4">
          <div className="relative w-full max-w-sm">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <Input 
                placeholder="Cari nama karyawan..." 
                className="pl-9 h-9" 
                value={search}
                onChange={e => setSearch(e.target.value)}
            />
          </div>
          <div className="flex items-center gap-2">
             <Select value={status} onValueChange={(v) => setStatus(v ?? "all")}>
                <SelectTrigger className="w-[140px] h-9">
                    <SelectValue placeholder="Status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">Semua Status</SelectItem>
                    <SelectItem value="draft">Draft</SelectItem>
                    <SelectItem value="approved">Approved</SelectItem>
                    <SelectItem value="paid">Paid</SelectItem>
                </SelectContent>
            </Select>
          </div>
        </CardHeader>
        <CardContent className="p-0">
          <Table>
            <TableHeader className="bg-slate-50/50 dark:bg-slate-900/50">
              <TableRow>
                <TableHead className="pl-6 py-4">Karyawan</TableHead>
                <TableHead>Presensi</TableHead>
                <TableHead>Gaji Pokok</TableHead>
                <TableHead>Take Home Pay</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right pr-6">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {isLoading ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10">Memuat data payroll...</TableCell></TableRow>
              ) : payrollsData?.payrolls.data.length === 0 ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10 text-muted-foreground">Belum ada data payroll untuk periode ini.</TableCell></TableRow>
              ) : (
                payrollsData?.payrolls.data.map((p: Payroll) => (
                  <TableRow key={p.id}>
                    <TableCell className="pl-6">
                        <div className="flex flex-col">
                            <span className="font-bold text-sm text-slate-900 dark:text-slate-100">{p.user.name}</span>
                            <span className="text-[10px] text-muted-foreground uppercase">{p.user.email}</span>
                        </div>
                    </TableCell>
                    <TableCell>
                        <div className="flex items-center gap-1.5 text-xs">
                            <Badge variant="secondary" className="px-1.5 py-0 h-5 text-[10px] bg-emerald-50 text-emerald-700 border-none">{p.present_days}H</Badge>
                            <Badge variant="secondary" className="px-1.5 py-0 h-5 text-[10px] bg-red-50 text-red-700 border-none">{p.absent_days}A</Badge>
                            <Badge variant="secondary" className="px-1.5 py-0 h-5 text-[10px] bg-orange-50 text-orange-700 border-none">{p.late_days}T</Badge>
                        </div>
                    </TableCell>
                    <TableCell className="text-sm font-medium">Rp {p.basic_salary.toLocaleString('id-ID')}</TableCell>
                    <TableCell className="text-sm font-bold text-blue-600">Rp {p.net_salary.toLocaleString('id-ID')}</TableCell>
                    <TableCell>{statusBadge(p.status)}</TableCell>
                    <TableCell className="text-right pr-6">
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="ghost" size="icon" className="h-8 w-8">
                                    <MoreVertical className="w-4 h-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" className="w-[160px]">
                                <DropdownMenuItem onClick={() => toast.info("Fitur cetak slip gaji segera hadir")}>
                                    <FileText className="w-4 h-4 mr-2" /> Slip Gaji
                                </DropdownMenuItem>
                                {p.status === 'draft' && (
                                    <DropdownMenuItem className="text-emerald-600" onClick={() => approveMutation.mutate(p.id)}>
                                        <CheckCircle2 className="w-4 h-4 mr-2" /> Approve
                                    </DropdownMenuItem>
                                )}
                                {p.status === 'approved' && (
                                    <DropdownMenuItem className="text-blue-600" onClick={() => payMutation.mutate(p.id)}>
                                        <DollarSign className="w-4 h-4 mr-2" /> Mark as Paid
                                    </DropdownMenuItem>
                                )}
                                {p.status !== 'paid' && (
                                    <DropdownMenuItem className="text-red-600" onClick={() => {
                                        if(confirm("Hapus data payroll ini?")) deleteMutation.mutate(p.id);
                                    }}>
                                        <Trash2 className="w-4 h-4 mr-2" /> Delete
                                    </DropdownMenuItem>
                                )}
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </TableCell>
                  </TableRow>
                ))
              )}
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  );
}
