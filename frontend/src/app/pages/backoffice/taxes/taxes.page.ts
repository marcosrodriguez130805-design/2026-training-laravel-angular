import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { 
  IonContent, IonList, IonItem, IonLabel, IonButton, 
  IonButtons, IonIcon, IonHeader, IonToolbar, 
  IonTitle, IonSpinner, ToastController, IonBadge,
  IonMenuButton, IonText, AlertController 
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { add, close, trashOutline, createOutline, receiptOutline, alertCircleOutline } from 'ionicons/icons';

import { TaxesApiService } from '../../../services/taxes/taxes-api.service';
import { Tax } from '../../../services/taxes/tax.model';
import { TaxFormComponent } from './tax-form/tax-form.component';

@Component({
  selector: 'app-taxes',
  templateUrl: './taxes.page.html',
  styleUrls: ['./taxes.page.scss'],
  standalone: true,
  imports: [
    CommonModule, IonContent, IonList, IonItem, IonLabel, 
    IonButton, IonButtons, IonIcon, IonHeader, 
    IonToolbar, IonTitle, IonSpinner, IonBadge,
    IonMenuButton, IonText, TaxFormComponent
  ]
})
export class TaxesPage implements OnInit {
  private taxesApiService = inject(TaxesApiService);
  private toastCtrl = inject(ToastController);
  private alertCtrl = inject(AlertController);

  taxes: Tax[] = [];
  selectedTax: Tax | null = null;
  isCreating = false;
  isLoading = false;

  constructor() {
    addIcons({ add, close, trashOutline, createOutline, receiptOutline, alertCircleOutline });
  }

  ngOnInit() {
    this.loadTaxes();
  }

  private async showToast(message: string, color: 'success' | 'danger' = 'success') {
    const toast = await this.toastCtrl.create({
      message, duration: 2000, color, position: 'bottom'
    });
    await toast.present();
  }

  loadTaxes() {
    this.isLoading = true;
    this.taxesApiService.getAll().subscribe({
      next: (data) => {
        this.taxes = data;
        this.isLoading = false;
      },
      error: () => {
        this.showToast('Error al cargar impuestos', 'danger');
        this.isLoading = false;
      }
    });
  }

  async handleDelete(tax: Tax) {
    const alert = await this.alertCtrl.create({
      header: '¿Eliminar impuesto?',
      subHeader: tax.name,
      message: `Vas a eliminar el impuesto del ${tax.percentage}%. Esta acción no se puede deshacer.`,
      mode: 'ios',
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
          cssClass: 'secondary'
        },
        {
          text: 'Eliminar',
          role: 'destructive',
          handler: () => {
            this.executeDelete(tax.uuid);
          }
        }
      ]
    });
    await alert.present();
  }

  private executeDelete(uuid: string) {
    this.taxesApiService.delete(uuid).subscribe({
      next: () => {
        this.taxes = this.taxes.filter(t => t.uuid !== uuid);
        this.showToast('Impuesto eliminado correctamente');
      },
      error: (err: any) => this.showToast(err.error?.message || 'Error al eliminar', 'danger')
    });
  }

  handleEdit(tax: Tax) {
    this.selectedTax = tax;
    this.isCreating = true;
  }

  handleCreate() {
    this.selectedTax = null;
    this.isCreating = true;
  }

  cancelForm() {
    this.isCreating = false;
    this.selectedTax = null;
  }

  onTaxSaved() {
    this.isCreating = false;
    this.selectedTax = null;
    this.loadTaxes();
    this.showToast('Impuesto guardado correctamente');
  }
}