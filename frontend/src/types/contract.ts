export type ContractStatus = 'active' | 'expired' | 'terminated' | 'draft';

export interface Contract {
  id: number;
  customer_id: number;
  contract_number: string;
  start_date: string;
  end_date: string | null;
  status: ContractStatus;
  terms: string | null;
  scanned_copy_path: string | null;
  digital_signature_path: string | null;
  signed_at: string | null;
  client_ip: string | null;
  created_at: string;
  updated_at: string;
  customer?: {
    id: number;
    name: string;
  };
}

export interface CreateContractInput {
  customer_id: number;
  start_date: string;
  end_date?: string;
  terms?: string;
}

export interface SignContractInput {
  signature: string; // Base64
}
