import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import api from "@/lib/axios";
import { KnowledgeBaseArticle, SlaPolicy } from "@/types/support";

// Knowledge Base Hooks
export interface KBFilters {
  page?: number;
  per_page?: number;
  category?: string;
  search?: string;
}

export function useKnowledgeBase(filters: KBFilters = {}) {
  return useQuery({
    queryKey: ["knowledge-base", filters],
    queryFn: async () => {
      const response = await api.get<{ data: KnowledgeBaseArticle[]; meta: any }>("/admin/knowledge-base", {
        params: filters,
      });
      return response.data;
    },
  });
}

export function useArticle(id: string) {
  return useQuery({
    queryKey: ["knowledge-base", id],
    queryFn: async () => {
      const response = await api.get<{ data: KnowledgeBaseArticle }>("/admin/knowledge-base/" + id);
      return response.data.data;
    },
    enabled: !!id,
  });
}

export function useCreateArticle() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: Partial<KnowledgeBaseArticle>) => {
      const response = await api.post("/admin/knowledge-base", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["knowledge-base"] });
      toast.success("Artikel berhasil dibuat");
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || "Gagal membuat artikel");
    },
  });
}

export function useUpdateArticle() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ id, data }: { id: string; data: Partial<KnowledgeBaseArticle> }) => {
      const response = await api.put("/admin/knowledge-base/" + id, data);
      return response.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ["knowledge-base"] });
      queryClient.invalidateQueries({ queryKey: ["knowledge-base", variables.id] });
      toast.success("Artikel berhasil diperbarui");
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || "Gagal memperbarui artikel");
    },
  });
}

export function useDeleteArticle() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: string) => {
      const response = await api.delete("/admin/knowledge-base/" + id);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["knowledge-base"] });
      toast.success("Artikel berhasil dihapus");
    },
  });
}

// SLA Policy Hooks
export function useSlaPolicies(filters: any = {}) {
  return useQuery({
    queryKey: ["sla-policies", filters],
    queryFn: async () => {
      const response = await api.get<{ data: SlaPolicy[]; meta: any }>("/admin/sla", {
        params: filters,
      });
      return response.data;
    },
  });
}

export function useCreateSlaPolicy() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: Partial<SlaPolicy>) => {
      const response = await api.post("/admin/sla", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["sla-policies"] });
      toast.success("Kebijakan SLA berhasil dibuat");
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || "Gagal membuat kebijakan SLA");
    },
  });
}

export function useUpdateSlaPolicy() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ id, data }: { id: string; data: Partial<SlaPolicy> }) => {
      const response = await api.put("/admin/sla/" + id, data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["sla-policies"] });
      toast.success("Kebijakan SLA berhasil diperbarui");
    },
  });
}

export function useDeleteSlaPolicy() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: string) => {
      const response = await api.delete("/admin/sla/" + id);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["sla-policies"] });
      toast.success("Kebijakan SLA berhasil dihapus");
    },
  });
}
