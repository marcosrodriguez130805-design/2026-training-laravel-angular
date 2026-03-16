import { TestBed } from '@angular/core/testing';
import { provideHttpClient, withInterceptors } from '@angular/common/http';
import { HttpTestingController, provideHttpClientTesting } from '@angular/common/http/testing';
import { API_BASE_URL } from './api-client.service';
import { ApiClientService } from './api-client.service';
import { apiErrorInterceptor } from './api-error.interceptor';
import type { CreateUserRequest, CreateUserResponse } from '../../../shared/models/user-api.models';

describe('ApiClientService', () => {
  let service: ApiClientService;
  let httpMock: HttpTestingController;
  const baseUrl = 'http://localhost:8000/api';

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        provideHttpClient(withInterceptors([apiErrorInterceptor])),
        provideHttpClientTesting(),
        { provide: API_BASE_URL, useValue: baseUrl },
      ],
    });
    service = TestBed.inject(ApiClientService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('createUser() should POST to /users with body and return CreateUserResponse on 201', () => {
    const body: CreateUserRequest = {
      name: 'Test User',
      email: 'test@example.com',
      password: 'password123',
      password_confirmation: 'password123',
    };
    const mockResponse: CreateUserResponse = {
      id: 'uuid-123',
      name: 'Test User',
      email: 'test@example.com',
      created_at: '2025-01-01T00:00:00+00:00',
      updated_at: '2025-01-01T00:00:00+00:00',
    };

    service.createUser(body).subscribe((res) => {
      expect(res).toEqual(mockResponse);
    });

    const req = httpMock.expectOne(`${baseUrl}/users`);
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual(body);
    req.flush(mockResponse, { status: 201, statusText: 'Created' });
  });

  it('createUser() should fail on 422 with normalized ApiValidationError', () => {
    const body: CreateUserRequest = {
      name: 'Test',
      email: 'taken@example.com',
      password: 'password123',
      password_confirmation: 'password123',
    };

    service.createUser(body).subscribe({
      next: () => fail('should have failed'),
      error: (err) => {
        expect(err.status).toBe(422);
        expect(err.error?.message).toBe('The email has already been taken.');
        expect(err.error?.fieldErrors).toEqual({ email: ['The email has already been taken.'] });
      },
    });

    const req = httpMock.expectOne(`${baseUrl}/users`);
    req.flush(
      { message: 'The email has already been taken.', errors: { email: ['The email has already been taken.'] } },
      { status: 422, statusText: 'Unprocessable Entity' }
    );
  });
});
