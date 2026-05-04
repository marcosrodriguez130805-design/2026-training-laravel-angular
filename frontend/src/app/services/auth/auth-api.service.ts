import { Injectable, Injector } from '@angular/core';
import { Observable } from 'rxjs';
import { BaseApiService } from '../api/base-api.service';

export interface AuthLoginResponse {
  uuid: string;
  restaurant_uuid: string;
  name: string;
  email: string;
  token: string;
}

@Injectable({
  providedIn: 'root',
})
export class AuthApiService extends BaseApiService {

  constructor(injector: Injector) {
    super(injector);
  }

  login(email: string, password: string): Observable<AuthLoginResponse> {
    return this.http.post<AuthLoginResponse>(this.apiUrl + '/login', {
      email,
      password,
    });
  }
}