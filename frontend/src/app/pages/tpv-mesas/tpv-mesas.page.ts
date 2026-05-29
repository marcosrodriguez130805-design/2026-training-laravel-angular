import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
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
  IonTitle,
  IonText,
  IonButton,
  IonButtons,
  IonToast,
} from '@ionic/angular/standalone';
import { MesaService, Mesa } from '../../services/mesa/mesa.service';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-tpv-mesas-page',
  templateUrl: './tpv-mesas.page.html',
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
    IonText,
    IonButton,
    IonButtons,
    IonToast,
  ],
})
export class TpvMesasPage {
  private router = inject(Router);
  private mesaService = inject(MesaService);

  mesas$: Observable<Mesa[]> = this.mesaService.mesas$;
  modoFusion = false;
  mesaOrigenSeleccionada: Mesa | null = null;
  mesaDestinoSeleccionada: Mesa | null = null;
  showToast = false;
  toastMessage = '';

  seleccionarMesa(mesa: Mesa): void {
    if (!this.modoFusion) {
      this.mesaService.seleccionarMesa(mesa.id);
      this.router.navigate(['/tpv-comanda']);
      return;
    }

    if (!this.mesaOrigenSeleccionada) {
      if (mesa.estado === 'libre') {
        window.alert('La mesa origen debe estar ocupada. Selecciona otra mesa.');
        return;
      }

      this.mesaOrigenSeleccionada = mesa;
      return;
    }

    if (this.mesaOrigenSeleccionada.id === mesa.id) {
      window.alert('No puedes seleccionar la misma mesa como destino. Elige otra mesa.');
      return;
    }

    this.mesaDestinoSeleccionada = mesa;
    this.mesaService.fusionarMesas(Number(this.mesaOrigenSeleccionada.id), Number(this.mesaDestinoSeleccionada.id));
    this.toastMessage = `Mesas ${this.mesaOrigenSeleccionada.nombre} y ${this.mesaDestinoSeleccionada.nombre} se han juntado.`;
    this.showToast = true;
    this.modoFusion = false;
    this.mesaOrigenSeleccionada = null;
    this.mesaDestinoSeleccionada = null;
  }

  toggleModoFusion(): void {
    this.modoFusion = !this.modoFusion;

    if (!this.modoFusion) {
      this.mesaOrigenSeleccionada = null;
      this.mesaDestinoSeleccionada = null;
    }
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
