export interface WorkOrder {
    id: string;
    ticket_number: string;
    customer_id: number | null;
    type: 'installation' | 'repair' | 'dismantling' | 'survey' | 'maintenance';
    status: 'pending' | 'scheduled' | 'on_way' | 'in_progress' | 'completed' | 'cancelled';
    priority: 'low' | 'medium' | 'high' | 'critical';
    scheduled_date: string | null;
    completed_date: string | null;
    technician_id: number | null;
    description: string;
    technician_notes: string | null;
    created_at?: string;
    customer?: {
        id: number;
        name: string;
    };
    technician?: {
        id: number;
        name: string;
    };
    items?: Array<{
        id: number;
        inventory_item_id: number;
        quantity: number;
        unit: string;
        inventory_item?: {
            name: string;
        };
    }>;
}
