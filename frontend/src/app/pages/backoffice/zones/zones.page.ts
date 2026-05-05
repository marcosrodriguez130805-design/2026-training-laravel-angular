import { Component } from '@angular/core';
import { IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/angular/standalone';

@Component({
  selector: 'app-zones',
  template: `
    <ion-header>
      <ion-toolbar>
        <ion-title>Zonas</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content class="page-content">
      <div class="placeholder">
        <h2>Gestión de Zonas</h2>
        <p>Aquí se mostrarán las zonas del restaurante.</p>
      </div>
    </ion-content>
  `,
  imports: [IonHeader, IonToolbar, IonTitle, IonContent],
  standalone: true,
})
export class ZonesPage {}