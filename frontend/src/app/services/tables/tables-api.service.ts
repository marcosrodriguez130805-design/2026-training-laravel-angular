import { Injectable, Injector } from '@angular/core';
import { Observable } from 'rxjs';
import { BaseApiService } from '../api/base-api.service';
import { Table } from './table.model';

@Injectable({
  providedIn: 'root',
})
export class TablesApiService extends BaseApiService {
  constructor(injector: Injector) {
    super(injector);
  }

  // Obtener todas las mesas del restaurante
  getAll(): Observable<Table[]> {
    return this.http.get<Table[]>(`${this.apiUrl}/tables`);
  }

  // Obtener una mesa concreta por su UUID
  getOne(uuid: string): Observable<Table> {
    return this.http.get<Table>(`${this.apiUrl}/tables/${uuid}`);
  }

  // Crear una mesa nueva (exige pasar los UUIDs correspondientes)
  create(data: Partial<Table>): Observable<Table> {
    return this.http.post<Table>(`${this.apiUrl}/tables`, data);
  }

  // Modificar el nombre de la mesa (siguiendo el método update de tu entidad PHP)
  update(uuid: string, data: Partial<Table>): Observable<Table> {
    return this.http.put<Table>(`${this.apiUrl}/tables/${uuid}`, data);
  }

  // Eliminar la mesa de la base de datos
  delete(uuid: string): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/tables/${uuid}`);
  }
}