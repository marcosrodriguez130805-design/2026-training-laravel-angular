# Modelo de datos

Este documento describe el esquema de base de datos del sistema TPV. Puedes usarlo como referencia durante el desarrollo.

> Los importes monetarios se almacenan como enteros en céntimos para evitar problemas de precisión con decimales.
> Ejemplo: 10,50 € → `1050`

El sistema utiliza **Soft Deletes** en todas las entidades: los registros eliminados no se borran físicamente, sino que se marcan con `deleted_at`.

---

## Entidades

### `users`
Usuarios que operan el TPV.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT | Identificador interno |
| `uuid` | VARCHAR | Identificador público único |
| `role` | VARCHAR | Rol del usuario |
| `image_src` | VARCHAR | Ruta de imagen de perfil |
| `name` | VARCHAR | Nombre |
| `email` | VARCHAR | Email |
| `password` | VARCHAR | Contraseña |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | Soft delete |

---

### `families`
Categorías para organizar el catálogo de productos.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT | Identificador interno |
| `uuid` | VARCHAR | Identificador público único |
| `name` | VARCHAR | Nombre de la familia |
| `active` | BOOLEAN | Si está activa |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | Soft delete |

---

### `taxes`
Impuestos aplicables a los productos (p. ej. IVA).

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT | Identificador interno |
| `uuid` | VARCHAR | Identificador público único |
| `name` | VARCHAR | Nombre (ej. "IVA General") |
| `percentage` | INT | Porcentaje (ej. `21` para el 21%) |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | Soft delete |

---

### `products`
Catálogo de productos disponibles en el TPV.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT | Identificador interno |
| `uuid` | VARCHAR | Identificador público único |
| `family_id` | BIGINT | FK → `families` |
| `tax_id` | BIGINT | FK → `taxes` |
| `image_src` | VARCHAR | Ruta de imagen del producto |
| `name` | VARCHAR | Nombre |
| `price` | INT | Precio en céntimos |
| `stock` | INT | Stock disponible |
| `active` | BOOLEAN | Si está disponible para la venta |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | Soft delete |

---

### `zones`
Zonas o salones del establecimiento.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT | Identificador interno |
| `uuid` | VARCHAR | Identificador público único |
| `name` | VARCHAR | Nombre de la zona |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | Soft delete |

---

### `tables`
Mesas dentro de cada zona.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT | Identificador interno |
| `uuid` | VARCHAR | Identificador público único |
| `zone_id` | BIGINT | FK → `zones` |
| `name` | VARCHAR | Nombre o número de mesa |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | Soft delete |

---

### `sales`
Ticket o venta en el TPV.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT | Identificador interno |
| `uuid` | VARCHAR | Identificador público único |
| `ticket_number` | INT | Número visible en el ticket (se asigna al cerrar) |
| `status` | VARCHAR | Estado de la venta (`open`, `closed`) |
| `table_id` | BIGINT | FK → `tables` |
| `opened_by_user_id` | BIGINT | FK → `users` |
| `closed_by_user_id` | BIGINT | FK → `users` (nullable) |
| `diners` | INT | Número de comensales |
| `opened_at` | TIMESTAMP | Fecha de apertura |
| `closed_at` | TIMESTAMP | Fecha de cierre (nullable) |
| `total` | INT | Total en céntimos (calculado al cerrar) |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | Soft delete |

> `ticket_number` se asigna automáticamente en el momento del cierre, no al crear la venta.

---

### `sales_lines`
Líneas de producto dentro de cada venta.

El precio y el impuesto se guardan en el momento de la venta para preservar el histórico, aunque el producto cambie de precio posteriormente.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT | Identificador interno |
| `uuid` | VARCHAR | Identificador público único |
| `sale_id` | BIGINT | FK → `sales` |
| `product_id` | BIGINT | FK → `products` |
| `user_id` | BIGINT | FK → `users` |
| `quantity` | INT | Cantidad |
| `price` | INT | Precio en céntimos en el momento de la venta |
| `tax_percentage` | INT | Porcentaje de impuesto aplicado |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP | Soft delete |

---

## Relaciones

```
families  ──< products
taxes     ──< products
zones     ──< tables
tables    ──< sales
sales     ──< sales_lines
products  ──< sales_lines
users     ──< sales (opened_by / closed_by)
users     ──< sales_lines
```

---

## Reglas de negocio

- Solo se pueden vender productos con `active = true`.
- Una venta debe tener al menos una línea para poder cerrarse.
- El `total` de la venta se calcula sumando todas las líneas:
  ```
  línea = price × quantity
  total línea con impuesto = línea + (línea × tax_percentage / 100)
  total venta = suma de totales con impuesto de todas las líneas
  ```
- El `ticket_number` solo se asigna al cerrar la venta.
- Se debe registrar qué usuario abre y qué usuario cierra cada venta.
- Cada línea registra el usuario que la añadió.
- Los registros eliminados se gestionan con Soft Deletes (`deleted_at`).
