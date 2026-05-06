import { Component, inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { IonContent, IonList, IonItem, IonLabel, IonBadge, IonButton, IonButtons, IonIcon, IonModal, IonAlert, IonText, IonHeader, IonToolbar, IonTitle } from '@ionic/angular/standalone';
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
imports: [CommonModule, ReactiveFormsModule, IonContent, IonList, IonItem, IonLabel, IonBadge, IonButton, IonButtons, IonIcon, IonModal, IonAlert, IonText, IonHeader, IonToolbar, IonTitle, FamilyFormComponent],  standalone: true,
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
  isLoading = false;
  isSaving = false;

  ngOnInit(): void {
    this.loadFamilies();
  }

  private loadFamilies(): void {
    this.isLoading = true;
    this.errorMessage = '';
    this.successMessage = '';

    this.familiesApiService.getAll().subscribe({
      next: (families) => {
        this.families = families;
        this.isLoading = false;
      },
      error: (error) => {
        this.errorMessage = error.message || 'Error cargando familias';
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
    this.errorMessage = '';
    this.successMessage = '';
  }

  onSaveFamily(data: { name: string; active: boolean }): void {
    this.errorMessage = '';
    this.successMessage = '';
    this.isSaving = true;

    if (this.selectedFamily) {
      this.familiesApiService.update(this.selectedFamily.uuid, data).subscribe({
        next: (updatedFamily) => {
          this.families = this.families.map((family) =>
            family.uuid === updatedFamily.uuid ? updatedFamily : family
          );
          this.successMessage = 'Familia actualizada correctamente';
          this.isSaving = false;
          this.closeModal();
        },
        error: (error) => {
          this.errorMessage = error.message || 'Error al actualizar la familia';
          this.isSaving = false;
        },
      });
    } else {
      this.familiesApiService.create(data).subscribe({
        next: (createdFamily) => {
          this.families = [createdFamily, ...this.families];
          this.successMessage = 'Familia creada correctamente';
          this.isSaving = false;
          this.closeModal(); // sin setTimeout
        },
        error: (error) => {
          this.errorMessage = error.message || 'Error al crear la familia';
          this.isSaving = false;
        },
      });
    }
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
  if (!this.deleteTarget) {
    return;
  }

  const uuid = this.deleteTarget.uuid;
  this.familiesApiService.delete(uuid).subscribe({
    next: () => {
      this.families = [...this.families.filter((family) => family.uuid !== uuid)];
      this.closeDeleteAlert();
    },
    error: (error) => {
      this.errorMessage = error.message || 'Error al eliminar la familia';
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
