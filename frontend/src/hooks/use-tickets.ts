import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import api from "@/lib/axios";

export interface Ticket {
  id: string;
  ticket_number: string;
  customer_id: string;
  technician_id: string | null;
  subject: string;
  description: string;
  status: "open" | "in_progress" | "resolved" | "closed";
  priority: "low" | "medium" | "high" | "critical";
  optical_power: string | null;
  evidence_photo: string | null;
  admin_notes: string | null;
  resolved_at: string | null;
  created_at: string;
  updated_at: string;
  customer?: {
    id: string;
    name: string;
    customer_id: string;
  };
  technician?: {
    id: string;
    name: string;
  };
}

export interface TicketFilters {
  page?: number;
  per_page?: number;
  search?: string;
  status?: string;
  priority?: string;
  technician_id?: string;
  year?: string;
  month?: string;
}

export interface TicketResponse {
  tickets: {
    data: Ticket[];
    current_page: number;
    last_page: number;
    total: number;
    per_page: number;
  };
  stats: {
    total: number;
    open: number;
    in_progress: number;
    resolved: number;
    closed: number;
  };
  options: {
    statuses: { value: string; label: string }[];
    priorities: { value: string; label: string }[];
    technicians: { id: string; name: string }[];
  };
}

export function useTickets(filters: TicketFilters = {}) {
  return useQuery({
    queryKey: ["tickets", filters],
    queryFn: async () => {
      const response = await api.get<TicketResponse>("/admin/tickets", {
        params: filters,
      });
      return response.data;
    },
  });
}

export function useTicket(id: string) {
  return useQuery({
    queryKey: ["tickets", id],
    queryFn: async () => {
      const response = await api.get<Ticket>("/admin/tickets/" + id);
      return response.data;
    },
    enabled: !!id,
  });
}

export function useUpdateTicket() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ id, data }: { id: string; data: Partial<Ticket> }) => {
      const response = await api.put("/admin/tickets/" + id, data);
      return response.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ["tickets"] });
      queryClient.invalidateQueries({ queryKey: ["tickets", variables.id] });
      toast.success("Tiket berhasil diperbarui");
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || "Gagal memperbarui tiket");
    },
  });
}

export function useDeleteTicket() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (id: string) => {
      const response = await api.delete("/admin/tickets/" + id);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["tickets"] });
      toast.success("Tiket berhasil dihapus");
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || "Gagal menghapus tiket");
    },
  });
}
