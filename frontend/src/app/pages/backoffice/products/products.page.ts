import { Component, OnInit, inject } from '@angular/core';
import { forkJoin } from 'rxjs';
import { Product } from '../../../services/products/product.model';
import { ProductsApiService } from '../../../services/products/products-api.service';
import { FamiliesApiService, Family } from '../../../services/families/families-api.service';

// Definimos la interfaz para la estructura agrupada
interface GroupedProducts {
  familyName: string;
  products: Product[];
}

@Component({
  selector: 'app-product-page',
  templateUrl: './product.page.html',
})
export class ProductPage implements OnInit {
  private productsApiService = inject(ProductsApiService);
  private familiesApiService = inject(FamiliesApiService);

  // Esta es la lista que usaremos en el HTML
  groupedProducts: GroupedProducts[] = [];
  
  // Guardamos las familias para poder buscar sus nombres por UUID
  families: Family[] = [];

  ngOnInit() {
    this.loadData();
  }

  loadData() {
    // Cargamos productos y familias a la vez
    forkJoin({
      products: this.productsApiService.getAll(),
      families: this.familiesApiService.getAll()
    }).subscribe({
      next: ({ products, families }) => {
        this.families = families;
        this.organizeByFamily(products);
      },
      error: (err) => console.error('Error cargando datos:', err)
    });
  }

  private organizeByFamily(products: Product[]) {
    const groups = products.reduce((acc, product) => {
      // Buscamos el nombre de la familia usando el UUID del producto
      const family = this.families.find(f => f.uuid === product.family_uuid);
      const categoryName = family ? family.name : 'Sin Familia';

      if (!acc[categoryName]) {
        acc[categoryName] = [];
      }
      acc[categoryName].push(product);
      return acc;
    }, {} as { [key: string]: Product[] });

    // Convertimos el objeto en un array ordenado para el *ngFor
    this.groupedProducts = Object.keys(groups).map(name => ({
      familyName: name,
      products: groups[name]
    })).sort((a, b) => a.familyName.localeCompare(b.familyName));
  }
}