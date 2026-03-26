"use client";

import { useState, useEffect } from "react";
import { useNetworkRouters, useNetworkMonitor } from "@/hooks/use-network";
import { 
  Card, 
  CardContent, 
  CardHeader, 
  CardTitle,
  CardDescription
} from "@/components/ui/card";
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from "@/components/ui/select";
import { Badge } from "@/components/ui/badge";
import { Progress } from "@/components/ui/progress";
import { 
  Cpu, 
  HardDrive, 
  Activity, 
  Users, 
  Wifi, 
  Info,
  Server,
  ArrowUp,
  ArrowDown,
  RefreshCw,
  AlertCircle
} from "lucide-react";
import { formatDistanceToNow } from "date-fns";
import { id as idLocale } from "date-fns/locale";

export default function NetworkPage() {
  const { data: routers, isLoading: isLoadingRouters } = useNetworkRouters();
  const [selectedRouterId, setSelectedRouterId] = useState<string | null>(null);

  // Set default router if available
  useEffect(() => {
    if (routers && routers.length > 0 && !selectedRouterId) {
      setSelectedRouterId(routers[0].id);
    }
  }, [routers, selectedRouterId]);

  const { data: stats, isLoading: isLoadingStats, isRefetching } = useNetworkMonitor(selectedRouterId);

  const formatBytes = (bytes: number) => {
    if (bytes === 0) return "0 B";
    const k = 1024;
    const sizes = ["B", "KB", "MB", "GB", "TB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
  };

  const getCpuColor = (load: number) => {
    if (load > 80) return "bg-red-500";
    if (load > 50) return "bg-orange-500";
    return "bg-blue-500";
  };

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div className="flex flex-col gap-1">
          <h1 className="text-3xl font-bold tracking-tight">Network Monitoring</h1>
          <p className="text-muted-foreground text-sm flex items-center gap-2">
            Pantau performa infrastruktur MikroTik secara real-time.
            {isRefetching && <RefreshCw className="w-3 h-3 animate-spin text-blue-500" />}
          </p>
        </div>

        <div className="flex items-center gap-3 bg-white dark:bg-slate-900 p-2 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
           <span className="text-xs font-semibold text-muted-foreground pl-2 uppercase tracking-wider">Pilih Router:</span>
           <Select 
            value={selectedRouterId || ""} 
            onValueChange={(v) => setSelectedRouterId(v)}
            disabled={isLoadingRouters}
           >
            <SelectTrigger className="w-[180px] h-9 border-none shadow-none focus:ring-0">
              <SelectValue placeholder="Pilih Router" />
            </SelectTrigger>
            <SelectContent>
              {routers?.map((router) => (
                <SelectItem key={router.id} value={router.id}>
                  {router.name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
      </div>

      {!selectedRouterId ? (
        <div className="flex flex-col items-center justify-center min-h-[400px] border-2 border-dashed rounded-3xl bg-slate-50/50 dark:bg-slate-900/10">
           <Server className="w-12 h-12 text-slate-300 mb-4" />
           <p className="text-slate-500">Silakan pilih router untuk melihat data monitoring.</p>
        </div>
      ) : (
        <div className="flex flex-col gap-6">
          {/* Main Status Grid */}
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            {/* CPU Load */}
            <Card className="border-none shadow-md bg-white dark:bg-slate-950">
              <CardContent className="p-6">
                <div className="flex items-center justify-between mb-4">
                   <div className="p-2 bg-blue-500/10 rounded-lg text-blue-600">
                      <Cpu className="w-5 h-5" />
                   </div>
                   <Badge variant="outline" className="text-[10px] uppercase font-bold tracking-tighter">Real-time</Badge>
                </div>
                <div className="space-y-3">
                   <div className="flex items-baseline justify-between">
                      <p className="text-sm font-medium text-muted-foreground">CPU Usage</p>
                      <h3 className="text-2xl font-bold">{stats?.resources?.cpu_load || 0}%</h3>
                   </div>
                   <Progress value={stats?.resources?.cpu_load || 0} className="h-1.5" indicatorClassName={getCpuColor(stats?.resources?.cpu_load || 0)} />
                </div>
              </CardContent>
            </Card>

            {/* Memory */}
            <Card className="border-none shadow-md bg-white dark:bg-slate-950">
              <CardContent className="p-6">
                <div className="flex items-center justify-between mb-4">
                   <div className="p-2 bg-emerald-500/10 rounded-lg text-emerald-600">
                      <HardDrive className="w-5 h-5" />
                   </div>
                   <p className="text-[10px] text-muted-foreground font-mono">RAM Info</p>
                </div>
                <div className="space-y-3">
                   <div className="flex items-baseline justify-between">
                      <p className="text-sm font-medium text-muted-foreground">Memory Free</p>
                      <h3 className="text-2xl font-bold">{stats?.resources?.memory_free ? formatBytes(stats.resources.memory_free) : "0 MB"}</h3>
                   </div>
                   <div className="text-[10px] text-muted-foreground">
                      Total: {stats?.resources?.memory_total ? formatBytes(stats.resources.memory_total) : "0 MB"}
                   </div>
                </div>
              </CardContent>
            </Card>

            {/* Active Users */}
            <Card className="border-none shadow-md bg-white dark:bg-slate-950">
              <CardContent className="p-6">
                <div className="flex items-center justify-between mb-4">
                   <div className="p-2 bg-purple-500/10 rounded-lg text-purple-600">
                      <Users className="w-5 h-5" />
                   </div>
                   <Badge className="bg-purple-500/10 text-purple-600 border-none">Active Clients</Badge>
                </div>
                <div className="space-y-1">
                   <h3 className="text-3xl font-bold tracking-tight">{stats?.active_users?.total || 0}</h3>
                   <div className="flex items-center gap-3 text-xs text-muted-foreground">
                      <span className="flex items-center gap-1"><div className="w-1.5 h-1.5 rounded-full bg-blue-500" /> {stats?.active_users?.pppoe || 0} PPPoE</span>
                      <span className="flex items-center gap-1"><div className="w-1.5 h-1.5 rounded-full bg-orange-500" /> {stats?.active_users?.hotspot || 0} Hotspot</span>
                   </div>
                </div>
              </CardContent>
            </Card>

            {/* Uptime */}
            <Card className="border-none shadow-md bg-white dark:bg-slate-950">
              <CardContent className="p-6">
                <div className="flex items-center justify-between mb-4">
                   <div className="p-2 bg-amber-500/10 rounded-lg text-amber-600">
                      <Activity className="w-5 h-5" />
                   </div>
                </div>
                <div className="space-y-1">
                   <p className="text-sm font-medium text-muted-foreground">System Uptime</p>
                   <h3 className="text-xl font-bold truncate">{stats?.resources?.uptime || "-"}</h3>
                   <p className="text-[10px] text-muted-foreground truncate">{stats?.resources?.board_name} - {stats?.resources?.version}</p>
                </div>
              </CardContent>
            </Card>
          </div>

          <div className="grid gap-6 lg:grid-cols-3">
            {/* Traffic Monitor Card */}
            <Card className="lg:col-span-2 border-none shadow-md bg-white dark:bg-slate-950 overflow-hidden">
               <CardHeader className="flex flex-row items-center justify-between bg-slate-50/50 dark:bg-slate-900/50 border-b">
                 <div className="space-y-1">
                    <CardTitle className="text-lg flex items-center gap-2">
                       <Wifi className="w-5 h-5 text-blue-500" />
                       Real-time Traffic (WAN)
                    </CardTitle>
                    <CardDescription>Interface ether1-public</CardDescription>
                 </div>
               </CardHeader>
               <CardContent className="p-8">
                  <div className="flex flex-col md:flex-row items-center justify-around gap-8">
                     {/* Download */}
                     <div className="flex flex-col items-center gap-4 text-center">
                        <div className="relative w-40 h-40 flex items-center justify-center">
                           <svg className="w-full h-full transform -rotate-90">
                              <circle cx="80" cy="80" r="70" className="stroke-slate-100 dark:stroke-slate-800" strokeWidth="8" fill="none" />
                              <circle 
                                cx="80" cy="80" r="70" 
                                className="stroke-blue-500 transition-all duration-1000 ease-in-out" 
                                strokeWidth="8" 
                                fill="none" 
                                strokeDasharray={440}
                                strokeDashoffset={440 - (Math.min((stats?.traffic?.rx || 0) / 100000000, 1) * 440)}
                                strokeLinecap="round"
                              />
                           </svg>
                           <div className="absolute inset-0 flex flex-col items-center justify-center">
                              <ArrowDown className="w-5 h-5 text-blue-500 mb-1" />
                              <span className="text-xl font-bold">{stats?.traffic?.rx_human || "0 bps"}</span>
                              <span className="text-[10px] uppercase text-muted-foreground font-bold tracking-widest">Download</span>
                           </div>
                        </div>
                     </div>

                     {/* Upload */}
                     <div className="flex flex-col items-center gap-4 text-center">
                        <div className="relative w-40 h-40 flex items-center justify-center">
                           <svg className="w-full h-full transform -rotate-90">
                              <circle cx="80" cy="80" r="70" className="stroke-slate-100 dark:stroke-slate-800" strokeWidth="8" fill="none" />
                              <circle 
                                cx="80" cy="80" r="70" 
                                className="stroke-emerald-500 transition-all duration-1000 ease-in-out" 
                                strokeWidth="8" 
                                fill="none" 
                                strokeDasharray={440}
                                strokeDashoffset={440 - (Math.min((stats?.traffic?.tx || 0) / 50000000, 1) * 440)}
                                strokeLinecap="round"
                              />
                           </svg>
                           <div className="absolute inset-0 flex flex-col items-center justify-center">
                              <ArrowUp className="w-5 h-5 text-emerald-500 mb-1" />
                              <span className="text-xl font-bold">{stats?.traffic?.tx_human || "0 bps"}</span>
                              <span className="text-[10px] uppercase text-muted-foreground font-bold tracking-widest">Upload</span>
                           </div>
                        </div>
                     </div>
                  </div>
               </CardContent>
            </Card>

            {/* Quick Info Card */}
            <Card className="border-none shadow-md bg-white dark:bg-slate-950 h-full">
               <CardHeader className="bg-slate-50/50 dark:bg-slate-900/50 border-b">
                 <CardTitle className="text-lg flex items-center gap-2">
                    <Info className="w-5 h-5 text-blue-500" />
                    Router Info
                 </CardTitle>
               </CardHeader>
               <CardContent className="p-6 space-y-4">
                  <div className="space-y-3">
                     <div className="flex justify-between text-sm py-2 border-b border-slate-50">
                        <span className="text-muted-foreground">Hostname</span>
                        <span className="font-semibold text-blue-600">{stats?.resources?.identity}</span>
                     </div>
                     <div className="flex justify-between text-sm py-2 border-b border-slate-50">
                        <span className="text-muted-foreground">IP Address</span>
                        <span className="font-medium">{routers?.find(r => r.id === selectedRouterId)?.ip_address}</span>
                     </div>
                     <div className="flex justify-between text-sm py-2 border-b border-slate-50">
                        <span className="text-muted-foreground">OS Version</span>
                        <span className="font-medium">{stats?.resources?.version}</span>
                     </div>
                     <div className="flex justify-between text-sm py-2 border-b border-slate-50">
                        <span className="text-muted-foreground">Board Name</span>
                        <span className="font-medium text-xs">{stats?.resources?.board_name}</span>
                     </div>
                     <div className="flex justify-between text-sm py-2">
                        <span className="text-muted-foreground">Status</span>
                        <Badge className="bg-emerald-500/10 text-emerald-600 border-none capitalize">{stats?.router?.status}</Badge>
                     </div>
                  </div>

                  <div className="mt-8 p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-xl border border-blue-50/50 flex gap-3">
                     <AlertCircle className="w-5 h-5 text-blue-500 shrink-0" />
                     <p className="text-[10px] leading-relaxed text-blue-700 dark:text-blue-400">
                        Data diperbarui secara otomatis setiap 5 detik. Pastikan koneksi MikroTik API (8728) terbuka di firewall router Anda.
                     </p>
                  </div>
               </CardContent>
            </Card>
          </div>
        </div>
      )}
    </div>
  );
}
