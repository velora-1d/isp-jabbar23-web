'use client';

import { useState } from 'react';
import { useInfrastructure } from '@/hooks/use-infrastructure';
import { Olt, Odp } from '@/types/infrastructure';
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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Loader2, Plus, Pencil, Trash2, Search, Server, MapPin } from 'lucide-react';
import { Textarea } from '@/components/ui/textarea';

export default function InfrastructurePage() {
  const { 
    olts, createOlt, updateOlt, deleteOlt,
    odps, createOdp, updateOdp, deleteOdp 
  } = useInfrastructure();

  const [activeTab, setActiveTab] = useState('olts');
  const [searchTerm, setSearchTerm] = useState('');
  
  // Dialog States
  const [isOltDialogOpen, setIsOltDialogOpen] = useState(false);
  const [isOdpDialogOpen, setIsOdpDialogOpen] = useState(false);
  const [editingOlt, setEditingOlt] = useState<Olt | null>(null);
  const [editingOdp, setEditingOdp] = useState<Odp | null>(null);

  // Form States
  const [oltForm, setOltForm] = useState({
    name: '',
    ip_address: '',
    brand: '',
    type: 'EPON',
    total_pon_ports: 4,
    location: '',
    status: 'active',
  });

  const [odpForm, setOdpForm] = useState({
    name: '',
    address: '',
    latitude: 0,
    longitude: 0,
    total_ports: 8,
    description: '',
    status: 'active',
  });

  // OLT Handlers
  const handleOpenOltDialog = (olt: Olt | null = null) => {
    if (olt) {
      setEditingOlt(olt);
      setOltForm({
        name: olt.name,
        ip_address: olt.ip_address || '',
        brand: olt.brand || '',
        type: olt.type,
        total_pon_ports: olt.total_pon_ports,
        location: olt.location || '',
        status: olt.status,
      });
    } else {
      setEditingOlt(null);
      setOltForm({
        name: '',
        ip_address: '',
        brand: '',
        type: 'EPON',
        total_pon_ports: 4,
        location: '',
        status: 'active',
      });
    }
    setIsOltDialogOpen(true);
  };

  const handleOltSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (editingOlt) {
      await updateOlt.mutateAsync({ id: editingOlt.id, data: oltForm });
    } else {
      await createOlt.mutateAsync(oltForm);
    }
    setIsOltDialogOpen(false);
  };

  // ODP Handlers
  const handleOpenOdpDialog = (odp: Odp | null = null) => {
    if (odp) {
      setEditingOdp(odp);
      setOdpForm({
        name: odp.name,
        address: odp.address || '',
        latitude: odp.latitude || 0,
        longitude: odp.longitude || 0,
        total_ports: odp.total_ports,
        description: odp.description || '',
        status: odp.status,
      });
    } else {
      setEditingOdp(null);
      setOdpForm({
        name: '',
        address: '',
        latitude: 0,
        longitude: 0,
        total_ports: 8,
        description: '',
        status: 'active',
      });
    }
    setIsOdpDialogOpen(true);
  };

  const handleOdpSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (editingOdp) {
      await updateOdp.mutateAsync({ id: editingOdp.id, data: odpForm });
    } else {
      await createOdp.mutateAsync(odpForm);
    }
    setIsOdpDialogOpen(false);
  };

  const filteredOlts = olts.data?.filter(o => o.name.toLowerCase().includes(searchTerm.toLowerCase())) || [];
  const filteredOdps = odps.data?.filter(o => o.name.toLowerCase().includes(searchTerm.toLowerCase())) || [];

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Infrastruktur Jaringan</h1>
          <p className="text-muted-foreground"> Kelola perangkat fisik OLT dan ODP. </p>
        </div>
        <div className="flex gap-2">
            <Button onClick={() => activeTab === 'olts' ? handleOpenOltDialog() : handleOpenOdpDialog()} className="gap-2">
                <Plus className="h-4 w-4" /> Tambah {activeTab === 'olts' ? 'OLT' : 'ODP'}
            </Button>
        </div>
      </div>

      <Tabs defaultValue="olts" value={activeTab} onValueChange={setActiveTab} className="w-full">
        <TabsList className="grid w-[400px] grid-cols-2 mb-4 bg-zinc-900 border border-zinc-800">
          <TabsTrigger value="olts" className="flex items-center gap-2">
            <Server className="h-4 w-4" /> OLT
          </TabsTrigger>
          <TabsTrigger value="odps" className="flex items-center gap-2">
            <MapPin className="h-4 w-4" /> ODP
          </TabsTrigger>
        </TabsList>

        <Card className="glass border-white/10">
            <CardHeader>
                <div className="relative flex-1 max-w-md">
                    <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <input
                        placeholder={`Cari ${activeTab === 'olts' ? 'OLT' : 'ODP'}...`}
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        className="w-full pl-10 pr-4 py-2 bg-zinc-950/50 border border-zinc-800 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/50 outline-none"
                    />
                </div>
            </CardHeader>
            <CardContent>
                <TabsContent value="olts" className="m-0">
                    {olts.isLoading ? (
                        <div className="flex justify-center py-8"><Loader2 className="h-8 w-8 animate-spin text-primary" /></div>
                    ) : (
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nama OLT</TableHead>
                                    <TableHead>IP Address</TableHead>
                                    <TableHead>Tipe/Brand</TableHead>
                                    <TableHead>PON Ports</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead className="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredOlts.map((olt) => (
                                    <TableRow key={olt.id}>
                                        <TableCell className="font-medium">{olt.name}</TableCell>
                                        <TableCell>{olt.ip_address || '-'}</TableCell>
                                        <TableCell>{olt.type} / {olt.brand || '-'}</TableCell>
                                        <TableCell>{olt.total_pon_ports}</TableCell>
                                        <TableCell>
                                            <Badge variant={olt.status === 'active' ? 'default' : 'secondary'}>{olt.status}</Badge>
                                        </TableCell>
                                        <TableCell className="text-right flex justify-end gap-2">
                                            <Button variant="ghost" size="icon" onClick={() => handleOpenOltDialog(olt)}><Pencil className="h-4 w-4" /></Button>
                                            <Button variant="ghost" size="icon" className="text-destructive" onClick={() => { if(confirm('Hapus OLT?')) deleteOlt.mutate(olt.id) }}><Trash2 className="h-4 w-4" /></Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    )}
                </TabsContent>

                <TabsContent value="odps" className="m-0">
                    {odps.isLoading ? (
                        <div className="flex justify-center py-8"><Loader2 className="h-8 w-8 animate-spin text-primary" /></div>
                    ) : (
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nama ODP</TableHead>
                                    <TableHead>Alamat</TableHead>
                                    <TableHead>Ports</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead className="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredOdps.map((odp) => (
                                    <TableRow key={odp.id}>
                                        <TableCell className="font-medium">{odp.name}</TableCell>
                                        <TableCell className="max-w-xs truncate">{odp.address || '-'}</TableCell>
                                        <TableCell>{odp.total_ports}</TableCell>
                                        <TableCell>
                                            <Badge variant={odp.status === 'active' ? 'default' : 'secondary'}>{odp.status}</Badge>
                                        </TableCell>
                                        <TableCell className="text-right flex justify-end gap-2">
                                            <Button variant="ghost" size="icon" onClick={() => handleOpenOdpDialog(odp)}><Pencil className="h-4 w-4" /></Button>
                                            <Button variant="ghost" size="icon" className="text-destructive" onClick={() => { if(confirm('Hapus ODP?')) deleteOdp.mutate(odp.id) }}><Trash2 className="h-4 w-4" /></Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    )}
                </TabsContent>
            </CardContent>
        </Card>
      </Tabs>

      {/* OLT DIALOG */}
      <Dialog open={isOltDialogOpen} onOpenChange={setIsOltDialogOpen}>
        <DialogContent className="glass border-white/20">
            <form onSubmit={handleOltSubmit} className="space-y-4">
                <DialogHeader>
                    <DialogTitle>{editingOlt ? 'Edit OLT' : 'Tambah OLT'}</DialogTitle>
                </DialogHeader>
                <div className="grid gap-4 py-4">
                    <div className="grid gap-2">
                        <Label>Nama OLT</Label>
                        <Input value={oltForm.name} onChange={e => setOltForm({...oltForm, name: e.target.value})} required />
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        <div className="grid gap-2">
                            <Label>IP Address</Label>
                            <Input value={oltForm.ip_address} onChange={e => setOltForm({...oltForm, ip_address: e.target.value})} />
                        </div>
                        <div className="grid gap-2">
                            <Label>Brand</Label>
                            <Input value={oltForm.brand} onChange={e => setOltForm({...oltForm, brand: e.target.value})} />
                        </div>
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        <div className="grid gap-2">
                            <Label>Tipe (EPON/GPON)</Label>
                            <Input value={oltForm.type} onChange={e => setOltForm({...oltForm, type: e.target.value})} />
                        </div>
                        <div className="grid gap-2">
                            <Label>Total PON Ports</Label>
                            <Input type="number" value={oltForm.total_pon_ports} onChange={e => setOltForm({...oltForm, total_pon_ports: Number(e.target.value)})} />
                        </div>
                    </div>
                </div>
                <DialogFooter>
                    <Button type="submit">Simpan</Button>
                </DialogFooter>
            </form>
        </DialogContent>
      </Dialog>

      {/* ODP DIALOG */}
      <Dialog open={isOdpDialogOpen} onOpenChange={setIsOdpDialogOpen}>
        <DialogContent className="glass border-white/20">
            <form onSubmit={handleOdpSubmit} className="space-y-4">
                <DialogHeader>
                    <DialogTitle>{editingOdp ? 'Edit ODP' : 'Tambah ODP'}</DialogTitle>
                </DialogHeader>
                <div className="grid gap-4 py-4">
                    <div className="grid gap-2">
                        <Label>Nama ODP</Label>
                        <Input value={odpForm.name} onChange={e => setOdpForm({...odpForm, name: e.target.value})} required />
                    </div>
                    <div className="grid gap-2">
                        <Label>Alamat</Label>
                        <Input value={odpForm.address} onChange={e => setOdpForm({...odpForm, address: e.target.value})} />
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        <div className="grid gap-2">
                            <Label>Latitude</Label>
                            <Input type="number" step="any" value={odpForm.latitude} onChange={e => setOdpForm({...odpForm, latitude: Number(e.target.value)})} />
                        </div>
                        <div className="grid gap-2">
                            <Label>Longitude</Label>
                            <Input type="number" step="any" value={odpForm.longitude} onChange={e => setOdpForm({...odpForm, longitude: Number(e.target.value)})} />
                        </div>
                    </div>
                </div>
                <DialogFooter>
                    <Button type="submit">Simpan</Button>
                </DialogFooter>
            </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
