import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import {
  IonContent,
  IonGrid,
  IonRow,
  IonCol,
  IonButton,
  IonIcon,
  IonBadge,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonButtons,
  IonLabel,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardContent
} from '@ionic/angular/standalone';
import { addIcons } from 'ionicons';
import { add, remove, close, cash, cart } from 'ionicons/icons';

interface TpvFamily {
  id: string;
  name: string;
  active: boolean;
}

interface TpvProduct {
  id: string;
  name: string;
  price: number;
  color: string;
  categoryId: string;
}

interface TicketItem {
  productId: string;
  name: string;
  price: number;
  quantity: number;
}

@Component({
  selector: 'app-tpv-page',
  templateUrl: './tpv.page.html',
  standalone: true,
  imports: [
    CommonModule,
    IonContent,
    IonGrid,
    IonRow,
    IonCol,
    IonButton,
    IonIcon,
    IonBadge,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonButtons,
    IonLabel,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardContent
  ]
})
export class TpvPage {
  families: TpvFamily[] = [
    { id: 'all', name: 'Todas', active: true },
    { id: 'burgers', name: 'Hamburguesas', active: false },
    { id: 'drinks', name: 'Bebidas', active: false },
    { id: 'desserts', name: 'Postres', active: false },
    { id: 'salads', name: 'Ensaladas', active: false }
  ];

  products: TpvProduct[] = [
    { id: 'p1', name: 'Burger Clásica', price: 12.9, color: '#fde68a', categoryId: 'burgers' },
    { id: 'p2', name: 'Combo Veggie', price: 14.5, color: '#bbf7d0', categoryId: 'burgers' },
    { id: 'p3', name: 'Cerveza Artesana', price: 4.8, color: '#bfdbfe', categoryId: 'drinks' },
    { id: 'p4', name: 'Limonada Rosa', price: 3.9, color: '#fecdd3', categoryId: 'drinks' },
    { id: 'p5', name: 'Tarta de Queso', price: 6.2, color: '#fbcfe8', categoryId: 'desserts' },
    { id: 'p6', name: 'Ensalada César', price: 10.7, color: '#d9f99d', categoryId: 'salads' }
  ];

  selectedFamilyId = 'all';
  currentTable = '';
  ticketItems: TicketItem[] = [];

  constructor() {
    addIcons({ add, remove, close, cash, cart });
  }

  get filteredProducts(): TpvProduct[] {
    return this.selectedFamilyId === 'all'
      ? this.products
      : this.products.filter(product => product.categoryId === this.selectedFamilyId);
  }

  selectFamily(familyId: string) {
    this.selectedFamilyId = familyId;
    this.families = this.families.map(item => ({
      ...item,
      active: item.id === familyId
    }));
  }

  addProduct(product: TpvProduct) {
    const existing = this.ticketItems.find(item => item.productId === product.id);
    if (existing) {
      existing.quantity += 1;
      return;
    }

    this.ticketItems = [
      ...this.ticketItems,
      { productId: product.id, name: product.name, price: product.price, quantity: 1 }
    ];
  }

  increaseQuantity(item: TicketItem) {
    item.quantity += 1;
  }

  decreaseQuantity(item: TicketItem) {
    item.quantity = Math.max(1, item.quantity - 1);
  }

  removeItem(item: TicketItem) {
    this.ticketItems = this.ticketItems.filter(ticket => ticket.productId !== item.productId);
  }

  get ticketTotal(): number {
    return this.ticketItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
  }

  checkout() {
    console.log('Cobrar ticket', this.ticketItems, this.ticketTotal);
  }
}
