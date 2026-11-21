import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { Invoice } from '@/types/finance';
import { toast } from 'sonner';

export const useInvoices = (params?: any) => {
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery<any>({
    queryKey: ['invoices', params],
    queryFn: async () => {
      const { data } = await axios.get('/api/admin/invoices', { params });
      return data; // Paginated response
    },
  });

  const generateInvoices = useMutation({
    mutationFn: async (data: { month: number; year: number }) => {
      const { data: response } = await axios.post('/api/admin/invoices/generate', data);
      return response;
    },
    onSuccess: (data) => {
      queryClient.invalidateQueries({ queryKey: ['invoices'] });
      toast.success(data?.message || 'Invoice berhasil di-generate');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal generate invoice');
    }
  });

  const getSnapToken = useMutation({
    mutationFn: async (invoiceId: string | number) => {
      const { data: response } = await axios.post(`/api/admin/invoices/${invoiceId}/snap-token`);
      return response;
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal mendapatkan token pembayaran');
    }
  });

  return {
    invoices: data,
    isLoading,
    generateInvoices,
    getSnapToken,
  };
};

export const useInvoice = (id: string | number) => {
  return useQuery({
    queryKey: ['invoice', id],
    queryFn: async () => {
      const { data } = await axios.get(`/api/admin/invoices/${id}`);
      return data.data as Invoice;
    },
    enabled: !!id,
  });
};

export const useMarkAsPaid = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ id, data }: { id: string | number; data: any }) => {
      const { data: response } = await axios.post(`/api/admin/invoices/${id}/pay`, data);
      return response;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['invoices'] });
      queryClient.invalidateQueries({ queryKey: ['invoice'] });
      toast.success('Pembayaran berhasil dicatat');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal mencatat pembayaran');
    }
  });
};

export const useGenerateInvoices = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { month: number; year: number }) => {
      const { data: response } = await axios.post('/api/admin/invoices/generate', data);
      return response;
    },
    onSuccess: (data) => {
      queryClient.invalidateQueries({ queryKey: ['invoices'] });
      toast.success(data.message);
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal generate invoice');
    }
  });
};
