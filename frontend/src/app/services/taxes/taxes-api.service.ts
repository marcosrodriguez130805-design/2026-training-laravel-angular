import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable } from 'rxjs';
import { Tax } from './tax.model';

@Injectable({ providedIn: 'root' })
export class TaxesApiService {
  private http = inject(HttpClient);
  private apiUrl = 'http://localhost:8000/api/taxes'; 

  private getHeaders(): HttpHeaders {
    const restaurantUuid = localStorage.getItem('restaurant_uuid') || '';
    return new HttpHeaders({
      'X-Restaurant-Id': restaurantUuid
    });
  }

  getAll(): Observable<Tax[]> {
    return this.http.get<Tax[]>(this.apiUrl, { headers: this.getHeaders() });
  }

  create(data: any): Observable<Tax> {
    return this.http.post<Tax>(this.apiUrl, data, { headers: this.getHeaders() });
  }

  update(uuid: string, data: any): Observable<Tax> {
    return this.http.put<Tax>(`${this.apiUrl}/${uuid}`, data, { headers: this.getHeaders() });
  }

  delete(uuid: string): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${uuid}`, { headers: this.getHeaders() });
  }
}