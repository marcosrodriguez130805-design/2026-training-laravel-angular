import { ChangeDetectorRef, Component, inject } from '@angular/core';
import { ReactiveFormsModule, FormBuilder, Validators, AbstractControl } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatSnackBar } from '@angular/material/snack-bar';
import { ApiClientService } from '../../../core/infrastructure/api/api-client.service';
import { ValidationMessagesService } from '../../../core/validation/validation-messages.service';
import type { ApiValidationError } from '../../../shared/models/api-error.models';
import type { CreateUserRequest } from '../../../shared/models/user-api.models';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [ReactiveFormsModule, MatFormFieldModule, MatInputModule, MatButtonModule],
  templateUrl: './register.component.html',
  styleUrl: './register.component.css',
})
export class RegisterComponent {
  private readonly fb = inject(FormBuilder);
  private readonly api = inject(ApiClientService);
  private readonly snackBar = inject(MatSnackBar);
  private readonly cdr = inject(ChangeDetectorRef);
  private readonly validationMessages = inject(ValidationMessagesService);

  protected readonly form = this.fb.nonNullable.group({
    name: ['', [Validators.required]],
    email: ['', [Validators.required, Validators.email]],
    password: ['', [Validators.required, Validators.minLength(8)]],
    password_confirmation: ['', [Validators.required]],
  }, { validators: this.passwordMatchValidator });

  protected submitting = false;
  protected formLevelMessage = '';

  private passwordMatchValidator(group: AbstractControl) {
    const password = group.get('password')?.value;
    const confirmation = group.get('password_confirmation')?.value;
    return password && confirmation && password !== confirmation
      ? { passwordMismatch: true }
      : null;
  }

  /** Mensaje de error a mostrar en el campo: passwordMismatch a nivel grupo, resto vía control (incl. serverError). */
  protected getFieldError(field: string): string {
    const password = this.form.get('password')?.value ?? '';
    const confirmation = this.form.get('password_confirmation')?.value ?? '';
    if (
      (field === 'password' || field === 'password_confirmation') &&
      password !== '' &&
      confirmation !== '' &&
      password !== confirmation
    ) {
      return this.validationMessages.getMessage('passwordMismatch', null, field);
    }
    return this.validationMessages.getControlMessage(this.form.get(field), field);
  }

  private clearServerErrors(): void {
    for (const key of ['name', 'email', 'password', 'password_confirmation']) {
      const control = this.form.get(key);
      const errors = control?.errors;
      if (errors && 'serverError' in errors) {
        const { serverError: _, ...rest } = errors;
        control!.setErrors(Object.keys(rest).length > 0 ? rest : null);
      }
    }
  }

  protected onSubmit(): void {
    this.formLevelMessage = '';
    this.clearServerErrors();
    if (this.submitting) {
      return;
    }
    this.form.updateValueAndValidity();
    this.form.markAllAsTouched();
    if (this.form.invalid) {
      this.cdr.detectChanges();
      return;
    }
    const value = this.form.getRawValue() as CreateUserRequest;
    this.submitting = true;
    this.api.createUser(value).subscribe({
      next: () => {
        this.submitting = false;
        this.form.reset();
        this.formLevelMessage = '';
        this.snackBar.open('Usuario registrado correctamente.', 'Cerrar', { duration: 5000 });
      },
      error: (err) => {
        this.submitting = false;
        const apiError = err.error as ApiValidationError | undefined;
        if (apiError?.fieldErrors && Object.keys(apiError.fieldErrors).length > 0) {
          for (const [field, messages] of Object.entries(apiError.fieldErrors)) {
            const control = this.form.get(field);
            const firstMessage = Array.isArray(messages) ? messages[0] : String(messages);
            if (control && firstMessage) {
              control.setErrors({
                ...(control.errors ?? {}),
                serverError: firstMessage,
              });
            }
          }
          this.form.markAllAsTouched();
          this.formLevelMessage =
            apiError.message ??
            Object.values(apiError.fieldErrors).flat().find(Boolean) ??
            '';
        } else {
          this.formLevelMessage = apiError?.message ?? err.message ?? 'Error al registrar.';
          this.snackBar.open(this.formLevelMessage, 'Cerrar', { duration: 5000 });
        }
        this.cdr.detectChanges();
      },
    });
  }
}
