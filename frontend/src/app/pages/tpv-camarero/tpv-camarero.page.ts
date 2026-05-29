import { Component, inject, OnInit } from '@angular/core';
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
  IonButton,
  IonIcon,
  IonModal,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonButtons,
  IonText,
  AlertController
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { add, personCircle } from 'ionicons/icons';
import { MesaService } from '../../services/mesa/mesa.service';
import { UsersApiService } from '../../services/users/users-api.service';
import { User } from '../../services/users/user.model';

@Component({
  selector: 'app-tpv-camarero-page',
  templateUrl: './tpv-camarero.page.html',
  standalone: true,
  styleUrls: ['./tpv-camarero.page.scss'],
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
    IonButton,
    IonIcon,
    IonModal,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonButtons,
    IonText,
  ],
})
export class TpvCamareroPage implements OnInit {
  private router = inject(Router);
  private mesaService = inject(MesaService);
  private usersApiService = inject(UsersApiService);
  private alertCtrl = inject(AlertController);

  camareros: User[] = [];
  selectedCamarero: User | null = null;
  pin = '';
  isPinModalOpen = false;
  isLoading = false;

  constructor() {
    addIcons({ add, personCircle });
    this.mesaService.resetCamarero();
  }

  ngOnInit(): void {
    this.loadCamareros();
  }

  private loadCamareros(): void {
    this.isLoading = true;
    this.usersApiService.getAll().subscribe({
      next: (users) => {
        this.isLoading = false;
        this.camareros = users;
      },
      error: async () => {
        this.isLoading = false;
        const alert = await this.alertCtrl.create({
          header: 'Error',
          message: 'No se pudieron cargar los camareros. Intenta de nuevo más tarde.',
          buttons: ['Aceptar'],
        });
        await alert.present();
      },
    });
  }

  openPinModal(camarero: User): void {
    this.selectedCamarero = camarero;
    this.pin = '';
    this.isPinModalOpen = true;
  }

  closePinModal(): void {
    this.isPinModalOpen = false;
    this.selectedCamarero = null;
    this.pin = '';
  }

  teclearNumero(numero: number): void {
    if (this.pin.length >= 6) {
      return;
    }
    this.pin += String(numero);
  }

  borrarPin(): void {
    this.pin = '';
  }

  async confirmarPin(): Promise<void> {
    if (!this.selectedCamarero) {
      return;
    }

    const pinValido = this.selectedCamarero.pin && this.selectedCamarero.pin === this.pin;

    if (pinValido) {
      this.mesaService.seleccionarCamarero(this.selectedCamarero.uuid);
      this.isPinModalOpen = false;
      this.router.navigate(['/tpv-mesas']);
      return;
    }

    const alert = await this.alertCtrl.create({
      header: 'PIN incorrecto',
      message: 'El PIN ingresado no coincide. Intenta de nuevo.',
      buttons: ['Aceptar'],
    });
    await alert.present();
    this.borrarPin();
  }
}
