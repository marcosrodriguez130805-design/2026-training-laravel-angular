import { Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { IonList, IonItem, IonLabel, IonInput, IonTextarea, IonCheckbox, IonButton, IonText, IonSelect, IonSelectOption } from '@ionic/angular/standalone';
import { Product } from '../../../../services/products/product.model';
import { FamiliesApiService, Family } from '../../../../services/families/families-api.service';

@Component({
  selector: 'app-product-form',
  templateUrl: './product-form.component.html',
  styleUrls: ['./product-form.component.scss'],
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, IonList, IonItem, IonLabel, IonInput, IonTextarea, IonCheckbox, IonButton, IonText, IonSelect, IonSelectOption],
})
export class ProductFormComponent implements OnInit, OnChanges {
  @Input() product: Product | null = null;
  @Output() save = new EventEmitter<{ family_uuid: string; name: string; description?: string; price: number; active: boolean }>();
  @Output() cancel = new EventEmitter<void>();

  private formBuilder = inject(FormBuilder);
  private familiesApiService = inject(FamiliesApiService);

  families: Family[] = [];

  form: FormGroup = this.formBuilder.group({
    family_uuid: ['', Validators.required],
    name: ['', [Validators.required, Validators.maxLength(255)]],
    description: [''],
    price: [0, [Validators.required, Validators.min(0)]],
    active: [true],
  });

  ngOnInit(): void {
    this.loadFamilies();
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['product']) {
      this.form.patchValue({
        family_uuid: this.product?.family_uuid ?? '',
        name: this.product?.name ?? '',
        description: this.product?.description ?? '',
        price: this.product?.price ?? 0,
        active: this.product?.active ?? true,
      });
    }
  }

  private loadFamilies(): void {
    this.familiesApiService.getAll().subscribe({
      next: (families) => {
        this.families = families.filter(f => f.active);
      },
      error: (error) => {
        console.error('Error loading families:', error);
      },
    });
  }

  get nameControl() {
    return this.form.get('name');
  }

  get priceControl() {
    return this.form.get('price');
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