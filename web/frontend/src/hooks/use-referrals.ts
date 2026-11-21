import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { Referral, ReferralStats } from '@/types/referral';
import { toast } from 'sonner';

export const useReferrals = (params?: { referrer_id?: number; status?: string }) => {
  const queryClient = useQueryClient();

  const { data: referrals, isLoading } = useQuery({
    queryKey: ['referrals', params],
    queryFn: async () => {
      const { data } = await axios.get('/api/admin/referrals', { params });
      return data.data as Referral[];
    },
  });

  const { data: stats } = useQuery({
    queryKey: ['referrals-stats'],
    queryFn: async () => {
      const { data } = await axios.get('/api/admin/referrals/stats');
      return data as ReferralStats;
    },
  });

  const payReward = useMutation({
    mutationFn: async (id: number) => {
      const { data } = await axios.post(`/api/admin/referrals/${id}/pay`);
      return data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['referrals'] });
      queryClient.invalidateQueries({ queryKey: ['referrals-stats'] });
      toast.success('Pembayaran reward berhasil dicatat');
    },
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Gagal memproses pembayaran');
    }
  });

  return {
    referrals,
    stats,
    isLoading,
    payReward,
  };
};
