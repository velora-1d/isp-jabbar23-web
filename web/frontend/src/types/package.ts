export interface Package {
  id: number;
  name: string;
  speed_down: number;
  speed_up: number;
  price: string | number;
  description: string | null;
  is_active: boolean;
  formatted_price: string;
  formatted_speed: string;
  created_at: string;
  updated_at: string;
}

export interface CreatePackageRequest {
  name: string;
  speed_down: number;
  speed_up: number;
  price: number;
  description?: string;
  is_active: boolean;
}

export interface UpdatePackageRequest extends Partial<CreatePackageRequest> {}
