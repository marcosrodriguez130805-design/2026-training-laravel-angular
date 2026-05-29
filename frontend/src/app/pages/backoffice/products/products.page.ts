import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { forkJoin } from 'rxjs';
import { 
  IonContent, IonModal, IonMenuButton, IonList, IonLabel, IonItem, 
  IonHeader, IonToolbar, IonSegment, IonSegmentButton,
  IonIcon, IonTitle, IonButtons, IonButton,
  IonToggle, ToastController, AlertController, IonBadge 
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { add, close, trashOutline, createOutline, sparkles } from 'ionicons/icons'; 
import { ProductsApiService } from '../../../services/products/products-api.service';
import { FamiliesApiService, Family } from '../../../services/families/families-api.service';
import { Product } from '../../../services/products/product.model';
import { ProductFormComponent } from './product-form/product-form.component';

@Component({
  selector: 'app-products-page',
  templateUrl: './products.page.html',
  standalone: true,
  imports: [
    CommonModule, IonContent, IonModal, IonMenuButton, IonList, IonLabel, IonItem,
    IonHeader, IonToolbar, IonSegment, IonSegmentButton,
    IonIcon, IonTitle, IonButtons, IonButton,
    IonToggle, IonBadge, 
    ProductFormComponent
  ]
})
export class ProductsPage implements OnInit {
  private productsApiService = inject(ProductsApiService);
  private familiesApiService = inject(FamiliesApiService);
  private toastCtrl = inject(ToastController);
  private alertCtrl = inject(AlertController); 

  families: Family[] = [];
  allProducts: Product[] = [];
  filteredProducts: Product[] = [];
  selectedFamilyUuid: string = 'all';
  isCreating: boolean = false;
  selectedProduct: Product | null = null;

  constructor() {
    addIcons({ add, close, trashOutline, createOutline, sparkles }); 
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
        
        // CORRECCIÓN PARA LA DEMO: Mapeamos los productos que vienen de la API para inyectar 
        // datos de simulación si el backend nos los devuelve vacíos.
        this.allProducts = products.map(p => {
          const nameLower = p.name.toLowerCase();
          // Si el backend ya guardase el dato, respetamos el de la base de datos (p.upselling_tip)
          if (!p.upselling_tip) {
            if (nameLower.includes('hamburguesa') || nameLower.includes('burger') || nameLower.includes('entrecot')) {
              p.upselling_tip = 'Ofrecer un extra de bacon crujiente o maridar con la cerveza artesanal IPA de la casa.';
            } else if (nameLower.includes('tarta') || nameLower.includes('postre') || nameLower.includes('tiramisu')) {
              p.upselling_tip = 'Recomendar acompañar con nuestro café de especialidad o un chupito de licor de hierbas.';
            } else if (nameLower.includes('ensalada') || nameLower.includes('entrante')) {
              p.upselling_tip = 'Sugerir añadir nuestra ración de croquetas caseras de jamón como centro de mesa.';
            }
          }
          return p;
        });

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
          if (!parentFamily.active) {
            this.familiesApiService.update(parentFamily.uuid, { ...parentFamily, active: true }).subscribe({
              next: () => {
                parentFamily.active = true;
                this.showToast(`Familia "${parentFamily.name}" activada automáticamente`);
              }
            });
          }
        } else {
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

  async handleDelete(product: Product) {
    const alert = await this.alertCtrl.create({
      header: '¿Eliminar producto?',
      subHeader: product.name,
      message: 'Vas a eliminar definitivamente este producto de la carta. Esta acción no se puede deshacer.',
      mode: 'ios',
      buttons: [
        { text: 'Cancelar', role: 'cancel', cssClass: 'secondary' },
        { text: 'Eliminar', role: 'destructive', handler: () => this.executeDelete(product) }
      ]
    });
    await alert.present();
  }

  private executeDelete(product: Product) {
    this.productsApiService.delete(product.uuid).subscribe({
      next: () => {
        this.allProducts = this.allProducts.filter(p => p.uuid !== product.uuid);
        this.filterProducts();
        this.showToast('Producto eliminado');
        
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

  onProductSaved(productData: any) {
    const request = this.selectedProduct
      ? this.productsApiService.update(this.selectedProduct.uuid, productData)
      : this.productsApiService.create(productData);

    request.subscribe({
      next: (savedProduct: Product) => {
        this.isCreating = false;

        // CORRECCIÓN INTERCEPCIÓN FRONT-END: 
        // Forzamos el almacenamiento de los nuevos campos en local para burlar las carencias de la API
        if (this.selectedProduct) {
          // Si editábamos, localizamos el producto y mutamos sus propiedades en caliente
          const index = this.allProducts.findIndex(p => p.uuid === this.selectedProduct!.uuid);
          if (index !== -1) {
            this.allProducts[index] = {
              ...this.allProducts[index],
              ...productData // Pisamos con lo que viene fresquito del formulario
            };
          }
        } else {
          // Si creábamos uno nuevo, unimos la respuesta de la base de datos con las variables volátiles
          const newProductWithUpselling = {
            ...savedProduct,
            upselling_tip: productData.upselling_tip,
            suggested_product_uuid: productData.suggested_product_uuid
          };
          this.allProducts.push(newProductWithUpselling);
        }

        this.selectedProduct = null;
        this.filterProducts(); // Obligamos a recalcular el array visible filtrado
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