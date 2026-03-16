import { Injectable } from '@angular/core';
import { AbstractControl } from '@angular/forms';

/** Mensajes por tipo de error. Clave = error key de Angular o custom (ej. passwordMismatch). */
const ERROR_MESSAGES: Record<
  string,
  string | ((value: unknown, fieldKey?: string) => string)
> = {
  required: (_, fieldKey) =>
    FIELD_REQUIRED_MESSAGES[fieldKey ?? ''] ?? 'Este campo es obligatorio.',
  email: () => 'Introduce un email válido.',
  minlength: (value: unknown) =>
    `Debe tener al menos ${(value && typeof value === 'object' && 'requiredLength' in value ? (value as { requiredLength?: number }).requiredLength : undefined) ?? 8} caracteres.`,
  passwordMismatch: () => 'Las contraseñas deben coincidir.',
  serverError: (value: unknown) =>
    typeof value === 'string' ? value : 'Error del servidor.',
};

const FIELD_REQUIRED_MESSAGES: Record<string, string> = {
  name: 'El nombre es obligatorio.',
  email: 'El email es obligatorio.',
  password: 'La contraseña es obligatoria.',
  password_confirmation: 'Confirma la contraseña.',
};

@Injectable({ providedIn: 'root' })
export class ValidationMessagesService {
  /**
   * Devuelve el mensaje para un error de validación.
   * @param errorKey - Clave del error (required, email, minlength, passwordMismatch, etc.)
   * @param errorValue - Valor del error (ej. { requiredLength: 8 } para minlength)
   * @param fieldKey - Nombre del campo (para mensajes específicos como required)
   */
  getMessage(
    errorKey: string,
    errorValue?: unknown,
    fieldKey?: string
  ): string {
    const message = ERROR_MESSAGES[errorKey];
    if (message == null) {
      return '';
    }
    return typeof message === 'function'
      ? message(errorValue, fieldKey)
      : message;
  }

  /**
   * Devuelve el mensaje del primer error del control, si está invalid y touched.
   * Útil para mostrar un solo mensaje por campo.
   */
  getControlMessage(
    control: AbstractControl | null,
    fieldKey?: string
  ): string {
    if (!control?.invalid || !control.touched || !control.errors) {
      return '';
    }
    const firstKey = Object.keys(control.errors)[0];
    const firstValue = firstKey ? control.errors[firstKey] : undefined;

    return this.getMessage(firstKey, firstValue, fieldKey);
  }
}
