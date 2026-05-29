import { Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { 
  IonItem, IonLabel, IonInput, IonTextarea, 
  IonCheckbox, IonButton, IonText, IonSelect, IonSelectOption,
  IonIcon 
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { sparklesOutline } from 'ionicons/icons'; 
import { Product } from '../../../../services/products/product.model';
import { FamiliesApiService, Family } from '../../../../services/families/families-api.service';
import { TaxesApiService } from '../../../../services/taxes/taxes-api.service';
import { Tax } from '../../../../services/taxes/tax.model';

@Component({
  selector: 'app-product-form',
  templateUrl: './product-form.component.html',
  standalone: true,
  imports: [
    CommonModule, ReactiveFormsModule, IonItem, IonLabel, 
    IonInput, IonTextarea, IonCheckbox, IonButton, IonText, IonSelect, 
    IonSelectOption, IonIcon 
  ],
})
export class ProductFormComponent implements OnInit, OnChanges {
  @Input() product: Product | null = null;
  @Input() products: Product[] = []; 
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
    suggested_product_uuid: [null],
    upselling_tip: ['']
  });

  constructor() {
    addIcons({ sparklesOutline }); 
  }

  get nameControl() {
    return this.form.get('name');
  }

  ngOnInit(): void {
    this.loadFamilies();
    this.loadTaxes();
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['product']) {
      if (this.product) {
        // Mapeo exhaustivo cuando se entra a editar un producto existente
        this.form.patchValue({
          ...this.product,
          tax_uuid: this.product.tax_uuid || (this.product as any).tax?.uuid || '',
          price: this.product.price / 100,
          description: this.product.description ?? '',
          suggested_product_uuid: this.product.suggested_product_uuid ?? null,
          upselling_tip: this.product.upselling_tip ?? ''
        });
      } else {
        // CORRECCIÓN: Si el producto pasa a ser null (reseteo al cerrar/guardar),
        // limpiamos por completo el formulario reactivo para evitar fugas de datos antiguos
        this.form.reset({
          family_uuid: '',
          tax_uuid: this.taxes.length > 0 ? this.taxes[0].uuid : '',
          name: '',
          description: '',
          price: 0,
          stock: 0,
          active: true,
          suggested_product_uuid: null,
          upselling_tip: ''
        });
      }
    }
  }

  private loadFamilies(): void {
    this.familiesApiService.getAll().subscribe({
      next: (f: Family[]) => this.families = f.filter(item => item.active),
      error: (e: any) => console.error('Error al cargar familias:', e)
    });
  }

  private loadTaxes(): void {
    this.taxesApiService.getAll().subscribe({
      next: (t: Tax[]) => {
        this.taxes = t;
        console.log('--- ¡ESPIANDO LOS IMPUESTOS! ---', t);
        if (!this.product && this.taxes.length > 0) {
          this.form.patchValue({ tax_uuid: this.taxes[0].uuid });
        } else if (this.product) {
          this.form.patchValue({
            tax_uuid: this.product.tax_uuid || (this.product as any).tax?.uuid || ''
          });
        }
      },
      error: (e: any) => console.error('Error al cargar impuestos:', e)
    });
  }

  onSubmit(): void {
    if (this.form.valid) {
      const val = this.form.value;
      this.save.emit({
        ...val,
        price: Math.round(val.price * 100),
        stock: Number(val.stock),
        suggested_product_uuid: val.suggested_product_uuid || null,
        upselling_tip: val.upselling_tip ? val.upselling_tip.trim() : ''
      });
    }
  }

  onCancel(): void {
    this.cancel.emit();
  }
}