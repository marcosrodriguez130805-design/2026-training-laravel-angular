import { inject, Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpEvent, HttpHandler, HttpInterceptor, HttpRequest } from '@angular/common/http';
import { AuthService } from '../services/auth/auth.service';

@Injectable()
export class InterceptorProvider implements HttpInterceptor {
  private authService = inject(AuthService);

  /**
   * Intercepta las peticiones HTTP y les añade las cabeceras por defecto
   * 
   */
  intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    return next.handle(this.setHeader(request));
  }


  /**
   * Clona la petición añadiendo las cabeceras
   * 
   */
  private setHeader(request: HttpRequest<any>): HttpRequest<any> {
    const token = this.authService.getToken();
    const restaurantId = this.authService.getRestaurantId();

    const headers: Record<string, string> = {
      Accept: 'application/json',
      'Accept-Language': 'es',
    };

    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    if (restaurantId) {
      headers['X-Restaurant-Id'] = restaurantId;
    }

    return request.clone({
      setHeaders: headers,
    });
  }

}
