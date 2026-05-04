import { inject, Injectable } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { AuthApiService, AuthLoginResponse } from './auth-api.service';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private authApiService = inject(AuthApiService);
  private readonly tokenKey = 'token';
  private readonly restaurantKey = 'restaurant_uuid';

  login(email: string, password: string): Observable<AuthLoginResponse> {
    return this.authApiService.login(email, password).pipe(
      tap((response: AuthLoginResponse) => {
        localStorage.setItem(this.tokenKey, response.token);
        localStorage.setItem(this.restaurantKey, response.restaurant_uuid);
      })
    );
  }

  logout(): void {
    localStorage.removeItem(this.tokenKey);
    localStorage.removeItem(this.restaurantKey);
  }

  getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  getRestaurantId(): string | null {
    return localStorage.getItem(this.restaurantKey);
  }

  isAuthenticated(): boolean {
    return !!this.getToken();
  }
}
