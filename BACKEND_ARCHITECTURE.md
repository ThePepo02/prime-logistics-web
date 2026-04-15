# Backend Architecture - Prime Logistics

## Descripción General

El backend de Prime Logistics ha sido refactorizado siguiendo prácticas de desarrollo profesional con Laravel 11, utilizando Eloquent ORM, servicios, y una arquitectura limpia.

## Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   └── ClientDataController.php      # Controlador principal de datos del cliente
│   └── Requests/                          # Form Requests (validación)
├── Models/                               # Modelos Eloquent
│   ├── User.php                          # Usuario (tabla: usuaris)
│   ├── Oferta.php                        # Ofertas (tabla: ofertes)
│   ├── Envio.php                         # Envíos (tabla: envios)
│   ├── EstatOferta.php                   # Estados de ofertas
│   ├── TipusTransport.php                # Tipos de transporte
│   └── TrackingStep.php                  # Pasos de tracking
├── Services/                             # Lógica de negocio
│   └── EnvioService.php                  # Servicios de envíos
└── Providers/
    └── AppServiceProvider.php

routes/
├── api.php                               # Rutas de API
└── web.php                               # Rutas de vistas

database/
├── migrations/                           # Migraciones de BD (ya creadas)
└── seeders/
    └── DatabaseSeeder.php                # Datos de prueba

resources/
└── js/
    └── components/client/                # Componentes Vue del cliente
```

## Modelos y Relaciones

### User (usuaris)
```php
- hasMany(Oferta::class)
- hasMany(Envio::class)
```

### Oferta (ofertes)
```php
- belongsTo(User::class)
- belongsTo(EstatOferta::class)
- belongsTo(TipusTransport::class)
- scopePendientes()  // Ofertas pendientes
```

### Envio (envios)
```php
- belongsTo(User::class)
- scopeEnTransito()      // En tránsito
- scopeCompletados()     // Completados
- scopeEnPreparacion()   // En preparación
```

### EstatOferta (estats_ofertes)
```php
- hasMany(Oferta::class)
- byName(string)  // Static method para obtener por nombre
```

## Endpoints API

Todos los endpoints están bajo `/api/client/`:

### Dashboard
- **GET** `/dashboard` - Datos generales del cliente

### Pedidos
- **GET** `/orders` - Lista de pedidos con filtros
- **POST** `/orders` - Crear nuevo pedido

### Tracking
- **GET** `/tracking?code=OC-000001` - Rastrear un envío

### Notificaciones
- **GET** `/notifications` - Obtener notificaciones
- **POST** `/accept-offer` - Aceptar una oferta
- **POST** `/reject-offer` - Rechazar una oferta

## Validación

Los formularios usan **Form Requests** localizados en `app/Http/Requests/`:
- `StoreOrderRequest.php`

## Servicios

La lógica de negocio se centraliza en `app/Services/`:

### EnvioService
```php
- getAllWithRelations()    // Todos los envíos con relaciones
- getByStatus(status)      // Filtrar por estado
- create(data)             // Crear nuevo envío
- getByCod(code)           // Obtener por código
- countByStatus()          // Contar por estado
```

## Seeder de Datos

Para poblar la base de datos con datos de prueba:

```bash
php artisan db:seed
```

Esto inserta:
- 3 estados de ofertas (enviada, acceptada, rebutjada)
- 4 tipos de transporte
- 9 pasos de tracking
- 1 cliente de prueba: Maria Garcia Lopez
- 2 ofertas de prueba
- 3 envíos de prueba

## Configuración

### .env
Asegúrate de tener configurado:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prime_logistics
DB_USERNAME=root
DB_PASSWORD=
```

### Caché
Algunas consultas se pueden cachear:
```php
$ofertes = cache()->remember('ofertes', 3600, function () {
    return Oferta::with(['client', 'estatOferta'])->get();
});
```

## Métodos Helper del Controlador

### normalizeShipmentStatus()
Convierte estados de envío a formato estándar:
- 'entreg*' → 'COMPLETADA'
- 'tr*' → 'EN TRANSITO'
- 'prepar*' → 'EN PREPARACION'

### normalizeTransportMode()
Normaliza tipos de transporte:
- 'mar', 'maritimo' → 'Marítimo'
- 'aire', 'aereo' → 'Aéreo'
- 'tierra', 'carretera' → 'Carretera'
- 'tren', 'ferrocarril' → 'Ferrocarril'

### getCurrentStepOrder()
Retorna el orden del paso actual en el tracking

### iconForStep()
Retorna emojis para cada paso del tracking

## Mejoras Realizadas

✅ Migraciones de BD creadas correctamente
✅ Modelos con Eloquent ORM
✅ Relaciones entre modelos
✅ Controlador refactorizado con Eloquent
✅ Separación de responsabilidades (Services)
✅ Validación con Form Requests
✅ Seeders con datos de prueba
✅ Rutas organizadas en api.php
✅ Métodos helper/privados para lógica reutilizable
✅ Documentación inline con docblocks

---

**Desarrollado**: Abril 2026  
**Versión Laravel**: 11.x  
**PHP**: 8.2+
