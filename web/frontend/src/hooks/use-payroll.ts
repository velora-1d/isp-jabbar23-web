import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import axios from "@/lib/axios";

export interface Payroll {
  id: number;
  user_id: number;
  user: {
    id: number;
    name: string;
    email: string;
  };
  period: string;
  basic_salary: number;
  allowances: number;
  overtime: number;
  bonus: number;
  deductions: number;
  tax: number;
  net_salary: number;
  working_days: number;
  present_days: number;
  absent_days: number;
  late_days: number;
  status: 'draft' | 'approved' | 'paid';
  notes: string | null;
  paid_at: string | null;
  created_at: string;
}

export function usePayrolls(params?: { period?: string; search?: string; status?: string; page?: number }) {
  return useQuery({
    queryKey: ["payrolls", params],
    queryFn: async () => {
      const response = await axios.get("/admin/payroll", { params });
      return response.data;
    },
  });
}

export function usePayroll(id: number | null) {
  return useQuery({
    queryKey: ["payroll", id],
    queryFn: async () => {
      if (!id) return null;
      const response = await axios.get(`/admin/payroll/${id}`);
      return response.data;
    },
    enabled: !!id,
  });
}

export function useCreatePayroll() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: any) => {
      const response = await axios.post("/admin/payroll", data);
      return response.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ["payrolls", { period: variables.period }] });
      queryClient.invalidateQueries({ queryKey: ["payrolls"] });
    },
  });
}

export function useUpdatePayroll() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ id, data }: { id: number; data: any }) => {
      const response = await axios.put(`/admin/payroll/${id}`, data);
      return response.data;
    },
    onSuccess: (data) => {
      queryClient.invalidateQueries({ queryKey: ["payrolls"] });
      queryClient.invalidateQueries({ queryKey: ["payroll", data.payroll.id] });
    },
  });
}

export function useApprovePayroll() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.post(`/admin/payroll/${id}/approve`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["payrolls"] });
    },
  });
}

export function useMarkPayrollPaid() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.post(`/admin/payroll/${id}/pay`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["payrolls"] });
    },
  });
}

export function useDeletePayroll() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (id: number) => {
      const response = await axios.delete(`/admin/payroll/${id}`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["payrolls"] });
    },
  });
}
