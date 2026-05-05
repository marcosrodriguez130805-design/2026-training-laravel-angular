import { Injectable, Injector } from '@angular/core';
import { Observable } from 'rxjs';
import { BaseApiService } from '../api/base-api.service';

export interface Family {
  uuid: string;
  restaurant_uuid: string;
  name: string;
  active: boolean;
  created_at: string;
  updated_at: string;
}

@Injectable({
  providedIn: 'root',
})
export class FamiliesApiService extends BaseApiService {
  constructor(injector: Injector) {
    super(injector);
  }

  getAll(): Observable<Family[]> {
    return this.http.get<Family[]>(this.apiUrl + '/families');
  }

  getOne(uuid: string): Observable<Family> {
    return this.http.get<Family>(`${this.apiUrl}/families/${uuid}`);
  }

  create(data: { name: string; active: boolean }): Observable<Family> {
    return this.http.post<Family>(this.apiUrl + '/families', data);
  }

  update(uuid: string, data: { name: string; active: boolean }): Observable<Family> {
    return this.http.put<Family>(`${this.apiUrl}/families/${uuid}`, data);
  }

  delete(uuid: string): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/families/${uuid}`);
  }

  toggleActive(uuid: string): Observable<Family> {
    return this.http.patch<Family>(`${this.apiUrl}/families/${uuid}/toggle-active`, {});
  }
}
