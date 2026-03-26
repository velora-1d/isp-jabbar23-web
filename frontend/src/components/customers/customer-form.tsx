'use client';

import React from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useRouter } from 'next/navigation';
import { 
  Form, 
  FormControl, 
  FormField, 
  FormItem, 
  FormLabel, 
  FormMessage,
  FormDescription
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
  User, 
  MapPin, 
  Settings, 
  Truck, 
  Network, 
  Loader2,
  ChevronRight,
  Save,
  X
} from 'lucide-react';
import { useCustomerFormData } from '@/hooks/use-customers';
import { toast } from 'sonner';

const customerSchema = z.object({
  name: z.string().min(3, 'Nama minimal 3 karakter'),
  phone: z.string().nullable().optional(),
  email: z.string().email('Email tidak valid').nullable().optional().or(z.literal('')),
  address: z.string().min(5, 'Alamat minimal 5 karakter'),
  rt_rw: z.string().nullable().optional(),
  kelurahan: z.string().nullable().optional(),
  kecamatan: z.string().nullable().optional(),
  kabupaten: z.string().nullable().optional(),
  provinsi: z.string().nullable().optional(),
  kode_pos: z.string().nullable().optional(),
  latitude: z.string().nullable().optional().or(z.number()).transform(v => v === '' ? null : v),
  longitude: z.string().nullable().optional().or(z.number()).transform(v => v === '' ? null : v),
  package_id: z.string().min(1, 'Paket harus dipilih'),
  status: z.string().min(1, 'Status harus dipilih'),
  assigned_to: z.string().nullable().optional(),
  team_size: z.coerce.number().min(1).max(10).optional().default(1),
  installation_date: z.string().nullable().optional(),
  billing_date: z.string().nullable().optional(),
  notes: z.string().nullable().optional(),
  olt_id: z.string().nullable().optional(),
  onu_index: z.string().nullable().optional(),
  pppoe_username: z.string().nullable().optional(),
  pppoe_password: z.string().nullable().optional(),
  username: z.string().min(3, 'Username minimal 3 karakter').optional(),
  password: z.string().min(4, 'Password minimal 4 karakter').optional(),
});

type CustomerFormValues = z.infer<typeof customerSchema>;

interface CustomerFormProps {
  initialData?: any;
  onSubmit: (data: CustomerFormValues) => Promise<void>;
  isLoading?: boolean;
}

