import { TestBed } from '@angular/core/testing';
import { ValidationMessagesService } from './validation-messages.service';

describe('ValidationMessagesService', () => {
  let service: ValidationMessagesService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ValidationMessagesService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('getMessage(serverError, string) should return the string', () => {
    expect(service.getMessage('serverError', 'The email has already been taken.')).toBe(
      'The email has already been taken.'
    );
  });

  it('getMessage(serverError, non-string) should return generic message', () => {
    expect(service.getMessage('serverError', null)).toBe('Error del servidor.');
    expect(service.getMessage('serverError', 123)).toBe('Error del servidor.');
  });
});
