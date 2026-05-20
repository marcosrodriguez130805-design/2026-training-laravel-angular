import { Component, inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { 
  IonContent, IonList, IonItem, IonLabel, IonButton, 
  IonButtons, IonIcon, IonHeader, IonToolbar, 
  IonTitle, IonSpinner, IonToggle, ToastController // <--- Añadido ToastController
} from '@ionic/angular/standalone';
import { FamiliesApiService } from '../../../services/families/families-api.service';
import { Family } from '../../../services/families/family.model';
import { FamilyFormComponent } from './family-form/family-form.component';
import { addIcons } from 'ionicons';
import { add, close, trash, create, folderOpenOutline } from 'ionicons/icons';

@Component({
  selector: 'app-families',
  templateUrl: 'families.page.html',
  standalone: true,
  imports: [
    CommonModule, ReactiveFormsModule, IonContent, IonList, IonItem, 
    IonLabel, IonButton, IonButtons, IonIcon, 
    IonHeader, IonToolbar, IonTitle, IonSpinner, IonToggle, 
    FamilyFormComponent
  ],
})
export class FamiliesPage implements OnInit {
  private familiesApiService = inject(FamiliesApiService);
  private toastCtrl = inject(ToastController); // <--- Inyectado

  families: Family[] = [];
  selectedFamily: Family | null = null;
  isModalOpen = false; 
  isLoading = false;
  isSaving = false;

  constructor() {
    addIcons({ add, close, trash, create, folderOpenOutline });
  }

  ngOnInit(): void {
    this.loadFamilies();
  }

  // --- FUNCIÓN PARA MOSTRAR NOTIFICACIONES FLOTANTES ---
  private async showToast(message: string, color: 'success' | 'danger' = 'success') {
    const toast = await this.toastCtrl.create({
      message,
      duration: 2000,
      color,
      position: 'bottom',
      cssClass: 'custom-toast'
    });
    await toast.present();
  }

  private loadFamilies(): void {
    this.isLoading = true;
    this.familiesApiService.getAll().subscribe({
      next: (families) => {
        this.families = families;
        this.isLoading = false;
      },
      error: (error) => {
        this.showToast(error.error?.message || 'Error cargando familias', 'danger');
        this.isLoading = false;
      },
    });
  }

  openCreateModal(): void {
    this.selectedFamily = null;
    this.isModalOpen = true;
  }

  openEditModal(family: Family): void {
    this.selectedFamily = family;
    this.isModalOpen = true;
  }

  closeModal(): void {
    this.isModalOpen = false;
    this.selectedFamily = null;
  }

  toggleActive(family: Family): void {
    this.familiesApiService.toggleActive(family.uuid).subscribe({
      next: (updatedFamily) => {
        this.families = this.families.map((item) =>
          item.uuid === updatedFamily.uuid ? updatedFamily : item
        );
        this.showToast(`Familia ${updatedFamily.active ? 'activada' : 'desactivada'}`);
      },
      error: (error) => {
        this.showToast(error.message || 'Error al cambiar el estado', 'danger');
      },
    });
  }

  onSaveFamily(data: any): void {
    this.isSaving = true;
    const request$ = this.selectedFamily
      ? this.familiesApiService.update(this.selectedFamily.uuid, data)
      : this.familiesApiService.create(data);

    request$.subscribe({
      next: () => {
        this.loadFamilies();
        this.showToast('Guardado correctamente');
        this.isSaving = false;
        this.closeModal();
      },
      error: (error) => {
        this.showToast(error.error?.message || 'Error al guardar', 'danger');
        this.isSaving = false;
      },
    });
  }

  confirmDelete(family: Family): void {
    if (confirm(`¿Eliminar la familia "${family.name}"? Esta acción no se puede deshacer.`)) {
      this.familiesApiService.delete(family.uuid).subscribe({
        next: () => {
          this.families = this.families.filter(f => f.uuid !== family.uuid);
          this.showToast('Familia eliminada');
        },
        error: (error) => {
          this.showToast(error.error?.message || 'Error al eliminar', 'danger');
        }
      });
    }
  }
}