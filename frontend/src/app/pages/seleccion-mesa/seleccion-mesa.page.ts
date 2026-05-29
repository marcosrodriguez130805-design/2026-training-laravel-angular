import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import {
  IonContent,
  IonGrid,
  IonRow,
  IonCol,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardContent,
  IonBadge,
  IonIcon,
  IonHeader,
  IonToolbar,
  IonTitle
} from '@ionic/angular/standalone';
import { MesaService, Mesa } from '../../services/mesa/mesa.service';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-seleccion-mesa-page',
  templateUrl: './seleccion-mesa.page.html',
  standalone: true,
  imports: [
    CommonModule,
    IonContent,
    IonGrid,
    IonRow,
    IonCol,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardContent,
    IonBadge,
    IonIcon,
    IonHeader,
    IonToolbar,
    IonTitle,
  ],
})
export class SeleccionMesaPage {
  private router = inject(Router);
  private mesaService = inject(MesaService);

  mesas$: Observable<Mesa[]> = this.mesaService.mesas$;

  selectMesa(id: string): void {
    this.mesaService.seleccionarMesa(id);
    this.router.navigate(['/tpv-comanda']);
  }

  statusLabel(estado: Mesa['estado']): string {
    switch (estado) {
      case 'libre':
        return 'Libre';
      case 'ocupada':
        return 'Ocupada';
      case 'cuenta':
        return 'Cuenta';
      default:
        return 'Desconocido';
    }
  }
}
