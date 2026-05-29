import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import {
  IonContent,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardContent,
  IonIcon,
  IonButton,
  IonGrid,
  IonRow,
  IonCol,
  IonHeader,
  IonToolbar,
  IonTitle
} from '@ionic/angular/standalone';

@Component({
  selector: 'app-selector-entorno-page',
  templateUrl: './selector-entorno.page.html',
  standalone: true,
  imports: [
    CommonModule,
    IonContent,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardContent,
    IonIcon,
    IonButton,
    IonGrid,
    IonRow,
    IonCol,
    IonHeader,
    IonToolbar,
    IonTitle
  ]
})
export class SelectorEntornoPage {
  private router = inject(Router);

  navigateToBackoffice() {
    this.router.navigate(['/backoffice']);
  }

  navigateToTpv() {
    this.router.navigate(['/tpv']);
  }
}
