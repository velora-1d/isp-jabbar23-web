"use client";

import { useState } from "react";
import { 
  useIpPools, 
  useIpAddresses,
  useCreateIpPool,
  useDeleteIpPool,
  useReleaseIp,
  IpPool
} from "@/hooks/use-ipam";
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
  Database, 
  Search, 
  Plus, 
  Trash2, 
  ExternalLink, 
  Info,
  ShieldCheck,
  Globe,
  Network
} from "lucide-react";
import { toast } from "sonner";
import { 
  Sheet, 
  SheetContent, 
  SheetHeader, 
  SheetTitle, 
  SheetTrigger 
} from "@/components/ui/sheet";

export default function IpamPage() {
  const [search, setSearch] = useState("");
  const [type, setType] = useState<string>("all");
  const [page, setPage] = useState(1);
  const [selectedPool, setSelectedPool] = useState<IpPool | null>(null);
  const [isCreateOpen, setIsCreateOpen] = useState(false);

  // Queries
  const { data: poolsData, isLoading } = useIpPools({ search, type: type === "all" ? undefined : type || undefined, page });
  const createMutation = useCreateIpPool();
  const deleteMutation = useDeleteIpPool();
  const releaseMutation = useReleaseIp();

  // Form State
  const [newPool, setNewPool] = useState({
    name: "",
    network: "",
    prefix: "24",
    type: "private",
    description: ""
  });

  const handleCreate = async () => {
    try {
      await createMutation.mutateAsync({
        ...newPool,
        prefix: parseInt(newPool.prefix)
      });
      toast.success("IP Pool berhasil dibuat.");
      setIsCreateOpen(false);
      setNewPool({ name: "", network: "", prefix: "24", type: "private", description: "" });
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Gagal membuat pool");
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm("Hapus pool ini? Semua IP Address di dalamnya akan ikut terhapus.")) return;
    try {
      await deleteMutation.mutateAsync(id);
      toast.success("Pool dihapus.");
    } catch (e: any) {
      toast.error(e.response?.data?.message || "Gagal menghapus pool");
    }
  };

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">IP Address Management</h1>
          <p className="text-muted-foreground text-sm">Kelola IP Pool, alokasi IP pelanggan, dan segmentasi jaringan.</p>
        </div>

        <Dialog open={isCreateOpen} onOpenChange={setIsCreateOpen}>
          <DialogTrigger>
            <Button className="bg-blue-600">
              <Plus className="w-4 h-4 mr-2" />
              Tambah Pool
            </Button>
          </DialogTrigger>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Buat IP Pool Baru</DialogTitle>
            </DialogHeader>
            <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                    <Label>Nama Pool</Label>
                    <Input value={newPool.name} onChange={e => setNewPool({...newPool, name: e.target.value})} placeholder="cth: Pool Pelanggan Pusat" />
                </div>
                <div className="flex gap-4">
                    <div className="grid gap-2 flex-1">
                        <Label>Network Address</Label>
                        <Input value={newPool.network} onChange={e => setNewPool({...newPool, network: e.target.value})} placeholder="10.10.10.0" />
                    </div>
                    <div className="grid gap-2 w-24">
                        <Label>Prefix</Label>
                        <Input type="number" value={newPool.prefix} onChange={e => setNewPool({...newPool, prefix: e.target.value})} placeholder="24" />
                    </div>
                </div>
                <div className="grid gap-2">
                    <Label>Tipe Jaringan</Label>
                    <Select value={newPool.type} onValueChange={val => setNewPool({...newPool, type: val ?? "private"})}>
                        <SelectTrigger>
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="private">Private (LAN)</SelectItem>
                            <SelectItem value="public">Public</SelectItem>
                            <SelectItem value="cgnat">CGNAT</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div className="grid gap-2">
                    <Label>Deskripsi</Label>
                    <Input value={newPool.description} onChange={e => setNewPool({...newPool, description: e.target.value})} />
                </div>
            </div>
            <DialogFooter>
                <Button className="w-full bg-blue-600" onClick={handleCreate} disabled={createMutation.isPending}>
                    {createMutation.isPending ? "Sedang Membuat..." : "Simpan Pool"}
                </Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid gap-4 md:grid-cols-4">
        <Card className="border-none shadow-sm bg-blue-50/50 dark:bg-blue-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-blue-600 dark:text-blue-400 font-medium">Total Pool</CardDescription>
            <CardTitle className="text-2xl">{poolsData?.stats.total_pools || 0}</CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-slate-50/50 dark:bg-slate-900/50">
          <CardHeader className="pb-2">
            <CardDescription className="font-medium">Total IP</CardDescription>
            <CardTitle className="text-2xl">{poolsData?.stats.total_ips || 0}</CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-emerald-50/50 dark:bg-emerald-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-emerald-600 dark:text-emerald-400 font-medium">Available</CardDescription>
            <CardTitle className="text-2xl">{poolsData?.stats.total_available || 0}</CardTitle>
          </CardHeader>
        </Card>
        <Card className="border-none shadow-sm bg-orange-50/50 dark:bg-orange-950/20">
          <CardHeader className="pb-2">
            <CardDescription className="text-orange-600 dark:text-orange-400 font-medium">Allocated</CardDescription>
            <CardTitle className="text-2xl">{poolsData?.stats.total_allocated || 0}</CardTitle>
          </CardHeader>
        </Card>
      </div>

      <Card className="border-none shadow-md">
        <CardHeader className="flex flex-row items-center justify-between">
          <div className="relative w-full max-w-sm">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <Input 
                placeholder="Cari pool..." 
                className="pl-9 h-9" 
                value={search}
                onChange={e => setSearch(e.target.value)}
            />
          </div>
          <div className="flex items-center gap-2">
            <Select value={type} onValueChange={(v) => setType(v ?? "all")}>
                <SelectTrigger className="w-[150px] h-9">
                    <SelectValue placeholder="Filter Tipe" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">Semua Tipe</SelectItem>
                    <SelectItem value="private">Private</SelectItem>
                    <SelectItem value="public">Public</SelectItem>
                    <SelectItem value="cgnat">CGNAT</SelectItem>
                </SelectContent>
            </Select>
          </div>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Nama Pool</TableHead>
                <TableHead>Network</TableHead>
                <TableHead>Tipe</TableHead>
                <TableHead>Usage</TableHead>
                <TableHead>Status IP</TableHead>
                <TableHead className="text-right">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {isLoading ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10">Memuat data...</TableCell></TableRow>
              ) : poolsData?.pools.data.length === 0 ? (
                <TableRow><TableCell colSpan={6} className="text-center py-10 text-muted-foreground">Belum ada IP Pool.</TableCell></TableRow>
              ) : (
                poolsData?.pools.data.map((pool: IpPool) => {
                  const usagePercent = Math.round((pool.allocated_ips / pool.total_ips) * 100) || 0;
                  return (
                    <TableRow key={pool.id}>
                      <TableCell>
                        <div className="flex flex-col">
                          <span className="font-bold">{pool.name}</span>
                          <span className="text-[10px] text-muted-foreground">{pool.description || '-'}</span>
                        </div>
                      </TableCell>
                      <TableCell className="font-mono">
                        <div className="flex items-center gap-1">
                          <Network className="w-3 h-3 text-slate-400" />
                          {pool.network}/{pool.prefix}
                        </div>
                      </TableCell>
                      <TableCell>
                        {pool.type === 'public' ? (
                          <Badge variant="outline" className="text-blue-600 bg-blue-50 border-blue-200 gap-1">
                            <Globe className="w-3 h-3" /> Public
                          </Badge>
                        ) : pool.type === 'cgnat' ? (
                          <Badge variant="outline" className="text-orange-600 bg-orange-50 border-orange-200 gap-1">
                            <ShieldCheck className="w-3 h-3" /> CGNAT
                          </Badge>
                        ) : (
                          <Badge variant="secondary" className="gap-1">
                            <Database className="w-3 h-3" /> Private
                          </Badge>
                        )}
                      </TableCell>
                      <TableCell>
                        <div className="flex flex-col gap-1 w-24">
                          <div className="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div 
                                className={`h-full transition-all ${usagePercent > 90 ? 'bg-red-500' : usagePercent > 70 ? 'bg-orange-500' : 'bg-blue-600'}`} 
                                style={{ width: `${usagePercent}%` }} 
                            />
                          </div>
                          <span className="text-[10px] text-muted-foreground">{usagePercent}% Terpakai</span>
                        </div>
                      </TableCell>
                      <TableCell>
                         <div className="flex gap-2 text-xs">
                            <span className="text-emerald-600 font-medium">{pool.available_ips} Avail</span>
                            <span className="text-slate-400">/</span>
                            <span className="text-blue-600 font-medium">{pool.allocated_ips} Alloc</span>
                         </div>
                      </TableCell>
                      <TableCell className="text-right">
                        <div className="flex items-center justify-end gap-2">
                           <Sheet>
                               <SheetTrigger>
                                   <Button variant="ghost" size="icon" className="h-8 w-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50" onClick={() => setSelectedPool(pool)}>
                                       <ExternalLink className="w-4 h-4" />
                                   </Button>
                               </SheetTrigger>
                               <PoolDetails pool={pool} />
                           </Sheet>
                           <Button 
                                variant="ghost" 
                                size="icon" 
                                className="h-8 w-8 text-red-500 hover:text-red-700 hover:bg-red-50"
                                onClick={() => handleDelete(pool.id)}
                            >
                                <Trash2 className="w-4 h-4" />
                           </Button>
                        </div>
                      </TableCell>
                    </TableRow>
                  );
                })
              )}
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  );
}

