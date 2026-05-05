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
  private readonly roleKey = 'role';
  private readonly nameKey = 'name';

  login(email: string, password: string): Observable<AuthLoginResponse> {
    return this.authApiService.login(email, password).pipe(
      tap((response: AuthLoginResponse) => {
        localStorage.setItem(this.tokenKey, response.token);
        localStorage.setItem(this.restaurantKey, response.restaurant_uuid);
        localStorage.setItem(this.roleKey, response.role);
        localStorage.setItem(this.nameKey, response.name);
      })
    );
  }

  logout(): void {
    localStorage.removeItem(this.tokenKey);
    localStorage.removeItem(this.restaurantKey);
    localStorage.removeItem(this.roleKey);
    localStorage.removeItem(this.nameKey);
  }

  getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  getRestaurantId(): string | null {
    return localStorage.getItem(this.restaurantKey);
  }

  getRole(): string | null {
    return localStorage.getItem(this.roleKey);
  }

  getName(): string | null {
    return localStorage.getItem(this.nameKey);
  }

  isAuthenticated(): boolean {
    return !!this.getToken();
  }

  isBackofficeUser(): boolean {
    const role = this.getRole();
    return role === 'admin' || role === 'manager';
  }
}
