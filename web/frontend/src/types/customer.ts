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
    kelurahan: string | null;
    kecamatan: string | null;
    kabupaten: string | null;
    provinsi: string | null;
    kode_pos: string | null;
    latitude?: number;
    longitude?: number;
    package_id: number;
    status: string;
    status_label: string;
    status_color: string;
    installation_date: string | null;
    billing_date: string | null;
    notes: string | null;
    created_at: string;
    updated_at: string;
    assigned_to: number | null;
    team_size: number | null;
    payment_token: string | null;
    partner_id: number | null;
    router_id: number | null;
    pppoe_username: string | null;
    pppoe_password: string | null;
    mikrotik_ip: string | null;
    olt_id: number | null;
    onu_index: string | null;
    ktp_number: string | null;
    odp_port: string | null;
    // Relations (for display)
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
    partners: Array<{ id: number; name: string }>;
    olts: Array<{ id: number; name: string; type: string }>;
    routers: Array<{ id: number; name: string }>;
    pppoe_profiles: Array<{ id: string; name: string }>;
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
