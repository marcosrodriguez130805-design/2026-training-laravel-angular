import { TestBed } from '@angular/core/testing';
import { NoopAnimationsModule } from '@angular/platform-browser/animations';
import { MatSnackBar } from '@angular/material/snack-bar';
import { ApiClientService } from '../../../core/infrastructure/api/api-client.service';
import { RegisterComponent } from './register.component';
import { of, throwError } from 'rxjs';

describe('RegisterComponent', () => {
  let mockApi: jasmine.SpyObj<Pick<ApiClientService, 'createUser'>>;
  let mockSnackBar: jasmine.SpyObj<Pick<MatSnackBar, 'open'>>;

  beforeEach(async () => {
    mockApi = jasmine.createSpyObj('ApiClientService', ['createUser']);
    mockSnackBar = jasmine.createSpyObj('MatSnackBar', ['open']);

    await TestBed.configureTestingModule({
      imports: [RegisterComponent, NoopAnimationsModule],
      providers: [
        { provide: ApiClientService, useValue: mockApi },
        { provide: MatSnackBar, useValue: mockSnackBar },
      ],
    }).compileComponents();
  });

  it('should create', () => {
    const fixture = TestBed.createComponent(RegisterComponent);
    expect(fixture.componentInstance).toBeTruthy();
  });

  it('should not call API when form is invalid', () => {
    const fixture = TestBed.createComponent(RegisterComponent);
    fixture.detectChanges();
    const comp = fixture.componentInstance as unknown as { form: { get: (n: string) => { setValue: (v: string) => void } | null }; onSubmit: () => void };
    comp.form.get('name')?.setValue('');
    comp.form.get('email')?.setValue('invalid');
    comp.form.get('password')?.setValue('short');
    comp.form.get('password_confirmation')?.setValue('short');
    comp.onSubmit();
    expect(mockApi.createUser).not.toHaveBeenCalled();
  });

  it('should call ApiClient.createUser with form value when valid', () => {
    const fixture = TestBed.createComponent(RegisterComponent);
    fixture.detectChanges();
    const comp = fixture.componentInstance as unknown as { form: { setValue: (v: object) => void; get: (n: string) => { value: string } | null }; onSubmit: () => void };
    comp.form.setValue({
      name: 'Test User',
      email: 'test@example.com',
      password: 'password1234',
      password_confirmation: 'password1234',
    });
    mockApi.createUser.and.returnValue(of({ id: '1', name: 'Test User', email: 'test@example.com', created_at: '', updated_at: '' }));
    comp.onSubmit();
    expect(mockApi.createUser).toHaveBeenCalledWith({
      name: 'Test User',
      email: 'test@example.com',
      password: 'password1234',
      password_confirmation: 'password1234',
    });
  });

  it('on success should show snackbar and reset form', () => {
    const fixture = TestBed.createComponent(RegisterComponent);
    fixture.detectChanges();
    const comp = fixture.componentInstance as unknown as { form: { setValue: (v: object) => void; get: (n: string) => { value: string } | null }; onSubmit: () => void };
    comp.form.setValue({
      name: 'Test',
      email: 'test@example.com',
      password: 'password1234',
      password_confirmation: 'password1234',
    });
    mockApi.createUser.and.returnValue(of({ id: '1', name: 'Test', email: 'test@example.com', created_at: '', updated_at: '' }));
    comp.onSubmit();
    expect(mockSnackBar.open).toHaveBeenCalledWith('Usuario registrado correctamente.', 'Cerrar', { duration: 5000 });
    expect(comp.form.get('name')?.value).toBe('');
  });

  it('on 422 error should set server errors on controls and formLevelMessage', () => {
    const fixture = TestBed.createComponent(RegisterComponent);
    fixture.detectChanges();
    const comp = fixture.componentInstance as unknown as {
      form: { setValue: (v: object) => void; get: (name: string) => { errors: Record<string, unknown> | null } | null };
      onSubmit: () => void;
      formLevelMessage: string;
    };
    comp.form.setValue({
      name: 'Test',
      email: 'taken@example.com',
      password: 'password1234',
      password_confirmation: 'password1234',
    });
    mockApi.createUser.and.returnValue(
      throwError(() => ({
        error: {
          message: 'The email has already been taken.',
          fieldErrors: { email: ['The email has already been taken.'] },
        },
      }))
    );
    comp.onSubmit();
    expect(comp.form.get('email')?.errors?.['serverError']).toBe('The email has already been taken.');
    expect(comp.formLevelMessage).toBe('The email has already been taken.');
  });
});
