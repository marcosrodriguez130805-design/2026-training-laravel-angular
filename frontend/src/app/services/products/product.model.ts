export interface Product {
  uuid: string;
  restaurant_uuid: string;
  family_uuid: string;
  tax_uuid: string;
  name: string;
  description?: string;
  price: number;
  stock: number;
  active: boolean;
  image_src?: string;
  created_at: string;
  updated_at: string;
  suggested_product_uuid?: string;
  upselling_tip?: string;
}