function PoolDetails({ pool }: { pool: IpPool }) {
    const [page, setPage] = useState(1);
    const { data: addresses, isLoading } = useIpAddresses(pool.id, { page });
    const releaseMutation = useReleaseIp();

    const handleRelease = async (id: number) => {
        if (!confirm("Lepas alokasi IP ini?")) return;
        try {
            await releaseMutation.mutateAsync(id);
            toast.success("IP berhasil dilepas.");
        } catch (e) {
            toast.error("Gagal melepas IP.");
        }
    };

    return (
        <SheetContent className="sm:max-w-xl">
            <SheetHeader>
                <SheetTitle className="flex items-center gap-2">
                    <Network className="w-5 h-5 text-blue-600" />
                    Detail Pool: {pool.name}
                </SheetTitle>
                <CardDescription>{pool.network}/{pool.prefix} ({pool.type.toUpperCase()})</CardDescription>
            </SheetHeader>

            <div className="py-6 space-y-4">
                <div className="relative">
                    <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                    <Input placeholder="Cari IP..." className="pl-9" />
                </div>

                <div className="rounded-md border">
                    <Table>
                        <TableHeader className="bg-slate-50 dark:bg-slate-900 border-b">
                            <TableRow>
                                <TableHead>IP Address</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Owner</TableHead>
                                <TableHead className="text-right">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {isLoading ? (
                                <TableRow><TableCell colSpan={4} className="text-center py-10">Memuat...</TableCell></TableRow>
                            ) : addresses?.data.map((addr: any) => (
                                <TableRow key={addr.id}>
                                    <TableCell className="font-mono font-medium">{addr.address}</TableCell>
                                    <TableCell>
                                        {addr.status === 'allocated' ? (
                                            <Badge className="bg-blue-600">Allocated</Badge>
                                        ) : (
                                            <Badge variant="outline" className="text-emerald-600 border-emerald-200">Available</Badge>
                                        )}
                                    </TableCell>
                                    <TableCell className="text-xs">
                                        {addr.customer ? (
                                            <div className="flex flex-col">
                                                <span className="font-bold">{addr.customer.name}</span>
                                                <span className="text-muted-foreground">{addr.customer.identifier}</span>
                                            </div>
                                        ) : '-'}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {addr.status === 'allocated' && (
                                            <Button 
                                                variant="ghost" 
                                                size="sm" 
                                                className="text-orange-600 hover:text-orange-700 hover:bg-orange-50 h-7"
                                                onClick={() => handleRelease(addr.id)}
                                            >
                                                Release
                                            </Button>
                                        )}
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </div>
                
                {addresses && (
                    <div className="flex items-center justify-between pt-2">
                        <span className="text-[10px] text-muted-foreground">Page {addresses.current_page} of {addresses.last_page}</span>
                        <div className="flex gap-2">
                            <Button variant="outline" size="sm" className="h-8" disabled={page === 1} onClick={() => setPage(p => p - 1)}>Prev</Button>
                            <Button variant="outline" size="sm" className="h-8" disabled={page === addresses.last_page} onClick={() => setPage(p => p + 1)}>Next</Button>
                        </div>
                    </div>
                )}
            </div>
        </SheetContent>
    );
}
