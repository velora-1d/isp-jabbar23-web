import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import api from "@/lib/axios";

export interface HotspotProfile {
  id: number;
  name: string;
  display_name: string;
  price: number;
  validity_hours: number;
  data_limit_mb: number | null;
  vouchers_count?: number;
}

export interface HotspotVoucher {
  id: number;
  code: string;
  username: string;
  password: string;
  status: 'available' | 'used' | 'expired';
  profile: HotspotProfile;
  router: {
    id: number;
    name: string;
  };
  creator: {
    id: number;
    name: string;
  };
  created_at: string;
  used_at: string | null;
}

export interface PaginatedVouchers {
  data: HotspotVoucher[];
  current_page: number;
  last_page: number;
  total: number;
}

export function useHotspotVouchers(params: { status?: string; router_id?: number; page?: number } = {}) {
  return useQuery({
    queryKey: ["hotspot", "vouchers", params],
    queryFn: async () => {
      const response = await api.get<PaginatedVouchers>("/admin/hotspot/vouchers", { params });
      return response.data;
    },
  });
}

export function useHotspotProfiles() {
  return useQuery({
    queryKey: ["hotspot", "profiles"],
    queryFn: async () => {
      const response = await api.get<HotspotProfile[]>("/admin/hotspot/profiles");
      return response.data;
    },
  });
}

export function useGenerateVouchers() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: { router_id: number; hotspot_profile_id: number; count: number }) => {
      const response = await api.post("/admin/hotspot/generate", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["hotspot", "vouchers"] });
    },
  });
}

export function useCreateHotspotProfile() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: Partial<HotspotProfile>) => {
      const response = await api.post("/admin/hotspot/profiles", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["hotspot", "profiles"] });
    },
  });
}

export function useBulkDeleteVouchers() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (ids: number[]) => {
      const response = await api.post("/admin/hotspot/bulk-delete", { ids });
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["hotspot", "vouchers"] });
    },
  });
}
