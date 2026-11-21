'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import axios from '@/lib/axios';
import { toast } from 'sonner';

export function useMarketing() {
    const queryClient = useQueryClient();

    // Promotions
    const promotionsQuery = useQuery({
        queryKey: ['promotions'],
        queryFn: async () => {
            const response = await axios.get('/api/admin/promotions');
            return response.data;
        }
    });

    const createPromotion = useMutation({
        mutationFn: async (data: any) => {
            const response = await axios.post('/api/admin/promotions', data);
            return response.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['promotions'] });
            toast.success('Promosi berhasil dibuat');
        },
        onError: (error: any) => {
            toast.error(error.response?.data?.message || 'Gagal membuat promosi');
        }
    });

    const deletePromotion = useMutation({
        mutationFn: async (id: string) => {
            await axios.delete(`/api/admin/promotions/${id}`);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['promotions'] });
            toast.success('Promosi berhasil dihapus');
        }
    });

    // Referrals
    const referralsQuery = useQuery({
        queryKey: ['referrals'],
        queryFn: async () => {
            const response = await axios.get('/api/admin/referrals');
            return response.data;
        }
    });

    const referralStatsQuery = useQuery({
        queryKey: ['referral-stats'],
        queryFn: async () => {
            const response = await axios.get('/api/admin/referrals/stats');
            return response.data;
        }
    });

    const payoutReferral = useMutation({
        mutationFn: async (id: string) => {
            const response = await axios.post(`/api/admin/referrals/${id}/payout`);
            return response.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['referrals'] });
            queryClient.invalidateQueries({ queryKey: ['referral-stats'] });
            toast.success('Reward referral berhasil dicairkan');
        },
        onError: (error: any) => {
            toast.error(error.response?.data?.message || 'Gagal mencairkan reward');
        }
    });

    return {
        promotions: promotionsQuery.data?.data || [],
        isLoadingPromotions: promotionsQuery.isLoading,
        createPromotion,
        deletePromotion,

        referrals: referralsQuery.data?.data || [],
        isLoadingReferrals: referralsQuery.isLoading,
        referralStats: referralStatsQuery.data || null,
        payoutReferral
    };
}
