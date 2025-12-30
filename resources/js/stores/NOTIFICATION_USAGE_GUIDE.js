/**
 * Guía de Uso: Sistema de Notificaciones con Notyf + Pinia
 * 
 * Este archivo documenta cómo usar el sistema de notificaciones
 * en tus componentes Vue 3.
 */

// ===== PASO 1: Importar el store =====
import { useNotificationStore } from '@/stores/notificationStore';

// ===== PASO 2: En tu componente Vue 3 (script setup) =====
/*
<script setup>
import { useNotificationStore } from '@/stores/notificationStore';

const notificationStore = useNotificationStore();
</script>
*/

// ===== PASO 3: Usar las notificaciones =====

// Opción A: Notificación de éxito
notificationStore.success('Usuario actualizado exitosamente');

// Opción B: Notificación de error
notificationStore.error('Error al actualizar el usuario');

// Opción C: Notificación personalizada (genérica)
notificationStore.notify('Acción completada', 'success');
notificationStore.notify('Algo salió mal', 'error');

// ===== EJEMPLO PRÁCTICO COMPLETO =====

/**
 * <script setup>
 * import { ref } from 'vue';
 * import { useNotificationStore } from '@/stores/notificationStore';
 * import axios from 'axios';
 * 
 * const notificationStore = useNotificationStore();
 * const loading = ref(false);
 * 
 * const deleteUser = async (userId) => {
 *   if (!confirm('¿Estás seguro?')) return;
 *   
 *   loading.value = true;
 *   try {
 *     await axios.delete(route('users.destroy', userId));
 *     notificationStore.success('Usuario eliminado exitosamente');
 *     // Recargar tabla, etc.
 *   } catch (error) {
 *     notificationStore.error('Error al eliminar el usuario');
 *     console.error(error);
 *   } finally {
 *     loading.value = false;
 *   }
 * };
 * </script>
 * 
 * <template>
 *   <button 
 *     @click="deleteUser(user.id)"
 *     :disabled="loading"
 *     class="px-4 py-2 bg-red-600 text-white rounded">
 *     Eliminar
 *   </button>
 * </template>
 */

// ===== OPCIONES DE CONFIGURACIÓN (notificationStore.js) =====
// Puedes modificar estos valores:
// - duration: 3000 (milisegundos)
// - position: { x: 'right', y: 'top' }
// - ripple: true (efecto visual)

// Posiciones disponibles:
// x: 'left', 'center', 'right'
// y: 'top', 'bottom'
