import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';

export type MesaEstado = 'libre' | 'ocupada' | 'cuenta';

export interface MesaProducto {
  productId: string;
  name: string;
  price: number;
  basePrice?: number;
  quantity: number;
  nota?: string;
  formato?: {
    nombre: string;
    factor: number;
  };
}

export interface Mesa {
  id: string;
  nombre: string;
  estado: MesaEstado;
  productosPedido: MesaProducto[];
}

@Injectable({
  providedIn: 'root',
})
export class MesaService {
  private mesasSubject = new BehaviorSubject<Mesa[]>([
    { id: '1', nombre: 'Mesa 1', estado: 'libre', productosPedido: [] },
    { id: '2', nombre: 'Mesa 2', estado: 'libre', productosPedido: [] },
    { id: '3', nombre: 'Mesa 3', estado: 'ocupada', productosPedido: [] },
    { id: '4', nombre: 'Mesa 4', estado: 'libre', productosPedido: [] },
    { id: '5', nombre: 'Mesa 5', estado: 'cuenta', productosPedido: [] },
    { id: '6', nombre: 'Mesa 6', estado: 'libre', productosPedido: [] },
  ]);

  private mesaActivaSubject = new BehaviorSubject<Mesa | null>(null);
  private camareroActivoSubject = new BehaviorSubject<string | null>(null);

  mesas$: Observable<Mesa[]> = this.mesasSubject.asObservable();
  mesaActiva$: Observable<Mesa | null> = this.mesaActivaSubject.asObservable();
  camareroActivo$: Observable<string | null> = this.camareroActivoSubject.asObservable();

  seleccionarMesa(id: string): void {
    const mesas = this.mesasSubject.value.map((mesa) => {
      if (mesa.id !== id) {
        return mesa;
      }

      const siguienteEstado: MesaEstado = mesa.estado === 'libre' ? 'ocupada' : mesa.estado;
      const mesaSeleccionada = {
        ...mesa,
        estado: siguienteEstado,
        productosPedido: [...mesa.productosPedido],
      };

      this.mesaActivaSubject.next(mesaSeleccionada);
      return mesaSeleccionada;
    });

    this.mesasSubject.next(mesas);
  }

  actualizarMesaActiva(productosPedido: MesaProducto[]): void {
    const mesaActiva = this.mesaActivaSubject.value;
    if (!mesaActiva) {
      return;
    }

    const mesaActualizada = {
      ...mesaActiva,
      estado: mesaActiva.estado === 'libre' ? 'ocupada' : mesaActiva.estado,
      productosPedido,
    };

    this.persistirMesa(mesaActualizada);
  }

  agregarProductoAlPedido(producto: { id: string; name: string; price: number }): void {
    const mesaActiva = this.mesaActivaSubject.value;
    if (!mesaActiva) {
      return;
    }

    const productosPedido = [...mesaActiva.productosPedido];
    const itemExistente = productosPedido.find((item) => item.productId === producto.id);

    if (itemExistente) {
      itemExistente.quantity += 1;
    } else {
      productosPedido.push({
        productId: producto.id,
        name: producto.name,
        price: producto.price,
        basePrice: producto.price,
        quantity: 1,
        formato: { nombre: 'Ración', factor: 1.0 },
      });
    }

    this.persistirMesa({
      ...mesaActiva,
      estado: mesaActiva.estado === 'libre' ? 'ocupada' : mesaActiva.estado,
      productosPedido,
    });
  }

  incrementarCantidad(productId: string): void {
    const mesaActiva = this.mesaActivaSubject.value;
    if (!mesaActiva) {
      return;
    }

    const productosPedido = mesaActiva.productosPedido.map((item) =>
      item.productId === productId ? { ...item, quantity: item.quantity + 1 } : item
    );

    this.persistirMesa({ ...mesaActiva, productosPedido });
  }

  decrementarCantidad(productId: string): void {
    const mesaActiva = this.mesaActivaSubject.value;
    if (!mesaActiva) {
      return;
    }

    const productosPedido = mesaActiva.productosPedido
      .map((item) =>
        item.productId === productId
          ? { ...item, quantity: Math.max(1, item.quantity - 1) }
          : item
      )
      .filter((item) => item.quantity > 0);

    this.persistirMesa({ ...mesaActiva, productosPedido });
  }

  eliminarProductoPedido(productId: string): void {
    const mesaActiva = this.mesaActivaSubject.value;
    if (!mesaActiva) {
      return;
    }

    const productosPedido = mesaActiva.productosPedido.filter((item) => item.productId !== productId);
    this.persistirMesa({ ...mesaActiva, productosPedido });
  }

  guardarComanda(): void {
    const mesaActiva = this.mesaActivaSubject.value;
    if (!mesaActiva) {
      return;
    }

    this.persistirMesa({ ...mesaActiva, estado: 'ocupada' });
  }

  cerrarMesaActual(): void {
    const mesaActiva = this.mesaActivaSubject.value;
    if (!mesaActiva) {
      return;
    }

    this.persistirMesa({
      ...mesaActiva,
      estado: 'libre',
      productosPedido: [],
    });
  }

  fusionarMesas(idOrigen: number, idDestino: number): void {
    const origenId = String(idOrigen);
    const destinoId = String(idDestino);
    const mesas = this.mesasSubject.value.map((mesa) => ({ ...mesa }));

    const mesaOrigen = mesas.find((mesa) => mesa.id === origenId);
    const mesaDestino = mesas.find((mesa) => mesa.id === destinoId);

    if (!mesaOrigen || !mesaDestino) {
      return;
    }

    mesaOrigen.productosPedido.forEach((productoOrigen) => {
      const productoDestino = mesaDestino.productosPedido.find(
        (producto) => producto.productId === productoOrigen.productId
      );

      if (productoDestino) {
        productoDestino.quantity += productoOrigen.quantity;
      } else {
        mesaDestino.productosPedido.push({ ...productoOrigen });
      }
    });

    mesaOrigen.productosPedido = [];
    mesaOrigen.estado = 'libre';
    mesaDestino.estado = 'ocupada';

    this.mesasSubject.next(mesas);

    if (this.mesaActivaSubject.value?.id === origenId) {
      this.mesaActivaSubject.next(mesaDestino);
    }
  }

  seleccionarCamarero(camareroId: string): void {
    this.camareroActivoSubject.next(camareroId);
  }

  resetCamarero(): void {
    this.camareroActivoSubject.next(null);
  }

  private persistirMesa(mesaActualizada: Mesa): void {
    const mesas = this.mesasSubject.value.map((mesa) =>
      mesa.id === mesaActualizada.id ? mesaActualizada : mesa
    );

    this.mesasSubject.next(mesas);
    this.mesaActivaSubject.next(mesaActualizada);
  }
}
