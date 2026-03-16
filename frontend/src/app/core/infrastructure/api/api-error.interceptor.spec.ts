import { TestBed } from '@angular/core/testing';
import {
  HttpClient,
  HttpErrorResponse,
  provideHttpClient,
  withInterceptors,
} from '@angular/common/http';
import { HttpTestingController, provideHttpClientTesting } from '@angular/common/http/testing';
import { apiErrorInterceptor } from './api-error.interceptor';
import type { ApiValidationError } from '../../../shared/models/api-error.models';

describe('apiErrorInterceptor', () => {
  let http: HttpClient;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        provideHttpClient(withInterceptors([apiErrorInterceptor])),
        provideHttpClientTesting(),
      ],
    });
    http = TestBed.inject(HttpClient);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should normalize 422 Laravel-style body to ApiValidationError', (done) => {
    const laravelBody = {
      message: 'The email has already been taken.',
      errors: { email: ['The email has already been taken.'] },
    };

    http.post('/users', {}).subscribe({
      next: () => done.fail('should have failed'),
      error: (err: HttpErrorResponse) => {
        expect(err.status).toBe(422);
        const body = err.error as ApiValidationError;
        expect(body.message).toBe('The email has already been taken.');
        expect(body.fieldErrors).toEqual({ email: ['The email has already been taken.'] });
        done();
      },
    });

    const req = httpMock.expectOne('/users');
    req.flush(laravelBody, { status: 422, statusText: 'Unprocessable Entity' });
  });

  it('should pass through non-HttpErrorResponse errors', (done) => {
    http.post('/users', {}).subscribe({
      next: () => done.fail('should have failed'),
      error: (err) => {
        expect(err).toBeInstanceOf(HttpErrorResponse);
        const body = (err as HttpErrorResponse).error as ApiValidationError;
        expect(body.fieldErrors).toEqual({});
        done();
      },
    });

    const req = httpMock.expectOne('/users');
    req.flush(
      { message: 'Server error' },
      { status: 500, statusText: 'Internal Server Error' }
    );
  });
});
