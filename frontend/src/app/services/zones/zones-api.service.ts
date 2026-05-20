import { Injectable, Injector } from '@angular/core';
import { Observable } from 'rxjs';
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

  create(data: Partial<Zone>): Observable<Zone> {
    return this.http.post<Zone>(this.apiUrl + '/zones', data);
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