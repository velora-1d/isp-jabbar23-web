import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "@/lib/axios";
import { Lead, LeadStatus } from "@/types/lead";

export const useLeads = (filters?: { status?: string; page?: number }) => {
  const queryClient = useQueryClient();

  const leadsQuery = useQuery({
    queryKey: ["leads", filters],
    queryFn: async () => {
      const response = await axios.get("/admin/leads", { params: filters });
      return response.data;
    },
  });

  const createLeadMutation = useMutation({
    mutationFn: async (data: Partial<Lead>) => {
      const response = await axios.post("/admin/leads", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["leads"] });
    },
  });

  const updateStatusMutation = useMutation({
    mutationFn: async ({ id, status }: { id: number; status: LeadStatus }) => {
      const response = await axios.patch(`/admin/leads/${id}/status`, { status });
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["leads"] });
      // Invalidate customers too in case lead is converted
      queryClient.invalidateQueries({ queryKey: ["customers"] });
    },
  });

  return {
    leadsQuery,
    createLeadMutation,
    updateStatusMutation,
  };
};
