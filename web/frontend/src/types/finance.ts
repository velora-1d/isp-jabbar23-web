export interface Invoice {
  id: number;
  invoice_number: string;
  customer_id: number;
  amount: number;
  tax_amount: number;
  total_after_tax: number;
  period_start: string;
  period_end: string;
  due_date: string;
  status: 'unpaid' | 'partial' | 'paid' | 'overdue' | 'cancelled';
  payment_date: string | null;
  payment_method: string | null;
  created_at: string;
  updated_at: string;
  customer?: {
    id: number;
    customer_id: string;
    name: string;
    package?: {
      id: number;
      name: string;
      price: number;
    };
  };
}

export interface Expense {
  id: number;
  category: string;
  amount: number;
  description: string;
  receipt_path: string | null;
  date: string;
  created_by: number;
  created_at: string;
  updated_at: string;
  creator?: {
    id: number;
    name: string;
  };
}

export interface Attendance {
  id: number;
  user_id: number;
  date: string;
  clock_in: string | null;
  clock_out: string | null;
  status: 'present' | 'late' | 'absent' | 'sick' | 'leave' | 'holiday';
  clock_in_location: string | null;
  clock_out_location: string | null;
  photo_in: string | null;
  photo_out: string | null;
  notes: string | null;
  user?: {
    id: number;
    name: string;
  };
}
