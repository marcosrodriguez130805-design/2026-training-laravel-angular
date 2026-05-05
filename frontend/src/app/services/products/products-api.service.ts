import { Injectable, Injector } from '@angular/core';
import { Observable } from 'rxjs';
import { BaseApiService } from '../api/base-api.service';
import { Product } from './product.model';

@Injectable({
  providedIn: 'root',
})
export class ProductsApiService extends BaseApiService {
  constructor(injector: Injector) {
    super(injector);
  }

  getAll(): Observable<Product[]> {
    return this.http.get<Product[]>(this.apiUrl + '/products');
  }

  getOne(uuid: string): Observable<Product> {
    return this.http.get<Product>(`${this.apiUrl}/products/${uuid}`);
  }

  create(data: { family_uuid: string; name: string; description?: string; price: number; active: boolean }): Observable<Product> {
    return this.http.post<Product>(this.apiUrl + '/products', data);
  }

  update(uuid: string, data: { family_uuid: string; name: string; description?: string; price: number; active: boolean }): Observable<Product> {
    return this.http.put<Product>(`${this.apiUrl}/products/${uuid}`, data);
  }

  delete(uuid: string): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/products/${uuid}`);
  }

  toggleActive(uuid: string): Observable<Product> {
    return this.http.patch<Product>(`${this.apiUrl}/products/${uuid}/toggle-active`, {});
  }
}