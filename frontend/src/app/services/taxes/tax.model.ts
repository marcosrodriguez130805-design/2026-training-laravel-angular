export interface Tax {
  uuid: string;
  name: string;
  percentage: number;       // Ej: 10 o 21
  active: boolean;
  created_at: string;  // ISO string
  updated_at: string;  // ISO string
}

export interface CreateTaxRequest {
  name: string;
  percentage: number;
  active: boolean;
}