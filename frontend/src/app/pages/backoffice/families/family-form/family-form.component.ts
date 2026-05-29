import { Component, EventEmitter, Input, OnChanges, Output, SimpleChanges, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { IonItem, IonLabel, IonInput, IonCheckbox, IonButton, IonText, IonSpinner } from '@ionic/angular/standalone';
import { Family } from '../../../../services/families/families-api.service';

@Component({
  selector: 'app-family-form',
  templateUrl: './family-form.component.html',
  styleUrls: ['./family-form.component.scss'],
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, IonItem, IonLabel, IonInput, IonCheckbox, IonButton, IonText, IonSpinner],
})
export class FamilyFormComponent implements OnChanges {
  @Input() family: Family | null = null;
  @Input() isSaving = false;
  @Output() save = new EventEmitter<{ name: string; active: boolean }>();
  @Output() cancel = new EventEmitter<void>();

  private formBuilder = inject(FormBuilder);
  form: FormGroup = this.formBuilder.group({
    name: ['', [Validators.required, Validators.maxLength(255)]],
    active: [true],
  });

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['family']) {
      if (this.family) {
        this.form.patchValue({
          name: this.family.name,
          active: this.family.active,
        });
      } else {
        this.form.reset({
          name: '',
          active: true,
        });
      }
    }
  }

  get nameControl() {
    return this.form.get('name');
  }

  onSubmit(): void {
    if (this.form.valid) {
      this.save.emit(this.form.value);
    }
  }

  onCancel(): void {
    this.cancel.emit();
  }
}
