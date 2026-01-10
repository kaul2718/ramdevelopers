# Sistema de Captadores - Guía de Implementación

## 📋 Overview

El sistema de **captadores** permite asignar usuarios (agentes/vendedores) a un proyecto inmobiliario (Development). Un usuario puede ser:
- **Captador secundario**: Participa en la venta del proyecto
- **Captador principal**: Es el responsable principal del proyecto

## 🏗️ Estructura

### Base de Datos
```
developments_captors
├── devt_id (FK) → developments.devt_id
├── user_id (FK) → users.id
├── devtUsr_is_main (boolean) - Indica si es el captador principal
└── timestamps
```

### Modelos

#### Development
```php
// Relación: Obtener todos los captadores de un desarrollo
$development->captors() // BelongsToMany
$development->developmentCaptors() // HasMany (relación directa)

// Uso:
$captors = $development->captors()->get();
$mainCaptor = $development->captors()->where('devtUsr_is_main', true)->first();
```

#### User
```php
// Relación: Obtener todos los developments capturados por un usuario
$user->capturedDevelopments() // BelongsToMany
$user->developmentCaptors() // HasMany (relación directa)
$user->mainCapturedDevelopments() // Developments donde es captador principal

// Uso:
$developments = $user->capturedDevelopments()->get();
$mainDevelopments = $user->mainCapturedDevelopments()->get();
```

#### DevelopmentCaptor
```php
// Métodos útiles
DevelopmentCaptor::byDevelopment($devtId)->get(); // Scope
DevelopmentCaptor::byUser($userId)->get(); // Scope
DevelopmentCaptor::main()->get(); // Scope - Solo principales

DevelopmentCaptor::getMainCaptor($devtId); // Obtener captador principal
DevelopmentCaptor::isUserCaptor($devtId, $userId); // Verificar si es captador
DevelopmentCaptor::getCaptorsByDevelopment($devtId); // Todos los captadores
DevelopmentCaptor::getDevelopmentsByUser($userId); // Todos los developments
DevelopmentCaptor::countCaptors($devtId); // Contar captadores

$captor->setAsMain(); // Establecer como principal
```

## 🚀 Rutas Disponibles

Todas las rutas están registradas en `web.php` bajo la ruta autenticada:

```php
// Listar captadores de un desarrollo (Vista Inertia)
GET /development/{development}/captors
→ DevelopmentCaptorController@index
→ Renderiza: Development/Captors/Index.vue

// Agregar un captador (AJAX)
POST /development/{development}/captors
→ DevelopmentCaptorController@store
→ Respuesta: JSON

// Mostrar un captador específico
GET /development/{development}/captors/{userId}
→ DevelopmentCaptorController@show

// Actualizar un captador
PUT /development/{development}/captors/{userId}
→ DevelopmentCaptorController@update

// Remover un captador
DELETE /development/{development}/captors/{userId}
→ DevelopmentCaptorController@destroy

// Establecer como captador principal
PATCH /development/{development}/captors/{userId}/set-main
→ DevelopmentCaptorController@setMain

// Obtener usuarios disponibles (AJAX)
GET /development/{development}/captors-available-list
→ DevelopmentCaptorController@getAvailableUsers
→ Respuesta: JSON con usuarios sin asignar
```

## 🎨 Frontend - Componentes Vue

### Vista Principal: `Development/Captors/Index.vue`
Ubicación: `resources/views/Development/Captors/Index.vue`

**Características:**
- Listar todos los captadores del desarrollo
- Agregar nuevos captadores desde un dropdown
- Marcar/desmarcar como captador principal
- Remover captadores
- Validación de usuarios duplicados

**Uso:**
```vue
<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Props que recibe:
// - development: Objeto del desarrollo
// - captors: Array de captadores actuales
// - availableUsers: Array de usuarios sin asignar
</script>
```

### Componente Card: `Development/CaptorsCard.vue`
Ubicación: `resources/js/Components/Development/CaptorsCard.vue`

**Características:**
- Mostrar un resumen de captadores
- Botón para ir a la gestión completa
- Marcar al captador principal con badge azul

**Uso en la vista Show:**
```vue
<template>
  <CaptorsCard :development="development" :captors="captors" />
</template>

<script setup>
import CaptorsCard from '@/Components/Development/CaptorsCard.vue'
</script>
```

## 📤 Ejemplos de Uso

