import { Injectable, Injector } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpHeaders } from '@angular/common/http';
import { BaseApiService } from '../api/base-api.service';
import { User } from './user.model';

@Injectable({
  providedIn: 'root',
})
export class UsersApiService extends BaseApiService {
  constructor(injector: Injector) {
    super(injector);
  }

  getAll(): Observable<User[]> {
    return this.http.get<User[]>(this.apiUrl + '/users');
  }

  create(data: any): Observable<User> {
    const restaurantId = data.restaurant_uuid || '00000000-0000-0000-0000-000000000000';
    
    // Inyectamos la cabecera exacta obligatoria que valida el CreateUserController de PHP
    const headers = new HttpHeaders({
      'X-Restaurant-Id': restaurantId
    });

    return this.http.post<User>(this.apiUrl + '/users', data, { headers });
  }

  update(uuid: string, data: any): Observable<User> {
  return this.http.put<User>(`${this.apiUrl}/users/${uuid}`, data);
}

  delete(uuid: string): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/users/${uuid}`);
  }
}