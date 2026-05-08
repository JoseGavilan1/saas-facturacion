<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

// Recibimos los productos desde el Controlador
defineProps({
    productos: Array,
});

// Preparamos el formulario
const form = useForm({
    nombre: '',
    codigo: '',
    descripcion: '',
    precio: '',
});

// Función para enviar los datos
const submit = () => {
    form.post(route('productos.store'), {
        onSuccess: () => form.reset(),
    });
};

// Función para formatear el precio a pesos chilenos
const formatPrecio = (valor) => {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
    }).format(valor);
};
</script>

<template>
    <Head title="Productos y Servicios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Productos y Servicios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-8">

                <div v-if="$page.props.errors.producto_relacion" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error de seguridad</p>
                    <p>{{ $page.props.errors.producto_relacion }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Agregar Nuevo Producto/Servicio</h3>

                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Producto/Servicio *</label>
                            <input v-model="form.nombre" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <span v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código / SKU (Opcional)</label>
                            <input v-model="form.codigo" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descripción (Opcional)</label>
                            <textarea v-model="form.descripcion" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio Neto (CLP) *</label>
                            <input v-model="form.precio" type="number" min="0" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <span v-if="form.errors.precio" class="text-red-500 text-xs mt-1">{{ form.errors.precio }}</span>
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-4">
                            <button type="submit" :disabled="form.processing" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition-colors disabled:opacity-50">
                                Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Catálogo</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="producto in productos" :key="producto.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ producto.codigo || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ producto.nombre }}</td>
                                        <td class="px-6 py-4 text-gray-500 text-sm truncate max-w-xs">{{ producto.descripcion || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">{{ formatPrecio(producto.precio) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <Link :href="route('productos.edit', producto.id)" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                                Editar
                                            </Link>
                                            <Link :href="route('productos.destroy', producto.id)" method="delete" as="button" class="text-red-600 hover:text-red-900 font-bold">
                                                Eliminar
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="productos.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Aún no tienes productos registrados.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
