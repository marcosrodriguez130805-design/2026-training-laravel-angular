import { Component, inject, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import {
  IonContent,
  IonGrid,
  IonRow,
  IonCol,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardContent,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonButton,
  IonIcon,
  IonBadge,
  IonList,
  IonItem,
  IonLabel,
  IonButtons,
  IonText,
  IonSelect,
  IonSelectOption,
  AlertController,
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { add, addCircleOutline, removeCircleOutline, closeOutline, documentTextOutline, arrowBack, cart, cash } from 'ionicons/icons';
import { MesaService, MesaProducto, Mesa } from '../../services/mesa/mesa.service';

interface TpvCategoria {
  id: string;
  nombre: string;
}

interface TpvProducto {
  id: string;
  categoryId: string;
  name: string;
  price: number;
  color: string;
}

@Component({
  selector: 'app-tpv-comanda-page',
  templateUrl: './tpv-comanda.page.html',
  standalone: true,
  imports: [
    CommonModule,
    IonContent,
    IonGrid,
    IonRow,
    IonCol,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardContent,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonButton,
    IonIcon,
    IonBadge,
    IonList,
    IonItem,
    IonLabel,
    IonButtons,
    IonText,
    IonSelect,
    IonSelectOption,
  ],
})
export class TpvComandaPage {
  private router = inject(Router);
  private mesaService = inject(MesaService);
  private alertController = inject(AlertController);

  mesaActiva: Mesa | null = null;
  selectedCategory = 'all';

  formatos = [
    { nombre: 'Ración', factor: 1.0 },
    { nombre: 'Media', factor: 0.6 },
    { nombre: 'Tapa', factor: 0.4 },
  ];

  categorias: TpvCategoria[] = [
    { id: 'all', nombre: 'Todas' },
    { id: 'burgers', nombre: 'Hamburguesas' },
    { id: 'drinks', nombre: 'Bebidas' },
    { id: 'desserts', nombre: 'Postres' },
    { id: 'salads', nombre: 'Ensaladas' },
  ];

  productos: TpvProducto[] = [
    { id: 'p1', categoryId: 'burgers', name: 'Burger Clásica', price: 12.9, color: '#fde68a' },
    { id: 'p2', categoryId: 'burgers', name: 'Burger BBQ', price: 13.5, color: '#fbcfe8' },
    { id: 'p3', categoryId: 'drinks', name: 'Cerveza Artesanal', price: 4.8, color: '#bfdbfe' },
    { id: 'p4', categoryId: 'drinks', name: 'Copa de Vino', price: 5.7, color: '#fcd34d' },
    { id: 'p5', categoryId: 'desserts', name: 'Tarta de Queso', price: 6.2, color: '#d9f99d' },
    { id: 'p6', categoryId: 'salads', name: 'Ensalada César', price: 10.7, color: '#a7f3d0' },
  ];

  constructor() {
    addIcons({ addCircleOutline, removeCircleOutline, closeOutline, documentTextOutline, arrowBack, cart, cash });
  }

  ngOnInit(): void {
    this.mesaService.mesaActiva$.subscribe((mesa) => {
      this.mesaActiva = mesa;
      if (!mesa) {
        this.router.navigate(['/tpv-mesas']);
      }
    });
  }

  get productosFiltrados(): TpvProducto[] {
    return this.selectedCategory === 'all'
      ? this.productos
      : this.productos.filter((product) => product.categoryId === this.selectedCategory);
  }

  selectCategory(categoryId: string): void {
    this.selectedCategory = categoryId;
  }

  addProduct(product: TpvProducto): void {
    this.mesaService.agregarProductoAlPedido({
      id: product.id,
      name: product.name,
      price: product.price,
    });
  }

  increaseQuantity(item: MesaProducto): void {
    if (!this.mesaActiva) {
      return;
    }

    const productosPedido = this.mesaActiva.productosPedido.map((producto) =>
      producto.productId === item.productId
        ? { ...producto, quantity: producto.quantity + 1 }
        : producto
    );

    this.mesaService.actualizarMesaActiva(productosPedido);
  }

  decreaseQuantity(item: MesaProducto): void {
    if (!this.mesaActiva) {
      return;
    }

    if (item.quantity <= 1) {
      this.eliminarProductoDelTicket(item);
      return;
    }

    const productosPedido = this.mesaActiva.productosPedido.map((producto) =>
      producto.productId === item.productId
        ? { ...producto, quantity: producto.quantity - 1 }
        : producto
    );

    this.mesaService.actualizarMesaActiva(productosPedido);
  }

  removeItem(item: MesaProducto): void {
    this.mesaService.eliminarProductoPedido(item.productId);
  }

  eliminarProductoDelTicket(item: MesaProducto): void {
    if (!this.mesaActiva) {
      return;
    }

    const productosPedido = this.mesaActiva.productosPedido.filter(
      (producto) => producto.productId !== item.productId
    );

    this.mesaService.actualizarMesaActiva(productosPedido);
  }

  async agregarNota(item: MesaProducto): Promise<void> {
    const alert = await this.alertController.create({
      header: 'Agregar nota',
      inputs: [
        {
          name: 'nota',
          type: 'text',
          value: item.nota || '',
          placeholder: 'Ej. Muy hecho, sin cebolla',
        },
      ],
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
        },
        {
          text: 'Guardar',
          handler: (form) => {
            const nota = form.nota?.trim();
            const productosPedido = this.mesaActiva?.productosPedido.map((producto) =>
              producto.productId === item.productId ? { ...producto, nota } : producto
            );

            if (productosPedido && this.mesaActiva) {
              this.mesaService.actualizarMesaActiva(productosPedido);
            }
          },
        },
      ],
    });

    await alert.present();
  }

  cambiarFormato(item: MesaProducto, nuevoFormato: { nombre: string; factor: number }): void {
    if (!this.mesaActiva) {
      return;
    }

    const basePrice = item.basePrice ?? item.price;
    const formato = this.formatos.find((f) => f.nombre === nuevoFormato.nombre) ?? nuevoFormato;
    const precioFinal = basePrice * formato.factor;

    const productosPedido = this.mesaActiva.productosPedido.map((producto) =>
      producto.productId === item.productId
        ? { ...producto, formato, price: Number(precioFinal.toFixed(2)), basePrice }
        : producto
    );

    this.mesaService.actualizarMesaActiva(productosPedido);
  }

  guardarYSalir(): void {
    this.mesaService.guardarComanda();
    this.router.navigate(['/tpv-mesas']);
  }

  cobrarYCerrar(): void {
    this.mesaService.cerrarMesaActual();
    window.alert('Pago registrado. La mesa ha sido liberada.');
    this.router.navigate(['/tpv-mesas']);
  }

  get ticketTotal(): number {
    return this.mesaActiva?.productosPedido.reduce((sum, item) => sum + item.price * item.quantity, 0) ?? 0;
  }
}
