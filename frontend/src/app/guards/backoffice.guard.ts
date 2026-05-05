import { inject } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth/auth.service';

export const backofficeGuard = () => {
  const authService = inject(AuthService);
  const router = inject(Router);

  if (authService.isBackofficeUser()) {
    return true;
  } else {
    router.navigate(['/login']);
    return false;
  }
};