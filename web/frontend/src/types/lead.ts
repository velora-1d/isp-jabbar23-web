import { Package } from "@/types/package";
import { User } from "@/types/user";
import { Customer } from "@/types/customer";

export type LeadStatus = 'new' | 'contacted' | 'qualified' | 'proposal' | 'negotiation' | 'won' | 'lost';
export type LeadSource = 'website' | 'whatsapp' | 'referral' | 'walk-in' | 'social_media' | 'other';

export interface Lead {
  id: number;
  lead_number: string;
  name: string;
  phone: string | null;
  email: string | null;
  address: string | null;
  rt_rw: string | null;
  kelurahan: string | null;
  kecamatan: string | null;
  kabupaten: string | null;
  provinsi: string | null;
  kode_pos: string | null;
  latitude: number | null;
  longitude: number | null;
  source: LeadSource;
  interested_package_id: number | null;
  interested_package?: Package;
  assigned_to: number | null;
  assigned_sales?: User;
  status: LeadStatus;
  notes: string | null;
  converted_at: string | null;
  customer_id: number | null;
  customer?: Customer;
  created_at: string;
  updated_at: string;
}
