import { Component, EventEmitter, Input, OnChanges, Output, SimpleChanges, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { IonItem, IonLabel, IonInput, IonCheckbox, IonButton, IonSpinner } from '@ionic/angular/standalone';
import { Zone } from '../../../../services/zones/zone.model';

@Component({
  selector: 'app-zone-form',
  templateUrl: './zone-form.component.html',
  styleUrls: ['./zone-form.component.scss'],
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, IonItem, IonLabel, IonInput, IonCheckbox, IonButton, IonSpinner],
})
export class ZoneFormComponent implements OnChanges {
  @Input() zone: Zone | null = null;
  @Input() isSaving = false;
  @Output() save = new EventEmitter<{ name: string }>();
  @Output() cancel = new EventEmitter<void>();

  private formBuilder = inject(FormBuilder);
  
  form: FormGroup = this.formBuilder.group({
    name: ['', [Validators.required, Validators.maxLength(255)]]
  });

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['zone']) {
      if (this.zone) {
        this.form.patchValue({
          name: this.zone.name
        });
      } else {
        this.form.reset({
          name: ''
        });
      }
    }
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