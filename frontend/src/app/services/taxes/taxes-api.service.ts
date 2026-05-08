import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable } from 'rxjs';

export interface Tax {
  uuid: string;
  name: string;
  percentage: number;
  active: boolean;
}

@Injectable({ providedIn: 'root' })
export class TaxesApiService {
  private http = inject(HttpClient);
  private apiUrl = 'http://localhost:8000/api/taxes'; 

  /**
   * Obtiene todos los impuestos. 
   * Es vital enviar el X-Restaurant-Id para que el CreateTaxController 
   * y los ListControllers de tu backend no rechacen la petición.
   */
  getAll(): Observable<Tax[]> {
    // Intentamos obtener el ID del restaurante desde el almacenamiento local
    const restaurantUuid = localStorage.getItem('restaurant_uuid') || 'tu-uuid-de-restaurante-por-defecto';

    const headers = new HttpHeaders({
      'X-Restaurant-Id': restaurantUuid
    });

    return this.http.get<Tax[]>(this.apiUrl, { headers });
  }
}