import { Injectable, Injector } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpHeaders } from '@angular/common/http'; // 🛠️ NUEVO: Importamos las cabeceras de Angular
import { BaseApiService } from '../api/base-api.service';
import { Zone } from './zone.model';

@Injectable({
  providedIn: 'root',
})
export class ZonesApiService extends BaseApiService {
  constructor(injector: Injector) {
    super(injector);
  }

  getAll(): Observable<Zone[]> {
    return this.http.get<Zone[]>(this.apiUrl + '/zones');
  }

  getOne(uuid: string): Observable<Zone> {
    return this.http.get<Zone>(`${this.apiUrl}/zones/${uuid}`);
  }

  // 🛠️ MODIFICADO: Añadimos la cabecera que exige el controlador de PHP
  create(data: Partial<Zone>): Observable<Zone> {
    // Sacamos el UUID del restaurante que metimos en el payload de la página
    const restaurantId = data.restaurant_uuid || (data as any).restaurantUuid || '00000000-0000-0000-0000-000000000000';

    // Creamos la cabecera exacta con el nombre "X-Restaurant-Id"
    const headers = new HttpHeaders({
      'X-Restaurant-Id': restaurantId
    });

    // Pasamos las headers como tercer parámetro en el .post()
    return this.http.post<Zone>(this.apiUrl + '/zones', data, { headers });
  }

  update(uuid: string, data: Partial<Zone>): Observable<Zone> {
    return this.http.put<Zone>(`${this.apiUrl}/zones/${uuid}`, data);
  }

  delete(uuid: string): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/zones/${uuid}`);
  }

  toggleActive(uuid: string): Observable<Zone> {
    return this.http.patch<Zone>(`${this.apiUrl}/zones/${uuid}/toggle-active`, {});
  }
}