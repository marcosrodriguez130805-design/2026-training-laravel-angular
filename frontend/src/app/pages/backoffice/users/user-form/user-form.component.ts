import { Component, EventEmitter, Input, OnInit, OnChanges, Output, SimpleChanges } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { IonItem, IonLabel, IonInput, IonSelect, IonSelectOption, IonButton, IonSpinner, IonText } from '@ionic/angular/standalone';
import { User } from '../../../../services/users/user.model';

@Component({
  selector: 'app-user-form',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, IonItem, IonLabel, IonInput, IonSelect, IonSelectOption, IonButton, IonSpinner, IonText],
  template: `
    <form [formGroup]="userForm" (ngSubmit)="onSubmit()">
      
      <div class="form-group">
        <ion-item class="ion-margin-bottom" [class.has-error]="userForm.get('name')?.invalid && userForm.get('name')?.touched">
          <ion-label class="label-stacked">Nombre completo</ion-label>
          <ion-input type="text" formControlName="name" placeholder="Ej. Juan Pérez"></ion-input>
        </ion-item>
        <ion-text color="danger" *ngIf="userForm.get('name')?.invalid && userForm.get('name')?.touched" class="error-text">
          <span *ngIf="userForm.get('name')?.errors?.['required']">El nombre es obligatorio.</span>
          <span *ngIf="userForm.get('name')?.errors?.['maxlength']">Máximo 255 caracteres.</span>
        </ion-text>
      </div>

      <div class="form-group">
        <ion-item class="ion-margin-bottom" [class.has-error]="userForm.get('email')?.invalid && userForm.get('email')?.touched">
          <ion-label class="label-stacked">Correo Electrónico</ion-label>
          <ion-input type="email" formControlName="email" placeholder="juan@restaurante.com"></ion-input>
        </ion-item>
        <ion-text color="danger" *ngIf="userForm.get('email')?.invalid && userForm.get('email')?.touched" class="error-text">
          <span *ngIf="userForm.get('email')?.errors?.['required']">El email es obligatorio.</span>
          <span *ngIf="userForm.get('email')?.errors?.['email']">Formato de email inválido.</span>
        </ion-text>
      </div>

      <div class="form-group">
        <ion-item class="ion-margin-bottom" [class.has-error]="userForm.get('password')?.invalid && userForm.get('password')?.touched">
          <ion-label class="label-stacked">{{ isEditMode ? 'Contraseña (Dejar en blanco para no cambiar)' : 'Contraseña (mín. 6 caracteres)' }}</ion-label>
          <ion-input type="password" formControlName="password" placeholder="••••••"></ion-input>
        </ion-item>
        <ion-text color="danger" *ngIf="userForm.get('password')?.invalid && userForm.get('password')?.touched" class="error-text">
          <span *ngIf="userForm.get('password')?.errors?.['required']">La contraseña es obligatoria.</span>
          <span *ngIf="userForm.get('password')?.errors?.['minlength']">Mínimo 6 caracteres.</span>
        </ion-text>
      </div>

      <div class="form-group">
        <ion-item class="ion-margin-bottom" [class.has-error]="userForm.get('role')?.invalid && userForm.get('role')?.touched">
          <ion-label class="label-stacked">Rol del Empleado</ion-label>
          <ion-select formControlName="role">
            <ion-select-option value="admin">Administrador (Backoffice + TPV)</ion-select-option>
            <ion-select-option value="waiter">Camarero (Solo TPV)</ion-select-option>
          </ion-select>
        </ion-item>
      </div>

      <div class="form-group">
        <ion-item class="ion-margin-bottom" [class.has-error]="userForm.get('pin')?.invalid && userForm.get('pin')?.touched">
          <ion-label class="label-stacked">PIN de acceso TPV (Opcional - 4 dígitos)</ion-label>
          <ion-input type="text" maxlength="4" formControlName="pin" placeholder="1234"></ion-input>
        </ion-item>
        <ion-text color="danger" *ngIf="userForm.get('pin')?.invalid && userForm.get('pin')?.touched" class="error-text">
          <span *ngIf="userForm.get('pin')?.errors?.['pattern']">Debe ser un PIN de 4 dígitos numéricos.</span>
        </ion-text>
      </div>

      <div class="form-actions">
        <ion-button fill="clear" class="btn-secondary" type="button" (click)="cancel.emit()" [disabled]="isSaving">
          Cancelar
        </ion-button>
        <ion-button class="btn-primary" type="submit" [disabled]="userForm.invalid || isSaving">
          <ion-spinner *ngIf="isSaving" slot="start" name="crescent"></ion-spinner>
          <span *ngIf="!isSaving">{{ isEditMode ? 'Guardar Cambios' : 'Crear Usuario' }}</span>
          <span *ngIf="isSaving">Guardando...</span>
        </ion-button>
      </div>

    </form>
  `,
  styles: [`
    :host {
      --spacing-xs: 4px;
      --spacing-sm: 8px;
      --spacing-md: 12px;
      --spacing-lg: 16px;
      --spacing-xl: 24px;
      --radius-md: 12px;
      --primary-blue: #0066ff;
      --border-light: #e2e8f0;
      --text-dark: #2d3748;
      --text-light: #718096;
      --white-pure: #ffffff;
      --danger-red: #ef4444;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: var(--spacing-lg);
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: var(--spacing-sm);
    }

    ion-item {
      --background: var(--white-pure);
      --padding-start: var(--spacing-lg);
      --padding-end: var(--spacing-lg);
      --padding-top: var(--spacing-md);
      --padding-bottom: var(--spacing-md);
      border-radius: var(--radius-md);
      border: 1px solid var(--border-light);
      margin-bottom: var(--spacing-lg);
      min-height: 48px;
      transition: all 0.2s ease;

      &:hover {
        border-color: #cbd5e0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
      }

      &:focus-within {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
      }

      &.has-error {
        border-color: var(--danger-red);

        &:focus-within {
          box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
      }

      ion-label {
        color: var(--text-dark);
        font-weight: 500;
        font-size: 0.95rem;
        margin: 0;

        &.label-stacked {
          margin-bottom: var(--spacing-xs);
        }
      }

      ion-input,
      ion-select {
        --color: var(--text-dark);
        --placeholder-color: var(--text-light);
        font-size: 1rem;
      }
    }

    .error-text {
      display: block;
      margin: -var(--spacing-md) var(--spacing-lg) var(--spacing-md);
      font-size: 0.85rem;
      color: var(--danger-red);
    }

    .form-actions {
      display: flex;
      gap: var(--spacing-lg);
      justify-content: flex-end;
      padding: var(--spacing-lg) 0;
      margin-top: var(--spacing-xl);

      ion-button {
        min-width: 120px;
        height: 44px;
        font-weight: 600;
        font-size: 0.95rem;
        border-radius: var(--radius-md);
        --padding-start: var(--spacing-lg);
        --padding-end: var(--spacing-lg);
        transition: all 0.2s ease;
        text-transform: none;

        &.btn-secondary {
          --background: transparent;
          --border-color: var(--border-light);
          --color: var(--text-dark);
          border: 1px solid var(--border-light);

          &:hover {
            --background: #f7fafc;
            --border-color: #cbd5e0;
          }
        }

        &.btn-primary {
          --background: var(--primary-blue);
          --color: var(--white-pure);
          box-shadow: 0 2px 8px rgba(0, 102, 255, 0.2);
          width: auto;
          min-width: 140px;

          &:hover {
            --background: #0052cc;
            box-shadow: 0 4px 16px rgba(0, 102, 255, 0.3);
            transform: translateY(-1px);
          }

          &:active {
            transform: translateY(0);
          }

          &[disabled] {
            --background: #cbd5e0;
            box-shadow: none;
            opacity: 0.6;
          }
        }

        ion-spinner {
          margin-right: var(--spacing-sm);
        }
      }
    }
  `]
})
export class UserFormComponent implements OnInit, OnChanges {
  @Input() user: User | null = null;
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
      password: ['', [Validators.minLength(6)]],
      role: ['waiter', Validators.required],
      pin: ['', [Validators.pattern('^[0-9]{4}$')]],
      image_src: [null]
    });
  }

  private checkEditMode() {
    if (!this.userForm) return;

    if (this.user) {
      this.isEditMode = true;
      this.userForm.patchValue({
        name: this.user.name,
        email: this.user.email,
        role: this.user.role,
        pin: this.user.pin,
        image_src: this.user.imageSrc,
        password: ''
      });
      this.userForm.get('password')?.setValidators([Validators.minLength(6)]);
    } else {
      this.isEditMode = false;
      this.userForm.reset({ role: 'waiter' });
      this.userForm.get('password')?.setValidators([Validators.required, Validators.minLength(6)]);
    }
    this.userForm.get('password')?.updateValueAndValidity();
  }

  onSubmit() {
    if (this.userForm.valid) {
      const value = { ...this.userForm.value };
      if (this.isEditMode && !value.password) {
        delete value.password;
      }
      this.save.emit(value);
    }
  }
}