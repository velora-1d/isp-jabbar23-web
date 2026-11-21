export interface Olt {
  id: number;
  name: string;
  ip_address: string | null;
  brand: string | null;
  type: string;
  total_pon_ports: number;
  location: string | null;
  status: 'active' | 'offline' | 'maintenance';
  username: string | null;
  port: number | null;
  community: string | null;
  server_profile: string | null;
  created_at: string;
  updated_at: string;
}

export interface Odp {
  id: number;
  name: string;
  address: string | null;
  latitude: number | null;
  longitude: number | null;
  total_ports: number;
  description: string | null;
  status: 'active' | 'maintenance' | 'full';
  coordinates: string;
  created_at: string;
  updated_at: string;
}

export type CreateOltRequest = Omit<Olt, 'id' | 'created_at' | 'updated_at'>;
export type CreateOdpRequest = Omit<Odp, 'id' | 'coordinates' | 'created_at' | 'updated_at'>;
