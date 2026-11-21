"use client";

import { useState } from "react";
import { 
  useHotspotVouchers, 
  useHotspotProfiles, 
  useGenerateVouchers 
} from "@/hooks/use-hotspot";
import { useNetworkRouters } from "@/hooks/use-network";
import { 
  Card, 
  CardContent, 
  CardHeader, 
  CardTitle,
  CardDescription
} from "@/components/ui/card";
import { 
  Tabs, 
  TabsContent, 
  TabsList, 
  TabsTrigger 
} from "@/components/ui/tabs";
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
  Wifi, 
  Ticket, 
  Plus, 
  Printer, 
  Search, 
  RefreshCw,
  MoreVertical,
  ArrowRight,
  Database
} from "lucide-react";
import { toast } from "sonner";
import { format } from "date-fns";
import { id } from "date-fns/locale";

export default function HotspotPage() {
  const [activeTab, setActiveTab] = useState("vouchers");
  const [routerId, setRouterId] = useState<string | null>(null);
  const [page, setPage] = useState(1);
  const [isGenerateModalOpen, setIsGenerateModalOpen] = useState(false);

  // Data Fetching
  const { data: routers } = useNetworkRouters();
  const { data: vouchers, isLoading: isLoadingVouchers } = useHotspotVouchers({ 
    router_id: routerId && routerId !== "all" ? parseInt(routerId) : undefined,
    page 
  });
  const { data: profiles, isLoading: isLoadingProfiles } = useHotspotProfiles();
  const generateMutation = useGenerateVouchers();

  // Form State for Generation
  const [genRouterId, setGenRouterId] = useState<string | null>(null);
  const [genProfileId, setGenProfileId] = useState<string | null>(null);
  const [genCount, setGenCount] = useState("10");

  const handleGenerate = async () => {
    if (!genRouterId || !genProfileId || !genCount) {
      toast.error("Mohon isi semua field.");
      return;
    }

    try {
      await generateMutation.mutateAsync({
        router_id: parseInt(genRouterId),
        hotspot_profile_id: parseInt(genProfileId),
        count: parseInt(genCount)
      });
      toast.success(`Berhasil membuat ${genCount} voucher.`);
      setIsGenerateModalOpen(false);
    } catch (error) {
      toast.error("Gagal membuat voucher.");
    }
  };

  const statusBadge = (status: string) => {
    switch (status) {
      case 'available': return <Badge className="bg-emerald-500/10 text-emerald-600 border-none">Tersedia</Badge>;
      case 'used': return <Badge variant="outline" className="text-blue-600">Terpakai</Badge>;
      case 'expired': return <Badge variant="destructive" className="bg-red-500/10 text-red-600 border-none">Expired</Badge>;
      default: return <Badge variant="secondary">{status}</Badge>;
    }
  };

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div className="flex flex-col gap-1">
          <h1 className="text-3xl font-bold tracking-tight">Manajemen Hotspot</h1>
          <p className="text-muted-foreground text-sm">
            Kelola voucher hotspot MikroTik dan profil paket layanan.
          </p>
        </div>

        <div className="flex items-center gap-2">
            <Dialog open={isGenerateModalOpen} onOpenChange={setIsGenerateModalOpen}>
                <DialogTrigger>
                    <Button className="bg-blue-600 hover:bg-blue-700 shadow-md">
                        <Ticket className="w-4 h-4 mr-2" />
                        Generate Bulks
                    </Button>
                </DialogTrigger>
                <DialogContent className="sm:max-w-[425px]">
                    <DialogHeader>
                        <DialogTitle>Generate Hotspot Vouchers</DialogTitle>
                    </DialogHeader>
                    <div className="grid gap-4 py-4">
                        <div className="grid gap-2">
                            <Label htmlFor="router">Pilih Router</Label>
                            <Select 
                                value={genRouterId} 
                                onValueChange={(val) => setGenRouterId(val)}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Router MikroTik" />
                                </SelectTrigger>
                                <SelectContent>
                                    {routers?.map((r) => (
                                        <SelectItem key={r.id} value={r.id.toString()}>{r.name}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="profile">Profil Paket</Label>
                            <Select 
                                value={genProfileId} 
                                onValueChange={(val) => setGenProfileId(val)}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Durasi Paket" />
                                </SelectTrigger>
                                <SelectContent>
                                    {profiles?.map((p) => (
                                        <SelectItem key={p.id} value={p.id.toString()}>{p.display_name} ({p.name})</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="count">Jumlah Voucher</Label>
                            <Input 
                                id="count" 
                                type="number" 
                                value={genCount} 
                                onChange={(e) => setGenCount(e.target.value)}
                                min="1"
                                max="500"
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button 
                            onClick={handleGenerate} 
                            disabled={generateMutation.isPending}
                            className="bg-blue-600 w-full"
                        >
                            {generateMutation.isPending ? "Generating..." : "Generate Sekarang"}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
      </div>

      <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
        <TabsList className="bg-slate-100 dark:bg-slate-900 mb-4">
          <TabsTrigger value="vouchers" className="gap-2">
            <Ticket className="w-4 h-4" />
            Voucher List
          </TabsTrigger>
          <TabsTrigger value="profiles" className="gap-2">
            <Wifi className="w-4 h-4" />
            Hotspot Profiles
          </TabsTrigger>
        </TabsList>

        <TabsContent value="vouchers">
          <Card className="border-none shadow-md">
            <CardHeader className="flex flex-row items-center justify-between">
              <div>
                <CardTitle>Voucher Hotspot</CardTitle>
                <CardDescription>Daftar voucher yang telah digenerate.</CardDescription>
              </div>
              <div className="flex items-center gap-2">
                <Select value={routerId} onValueChange={(val) => setRouterId(val)}>
                    <SelectTrigger className="w-[180px] h-9">
                        <SelectValue placeholder="Filter Router" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="all">Semua Router</SelectItem>
                        {routers?.map((r) => (
                            <SelectItem key={r.id} value={r.id.toString()}>{r.name}</SelectItem>
                        ))}
                    </SelectContent>
                </Select>
                <Button variant="outline" size="sm" onClick={() => toast.info("Fitur cetak sedang dikembangkan.")}>
                    <Printer className="w-4 h-4 mr-2" />
                    Cetak Terpilih
                </Button>
              </div>
            </CardHeader>
            <CardContent>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Username</TableHead>
                    <TableHead>Profil</TableHead>
                    <TableHead>Router</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Tgl Dibuat</TableHead>
                    <TableHead>Tgl Pakai</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {isLoadingVouchers ? (
                    <TableRow><TableCell colSpan={6} className="text-center py-10">Loading vouchers...</TableCell></TableRow>
                  ) : vouchers?.data.length === 0 ? (
                    <TableRow><TableCell colSpan={6} className="text-center py-10">Belum ada voucher.</TableCell></TableRow>
                  ) : (
                    vouchers?.data.map((v) => (
                      <TableRow key={v.id}>
                        <TableCell className="font-mono font-bold text-blue-600">{v.username}</TableCell>
                        <TableCell>{v.profile.display_name}</TableCell>
                        <TableCell>{v.router.name}</TableCell>
                        <TableCell>{statusBadge(v.status)}</TableCell>
                        <TableCell className="text-xs text-muted-foreground">{format(new Date(v.created_at), 'dd MMM yyyy', { locale: id })}</TableCell>
                        <TableCell className="text-xs">{v.used_at ? format(new Date(v.used_at), 'dd/MM/yyyy HH:mm') : '-'}</TableCell>
                      </TableRow>
                    ))
                  )}
                </TableBody>
              </Table>
              {vouchers && (
                <div className="flex items-center justify-between mt-4">
                    <p className="text-xs text-muted-foreground">Total: {vouchers.total} voucher</p>
                    <div className="flex gap-2">
                        <Button variant="outline" size="sm" disabled={page === 1} onClick={() => setPage(p => p - 1)}>Prev</Button>
                        <Button variant="outline" size="sm" disabled={page === vouchers.last_page} onClick={() => setPage(p => p + 1)}>Next</Button>
                    </div>
                </div>
              )}
            </CardContent>
          </Card>
        </TabsContent>

        <TabsContent value="profiles">
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
             {isLoadingProfiles ? (
                 <p className="p-10 text-center col-span-full font-medium text-slate-400">Loading profiles...</p>
             ) : (
                 profiles?.map((p) => (
                    <Card key={p.id} className="border-none shadow-md overflow-hidden hover:ring-2 hover:ring-blue-500/20 transition-all">
                        <CardHeader className="bg-slate-50 dark:bg-slate-900/50 flex flex-row items-center justify-between">
                            <CardTitle className="text-lg">{p.display_name}</CardTitle>
                            <Badge className="bg-blue-600">{p.name}</Badge>
                        </CardHeader>
                        <CardContent className="p-6">
                            <div className="space-y-4">
                                <div className="flex flex-col gap-1">
                                    <span className="text-3xl font-bold">Rp {p.price.toLocaleString('id-ID')}</span>
                                    <span className="text-xs text-muted-foreground italic">Validity: {p.validity_hours} Jam</span>
                                </div>
                                
                                <div className="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                                   <div className="flex flex-col">
                                       <span className="text-[10px] text-muted-foreground uppercase font-bold tracking-widest">Data Limit</span>
                                       <span className="font-medium">{p.data_limit_mb ? `${p.data_limit_mb} MB` : 'Unlimited'}</span>
                                   </div>
                                   <div className="flex flex-col">
                                       <span className="text-[10px] text-muted-foreground uppercase font-bold tracking-widest">Vouchers</span>
                                       <span className="font-medium">{p.vouchers_count || 0} Terbuat</span>
                                   </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                 ))
             )}
             
             {/* Add New Profile Card */}
             <Dialog>
                <DialogTrigger>
                    <Card className="border-2 border-dashed border-slate-200 dark:border-slate-800 bg-transparent shadow-none hover:bg-slate-50 dark:hover:bg-slate-900/50 cursor-pointer transition-colors group">
                        <CardContent className="h-full flex flex-col items-center justify-center p-10 py-16 gap-3">
                            <div className="p-3 bg-slate-100 dark:bg-slate-800 rounded-full group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <Plus className="w-6 h-6" />
                            </div>
                            <span className="font-medium text-slate-500">Tambah Profil Baru</span>
                        </CardContent>
                    </Card>
                </DialogTrigger>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Tambah Profil Hotspot Baru</DialogTitle>
                    </DialogHeader>
                    <div className="grid gap-4 py-4">
                        <div className="grid gap-2">
                            <Label>Nama Teknis (MikroTik)</Label>
                            <Input placeholder="cth: paket_1jam" id="name" />
                        </div>
                        <div className="grid gap-2">
                            <Label>Nama Tampilan</Label>
                            <Input placeholder="cth: Paket Hemat 1 Jam" id="display_name" />
                        </div>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="grid gap-2">
                                <Label>Harga (Rp)</Label>
                                <Input type="number" id="price" />
                            </div>
                            <div className="grid gap-2">
                                <Label>Masa Aktif (Jam)</Label>
                                <Input type="number" id="validity" />
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button className="bg-blue-600 w-full" onClick={() => {
                            toast.info("Fitur simpan profil akan segera aktif.");
                        }}>Simpan Profil</Button>
                    </DialogFooter>
                </DialogContent>
             </Dialog>
          </div>
        </TabsContent>
      </Tabs>
    </div>
  );
}
