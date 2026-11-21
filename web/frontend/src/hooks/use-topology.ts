import { useQuery } from "@tanstack/react-query";
import axios from "@/lib/axios";

export interface TopologyNode {
  id: string;
  label: string;
  title: string;
  group: 'router' | 'olt' | 'odp' | 'customer';
  shape: string;
  color: string;
  size?: number;
  data?: any;
}

export interface TopologyEdge {
  from: string;
  to: string;
  color?: string;
  dashes?: boolean;
  width?: number;
}

export interface TopologyData {
  nodes: TopologyNode[];
  edges: TopologyEdge[];
}

export function useTopologyData() {
  return useQuery({
    queryKey: ["topology-data"],
    queryFn: async () => {
      const response = await axios.get("/admin/topology/data");
      return response.data as TopologyData;
    },
  });
}
