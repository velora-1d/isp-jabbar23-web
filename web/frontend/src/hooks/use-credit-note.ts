import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "@/lib/axios";

export interface CreditNote {
  id: number;
  credit_number: string;
  customer_id: number;
  customer: {
    id: number;
    name: string;
    identifier: string;
  };
  amount: number;
  issue_date: string;
  reason: 'overpayment' | 'refund' | 'discount' | 'adjustment' | 'other';
  notes: string | null;
  status: 'pending' | 'applied' | 'cancelled';
  applied_to_invoice_id: number | null;
  applied_invoice?: any;
  created_at: string;
}

export function useCreditNotes(params?: { search?: string; status?: string; reason?: string; page?: number }) {
  return useQuery({
    queryKey: ["credit-notes", params],
    queryFn: async () => {
      const response = await axios.get("/admin/credit-notes", { params });
      return response.data;
    },
  });
}

export function useCreditNote(id: number | null) {
  return useQuery({
    queryKey: ["credit-note", id],
    queryFn: async () => {
      if (!id) return null;
      const response = await axios.get(`/admin/credit-notes/${id}`);
      return response.data;
    },
    enabled: !!id,
  });
}

export function useCreateCreditNote() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: any) => {
      const response = await axios.post("/admin/credit-notes", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["credit-notes"] });
    },
  });
}

export function useApplyCreditNote() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ id, invoice_id }: { id: number; invoice_id: number }) => {
      const response = await axios.post(`/admin/credit-notes/${id}/apply`, { invoice_id });
      return response.data;
    },
    onSuccess: () => {
        queryClient.invalidateQueries({ queryKey: ["credit-notes"] });
        queryClient.invalidateQueries({ queryKey: ["invoices"] });
    },
  });
}

export function useCancelCreditNote() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.post(`/admin/credit-notes/${id}/cancel`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["credit-notes"] });
    },
  });
}
