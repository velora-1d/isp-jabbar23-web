'use client';

import { useAuth } from '@/hooks/use-auth';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';
import AttendanceCard from './admin/hrd/components/AttendanceCard';

export default function DashboardPage() {
  const { user, isLoading, logout } = useAuth();
  const router = useRouter();

  useEffect(() => {
    if (!isLoading && !user) {
      router.push('/login');
    }
  }, [user, isLoading, router]);

  if (isLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-zinc-950 text-zinc-100">
        <p className="animate-pulse">Memuat data...</p>
      </div>
    );
  }

  if (!user) return null;

  return (
    <div className="min-h-screen bg-zinc-950 text-zinc-100 p-8">
      <div className="mx-auto max-w-6xl space-y-8">
        <header className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold tracking-tight">Dashboard</h1>
            <p className="text-zinc-400">Selamat datang kembali, {user.name}!</p>
          </div>
          <Button 
            variant="outline" 
            onClick={() => logout()}
            className="border-zinc-800 bg-zinc-900 hover:bg-zinc-800 text-zinc-100"
          >
            Logout
          </Button>
        </header>

        <AttendanceCard />

        <div className="grid gap-6 md:grid-cols-3">
          <Card className="border-zinc-800 bg-zinc-900 text-zinc-100">
            <CardHeader>
              <CardTitle className="text-sm font-medium">Status Akun</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold text-green-500">Aktif</div>
              <p className="text-xs text-zinc-400 mt-1">Layanan ISP Jabbar23</p>
            </CardContent>
          </Card>
          
          <Card className="border-zinc-800 bg-zinc-900 text-zinc-100">
            <CardHeader>
              <CardTitle className="text-sm font-medium">Paket</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">50 Mbps</div>
              <p className="text-xs text-zinc-400 mt-1">Unlimited Fiber</p>
            </CardContent>
          </Card>

          <Card className="border-zinc-800 bg-zinc-900 text-zinc-100">
            <CardHeader>
              <CardTitle className="text-sm font-medium">Tagihan Terakhir</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">Rp 350.000</div>
              <p className="text-xs text-zinc-400 mt-1">Jatuh tempo: 05 Maret</p>
            </CardContent>
          </Card>
        </div>

        <Card className="border-zinc-800 bg-zinc-900 text-zinc-100">
          <CardHeader>
            <CardTitle>Aktivitas Terakhir</CardTitle>
            <CardDescription className="text-zinc-400">
              Riwayat login dan penggunaan sistem
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              <div className="flex items-center justify-between border-b border-zinc-800 pb-4 last:border-0 last:pb-0">
                <div>
                  <p className="font-medium">Login Berhasil</p>
                  <p className="text-xs text-zinc-400">{new Date().toLocaleString('id-ID')}</p>
                </div>
                <div className="text-sm text-zinc-400">via Chrome / Windows</div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
