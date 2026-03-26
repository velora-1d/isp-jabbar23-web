'use client';

import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { useQuery } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';

export default function AttendanceAdminPage() {
  const { data: attendances, isLoading } = useQuery({
    queryKey: ['admin-attendances'],
    queryFn: async () => {
      const response = await axios.get('/api/admin/attendances');
      return response.data.data;
    }
  });

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold tracking-tight">Manajemen Absensi</h1>
        <p className="text-zinc-400">Pantau kehadiran karyawan dan lokasi absensi secara real-time.</p>
      </div>

      <Card className="border-zinc-800 bg-zinc-900 text-zinc-100">
        <CardHeader>
          <CardTitle>Rekap Absensi Hari Ini</CardTitle>
          <CardDescription className="text-zinc-400">
            Daftar karyawan yang sudah melakukan absensi hari ini.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div className="relative w-full overflow-auto">
            <table className="w-full caption-bottom text-sm">
              <thead className="[&_tr]:border-b border-zinc-800">
                <tr className="border-b transition-colors hover:bg-zinc-800/50 data-[state=selected]:bg-zinc-800">
                  <th className="h-12 px-4 text-left align-middle font-medium text-zinc-400">Karyawan</th>
                  <th className="h-12 px-4 text-left align-middle font-medium text-zinc-400">Jam Masuk</th>
                  <th className="h-12 px-4 text-left align-middle font-medium text-zinc-400">Lokasi</th>
                  <th className="h-12 px-4 text-left align-middle font-medium text-zinc-400">Status</th>
                </tr>
              </thead>
              <tbody className="[&_tr:last-child]:border-0">
                {isLoading ? (
                  <tr>
                    <td colSpan={4} className="p-4 text-center animate-pulse">Memuat data...</td>
                  </tr>
                ) : attendances?.length === 0 ? (
                  <tr>
                    <td colSpan={4} className="p-4 text-center text-zinc-500">Belum ada data absensi hari ini.</td>
                  </tr>
                ) : attendances?.map((attendance: any) => (
                  <tr key={attendance.id} className="border-b border-zinc-800 transition-colors hover:bg-zinc-800/50">
                    <td className="p-4 align-middle font-medium">{attendance.user?.name || 'Admin'}</td>
                    <td className="p-4 align-middle">
                      {format(new Date(attendance.check_in), 'HH:mm:ss', { locale: id })}
                    </td>
                    <td className="p-4 align-middle text-xs text-zinc-400">
                      {attendance.latitude}, {attendance.longitude}
                    </td>
                    <td className="p-4 align-middle">
                      <span className="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-green-500/10 text-green-500 border border-green-500/20">
                        Hadir
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
