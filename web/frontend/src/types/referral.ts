export type ReferralStatus = 'pending' | 'qualified' | 'rewarded' | 'expired';

export interface Referral {
  id: number;
  code: string;
  referrer_id: number;
  referred_id: number | null;
  status: ReferralStatus;
  reward_amount: number;
  reward_paid: boolean;
  qualified_at: string | null;
  rewarded_at: string | null;
  created_at: string;
  updated_at: string;
  referrer?: {
    id: number;
    name: string;
  };
  referred?: {
    id: number;
    name: string;
  };
}

export interface ReferralStats {
  total_referrals: number;
  total_qualified: number;
  total_reward_paid: number;
  pending_rewards: number;
}
