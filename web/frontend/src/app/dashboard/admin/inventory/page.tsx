'use client';

import { useState } from 'react';
import { useInventory } from '@/hooks/use-inventory';
import { InventoryItem, InventoryTransaction } from '@/types/inventory';
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
import { 
  Loader2, 
  Plus, 
  ArrowUpRight, 
  ArrowDownRight, 
  Search, 
  Box, 
  AlertTriangle,
  History
} from 'lucide-react';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

export default function InventoryPage() {
    const { items, categories, locations, createItem, recordTransaction } = useInventory();
    const [searchTerm, setSearchTerm] = useState('');
    const [isItemDialogOpen, setIsItemDialogOpen] = useState(false);
    const [isTransactionDialogOpen, setIsTransactionDialogOpen] = useState(false);
    const [selectedItem, setSelectedItem] = useState<InventoryItem | null>(null);

    // Form States
    const [itemForm, setItemForm] = useState({
        name: '',
        category_id: '',
        sku: '',
        unit: 'pcs',
        min_stock_alert: 5,
        purchase_price: 0,
        selling_price: 0,
        description: '',
    });

    const [transactionForm, setTransactionForm] = useState({
        inventory_item_id: '',
        location_id: '',
        type: 'in',
        quantity: 1,
        notes: '',
        reference_no: '',
    });

    const handleTransactionOpen = (item: InventoryItem) => {
        setSelectedItem(item);
        setTransactionForm({
            ...transactionForm,
            inventory_item_id: item.id.toString(),
        });
        setIsTransactionDialogOpen(true);
    };

    const handleItemSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        await createItem.mutateAsync(itemForm);
        setIsItemDialogOpen(false);
    };

    const handleTransactionSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        await recordTransaction.mutateAsync(transactionForm);
        setIsTransactionDialogOpen(false);
    };

    const filteredItems = items.data?.filter(i => 
        i.name.toLowerCase().includes(searchTerm.toLowerCase()) || 
        i.sku?.toLowerCase().includes(searchTerm.toLowerCase())
    ) || [];

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h1 className="text-3xl font-bold tracking-tight">Gudang & Inventaris</h1>
                    <p className="text-muted-foreground"> Kelola stok perangkat dan material jaringan. </p>
                </div>
                <div className="flex gap-2">
                    <Button variant="outline" className="gap-2">
                        <History className="h-4 w-4" /> Riwayat
                    </Button>
                    <Button onClick={() => setIsItemDialogOpen(true)} className="gap-2">
                        <Plus className="h-4 w-4" /> Tambah Barang
                    </Button>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Card className="glass border-white/10">
                    <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle className="text-sm font-medium">Total Jenis Barang</CardTitle>
                        <Box className="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div className="text-2xl font-bold">{items.data?.length || 0}</div>
                        <p className="text-xs text-muted-foreground">Terdaftar di master data</p>
                    </CardContent>
                </Card>
                <Card className="glass border-white/10">
                    <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle className="text-sm font-medium">Stok Kritis</CardTitle>
                        <AlertTriangle className="h-4 w-4 text-amber-500" />
                    </CardHeader>
                    <CardContent>
                        <div className="text-2xl font-bold text-amber-500">
                            {items.data?.filter(i => (i.total_stock || 0) <= i.min_stock_alert).length || 0}
                        </div>
                        <p className="text-xs text-muted-foreground">Perlu pengadaan segera</p>
                    </CardContent>
                </Card>
            </div>

            <Card className="glass border-white/10">
                <CardHeader>
                    <div className="relative flex-1 max-w-md">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                        <input
                            placeholder="Cari SKU atau nama barang..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full pl-10 pr-4 py-2 bg-zinc-950/50 border border-zinc-800 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/50 outline-none"
                        />
                    </div>
                </CardHeader>
                <CardContent>
                    {items.isLoading ? (
                        <div className="flex justify-center py-8"><Loader2 className="h-8 w-8 animate-spin text-primary" /></div>
                    ) : (
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>SKU</TableHead>
                                    <TableHead>Nama Barang</TableHead>
                                    <TableHead>Kategori</TableHead>
                                    <TableHead>Total Stok</TableHead>
                                    <TableHead>Harga Beli</TableHead>
                                    <TableHead className="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredItems.map((item) => (
                                    <TableRow key={item.id}>
                                        <TableCell className="font-mono text-xs">{item.sku || '-'}</TableCell>
                                        <TableCell>
                                            <div className="font-medium">{item.name}</div>
                                            {(item.total_stock || 0) <= item.min_stock_alert && (
                                                <span className="text-[10px] text-amber-500 flex items-center gap-1">
                                                    <AlertTriangle className="h-2 w-2" /> Stok Rendah
                                                </span>
                                            )}
                                        </TableCell>
                                        <TableCell><Badge variant="outline">{item.category?.name || 'Uncategorized'}</Badge></TableCell>
                                        <TableCell>
                                            <span className={item.total_stock === 0 ? 'text-destructive font-bold' : ''}>
                                                {item.total_stock} {item.unit}
                                            </span>
                                        </TableCell>
                                        <TableCell>Rp {new Intl.NumberFormat('id-ID').format(item.purchase_price)}</TableCell>
                                        <TableCell className="text-right flex justify-end gap-2">
                                            <Button variant="ghost" size="sm" onClick={() => handleTransactionOpen(item)} className="gap-1 text-blue-400">
                                                <ArrowUpRight className="h-3 w-3" /> Mutasi
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    )}
                </CardContent>
            </Card>

            {/* DIALOG TAMBAH BARANG */}
            <Dialog open={isItemDialogOpen} onOpenChange={setIsItemDialogOpen}>
                <DialogContent className="glass border-white/20 max-w-lg">
                    <form onSubmit={handleItemSubmit} className="space-y-4">
                        <DialogHeader>
                            <DialogTitle>Tambah Barang Baru</DialogTitle>
                        </DialogHeader>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="col-span-2 space-y-2">
                                <Label>Nama Barang</Label>
                                <Input value={itemForm.name} onChange={e => setItemForm({...itemForm, name: e.target.value})} required placeholder="Contoh: Router ZTE F609" />
                            </div>
                            <div className="space-y-2">
                                <Label>Kategori</Label>
                                <Select value={(itemForm.category_id ?? '') as string} onValueChange={val => setItemForm({...itemForm, category_id: val ?? ''})}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih Kategori" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {categories.data?.map(c => (
                                            <SelectItem key={c.id} value={c.id.toString()}>{c.name}</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label>SKU / Part Number</Label>
                                <Input value={itemForm.sku ?? ''} onChange={e => setItemForm({...itemForm, sku: e.target.value})} />
                            </div>
                            <div className="space-y-2">
                                <Label>Satuan</Label>
                                <Input value={itemForm.unit} onChange={e => setItemForm({...itemForm, unit: e.target.value})} placeholder="pcs, meter, dll" />
                            </div>
                            <div className="space-y-2">
                                <Label>Min. Stock Alert</Label>
                                <Input type="number" value={itemForm.min_stock_alert} onChange={e => setItemForm({...itemForm, min_stock_alert: Number(e.target.value)})} />
                            </div>
                        </div>
                        <DialogFooter>
                            <Button type="submit">Simpan Master Barang</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            {/* DIALOG TRANSAKSI / MUTASI */}
            <Dialog open={isTransactionDialogOpen} onOpenChange={setIsTransactionDialogOpen}>
                <DialogContent className="glass border-white/20">
                    <form onSubmit={handleTransactionSubmit} className="space-y-4">
                        <DialogHeader>
                            <DialogTitle>Input Mutasi Stok</DialogTitle>
                            <DialogDescription>Mencatat stok masuk atau keluar untuk: <strong>{selectedItem?.name}</strong></DialogDescription>
                        </DialogHeader>
                        <div className="grid gap-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label>Tipe Transaksi</Label>
                                    <Select value={transactionForm.type} onValueChange={val => setTransactionForm({...transactionForm, type: val as any})}>
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="in">Barang Masuk (IN)</SelectItem>
                                            <SelectItem value="out">Barang Keluar (OUT)</SelectItem>
                                            <SelectItem value="adjustment">Penyesuaian (ADJ)</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div className="space-y-2">
                                    <Label>Lokasi / Gudang</Label>
                                    <Select value={(transactionForm.location_id ?? '') as string} onValueChange={val => setTransactionForm({...transactionForm, location_id: val ?? ''})}>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih Lokasi" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {locations.data?.map(l => (
                                                <SelectItem key={l.id} value={l.id.toString()}>{l.name}</SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label>Jumlah ({selectedItem?.unit})</Label>
                                    <Input type="number" step="any" value={transactionForm.quantity} onChange={e => setTransactionForm({...transactionForm, quantity: Number(e.target.value)})} required />
                                </div>
                                <div className="space-y-2">
                                    <Label>No. Referensi (PO/WO)</Label>
                                    <Input value={transactionForm.reference_no ?? ''} onChange={e => setTransactionForm({...transactionForm, reference_no: e.target.value})} placeholder="Opsional" />
                                </div>
                            </div>
                            <div className="space-y-2">
                                <Label>Catatan</Label>
                                <Input value={transactionForm.notes} onChange={e => setTransactionForm({...transactionForm, notes: e.target.value})} />
                            </div>
                        </div>
                        <DialogFooter>
                            <Button type="submit" className="w-full">Simpan Transaksi</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