export function CustomerForm({ initialData, onSubmit, isLoading }: CustomerFormProps) {
  const router = useRouter();
  const { data: formData, isLoading: isLoadingOptions } = useCustomerFormData();

  const form = useForm<CustomerFormValues>({
    resolver: zodResolver(customerSchema),
    defaultValues: initialData || {
      name: '',
      phone: '',
      email: '',
      address: '',
      rt_rw: '',
      kelurahan: '',
      kecamatan: '',
      kabupaten: '',
      provinsi: '',
      kode_pos: '',
      latitude: null,
      longitude: null,
      package_id: '',
      status: 'registered',
      assigned_to: '',
      team_size: 2,
      installation_date: '',
      billing_date: '',
      notes: '',
      olt_id: '',
      onu_index: '',
      username: '',
      password: '',
    },
  });

  const handleSubmit = async (values: CustomerFormValues) => {
    try {
      await onSubmit(values);
    } catch (error: any) {
      console.error(error);
      toast.error(error.response?.data?.message || 'Terjadi kesalahan saat menyimpan data.');
    }
  };

  if (isLoadingOptions) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[400px] space-y-4">
        <Loader2 className="w-8 h-8 animate-spin text-blue-500" />
        <p className="text-gray-400 animate-pulse text-sm">Memuat data referensi...</p>
      </div>
    );
  }

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(handleSubmit)} className="space-y-8 pb-20">
        
        {/* Section 1: Personal Info */}
        <Card className="bg-gray-900/40 border-gray-800 backdrop-blur-md overflow-hidden">
          <CardHeader className="bg-gradient-to-r from-blue-600/10 to-transparent border-b border-gray-800/50">
            <CardTitle className="text-lg flex items-center space-x-2">
              <User className="w-5 h-5 text-blue-400" />
              <span>Informasi Personal</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormField
              control={form.control}
              name="name"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Nama Lengkap *</FormLabel>
                  <FormControl>
                    <Input placeholder="John Doe" className="bg-gray-950/50 border-gray-800" {...field} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="phone"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>No. Telepon</FormLabel>
                  <FormControl>
                    <Input placeholder="0812XXXXXXXX" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="email"
              render={({ field }) => (
                <FormItem className="md:col-span-2">
                  <FormLabel>Email</FormLabel>
                  <FormControl>
                    <Input placeholder="john@example.com" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
          </CardContent>
        </Card>

        {/* Section 1.5: Account Credentials */}
        <Card className="bg-gray-900/40 border-gray-800 backdrop-blur-md overflow-hidden">
          <CardHeader className="bg-gradient-to-r from-indigo-600/10 to-transparent border-b border-gray-800/50">
            <CardTitle className="text-lg flex items-center space-x-2">
              <Settings className="w-5 h-5 text-indigo-400" />
              <span>Kredensial Login (Aplikasi)</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormField
              control={form.control}
              name="username"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Username</FormLabel>
                  <FormControl>
                    <Input placeholder="username_pelanggan" className="bg-gray-950/50 border-gray-800" {...field} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="password"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Password</FormLabel>
                  <FormControl>
                    <Input type="password" placeholder="********" className="bg-gray-950/50 border-gray-800" {...field} />
                  </FormControl>
                  <FormDescription className="text-xs">
                    Minimal 4 karakter. Biarkan kosong jika tidak ingin mengubah.
                  </FormDescription>
                  <FormMessage />
                </FormItem>
              )}
            />
          </CardContent>
        </Card>

        {/* Section 2: Address */}
        <Card className="bg-gray-900/40 border-gray-800 backdrop-blur-md">
          <CardHeader className="bg-gradient-to-r from-cyan-600/10 to-transparent border-b border-gray-800/50">
            <CardTitle className="text-lg flex items-center space-x-2">
              <MapPin className="w-5 h-5 text-cyan-400" />
              <span>Informasi Alamat</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="p-6 space-y-6">
            <FormField
              control={form.control}
              name="address"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Alamat Lengkap *</FormLabel>
                  <FormControl>
                    <Textarea 
                      placeholder="Jl. Merdeka No. 1..." 
                      className="bg-gray-950/50 border-gray-800 min-h-[100px] resize-none" 
                      {...field} 
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
              <FormField
                control={form.control}
                name="rt_rw"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>RT/RW</FormLabel>
                    <FormControl>
                      <Input placeholder="001/002" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={form.control}
                name="kelurahan"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Kelurahan</FormLabel>
                    <FormControl>
                      <Input placeholder="Cipedak" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={form.control}
                name="kecamatan"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Kecamatan</FormLabel>
                    <FormControl>
                      <Input placeholder="Jagakarsa" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={form.control}
                name="kode_pos"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Kode Pos</FormLabel>
                    <FormControl>
                      <Input placeholder="12630" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <FormField
                control={form.control}
                name="kabupaten"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Kabupaten / Kota</FormLabel>
                    <FormControl>
                      <Input placeholder="Jakarta Selatan" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={form.control}
                name="provinsi"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Provinsi</FormLabel>
                    <FormControl>
                      <Input placeholder="DKI Jakarta" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </div>
            <div className="grid grid-cols-2 gap-4">
              <FormField
                control={form.control}
                name="latitude"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Latitude</FormLabel>
                    <FormControl>
                      <Input placeholder="-6.12345" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={form.control}
                name="longitude"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Longitude</FormLabel>
                    <FormControl>
                      <Input placeholder="106.12345" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
            </div>
          </CardContent>
        </Card>

        {/* Section 3: Subscription */}
        <Card className="bg-gray-900/40 border-gray-800 backdrop-blur-md">
          <CardHeader className="bg-gradient-to-r from-amber-600/10 to-transparent border-b border-gray-800/50">
            <CardTitle className="text-lg flex items-center space-x-2">
              <Settings className="w-5 h-5 text-amber-400" />
              <span>Detail Langganan</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormField
              control={form.control}
              name="package_id"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Paket Internet *</FormLabel>
                  <Select onValueChange={field.onChange} defaultValue={field.value}>
                    <FormControl>
                      <SelectTrigger className="bg-gray-950/50 border-gray-800">
                        <SelectValue placeholder="Pilih paket" />
                      </SelectTrigger>
                    </FormControl>
                    <SelectContent className="bg-gray-900 border-gray-800">
                      {formData?.packages.map((pkg) => (
                        <SelectItem key={pkg.id} value={pkg.id.toString()}>
                          {pkg.name} - Rp {pkg.price.toLocaleString('id-ID')}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="status"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Status *</FormLabel>
                  <Select onValueChange={field.onChange} defaultValue={field.value}>
                    <FormControl>
                      <SelectTrigger className="bg-gray-950/50 border-gray-800">
                        <SelectValue placeholder="Pilih status" />
                      </SelectTrigger>
                    </FormControl>
                    <SelectContent className="bg-gray-900 border-gray-800">
                      {Object.entries(formData?.statuses || {}).map(([key, label]) => (
                        <SelectItem key={key} value={key}>
                          {label as string}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="installation_date"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Tanggal Instalasi</FormLabel>
                  <FormControl>
                    <Input type="date" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="billing_date"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Tanggal Tagihan</FormLabel>
                  <FormControl>
                    <Input type="date" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
          </CardContent>
        </Card>

        {/* Section 4: Installation Team */}
        <Card className="bg-gray-900/40 border-gray-800 backdrop-blur-md">
          <CardHeader className="bg-gradient-to-r from-emerald-600/10 to-transparent border-b border-gray-800/50">
            <CardTitle className="text-lg flex items-center space-x-2">
              <Truck className="w-5 h-5 text-emerald-400" />
              <span>Tim & Teknisi</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormField
              control={form.control}
              name="assigned_to"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>PIC / Teknisi</FormLabel>
                  <Select onValueChange={field.onChange} defaultValue={field.value || undefined}>
                    <FormControl>
                      <SelectTrigger className="bg-gray-950/50 border-gray-800">
                        <SelectValue placeholder="Belum ditentukan" />
                      </SelectTrigger>
                    </FormControl>
                    <SelectContent className="bg-gray-900 border-gray-800">
                      <SelectItem value="none">-- Belum Ditentukan --</SelectItem>
                      {formData?.technicians.map((tech) => (
                        <SelectItem key={tech.id} value={tech.id.toString()}>
                          {tech.name}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="team_size"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Jumlah Tim Anggota</FormLabel>
                  <FormControl>
                    <Input 
                      type="number" 
                      min={1} 
                      max={10} 
                      className="bg-gray-950/50 border-gray-800" 
                      {...field} 
                      value={field.value ?? ''}
                      onChange={(e) => field.onChange(e.target.value === '' ? '' : Number(e.target.value))}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
          </CardContent>
        </Card>

        {/* Section 5: Network */}
        <Card className="bg-gray-900/40 border-gray-800 backdrop-blur-md">
          <CardHeader className="bg-gradient-to-r from-purple-600/10 to-transparent border-b border-gray-800/50">
            <CardTitle className="text-lg flex items-center space-x-2">
              <Network className="w-5 h-5 text-purple-400" />
              <span>Konfigurasi Jaringan (OLT)</span>
            </CardTitle>
          </CardHeader>
          <CardContent className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <FormField
              control={form.control}
              name="olt_id"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Pilih OLT</FormLabel>
                  <Select onValueChange={field.onChange} defaultValue={field.value || undefined}>
                    <FormControl>
                      <SelectTrigger className="bg-gray-950/50 border-gray-800">
                        <SelectValue placeholder="Tidak terhubung OLT" />
                      </SelectTrigger>
                    </FormControl>
                    <SelectContent className="bg-gray-900 border-gray-800">
                       <SelectItem value="none">-- Tidak Terhubung --</SelectItem>
                      {formData?.olts.map((olt) => (
                        <SelectItem key={olt.id} value={olt.id.toString()}>
                          {olt.name} ({olt.type})
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="onu_index"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>ONU Interface / Index</FormLabel>
                  <FormControl>
                    <Input placeholder="gpon-onu_1/1/1:1" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                  </FormControl>
                  <FormDescription className="text-xs">
                    Format Huawei: 0/1/0:1 | ZTE: gpon-onu_1/1/1:1
                  </FormDescription>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="pppoe_username"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>PPPoE Username</FormLabel>
                  <FormControl>
                    <Input placeholder="user_pppoe" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
             <FormField
              control={form.control}
              name="pppoe_password"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>PPPoE Password</FormLabel>
                  <FormControl>
                    <Input type="password" placeholder="********" className="bg-gray-950/50 border-gray-800" {...field} value={field.value || ''} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
          </CardContent>
        </Card>

        {/* Notes */}
        <Card className="bg-gray-900/40 border-gray-800 backdrop-blur-md">
          <CardHeader>
            <CardTitle className="text-sm font-medium">Catatan Internal</CardTitle>
          </CardHeader>
          <CardContent className="p-6">
            <FormField
              control={form.control}
              name="notes"
              render={({ field }) => (
                <FormItem>
                  <FormControl>
                    <Textarea 
                      placeholder="Masukkan catatan teknis atau instruksi khusus..." 
                      className="bg-gray-950/50 border-gray-800 min-h-[100px] resize-none" 
                      {...field} 
                      value={field.value || ''}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
          </CardContent>
        </Card>

        {/* Footer Actions */}
        <div className="fixed bottom-0 left-0 right-0 p-4 bg-gray-950/80 backdrop-blur-xl border-t border-gray-800 flex items-center justify-end space-x-4 z-50">
          <Button 
            type="button" 
            variant="ghost" 
            onClick={() => router.back()}
            disabled={isLoading}
            className="text-gray-400 hover:text-white"
          >
            <X className="w-4 h-4 mr-2" />
            Batal
          </Button>
          <Button 
            type="submit" 
            disabled={isLoading}
            className="bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 shadow-lg shadow-blue-500/20 px-8"
          >
            {isLoading ? (
              <Loader2 className="w-4 h-4 mr-2 animate-spin" />
            ) : (
              <Save className="w-4 h-4 mr-2" />
            )}
            {initialData ? 'Perbarui Pelanggan' : 'Daftarkan Pelanggan'}
          </Button>
        </div>

      </form>
    </Form>
  );
}
