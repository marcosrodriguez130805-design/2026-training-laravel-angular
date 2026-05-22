export interface Table {
  uuid: string;
  restaurant_uuid: string; // O restaurantUuid según el transformer de tu API
  zone_uuid: string;       // O zoneUuid según el transformer de tu API
  name: string;
  created_at: string;
  updated_at: string;
}