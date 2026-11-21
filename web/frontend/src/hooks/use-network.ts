import { useQuery } from "@tanstack/react-query";
import api from "@/lib/axios";

export interface RouterInfo {
  id: string;
  name: string;
  ip_address: string;
  type: string;
}

export interface NetworkStats {
  router: {
    id: string;
    name: string;
    status: string;
  };
  resources: {
    identity: string;
    uptime: string;
    cpu_load: number;
    memory_free: number;
    memory_total: number;
    hdd_free: number;
    version: string;
    board_name: string;
  };
  active_users: {
    pppoe: number;
    hotspot: number;
    total: number;
  };
  traffic: {
    rx: number;
    tx: number;
    rx_human: string;
    tx_human: string;
  };
  timestamp: string;
}

export function useNetworkRouters() {
  return useQuery({
    queryKey: ["network", "routers"],
    queryFn: async () => {
      const response = await api.get<RouterInfo[]>("/admin/network/routers");
      return response.data;
    },
  });
}

export function useNetworkMonitor(routerId: string | null) {
  return useQuery({
    queryKey: ["network", "monitor", routerId],
    queryFn: async () => {
      const response = await api.get<NetworkStats>(`/admin/network/monitor/${routerId}`);
      return response.data;
    },
    enabled: !!routerId,
    refetchInterval: 5000, // Sync every 5 seconds for real-time feel
  });
}
