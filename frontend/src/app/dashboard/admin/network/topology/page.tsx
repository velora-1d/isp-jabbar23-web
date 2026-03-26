"use client";

import { useTopologyData } from "@/hooks/use-topology";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { 
  Router, 
  Database, 
  Network, 
  Wifi, 
  ArrowRight,
  Cpu,
  Zap,
  Activity
} from "lucide-react";

export default function TopologyPage() {
  const { data: topology, isLoading } = useTopologyData();

  if (isLoading) return <div className="p-10 text-center">Loading network topology...</div>;

  return (
    <div className="flex flex-col gap-6 p-6">
      <div className="flex flex-col gap-1">
        <h1 className="text-3xl font-bold tracking-tight">Network Topology</h1>
        <p className="text-muted-foreground text-sm">Visualisasi hirarki infrastruktur jaringan (Core - Distribution - Access).</p>
      </div>

      <div className="grid gap-8">
        {/* Simple Hierarchy Visualization */}
        <div className="flex flex-col items-center gap-12 py-10">
          
          {/* Core Layer: Routers */}
          <div className="flex flex-wrap justify-center gap-8 w-full">
            {topology?.nodes.filter(n => n.group === 'router').map(node => (
              <NodeCard key={node.id} node={node} icon={<Router className="w-6 h-6" />} color="bg-emerald-500" />
            ))}
          </div>

          <div className="w-px h-12 bg-slate-200 dark:bg-slate-800 relative">
             <div className="absolute top-0 -left-1 w-2 h-2 rounded-full bg-slate-300" />
             <div className="absolute bottom-0 -left-1 w-2 h-2 rounded-full bg-slate-300" />
          </div>

          {/* Distribution Layer: OLTs */}
          <div className="flex flex-wrap justify-center gap-8 w-full">
            {topology?.nodes.filter(n => n.group === 'olt').map(node => (
              <NodeCard key={node.id} node={node} icon={<Database className="w-6 h-6" />} color="bg-blue-600" />
            ))}
          </div>

          <div className="w-px h-12 bg-slate-200 dark:bg-slate-800 relative">
             <div className="absolute top-0 -left-1 w-2 h-2 rounded-full bg-slate-300" />
             <div className="absolute bottom-0 -left-1 w-2 h-2 rounded-full bg-slate-300" />
          </div>

          {/* Access Layer: ODPs */}
          <div className="flex flex-wrap justify-center gap-4 w-full px-4">
            {topology?.nodes.filter(n => n.group === 'odp').map(node => (
              <div key={node.id} className="group relative flex flex-col items-center">
                 <div className="p-3 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 border border-purple-200 dark:border-purple-800 group-hover:scale-110 transition-transform cursor-pointer shadow-sm">
                    <Wifi className="w-4 h-4" />
                 </div>
                 <span className="mt-2 text-[10px] font-medium text-slate-500 max-w-[80px] text-center truncate">{node.label}</span>
                 
                 {/* Tooltip-like popup on hover */}
                 <div className="absolute bottom-full mb-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-[10px] p-2 rounded shadow-xl whitespace-nowrap z-10">
                    {node.title}
                 </div>
              </div>
            ))}
          </div>

        </div>
      </div>
      
      {/* Legend & Stats */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 border-t pt-8">
         <Card className="border-none shadow-sm bg-slate-50 dark:bg-slate-900/50">
            <CardContent className="p-4 flex items-center gap-4">
               <div className="p-2 bg-emerald-100 text-emerald-600 rounded-lg"><Cpu className="w-5 h-5" /></div>
               <div>
                  <div className="text-xl font-bold">{topology?.nodes.filter(n => n.group === 'router').length}</div>
                  <div className="text-xs text-muted-foreground">Core Routers</div>
               </div>
            </CardContent>
         </Card>
         <Card className="border-none shadow-sm bg-slate-50 dark:bg-slate-900/50">
            <CardContent className="p-4 flex items-center gap-4">
               <div className="p-2 bg-blue-100 text-blue-600 rounded-lg"><Zap className="w-5 h-5" /></div>
               <div>
                  <div className="text-xl font-bold">{topology?.nodes.filter(n => n.group === 'olt').length}</div>
                  <div className="text-xs text-muted-foreground">OLT Units</div>
               </div>
            </CardContent>
         </Card>
         <Card className="border-none shadow-sm bg-slate-50 dark:bg-slate-900/50">
            <CardContent className="p-4 flex items-center gap-4">
               <div className="p-2 bg-purple-100 text-purple-600 rounded-lg"><Activity className="w-5 h-5" /></div>
               <div>
                  <div className="text-xl font-bold">{topology?.nodes.filter(n => n.group === 'odp').length}</div>
                  <div className="text-xs text-muted-foreground">ODP Distribution</div>
               </div>
            </CardContent>
         </Card>
      </div>
    </div>
  );
}

function NodeCard({ node, icon, color }: { node: any, icon: React.ReactNode, color: string }) {
  return (
    <Card className="min-w-[200px] border-none shadow-lg overflow-hidden group hover:ring-2 hover:ring-offset-2 transition-all">
      <div className={`h-1 ${color}`} />
      <CardContent className="p-4 flex items-center gap-3">
        <div className={`p-2 rounded-lg text-white ${color} group-hover:scale-110 transition-transform`}>
          {icon}
        </div>
        <div className="flex flex-col">
          <span className="font-bold text-sm tracking-tight">{node.label}</span>
          <span className="text-[10px] text-muted-foreground font-mono">{node.title.split('\n')[0]}</span>
        </div>
      </CardContent>
    </Card>
  );
}
