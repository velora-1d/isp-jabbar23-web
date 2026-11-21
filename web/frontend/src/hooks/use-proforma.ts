import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "@/lib/axios";

export interface ProformaInvoice {
  id: number;
  proforma_number: string;
  customer_id: number;
  customer: {
    id: number;
    name: string;
    identifier: string;
  };
  amount: number;
  issue_date: string;
  valid_until: string;
  notes: string | null;
  status: 'pending' | 'converted' | 'expired' | 'cancelled';
  converted_invoice_id: number | null;
  converted_invoice?: any;
  created_at: string;
}

export function useProformas(params?: { search?: string; status?: string; page?: number }) {
  return useQuery({
    queryKey: ["proformas", params],
    queryFn: async () => {
      const response = await axios.get("/admin/proforma", { params });
      return response.data;
    },
  });
}

export function useProforma(id: number | null) {
  return useQuery({
    queryKey: ["proforma", id],
    queryFn: async () => {
      if (!id) return null;
      const response = await axios.get(`/admin/proforma/${id}`);
      return response.data as ProformaInvoice;
    },
    enabled: !!id,
  });
}

export function useCreateProforma() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: any) => {
      const response = await axios.post("/admin/proforma", data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["proformas"] });
    },
  });
}

export function useConvertProforma() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.post(`/admin/proforma/${id}/convert`);
      return response.data;
    },
    onSuccess: () => {
        queryClient.invalidateQueries({ queryKey: ["proformas"] });
        queryClient.invalidateQueries({ queryKey: ["invoices"] });
    },
  });
}

export function useCancelProforma() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.post(`/admin/proforma/${id}/cancel`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["proformas"] });
    },
  });
}
