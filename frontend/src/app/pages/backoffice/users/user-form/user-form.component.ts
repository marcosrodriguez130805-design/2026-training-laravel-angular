import { Component, EventEmitter, Input, OnInit, OnChanges, Output, SimpleChanges } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { IonItem, IonLabel, IonInput, IonSelect, IonSelectOption, IonButton } from '@ionic/angular/standalone';
import { User } from '../../../../services/users/user.model';

@Component({
  selector: 'app-user-form',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, IonItem, IonLabel, IonInput, IonSelect, IonSelectOption, IonButton],
  template: `
    <form [formGroup]="userForm" (ngSubmit)="onSubmit()">
      <ion-item lines="none" class="ion-margin-bottom">
        <ion-input label="Nombre completo" labelPlacement="stacked" type="text" formControlName="name" placeholder="Ej. Juan Pérez"></ion-input>
      </ion-item>

      <ion-item lines="none" class="ion-margin-bottom">
        <ion-input label="Correo Electrónico" labelPlacement="stacked" type="email" formControlName="email" placeholder="juan@restaurante.com"></ion-input>
      </ion-item>

      <ion-item lines="none" class="ion-margin-bottom">
        <ion-input 
          [label]="isEditMode ? 'Contraseña (Dejar en blanco para no cambiar)' : 'Contraseña (mín. 6 caracteres)'" 
          labelPlacement="stacked" 
          type="password" 
          formControlName="password" 
          placeholder="******">
        </ion-input>
      </ion-item>

      <ion-item lines="none" class="ion-margin-bottom">
        <ion-select label="Rol del Empleado" labelPlacement="stacked" formControlName="role">
          <ion-select-option value="admin">Administrador (Backoffice + TPV)</ion-select-option>
          <ion-select-option value="waiter">Camarero (Solo TPV)</ion-select-option>
        </ion-select>
      </ion-item>

      <ion-item lines="none" class="ion-margin-bottom">
        <ion-input label="PIN de acceso TPV (Opcional - 4 dígitos)" labelPlacement="stacked" type="text" maxlength="4" formControlName="pin" placeholder="1234"></ion-input>
      </ion-item>

      <div class="ion-padding-top">
        <ion-button type="submit" expand="block" [disabled]="userForm.invalid || isSaving">
          {{ isSaving ? 'Guardando...' : (isEditMode ? 'Guardar Cambios' : 'Crear Usuario') }}
        </ion-button>
        <ion-button type="button" expand="block" fill="clear" color="medium" (click)="cancel.emit()">
          Cancelar
        </ion-button>
      </div>
    </form>
  `
})
export class UserFormComponent implements OnInit, OnChanges {
  @Input() user: User | null = null; // 🛠️ Recibe el usuario a editar si existe
  @Input() isSaving = false;
  @Output() save = new EventEmitter<any>();
  @Output() cancel = new EventEmitter<void>();

  userForm!: FormGroup;
  isEditMode = false;

  constructor(private fb: FormBuilder) {
    this.initForm();
  }

  ngOnInit() {
    this.checkEditMode();
  }

  ngOnChanges(changes: SimpleChanges) {
    if (changes['user']) {
      this.checkEditMode();
    }
  }

  private initForm() {
    this.userForm = this.fb.group({
      name: ['', [Validators.required, Validators.maxLength(255)]],
      email: ['', [Validators.required, Validators.email, Validators.maxLength(255)]],
      password: ['', [Validators.minLength(6)]], // Quitamos el required por defecto
      role: ['waiter', Validators.required],
      pin: ['', [Validators.pattern('^[0-9]{4}$')]],
      image_src: [null]
    });
  }

  private checkEditMode() {
    if (!this.userForm) return;

    if (this.user) {
      this.isEditMode = true;
      // Ponemos los datos del usuario en los inputs
      this.userForm.patchValue({
        name: this.user.name,
        email: this.user.email,
        role: this.user.role,
        pin: this.user.pin,
        image_src: this.user.imageSrc,
        password: '' // Siempre vacío al abrir
      });
      // Al editar, el password NO es obligatorio
      this.userForm.get('password')?.setValidators([Validators.minLength(6)]);
    } else {
      this.isEditMode = false;
      this.userForm.reset({ role: 'waiter' });
      // Al crear, el password SÍ es obligatorio
      this.userForm.get('password')?.setValidators([Validators.required, Validators.minLength(6)]);
    }
    this.userForm.get('password')?.updateValueAndValidity();
  }

  onSubmit() {
    if (this.userForm.valid) {
      // Si estamos editando y el password está vacío, lo eliminamos del envío para que Laravel no proteste
      const value = { ...this.userForm.value };
      if (this.isEditMode && !value.password) {
        delete value.password;
      }
      this.save.emit(value);
    }
  }
}