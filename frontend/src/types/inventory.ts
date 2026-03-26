export interface InventoryCategory {
  id: number;
  name: string;
  slug: string;
  description: string | null;
}

export interface Location {
  id: number;
  name: string;
  type: 'warehouse' | 'vehicle' | 'site';
  address: string | null;
  is_active: boolean;
}

export interface Stock {
  id: number;
  inventory_item_id: number;
  location_id: number;
  quantity: number;
  location?: Location;
}

export interface InventorySerial {
  id: number;
  inventory_item_id: number;
  serial_number: string;
  status: 'available' | 'assigned' | 'defective' | 'returned';
  customer_id: number | null;
  location_id: number | null;
}

export interface InventoryItem {
  id: number;
  category_id: number;
  sku: string | null;
  name: string;
  description: string | null;
  unit: string;
  min_stock_alert: number;
  purchase_price: number;
  selling_price: number;
  is_active: boolean;
  category?: InventoryCategory;
  stocks?: Stock[];
  total_stock?: number;
}

export interface InventoryTransaction {
  id: number;
  inventory_item_id: number;
  inventory_serial_id: number | null;
  type: 'in' | 'out' | 'transfer' | 'adjustment';
  quantity: number;
  reference_no: string | null;
  user_id: number;
  notes: string | null;
  created_at: string;
}
