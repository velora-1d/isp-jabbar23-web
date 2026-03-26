'use client';

import { useAnalytics } from '@/hooks/use-analytics';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { 
  Activity, 
  Users, 
  TrendingUp, 
  Clock, 
  Server, 
  Signal,
  CreditCard,
  Target
} from 'lucide-react';
import { 
  BarChart, 
  Bar, 
  XAxis, 
  YAxis, 
  CartesianGrid, 
  Tooltip, 
  ResponsiveContainer,
  AreaChart,
  Area
} from 'recharts';

export function AnalyticsDashboard() {
  const { data, isLoading } = useAnalytics();

  if (isLoading) {
    return (
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 animate-pulse">
        {[1, 2, 3].map((i) => (
          <div key={i} className="h-32 bg-zinc-900 rounded-2xl border border-zinc-800" />
        ))}
      </div>
    );
  }

  const networkData = [
    { name: 'Online', value: data?.network?.total_online || 0, color: '#3b82f6' },
    { name: 'Offline', value: data?.network?.total_offline || 0, color: '#ef4444' }
  ];

  const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];
  const revenueData = data?.finance?.monthly_revenue?.map((item: any) => ({
    name: monthNames[parseInt(item.month) - 1],
    total: parseFloat(item.total)
  })) || [];

  return (
    <div className="space-y-8">
      {/* Top Stats */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <StatCard 
          title="Network Status" 
          value={`${data?.network?.total_online} / ${data?.network?.total_customers}`} 
          sub="Pelanggan Aktif Online"
          icon={<Signal className="text-blue-500" />} 
          trend="+2% vs kemarin"
        />
        <StatCard 
          title="Total Revenue YTD" 
          value={`Rp ${data?.finance?.total_ytd?.toLocaleString()}`} 
          sub="Pendapatan Tahun Ini"
          icon={<TrendingUp className="text-emerald-500" />} 
        />
        <StatCard 
          title="Collection Rate" 
          value={`${data?.finance?.collection_rate}%`} 
          sub="Rasio Tagihan Terbayar"
          icon={<CreditCard className="text-purple-500" />} 
        />
        <StatCard 
          title="Staff Activity" 
          value={`${data?.staff?.staff_online} / ${data?.staff?.total_staff}`} 
          sub="Teknisi Hadir Hari Ini"
          icon={<Users className="text-orange-500" />} 
        />
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {/* Revenue Chart */}
        <Card className="md:col-span-2 bg-zinc-900/40 border-zinc-800 backdrop-blur-sm">
          <CardHeader>
            <CardTitle className="text-lg flex items-center gap-2">
              <TrendingUp className="w-5 h-5 text-emerald-500" />
              Monthly Revenue Performance
            </CardTitle>
            <CardDescription>Visualisasi pertumbuhan pendapatan bulanan tahun {new Date().getFullYear()}</CardDescription>
          </CardHeader>
          <CardContent className="h-[300px] pl-2">
            <ResponsiveContainer width="100%" height="100%">
              <AreaChart data={revenueData}>
                <defs>
                  <linearGradient id="colorTotal" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#10b981" stopOpacity={0.3}/>
                    <stop offset="95%" stopColor="#10b981" stopOpacity={0}/>
                  </linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" stroke="#27272a" vertical={false} />
                <XAxis dataKey="name" stroke="#71717a" fontSize={12} tickLine={false} axisLine={false} />
                <YAxis stroke="#71717a" fontSize={12} tickLine={false} axisLine={false} tickFormatter={(value: number) => `Rp${value/1000000}M`} />
                <Tooltip 
                  contentStyle={{ backgroundColor: '#18181b', border: '1px solid #27272a', borderRadius: '8px' }}
                  itemStyle={{ color: '#fff' }}
                />
                <Area type="monotone" dataKey="total" stroke="#10b981" fillOpacity={1} fill="url(#colorTotal)" strokeWidth={2} />
              </AreaChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>

        {/* Router Health */}
        <Card className="bg-zinc-900/40 border-zinc-800 backdrop-blur-sm">
          <CardHeader>
            <CardTitle className="text-lg flex items-center gap-2">
              <Server className="w-5 h-5 text-blue-500" />
              Router Health Status
            </CardTitle>
            <CardDescription>Status koneksi dan beban router real-time</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-6">
              {data?.network?.routers?.map((router: any) => (
                <div key={router.name} className="space-y-2">
                  <div className="flex justify-between text-sm">
                    <span className="font-medium text-zinc-300">{router.name}</span>
                    <span className="text-blue-400 font-bold">{router.online} Active</span>
                  </div>
                  <div className="h-2 bg-zinc-800 rounded-full overflow-hidden">
                    <div 
                      className="h-full bg-blue-500 transition-all duration-1000" 
                      style={{ width: `${Math.min(100, (router.online / 250) * 100)}%` }}
                    />
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}

function StatCard({ title, value, sub, icon, trend }: { title: string, value: string, sub: string, icon: React.ReactNode, trend?: string }) {
  return (
    <Card className="bg-zinc-900/40 border-zinc-800 hover:border-zinc-700 transition-all group overflow-hidden">
      <div className="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
        {icon}
      </div>
      <CardContent className="p-6">
        <div className="flex items-center gap-3 mb-2">
           <div className="p-2 bg-zinc-950 border border-zinc-800 rounded-lg">
              {icon}
           </div>
           <span className="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">{title}</span>
        </div>
        <div className="flex flex-col">
          <span className="text-2xl font-bold text-white tracking-tight">{value}</span>
          <span className="text-[10px] text-zinc-400 mt-1">{sub}</span>
        </div>
        {trend && (
          <div className="mt-4 flex items-center gap-1 text-[10px] text-emerald-500 bg-emerald-500/10 w-fit px-2 py-0.5 rounded-full border border-emerald-500/20">
            {trend}
          </div>
        )}
      </CardContent>
    </Card>
  );
}
