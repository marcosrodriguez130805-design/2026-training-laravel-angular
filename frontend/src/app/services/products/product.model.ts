export interface Product {
  uuid: string;
  restaurant_uuid: string;
  family_uuid: string;
  name: string;
  description?: string;
  price: number;
  active: boolean;
  created_at: string;
  updated_at: string;
}