import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { forkJoin } from 'rxjs';
import { 
  IonContent, IonList, IonLabel, IonItem, 
  IonHeader, IonToolbar, IonSegment, IonSegmentButton,
  IonIcon, IonText, IonTitle, IonButtons, IonButton,
  IonToggle, ToastController 
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { add, close, trash, create } from 'ionicons/icons'; 
import { ProductsApiService } from '../../../services/products/products-api.service';
import { FamiliesApiService, Family } from '../../../services/families/families-api.service';
import { Product } from '../../../services/products/product.model';
import { ProductFormComponent } from './product-form/product-form.component';

@Component({
  selector: 'app-products-page',
  templateUrl: './products.page.html',
  standalone: true,
  imports: [
    CommonModule, IonContent, IonList, IonLabel, IonItem,
    IonHeader, IonToolbar, IonSegment, IonSegmentButton,
    IonIcon, IonText, IonTitle, IonButtons, IonButton,
    IonToggle, 
    ProductFormComponent
  ]
})
export class ProductsPage implements OnInit {
  private productsApiService = inject(ProductsApiService);
  private familiesApiService = inject(FamiliesApiService);
  private toastCtrl = inject(ToastController);

  families: Family[] = [];
  allProducts: Product[] = [];
  filteredProducts: Product[] = [];
  selectedFamilyUuid: string = 'all';
  isCreating: boolean = false;
  selectedProduct: Product | null = null;

  constructor() {
    addIcons({ add, close, trash, create });
  }

  ngOnInit() {
    this.loadData();
  }

  private async showToast(message: string, color: 'success' | 'danger' = 'success') {
    const toast = await this.toastCtrl.create({
      message,
      duration: 2000,
      color,
      position: 'bottom'
    });
    await toast.present();
  }

  loadData() {
    forkJoin({
      products: this.productsApiService.getAll(),
      families: this.familiesApiService.getAll()
    }).subscribe({
      next: ({ products, families }) => {
        this.families = families; 
        this.allProducts = products;
        this.filterProducts();
      },
      error: () => this.showToast('Error al cargar datos', 'danger')
    });
  }

  toggleProductActive(product: Product, event: any) {
    const newStatus = event.detail.checked;
    if (product.active === newStatus) return;

    this.productsApiService.update(product.uuid, { ...product, active: newStatus }).subscribe({
      next: () => {
        product.active = newStatus;
        this.showToast(`Producto ${newStatus ? 'activado' : 'desactivado'}`);

        const parentFamily = this.families.find(f => f.uuid === product.family_uuid);
        if (!parentFamily) return;

        if (newStatus === true) {
          // LÓGICA: Activar familia si estaba inactiva
          if (!parentFamily.active) {
            this.familiesApiService.update(parentFamily.uuid, { ...parentFamily, active: true }).subscribe({
              next: () => {
                parentFamily.active = true;
                this.showToast(`Familia "${parentFamily.name}" activada automáticamente`);
              }
            });
          }
        } else {
          // LÓGICA: Desactivar familia si no quedan más productos activos
          const hasOtherActiveProducts = this.allProducts.some(
            p => p.family_uuid === product.family_uuid && p.active && p.uuid !== product.uuid
          );

          if (!hasOtherActiveProducts && parentFamily.active) {
            this.familiesApiService.update(parentFamily.uuid, { ...parentFamily, active: false }).subscribe({
              next: () => {
                parentFamily.active = false;
                this.showToast(`Familia "${parentFamily.name}" desactivada (sin productos activos)`, 'danger');
              }
            });
          }
        }
      },
      error: () => {
        this.showToast('Error al cambiar estado', 'danger');
        this.loadData();
      }
    });
  }

  handleDelete(product: Product) {
    if (confirm(`¿Eliminar ${product.name}?`)) {
      this.productsApiService.delete(product.uuid).subscribe({
        next: () => {
          this.allProducts = this.allProducts.filter(p => p.uuid !== product.uuid);
          this.filterProducts();
          this.showToast('Producto eliminado');
          
          // Al eliminar, también comprobamos si la familia debe desactivarse
          const parentFamily = this.families.find(f => f.uuid === product.family_uuid);
          if (parentFamily && parentFamily.active) {
            const hasActive = this.allProducts.some(p => p.family_uuid === product.family_uuid && p.active);
            if (!hasActive) {
              this.familiesApiService.update(parentFamily.uuid, { ...parentFamily, active: false }).subscribe({
                next: () => parentFamily.active = false
              });
            }
          }
        },
        error: () => this.showToast('Error al eliminar producto', 'danger')
      });
    }
  }

  onProductSaved(productData: any) {
    const request = this.selectedProduct
      ? this.productsApiService.update(this.selectedProduct.uuid, productData)
      : this.productsApiService.create(productData);

    request.subscribe({
      next: () => {
        this.isCreating = false;
        this.selectedProduct = null;
        this.loadData();
        this.showToast('Producto guardado correctamente');
      },
      error: (e: any) => this.showToast(e.error?.message || 'Error al guardar', 'danger')
    });
  }

  handleEdit(product: Product) { this.selectedProduct = product; this.isCreating = true; }
  handleCreate() { this.selectedProduct = null; this.isCreating = true; }
  cancelCreate() { this.isCreating = false; this.selectedProduct = null; }
  onFamilyChange(event: any) { this.selectedFamilyUuid = event.detail.value; this.filterProducts(); }
  
  private filterProducts() {
    this.filteredProducts = this.selectedFamilyUuid === 'all' 
      ? this.allProducts 
      : this.allProducts.filter(p => p.family_uuid === this.selectedFamilyUuid);
  }
}