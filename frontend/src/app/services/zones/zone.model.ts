export interface Zone {
  uuid: string;
  restaurant_uuid: string; // Mapeado del backend snake_case
  name: string;
  active: boolean;
  created_at: string;
  updated_at: string;
}