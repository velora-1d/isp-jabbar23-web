import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "@/lib/axios";

export interface IpPool {
  id: number;
  name: string;
  network: string;
  prefix: number;
  gateway: string | null;
  dns_primary: string | null;
  dns_secondary: string | null;
  type: 'public' | 'private' | 'cgnat';
  description: string | null;
  total_ips: number;
  allocated_ips: number;
  available_ips: number;
  created_at: string;
}

export interface IpAddress {
  id: number;
  ip_pool_id: number;
  address: string;
  status: 'available' | 'allocated' | 'reserved';
  customer_id: number | null;
  customer?: {
    id: number;
    name: string;
    identifier: string;
  };
  created_at: string;
}

export function useIpPools(params?: { search?: string; type?: string; page?: number }) {
  return useQuery({
    queryKey: ["ip-pools", params],
    queryFn: async () => {
      const response = await axios.get("/admin/ipam/pools", { params });
      return response.data;
    },
  });
}

export function useIpAddresses(poolId: number | null, params?: { status?: string; search?: string; page?: number }) {
  return useQuery({
    queryKey: ["ip-addresses", poolId, params],
    queryFn: async () => {
      if (!poolId) return null;
      const response = await axios.get(`/admin/ipam/pools/${poolId}`, { params });
      return response.data;
    },
    enabled: !!poolId,
  });
}

export function useCreateIpPool() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: any) => {
      const response = await axios.post("/admin/ipam/pools", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["ip-pools"] });
    },
  });
}

export function useDeleteIpPool() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.delete(`/admin/ipam/pools/${id}`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["ip-pools"] });
    },
  });
}

export function useAllocateIp() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: { ip_address_id: number; customer_id: number }) => {
      const response = await axios.post("/admin/ipam/allocate", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["ip-addresses"] });
      queryClient.invalidateQueries({ queryKey: ["ip-pools"] });
    },
  });
}

export function useReleaseIp() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.post(`/admin/ipam/release/${id}`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["ip-addresses"] });
      queryClient.invalidateQueries({ queryKey: ["ip-pools"] });
    },
  });
}
