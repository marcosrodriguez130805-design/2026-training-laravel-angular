import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { 
  IonContent, IonList, IonLabel, IonItem, IonHeader, IonToolbar, 
  IonIcon, IonTitle, IonButtons, IonButton, ToastController, 
  AlertController, IonSpinner, IonMenuButton, IonSegment, 
  IonSegmentButton, IonModal 
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { add, close, trash, create, personOutline, folderOpenOutline } from 'ionicons/icons'; // 🛠️ Añadido 'create'
import { UsersApiService } from '../../../services/users/users-api.service';
import { FamiliesApiService } from '../../../services/families/families-api.service';
import { User } from '../../../services/users/user.model';
import { UserFormComponent } from './user-form/user-form.component';

@Component({
  selector: 'app-users-page',
  templateUrl: './users.page.html',
  standalone: true,
  imports: [
    CommonModule, IonContent, IonList, IonLabel, IonItem, IonHeader, 
    IonToolbar, IonIcon, IonTitle, IonButtons, IonButton, IonSpinner, 
    IonMenuButton, IonSegment, IonSegmentButton, IonModal, UserFormComponent
  ]
})
export class UsersPage implements OnInit {
  private usersApiService = inject(UsersApiService);
  private familiesApiService = inject(FamiliesApiService);
  private toastCtrl = inject(ToastController);
  private alertCtrl = inject(AlertController);

  users: User[] = [];
  selectedUser: User | null = null; // 🛠️ Guardamos el usuario que se está editando
  isLoading = false;
  isModalOpen = false;
  isSaving = false;
  
  selectedRoleFilter = 'all'; 
  private currentRestaurantUuid = '';

  constructor() {
    // 🛠️ Registramos 'create' junto a los demás iconos
    addIcons({ add, close, trash, create, personOutline, folderOpenOutline });
  }

  ngOnInit() {
    this.loadInitialData();
  }

  private async showToast(message: string, color: 'success' | 'danger' = 'success') {
    const toast = await this.toastCtrl.create({ message, duration: 2000, color, position: 'bottom' });
    await toast.present();
  }

  loadInitialData() {
    this.isLoading = true;
    
    this.familiesApiService.getAll().subscribe({
      next: (families) => {
        if (families.length > 0) {
          this.currentRestaurantUuid = families[0].restaurant_uuid;
        }
        this.loadUsers();
      },
      error: () => {
        this.loadUsers();
      }
    });
  }

  private loadUsers() {
    this.usersApiService.getAll().subscribe({
      next: (usersData) => {
        this.users = usersData;
        this.isLoading = false;
      },
      error: () => {
        this.showToast('Error al recuperar la plantilla de empleados', 'danger');
        this.isLoading = false;
      }
    });
  }

  get filteredUsers(): User[] {
    if (this.selectedRoleFilter === 'all') {
      return this.users;
    }
    return this.users.filter(user => user.role === this.selectedRoleFilter);
  }

  onRoleFilterChange(event: any) {
    this.selectedRoleFilter = event.detail.value;
  }

  onSaveUser(formData: any) {
    this.isSaving = true;
    let request$;

    if (this.selectedUser) {
      // 🛠️ CASO EDICIÓN: Estructura limpia para el UpdateUserController de PHP
      const updatePayload = {
        name: formData.name,
        email: formData.email,
        role: formData.role,
        pin: formData.pin || null,
        image_src: formData.image_src || null,
        ...(formData.password ? { password: formData.password } : {}) // Solo viaja si se ha escrito
      };
      request$ = this.usersApiService.update(this.selectedUser.uuid, updatePayload);
    } else {
      // 🛠️ CASO CREACIÓN: Conservamos la lógica original con el ID del restaurante
      const payload = {
        name: formData.name,
        email: formData.email,
        password: formData.password,
        role: formData.role,
        pin: formData.pin || null,
        image_src: formData.image_src || null,
        restaurant_uuid: this.currentRestaurantUuid || '00000000-0000-0000-0000-000000000000'
      };
      request$ = this.usersApiService.create(payload);
    }

    request$.subscribe({
      next: () => {
        this.loadUsers();
        this.showToast(this.selectedUser ? 'Empleado actualizado correctamente' : 'Empleado dado de alta con éxito');
        this.isSaving = false;
        this.closeModal();
      },
      error: (err) => {
        console.error('Error devuelto por Laravel:', err);
        this.showToast(err.error?.error || 'No se pudo procesar el usuario', 'danger');
        this.isSaving = false;
      }
    });
  }

  async confirmDelete(user: User) {
    const alert = await this.alertCtrl.create({
      header: '¿Dar de baja?',
      subHeader: user.name,
      message: `¿Estás seguro de eliminar el acceso del usuario al sistema?`,
      mode: 'ios',
      buttons: [
        { text: 'Cancelar', role: 'cancel' },
        {
          text: 'Eliminar',
          role: 'destructive',
          handler: () => {
            this.usersApiService.delete(user.uuid).subscribe({
              next: () => {
                this.users = this.users.filter(u => u.uuid !== user.uuid);
                this.showToast('Usuario eliminado del sistema');
              },
              error: () => this.showToast('Error al eliminar usuario', 'danger')
            });
          }
        }
      ]
    });
    await alert.present();
  }

  openCreateModal() { 
    this.selectedUser = null; 
    this.isModalOpen = true; 
  }

  // 🛠️ Nuevo método para abrir el modal cargando un usuario existente
  openEditModal(user: User) {
    this.selectedUser = user;
    this.isModalOpen = true;
  }

  closeModal() { 
    this.isModalOpen = false; 
    this.selectedUser = null; // Limpiamos siempre al cerrar
  }
}