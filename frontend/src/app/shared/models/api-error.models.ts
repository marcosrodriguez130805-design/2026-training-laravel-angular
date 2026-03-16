/**
 * Contrato estándar de error de validación de la API.
 * El interceptor HTTP normaliza las respuestas de error (p. ej. Laravel) a esta forma.
 */
export interface ApiValidationError {
  message?: string;
  fieldErrors: Record<string, string[]>;
}
