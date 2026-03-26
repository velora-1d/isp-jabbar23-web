export interface StatusLog {
    id: number;
    status: string;
    status_label: string;
    previous_status: string | null;
    previous_status_label: string;
    notes: string | null;
    changed_at: string;
    changed_by_user?: {
        id: number;
        name: string;
    };
}

export interface Invoice {
    id: number;
    invoice_number: string;
    amount: number;
    status: 'unpaid' | 'paid' | 'cancelled' | 'pending';
    due_date: string;
    paid_at: string | null;
}

export interface Customer {
    id: string;
    customer_id: string;
    name: string;
    phone: string | null;
    email: string | null;
    address: string;
    rt_rw: string | null;
    kelurahan: string;
    kecamatan: string;
    kabupaten: string;
    provinsi: string;
    status: string;
    status_label: string;
    status_color: string;
    created_at: string;
    package?: {
        id: number;
        name: string;
        price: number;
    };
    partner?: {
        id: number;
        name: string;
    };
    technician?: {
        id: number;
        name: string;
    };
    router?: {
        id: number;
        name: string;
    };
    olt?: {
        id: number;
        name: string;
    };
    status_logs?: StatusLog[];
    invoices?: Invoice[];
    pppoe_username?: string;
    mikrotik_ip?: string;
}

export interface CustomerStats {
    total: number;
    active: number;
    pending: number;
    suspended: number;
}

export interface FilterOptions {
    statuses: Record<string, string>;
    packages: { id: number; name: string }[];
    locations: {
        kelurahan: string[];
        kecamatan: string[];
        kabupaten: string[];
        provinsi: string[];
    };
}

export interface CustomerFormData {
    packages: Array<{ id: number; name: string; price: number }>;
    technicians: Array<{ id: number; name: string }>;
    olts: Array<{ id: number; name: string; type: string }>;
    statuses: Record<string, string>;
}

export interface CustomersResponse {
    customers: {
        data: Customer[];
        current_page: number;
        last_page: number;
        total: number;
    };
    stats: CustomerStats;
    options: FilterOptions;
}
