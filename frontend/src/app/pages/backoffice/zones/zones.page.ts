import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { 
  IonContent, IonList, IonLabel, IonItem, 
  IonHeader, IonToolbar, IonIcon, IonText, 
  IonTitle, IonButtons, IonButton, IonToggle, 
  ToastController, AlertController, IonSpinner,
  IonMenuButton 
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { add, close, trash, create, folderOpenOutline } from 'ionicons/icons'; 
import { ZonesApiService } from '../../../services/zones/zones-api.service';
import { Zone } from '../../../services/zones/zone.model';
import { ZoneFormComponent } from '../zones/zone-form/zone-form.component';

@Component({
  selector: 'app-zones-page',
  templateUrl: './zones.page.html',
  styleUrls: ['./zones.page.scss'],
  standalone: true,
  imports: [
    CommonModule, IonContent, IonList, IonLabel, IonItem,
    IonHeader, IonToolbar, IonIcon, IonText, IonTitle, 
    IonButtons, IonButton, IonToggle, IonSpinner,
    IonMenuButton,
    ZoneFormComponent
  ]
})
export class ZonesPage implements OnInit {
  private zonesApiService = inject(ZonesApiService);
  private toastCtrl = inject(ToastController);
  private alertCtrl = inject(AlertController);

  zones: Zone[] = [];
  isLoading: boolean = false;
  isModalOpen: boolean = false;
  selectedZone: Zone | null = null;
  isSaving: boolean = false;

  // Variable para almacenar el ID del restaurante real que extraemos de la base de datos
  private currentRestaurantUuid: string = '';

  constructor() {
    addIcons({ add, close, trash, create, folderOpenOutline });
  }

  ngOnInit(): void {
    this.loadData();
  }

  private async showToast(message: string, color: 'success' | 'danger' = 'success') {
    const toast = await this.toastCtrl.create({
      message, duration: 2000, color, position: 'bottom'
    });
    await toast.present();
  }

  loadData(): void {
    this.isLoading = true;
    this.zonesApiService.getAll().subscribe({
      next: (res: Zone[]) => {
        this.zones = res;
        
        // 💡 TRUCO: Si la base de datos ya nos devuelve zonas creadas previamente, 
        // le "robamos" el UUID del restaurante para usarlo en las nuevas.
        if (res.length > 0) {
          this.currentRestaurantUuid = res[0].restaurant_uuid || (res[0] as any).restaurantUuid || '';
        }
        
        this.isLoading = false;
      },
      error: (error) => {
        this.showToast(error.error?.message || 'Error al cargar zonas', 'danger');
        this.isLoading = false;
      }
    });
  }

  toggleActive(zone: Zone): void {
    this.zonesApiService.toggleActive(zone.uuid).subscribe({
      next: (updatedZone: Zone) => {
        this.zones = this.zones.map((item) =>
          item.uuid === updatedZone.uuid ? updatedZone : item
        );
        this.showToast(`Zona ${updatedZone.active ? 'activada' : 'desactivada'}`);
      },
      error: (error) => {
        this.showToast(error.message || 'Error al cambiar el estado', 'danger');
      }
    });
  }

  async confirmDelete(zone: Zone) {
    const alert = await this.alertCtrl.create({
      header: '¿Eliminar zona?',
      subHeader: zone.name,
      message: 'Vas a eliminar definitivamente esta zona y afectará a sus mesas. ¿Continuar?',
      mode: 'ios',
      buttons: [
        { text: 'Cancelar', role: 'cancel' },
        {
          text: 'Eliminar',
          role: 'destructive',
          handler: () => this.executeDelete(zone)
        }
      ]
    });
    await alert.present();
  }

  private executeDelete(zone: Zone): void {
    this.zonesApiService.delete(zone.uuid).subscribe({
      next: () => {
        this.zones = this.zones.filter(z => z.uuid !== zone.uuid);
        this.showToast('Zona eliminada correctamente');
      },
      error: (error) => {
        this.showToast(error.error?.message || 'Error al eliminar la zona', 'danger');
      }
    });
  }

  onSaveZone(formData: any): void {
    this.isSaving = true;
    let request$;

    if (this.selectedZone) {
      // Caso EDICIÓN: Mandamos solo el name tal y como pide tu método updateName de PHP
      const updatePayload = {
        name: formData.name
      };
      request$ = this.zonesApiService.update(this.selectedZone.uuid, updatePayload);
    } else {
      // Caso CREACIÓN: Forzamos los parámetros estrictos que pide el constructor dddCreate de PHP
      // ⚠️ IMPORTANTE: Si 'currentRestaurantUuid' está vacío porque no había zonas previas,
      // pon aquí entre las comillas un UUID real de tu tabla 'restaurants' de la base de datos.
      const restaurantUuid = this.currentRestaurantUuid || 'AQUI_PEGA_UN_UUID_DE_TU_TABLA_RESTAURANTS';

      const createPayload = {
        uuid: crypto.randomUUID(), // Generamos el UUID de la zona desde el cliente (Muy común en DDD)
        name: formData.name,
        restaurant_uuid: restaurantUuid,
        restaurantUuid: restaurantUuid // Enviamos ambos formatos para evitar problemas con el mapeador
      };
      
      request$ = this.zonesApiService.create(createPayload);
    }

    request$.subscribe({
      next: () => {
        this.loadData();
        this.showToast('Zona guardada en Base de Datos correctamente');
        this.isSaving = false;
        this.closeModal();
      },
      error: (error) => {
        // Imprime esto en tu consola del navegador (F12) para ver el mensaje interno real de PHP
        console.error('Error detallado devuelto por PHP:', error);
        this.showToast(error.error?.message || 'Error de consistencia en el servidor', 'danger');
        this.isSaving = false;
      }
    });
  }

  openCreateModal(): void { this.selectedZone = null; this.isModalOpen = true; }
  openEditModal(zone: Zone): void { this.selectedZone = zone; this.isModalOpen = true; }
  closeModal(): void { this.isModalOpen = false; this.selectedZone = null; }
}