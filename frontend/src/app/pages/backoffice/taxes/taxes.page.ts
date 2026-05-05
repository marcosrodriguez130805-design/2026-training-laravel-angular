import { Component } from '@angular/core';
import { IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/angular/standalone';

@Component({
  selector: 'app-taxes',
  template: `
    <ion-header>
      <ion-toolbar>
        <ion-title>Impuestos</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content class="page-content">
      <div class="placeholder">
        <h2>Gestión de Impuestos</h2>
        <p>Aquí se mostrará la configuración de impuestos del backoffice.</p>
      </div>
    </ion-content>
  `,
  imports: [IonHeader, IonToolbar, IonTitle, IonContent],
  standalone: true,
})
export class TaxesPage {}