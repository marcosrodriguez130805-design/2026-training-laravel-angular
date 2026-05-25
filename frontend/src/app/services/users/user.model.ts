export interface User {
  uuid: string;         // Viene del get/list como string
  restaurantUuid: string; // Ojo, tu entidad usa camelCase puro al mapear (restaurantUuid)
  role: string;
  name: string;
  email: string;
  pin: string | null;
  imageSrc: string | null;
  createdAt?: string;
  updatedAt?: string;
}