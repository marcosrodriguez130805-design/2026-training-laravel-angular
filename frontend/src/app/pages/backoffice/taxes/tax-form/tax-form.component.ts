import { Component, Input, Output, EventEmitter, inject } from '@angular/core'; // IMPORTANTE: de @angular/core
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { 
  IonItem, IonLabel, IonInput, IonButton, 
  IonToggle, IonSpinner 
} from '@ionic/angular/standalone';
import { TaxesApiService } from '../../../../services/taxes/taxes-api.service';
import { Tax } from '../../../../services/taxes/tax.model';

@Component({
  selector: 'app-tax-form',
  templateUrl: './tax-form.component.html',
  standalone: true,
  imports: [
    CommonModule, ReactiveFormsModule, IonItem, IonLabel, 
    IonInput, IonButton, IonToggle, IonSpinner
  ]
})
export class TaxFormComponent {
  private fb = inject(FormBuilder);
  private taxesApiService = inject(TaxesApiService);

  @Input() set tax(value: Tax | null) {
    if (value) {
      this.isEditing = true;
      this.form.patchValue(value);
    } else {
      this.isEditing = false;
      this.form.reset({ active: true, value: 0 });
    }
  }

  @Output() save = new EventEmitter<void>();
  @Output() cancel = new EventEmitter<void>();

  isEditing = false;
  isSaving = false;

  form: FormGroup = this.fb.group({
    name: ['', [Validators.required]],
    value: [0, [Validators.required, Validators.min(0)]],
    active: [true]
  });

  onSubmit() {
    if (this.form.invalid) return;
    this.isSaving = true;

    const request$ = this.isEditing && this.tax
      ? this.taxesApiService.update(this.tax.uuid, this.form.value)
      : this.taxesApiService.create(this.form.value);

    request$.subscribe({
      next: () => {
        this.isSaving = false;
        this.save.emit();
      },
      error: () => this.isSaving = false
    });
  }
}