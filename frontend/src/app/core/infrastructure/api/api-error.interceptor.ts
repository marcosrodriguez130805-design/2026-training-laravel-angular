import {
  HttpErrorResponse,
  type HttpHandlerFn,
  type HttpRequest,
  HttpInterceptorFn,
} from '@angular/common/http';
import { catchError, throwError } from 'rxjs';
import type { ApiValidationError } from '../../../shared/models/api-error.models';

function toFieldErrors(raw: unknown): Record<string, string[]> {
  if (raw == null || typeof raw !== 'object') {
    return {};
  }
  const entries = Object.entries(raw as Record<string, unknown>).map(
    ([key, value]): [string, string[]] => {
      if (Array.isArray(value)) {
        return [key, value.map((v) => (typeof v === 'string' ? v : String(v)))];
      }
      return [key, value != null ? [String(value)] : []];
    }
  );
  return Object.fromEntries(entries);
}

function normalizeBody(body: unknown): ApiValidationError {
  if (body != null && typeof body === 'object' && 'errors' in body) {
    const b = body as { message?: string; errors?: unknown };
    return {
      message: typeof b.message === 'string' ? b.message : undefined,
      fieldErrors: toFieldErrors(b.errors),
    };
  }
  const message =
    body != null && typeof body === 'object' && 'message' in body
      ? (body as { message?: unknown }).message
      : undefined;
  return {
    message: typeof message === 'string' ? message : undefined,
    fieldErrors: {},
  };
}

export const apiErrorInterceptor: HttpInterceptorFn = (req: HttpRequest<unknown>, next: HttpHandlerFn) => {
  return next(req).pipe(
    catchError((err: unknown) => {
      if (err instanceof HttpErrorResponse && err.error != null) {
        const normalized: ApiValidationError = normalizeBody(err.error);
        return throwError(
          () =>
            new HttpErrorResponse({
              error: normalized,
              headers: err.headers,
              status: err.status,
              statusText: err.statusText,
              url: err.url ?? undefined,
            })
        );
      }
      return throwError(() => err);
    })
  );
};
