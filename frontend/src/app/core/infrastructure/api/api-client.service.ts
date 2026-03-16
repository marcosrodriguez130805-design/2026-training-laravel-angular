import { Injectable, inject, InjectionToken } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import type { CreateUserRequest, CreateUserResponse } from '../../../shared/models/user-api.models';

export const API_BASE_URL = new InjectionToken<string>('API_BASE_URL');

@Injectable({ providedIn: 'root' })
export class ApiClientService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = inject(API_BASE_URL);

  createUser(body: CreateUserRequest): Observable<CreateUserResponse> {
    return this.post<CreateUserResponse>('/users', body);
  }

  post<T>(path: string, body: unknown): Observable<T> {
    const url = `${this.baseUrl}${path.startsWith('/') ? path : `/${path}`}`;
    return this.http.post<T>(url, body);
  }

  get<T>(path: string): Observable<T> {
    const url = `${this.baseUrl}${path.startsWith('/') ? path : `/${path}`}`;
    return this.http.get<T>(url);
  }
}
