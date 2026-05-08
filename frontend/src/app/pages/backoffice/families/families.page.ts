import { Component, inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { IonContent, IonList, IonItem, IonLabel, IonBadge, IonButton, IonButtons, IonIcon, IonModal, IonAlert, IonText, IonHeader, IonToolbar, IonTitle, IonSpinner } from '@ionic/angular/standalone';
import { FamiliesApiService } from '../../../services/families/families-api.service';
import { Family } from '../../../services/families/family.model';
import { FamilyFormComponent } from './family-form/family-form.component';
import { addIcons } from 'ionicons';
import { addOutline, pencilOutline, trashOutline, shuffleOutline } from 'ionicons/icons';

addIcons({ addOutline, pencilOutline, trashOutline, shuffleOutline });

@Component({
  selector: 'app-families',
  templateUrl: 'families.page.html',
  styleUrls: ['families.page.scss'],
imports: [CommonModule, ReactiveFormsModule, IonContent, IonList, IonItem, IonLabel, IonBadge, IonButton, IonButtons, IonIcon, IonModal, IonAlert, IonText, IonHeader, IonToolbar, IonTitle, IonSpinner, FamilyFormComponent],  standalone: true,
})
export class FamiliesPage implements OnInit {
  private familiesApiService = inject(FamiliesApiService);

  families: Family[] = [];
  selectedFamily: Family | null = null;
  isModalOpen = false;
  isDeleteAlertOpen = false;
  deleteTarget: Family | null = null;
  errorMessage = '';
  successMessage = '';
  isLoading = false; // Controla el spinner de la lista
  isSaving = false;  // Controla el spinner del botón guardar

  ngOnInit(): void {
    this.loadFamilies();
  }

  private loadFamilies(): void {
    this.isLoading = true;
    this.errorMessage = '';

    this.familiesApiService.getAll().subscribe({
      next: (families) => {
        this.families = families;
        this.isLoading = false;
      },
      error: (error) => {
        // Captura el mensaje de error del backend
        this.errorMessage = error.error?.message || 'Error cargando familias';
        this.isLoading = false;
      },
    });
  }

  openCreateModal(): void {
    this.selectedFamily = null;
    this.isModalOpen = true;
    this.errorMessage = '';
    this.successMessage = '';
  }

  openEditModal(family: Family): void {
    this.selectedFamily = family;
    this.isModalOpen = true;
    this.errorMessage = '';
    this.successMessage = '';
  }

  closeModal(): void {
    this.isModalOpen = false;
    this.selectedFamily = null;
  }

  onSaveFamily(data: { name: string; active: boolean }): void {
    this.errorMessage = '';
    this.isSaving = true;

    const request$ = this.selectedFamily
      ? this.familiesApiService.update(this.selectedFamily.uuid, data)
      : this.familiesApiService.create(data);

    request$.subscribe({
      next: (savedFamily) => {
        if (this.selectedFamily) {
          // Lógica de actualización
          this.families = this.families.map((f) =>
            f.uuid === savedFamily.uuid ? savedFamily : f
          );
          this.successMessage = 'Familia actualizada correctamente'; // Confirmación al actualizar
        } else {
          // Lógica de creación
          this.families = [savedFamily, ...this.families];
          this.successMessage = 'Familia creada correctamente';
        }
        
        this.isSaving = false;
        this.closeModal();
        
        // Limpiamos el mensaje de éxito tras 3 segundos
        setTimeout(() => this.successMessage = '', 3000);
      },
      error: (error) => {
        // Captura el error de "Nombre Duplicado" lanzado por el Backend
        this.errorMessage = error.error?.message || 'Error al guardar la familia';
        this.isSaving = false;
      },
    });
  }

  confirmDelete(family: Family): void {
    this.deleteTarget = family;
    this.isDeleteAlertOpen = true;
  }

  closeDeleteAlert(): void {
    this.isDeleteAlertOpen = false;
    this.deleteTarget = null;
  }

  deleteFamily(): void {
    if (!this.deleteTarget) return;

    const uuid = this.deleteTarget.uuid;
    this.familiesApiService.delete(uuid).subscribe({
      next: () => {
        this.families = [...this.families.filter((f) => f.uuid !== uuid)];
        this.successMessage = 'Familia eliminada correctamente';
        this.closeDeleteAlert();
        setTimeout(() => this.successMessage = '', 3000);
      },
      error: (error) => {
        this.errorMessage = error.error?.message || 'Error al eliminar la familia';
        this.closeDeleteAlert();
      },
    });
  }

  toggleActive(family: Family): void {
    this.errorMessage = '';
    this.successMessage = '';

    this.familiesApiService.toggleActive(family.uuid).subscribe({
      next: (updatedFamily) => {
        this.families = this.families.map((item) =>
          item.uuid === updatedFamily.uuid ? updatedFamily : item
        );
        this.successMessage = `Familia ${updatedFamily.active ? 'activada' : 'desactivada'} correctamente`;
        setTimeout(() => this.successMessage = '', 3000);
      },
      error: (error) => {
        this.errorMessage = error.message || 'Error al cambiar el estado';
        setTimeout(() => this.errorMessage = '', 5000);
      },
    });
  }

  get deleteAlertButtons() {
  return [
    {
      text: 'Cancelar',
      role: 'cancel',
      handler: () => this.closeDeleteAlert(),
    },
    {
      text: 'Eliminar',
      handler: () => {
        this.deleteFamily();
        return true;
      },
    },
  ];
}
}
