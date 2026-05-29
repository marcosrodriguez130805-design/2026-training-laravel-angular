import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { 
  IonContent, IonList, IonLabel, IonItem, 
  IonHeader, IonToolbar, IonIcon, 
  IonTitle, IonButtons, IonButton, 
  ToastController, AlertController, IonSpinner,
  IonMenuButton, IonSegment, IonSegmentButton,
  IonModal // 🛠️ NUEVO: Importación nativa añadida para solucionar la UX del modal
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { add, close, trash, create, gridOutline, folderOpenOutline } from 'ionicons/icons'; 
import { TablesApiService } from '../../../services/tables/tables-api.service';
import { ZonesApiService } from '../../../services/zones/zones-api.service';
import { Table } from '../../../services/tables/table.model';
import { Zone } from '../../../services/zones/zone.model';
import { TableFormComponent } from './table-form/table-form.component';

@Component({
  selector: 'app-tables-page',
  templateUrl: './tables.page.html',
  styleUrls: ['./tables.page.scss'],
  standalone: true,
  imports: [
    CommonModule, IonContent, IonList, IonLabel, IonItem,
    IonHeader, IonToolbar, IonIcon, IonTitle, 
    IonButtons, IonButton, IonSpinner, IonMenuButton,
    IonSegment, IonSegmentButton, TableFormComponent,
    IonModal // 🛠️ NUEVO: Registrado en los imports para habilitar su uso en el HTML
  ]
})
export class TablesPage implements OnInit {
  private tablesApiService = inject(TablesApiService);
  private zonesApiService = inject(ZonesApiService);
  private toastCtrl = inject(ToastController);
  private alertCtrl = inject(AlertController);

  tables: Table[] = [];
  zones: Zone[] = [];
  isLoading: boolean = false;
  isModalOpen: boolean = false;
  selectedTable: Table | null = null;
  isSaving: boolean = false;
  
  selectedZoneUuid: string = 'all';

  constructor() {
    addIcons({ add, close, trash, create, gridOutline, folderOpenOutline });
  }

  ngOnInit() {
    this.loadInitialData();
  }

  private async showToast(message: string, color: 'success' | 'danger' = 'success') {
    const toast = await this.toastCtrl.create({
      message, duration: 2000, color, position: 'bottom'
    });
    await toast.present();
  }

  loadInitialData() {
    this.isLoading = true;
    this.zonesApiService.getAll().subscribe({
      next: (zonesData) => {
        this.zones = zonesData;
        this.loadTables();
      },
      error: () => {
        this.showToast('Error al cargar las zonas configuradas', 'danger');
        this.isLoading = false;
      }
    });
  }

  private loadTables() {
    this.tablesApiService.getAll().subscribe({
      next: (tablesData) => {
        this.tables = tablesData;
        this.isLoading = false;
      },
      error: () => {
        this.showToast('Error al recuperar el listado de mesas', 'danger');
        this.isLoading = false;
      }
    });
  }

  get filteredTables(): Table[] {
    if (this.selectedZoneUuid === 'all') {
      return this.tables;
    }
    return this.tables.filter(table => {
      const currentZoneUuid = table.zone_uuid || (table as any).zoneUuid;
      return currentZoneUuid === this.selectedZoneUuid;
    });
  }

  getZoneName(zoneUuid: string | undefined): string {
    if (!zoneUuid) return 'Sin zona asignada';
    const zone = this.zones.find(z => z.uuid === zoneUuid || (z as any).zoneUuid === zoneUuid);
    return zone ? zone.name : 'Sin zona asignada';
  }

  onZoneChange(event: any) {
    this.selectedZoneUuid = event.detail.value;
  }

  async confirmDelete(table: Table) {
    const alert = await this.alertCtrl.create({
      header: '¿Eliminar mesa?',
      subHeader: table.name,
      message: `¿Estás seguro de eliminar la ${table.name}? Esta acción modificará el mapa del restaurante de la BBDD.`,
      mode: 'ios',
      buttons: [
        { text: 'Cancelar', role: 'cancel' },
        {
          text: 'Eliminar',
          role: 'destructive',
          handler: () => this.executeDelete(table)
        }
      ]
    });
    await alert.present();
  }

  private executeDelete(table: Table) {
    this.tablesApiService.delete(table.uuid).subscribe({
      next: () => {
        this.tables = this.tables.filter(t => t.uuid !== table.uuid);
        this.showToast('Mesa eliminada de la base de datos');
      },
      error: (err) => this.showToast(err.error?.message || 'Error al eliminar la mesa', 'danger')
    });
  }

  onSaveTable(formData: any) {
    this.isSaving = true;
    let request$;

    if (this.selectedTable) {
      const updatePayload = { name: formData.name };
      request$ = this.tablesApiService.update(this.selectedTable.uuid, updatePayload);
    } else {
      const restaurantUuid = this.zones.length > 0 ? (this.zones[0].restaurant_uuid || (this.zones[0] as any).restaurantUuid) : '00000000-0000-0000-0000-000000000000';
      
      const createPayload = {
        uuid: crypto.randomUUID(),
        name: formData.name,
        zone_uuid: formData.zone_uuid,
        zoneUuid: formData.zone_uuid,
        restaurant_uuid: restaurantUuid,
        restaurantUuid: restaurantUuid
      };
      request$ = this.tablesApiService.create(createPayload);
    }

    request$.subscribe({
      next: () => {
        this.loadInitialData();
        this.showToast('Cambios almacenados con éxito');
        this.isSaving = false;
        this.closeModal();
      },
      error: (err) => {
        console.error('Error en API de Mesas:', err);
        this.showToast(err.error?.message || 'Fallo de consistencia al guardar la mesa', 'danger');
        this.isSaving = false;
      }
    });
  }

  openCreateModal() { this.selectedTable = null; this.isModalOpen = true; }
  openEditModal(table: Table) { this.selectedTable = table; this.isModalOpen = true; }
  closeModal() { this.isModalOpen = false; this.selectedTable = null; }
}