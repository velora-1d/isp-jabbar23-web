import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { Invoice } from '@/types/finance';
import { toast } from 'sonner';

export const useInvoices = (params?: any) => {
  const queryClient = useQueryClient();

  const { data: invoices, isLoading } = useQuery({
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
      toast.success(data.message);
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal generate invoice');
    }
  });

  const payInvoice = useMutation({
    mutationFn: async ({ id, method }: { id: number; method: string }) => {
      const { data } = await axios.post(`/api/admin/invoices/${id}/pay`, { payment_method: method });
      return data;
    },
    onSuccess: () => {
      toast.success('Pembayaran berhasil dicatat');
    }
  });

  const getSnapToken = useMutation({
    mutationFn: async (id: number) => {
      const { data } = await axios.get(`/api/admin/invoices/${id}/snap-token`);
      return data;
    }
  });

  return {
    invoices,
    isLoading,
    generateInvoices,
    payInvoice,
    getSnapToken,
  };
};