### 1. Obtener captadores de un desarrollo (Backend)
```php
// En un controlador
$development = Development::find($devtId);
$captors = $development->captors()->with('user')->get();

// O usando el método del modelo
$captors = \App\Models\DevelopmentCaptor::getCaptorsByDevelopment($devtId);
```

### 2. Asignar un usuario como captador (Backend)
```php
\App\Models\DevelopmentCaptor::create([
    'devt_id' => $developmentId,
    'user_id' => $userId,
    'devtUsr_is_main' => false,
]);
```

### 3. Establecer como principal (Backend)
```php
$captor = \App\Models\DevelopmentCaptor::where('devt_id', $devtId)
    ->where('user_id', $userId)
    ->first();

$captor->setAsMain();
```

### 4. Obtener developments de un usuario (Backend)
```php
$user = User::find($userId);
$developments = $user->capturedDevelopments()->get();
$mainDevelopments = $user->mainCapturedDevelopments()->get();
```

### 5. Desde el Frontend (Vue)
```vue
<script setup>
import { ref } from 'vue'

const addCaptor = async () => {
  const response = await fetch(`/development/${developmentId}/captors`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
    body: JSON.stringify({
      user_id: selectedUserId,
      devtUsr_is_main: isMain,
    }),
  })
  
  if (response.ok) {
    // Recargar o actualizar la lista
    window.location.reload()
  }
}

const removeCaptor = async (userId) => {
  await fetch(`/development/${developmentId}/captors/${userId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
  })
  
  window.location.reload()
}

const setMain = async (userId) => {
  await fetch(`/development/${developmentId}/captors/${userId}/set-main`, {
    method: 'PATCH',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
  })
  
  window.location.reload()
}
</script>
```

## 🔐 Permisos

Los permisos ya están definidos en `RoleSeeder.php`:
- `create development captors`
- `read development captors`
- `update development captors`
- `delete development captors`

Están asignados a:
- **Admin**
- **Manager**
- **Agent** (solo lectura)

## 📊 Casos de Uso en CRM Inmobiliario

### 1. Dashboard de Agente
```php
// Mostrar todos los developments que el agente vende
$agentDevelopments = Auth::user()->capturedDevelopments()->get();
$mainResponsibilities = Auth::user()->mainCapturedDevelopments()->get();
```

### 2. Reportes de Ventas
```php
// Cuántos developments vende cada agente
$agents = User::with(['capturedDevelopments' => function($q) {
    $q->count();
}])->get();
```

### 3. Auditoría y Seguimiento
```php
// Ver quién agregó cada captador y cuándo
$captor = DevelopmentCaptor::find(['devt_id', 'user_id']);
echo $captor->created_at; // Cuándo se asignó
echo $captor->updated_at; // Última actualización
```

### 4. Rotación de Captadores
```php
// Si un agente se va, reasignar sus developments
$oldAgentDevelopments = $oldAgent->developmentCaptors;
foreach ($oldAgentDevelopments as $captor) {
    $captor->update(['user_id' => $newAgent->id]);
}
```

## 🐛 Debugging

```php
// En tinker o un método
$development = Development::find(1);

// Ver captadores
dd($development->captors()->with('user')->get());

// Ver detalles completos
dd($development->developmentCaptors()->with('user')->get());

// Validar si un usuario es captador
dd(DevelopmentCaptor::isUserCaptor(1, 5)); // boolean

// Obtener captador principal
dd(DevelopmentCaptor::getMainCaptor(1)); // DevelopmentCaptor|null
```

## 🚨 Consideraciones Importantes

1. **Captador Principal**: Solo puede haber un captador principal por desarrollo (se aplica automáticamente al asignar)
2. **Usuarios Activos**: Solo usuarios con `usr_active = true` pueden asignarse
3. **Validación de Duplicados**: No puede haber dos records con mismo `devt_id` + `user_id`
4. **Cascada**: Al eliminar un desarrollo o usuario, se eliminan automáticamente los captadores (ON DELETE CASCADE)
5. **Timestamps**: Se registran automáticamente `created_at` y `updated_at`

## 📝 Próximas Mejoras Sugeridas

- [ ] Historial de cambios de captadores
- [ ] Notificaciones cuando se asignan/reasignan
- [ ] Filtros por captador en listado de developments
- [ ] Dashboard de performance por captador
- [ ] Comisiones asociadas a captadores
