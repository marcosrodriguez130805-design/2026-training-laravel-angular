import { Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { IonList, IonItem, IonLabel, IonInput, IonTextarea, IonCheckbox, IonButton, IonText, IonSelect, IonSelectOption } from '@ionic/angular/standalone';
import { Product } from '../../../../services/products/product.model';
import { FamiliesApiService, Family } from '../../../../services/families/families-api.service';
import { TaxesApiService, Tax } from '../../../../services/taxes/taxes-api.service';

@Component({
  selector: 'app-product-form',
  templateUrl: './product-form.component.html',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, IonList, IonItem, IonLabel, IonInput, IonTextarea, IonCheckbox, IonButton, IonText, IonSelect, IonSelectOption],
})
export class ProductFormComponent implements OnInit, OnChanges {
  @Input() product: Product | null = null;
  @Output() save = new EventEmitter<any>();
  @Output() cancel = new EventEmitter<void>();

  private formBuilder = inject(FormBuilder);
  private familiesApiService = inject(FamiliesApiService);
  private taxesApiService = inject(TaxesApiService);

  families: Family[] = [];
  taxes: Tax[] = [];

  form: FormGroup = this.formBuilder.group({
    family_uuid: ['', Validators.required],
    tax_uuid: ['', Validators.required],
    name: ['', [Validators.required, Validators.maxLength(255)]],
    description: [''],
    price: [0, [Validators.required, Validators.min(0)]],
    stock: [0, [Validators.required, Validators.min(0)]],
    active: [true],
  });

  // Getters para validaciones en el HTML
  get nameControl() {
    return this.form.get('name');
  }

  get priceControl() {
    return this.form.get('price');
  }

  get taxControl() {
    return this.form.get('tax_uuid');
  }

  ngOnInit(): void {
    this.loadFamilies();
    this.loadTaxes();
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['product'] && this.product) {
      this.form.patchValue({
        ...this.product,
        price: this.product.price / 100,
        description: this.product.description ?? '',
      });
    }
  }

  private loadFamilies(): void {
    this.familiesApiService.getAll().subscribe({
      next: (f: Family[]) => this.families = f.filter(item => item.active),
      error: (e: any) => console.error('Error detectando familias:', e)
    });
  }

  private loadTaxes(): void {
  this.taxesApiService.getAll().subscribe({
    next: (t: Tax[]) => {
      console.log('Impuestos detectados:', t); // Mira la consola del navegador (F12)
      this.taxes = t; // Quita el .filter(...) un momento
      
      if (!this.product && this.taxes.length > 0) {
        this.form.patchValue({ tax_uuid: this.taxes[0].uuid });
      }
    },
    error: (e: any) => console.error('Error detectando impuestos:', e)
  });
}

  onSubmit(): void {
    if (this.form.valid) {
      const val = this.form.value;
      this.save.emit({
        ...val,
        price: Math.round(val.price * 100),
        stock: Number(val.stock)
      });
    } else {
      this.form.markAllAsTouched();
    }
  }

  onCancel(): void {
    this.cancel.emit();
  }
}