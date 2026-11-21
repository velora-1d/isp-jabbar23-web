import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "@/lib/axios";

export interface LeaveRequest {
  id: number;
  user_id: number;
  user: {
    id: number;
    name: string;
  };
  type: 'annual' | 'sick' | 'personal' | 'maternity' | 'paternity' | 'unpaid' | 'other';
  start_date: string;
  end_date: string;
  days: number;
  reason: string;
  status: 'pending' | 'approved' | 'rejected' | 'cancelled';
  approved_by: number | null;
  approver?: {
    id: number;
    name: string;
  };
  approved_at: string | null;
  rejection_reason: string | null;
  created_at: string;
}

export function useLeaves(params?: { search?: string; status?: string; type?: string; page?: number }) {
  return useQuery({
    queryKey: ["leaves", params],
    queryFn: async () => {
      const response = await axios.get("/admin/leave", { params });
      return response.data;
    },
  });
}

export function useCreateLeave() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: any) => {
      const response = await axios.post("/admin/leave", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["leaves"] });
    },
  });
}

export function useApproveLeave() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.post(`/admin/leave/${id}/approve`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["leaves"] });
    },
  });
}

export function useRejectLeave() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ id, rejection_reason }: { id: number; rejection_reason: string }) => {
      const response = await axios.post(`/admin/leave/${id}/reject`, { rejection_reason });
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["leaves"] });
    },
  });
}

export function useDeleteLeave() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.delete(`/admin/leave/${id}`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["leaves"] });
    },
  });
}
