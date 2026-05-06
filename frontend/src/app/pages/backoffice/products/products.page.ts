import { Component, inject, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { IonContent, IonList, IonItem, IonLabel, IonBadge, IonButton, IonButtons, IonIcon, IonModal, IonText, IonHeader, IonToolbar, IonTitle } from '@ionic/angular/standalone';
import { ProductsApiService } from '../../../services/products/products-api.service';
import { Product } from '../../../services/products/product.model';
import { ProductFormComponent } from './product-form/product-form.component';
import { addIcons } from 'ionicons';
import { addOutline, pencilOutline, trashOutline, shuffleOutline } from 'ionicons/icons';

addIcons({ addOutline, pencilOutline, trashOutline, shuffleOutline });

@Component({
  selector: 'app-products',
  templateUrl: 'products.page.html',
  styleUrls: ['products.page.scss'],
  imports: [CommonModule, ReactiveFormsModule, IonContent, IonList, IonItem, IonLabel, IonBadge, IonButton, IonButtons, IonIcon, IonModal, IonText, IonHeader, IonToolbar, IonTitle, ProductFormComponent],
  standalone: true,
})
export class ProductsPage implements OnInit {
  private productsApiService = inject(ProductsApiService);

  products: Product[] = [];
  selectedProduct: Product | null = null;
  isModalOpen = false;
  isDeleteAlertOpen = false;
  deleteTarget: Product | null = null;
  errorMessage = '';
  isLoading = false;

  ngOnInit(): void {
    this.loadProducts();
  }

  private loadProducts(): void {
    this.isLoading = true;
    this.errorMessage = '';

    this.productsApiService.getAll().subscribe({
      next: (products) => {
        this.products = products;
        this.isLoading = false;
      },
      error: (error) => {
        this.errorMessage = error.message || 'Error cargando productos';
        this.isLoading = false;
      },
    });
  }

  openCreateModal(): void {
    this.selectedProduct = null;
    this.isModalOpen = true;
  }

  openEditModal(product: Product): void {
    this.selectedProduct = product;
    this.isModalOpen = true;
  }

  closeModal(): void {
    this.isModalOpen = false;
    this.selectedProduct = null;
  }

  onSaveProduct(data: { family_uuid: string; name: string; description?: string; price: number; active: boolean }): void {
    this.errorMessage = '';

    if (this.selectedProduct) {
      this.productsApiService.update(this.selectedProduct.uuid, data).subscribe({
        next: (updatedProduct) => {
          this.products = this.products.map((product) =>
            product.uuid === updatedProduct.uuid ? updatedProduct : product
          );
          this.closeModal();
        },
        error: (error) => {
          this.errorMessage = error.message || 'Error al actualizar el producto';
        },
      });
    } else {
      this.productsApiService.create(data).subscribe({
        next: (createdProduct) => {
          this.products = [createdProduct, ...this.products];
          this.closeModal();
        },
        error: (error) => {
          this.errorMessage = error.message || 'Error al crear el producto';
        },
      });
    }
  }

  confirmDelete(product: Product): void {
    this.deleteTarget = product;
    this.isDeleteAlertOpen = true;
  }

  closeDeleteAlert(): void {
    this.isDeleteAlertOpen = false;
    this.deleteTarget = null;
  }

  deleteProduct(): void {
    if (!this.deleteTarget) {
      return;
    }

    const uuid = this.deleteTarget.uuid;
    this.productsApiService.delete(uuid).subscribe({
      next: () => {
        this.products = this.products.filter((product) => product.uuid !== uuid);
        this.closeDeleteAlert();
      },
      error: (error) => {
        this.errorMessage = error.message || 'Error al eliminar el producto';
        this.closeDeleteAlert();
      },
    });
  }

  toggleActive(product: Product): void {
    this.errorMessage = '';

    this.productsApiService.toggleActive(product.uuid).subscribe({
      next: (updatedProduct) => {
        this.products = this.products.map((item) =>
          item.uuid === updatedProduct.uuid ? updatedProduct : item
        );
      },
      error: (error) => {
        this.errorMessage = error.message || 'Error al cambiar el estado';
      },
    });
  }

  get deleteAlertButtons() {
    return [
      {
        text: 'Cancelar',
        role: 'cancel',
        handler: () => this.closeDeleteAlert(),
      },
      {
        text: 'Eliminar',
        role: 'confirm',
        handler: () => this.deleteProduct(),
      },
    ];
  }
}