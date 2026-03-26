import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { Contract, CreateContractInput, SignContractInput } from '@/types/contract';
import { toast } from 'sonner';

export const useContracts = (customerId?: number) => {
  const queryClient = useQueryClient();

  const { data: contracts, isLoading } = useQuery({
    queryKey: ['contracts', customerId],
    queryFn: async () => {
      const { data } = await axios.get('/api/admin/contracts', {
        params: { customer_id: customerId }
      });
      return data.data as Contract[];
    },
    enabled: !!customerId || customerId === undefined,
  });

  const createContract = useMutation({
    mutationFn: async (input: CreateContractInput) => {
      const { data } = await axios.post('/api/admin/contracts', input);
      return data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['contracts'] });
      toast.success('Draf kontrak berhasil dibuat');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal membuat kontrak');
    }
  });

  const signContract = useMutation({
    mutationFn: async ({ id, signature }: { id: number; signature: string }) => {
      const { data } = await axios.post(`/api/admin/contracts/${id}/sign`, { signature });
      return data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['contracts'] });
      toast.success('Kontrak berhasil ditandatangani digital');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal menandatangani kontrak');
    }
  });

  return {
    contracts,
    isLoading,
    createContract,
    signContract,
  };
};
