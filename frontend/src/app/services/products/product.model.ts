export interface Product {
  uuid: string;
  restaurant_uuid: string;
  family_uuid: string;
  tax_uuid: string;    // <--- Añade esto
  name: string;
  description?: string;
  price: number;
  stock: number;      // <--- Añade esto
  active: boolean;
  image_src?: string;
  created_at: string;
  updated_at: string;
}