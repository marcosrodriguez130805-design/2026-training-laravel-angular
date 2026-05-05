import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { RouterModule } from '@angular/router';
import { IonIcon, IonButton } from '@ionic/angular/standalone';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../services/auth/auth.service';
import { addIcons } from 'ionicons';
import { peopleOutline, receiptOutline, fastFoodOutline, mapOutline, gridOutline, logOutOutline } from 'ionicons/icons';

addIcons({ peopleOutline, receiptOutline, fastFoodOutline, mapOutline, gridOutline, logOutOutline });

@Component({
  selector: 'app-backoffice',
  templateUrl: 'backoffice.page.html',
  styleUrls: ['backoffice.page.scss'],
  imports: [CommonModule, RouterModule, IonIcon, IonButton],
  standalone: true,
})
export class BackofficePage {
  private authService = inject(AuthService);
  private router = inject(Router);

  get userName(): string | null {
  return this.authService.getName();
}

  logout(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}