<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

// Recibimos los clientes y productos desde el Controlador
const props = defineProps({
    clientes: Array,
    productos: Array,
});

// Preparamos el formulario con un producto inicial vacío
const form = useForm({
    cliente_id: '',
    detalles: [
        { producto_id: '', cantidad: 1 }
    ],
});

// Funciones para agregar o quitar líneas de productos
const agregarLinea = () => {
    form.detalles.push({ producto_id: '', cantidad: 1 });
};

const quitarLinea = (index) => {
    if (form.detalles.length > 1) {
        form.detalles.splice(index, 1);
    }
};

// --- CÁLCULOS EN TIEMPO REAL ---
const subtotalFactura = computed(() => {
    return form.detalles.reduce((total, item) => {
        if (!item.producto_id) return total;
        // Buscamos el precio del producto seleccionado
        const producto = props.productos.find(p => p.id === item.producto_id);
        return total + (producto ? producto.precio * item.cantidad : 0);
    }, 0);
});

const ivaFactura = computed(() => Math.round(subtotalFactura.value * 0.19));
const totalFactura = computed(() => subtotalFactura.value + ivaFactura.value);

// Formateador de pesos chilenos
const formatPrecio = (valor) => {
    return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(valor || 0);
};

// Enviar el formulario
const submit = () => {
    form.post(route('facturas.store'));
};
</script>

<template>
    <Head title="Nueva Factura" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Emitir Nueva Factura</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <form @submit.prevent="submit" class="space-y-8">

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Datos del Cliente</h3>
                            <div class="max-w-md">
                                <label class="block text-sm font-medium text-gray-700">Seleccionar Cliente *</label>
                                <select v-model="form.cliente_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="" disabled>-- Elige un cliente --</option>
                                    <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                                        {{ cliente.nombre }} (RUT: {{ cliente.rut }})
                                    </option>
                                </select>
                                <span v-if="form.errors.cliente_id" class="text-red-500 text-xs mt-1">{{ form.errors.cliente_id }}</span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Productos / Servicios</h3>

                            <div class="space-y-4">
                                <div v-for="(detalle, index) in form.detalles" :key="index" class="flex flex-wrap md:flex-nowrap gap-4 items-end bg-white p-4 border border-gray-200 rounded-lg shadow-sm">

                                    <div class="w-full md:w-1/2">
                                        <label class="block text-sm font-medium text-gray-700">Producto *</label>
                                        <select v-model="detalle.producto_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                            <option value="" disabled>-- Selecciona --</option>
                                            <option v-for="producto in productos" :key="producto.id" :value="producto.id">
                                                {{ producto.nombre }} - {{ formatPrecio(producto.precio) }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="w-full md:w-1/4">
                                        <label class="block text-sm font-medium text-gray-700">Cantidad *</label>
                                        <input v-model="detalle.cantidad" type="number" min="1" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    </div>

                                    <div class="w-full md:w-1/4 text-right">
                                        <span class="block text-sm font-medium text-gray-500 mb-2">Subtotal</span>
                                        <span class="font-semibold text-gray-900">
                                            {{ formatPrecio(detalle.producto_id ? productos.find(p => p.id === detalle.producto_id)?.precio * detalle.cantidad : 0) }}
                                        </span>
                                    </div>

                                    <div class="w-full md:w-auto text-right">
                                        <button v-if="form.detalles.length > 1" type="button" @click="quitarLinea(index)" class="text-red-500 hover:text-red-700 font-medium text-sm mt-2 md:mt-0">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" @click="agregarLinea" class="mt-4 text-indigo-600 hover:text-indigo-900 font-medium text-sm flex items-center">
                                + Agregar otro producto
                            </button>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-200">
                            <div class="w-full md:w-1/3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Subtotal Neto:</span>
                                    <span class="font-medium text-gray-900">{{ formatPrecio(subtotalFactura) }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">IVA (19%):</span>
                                    <span class="font-medium text-gray-900">{{ formatPrecio(ivaFactura) }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-300 pt-2 mt-2">
                                    <span class="text-lg font-bold text-gray-900">Total a Cobrar:</span>
                                    <span class="text-lg font-bold text-indigo-600">{{ formatPrecio(totalFactura) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" :disabled="form.processing || form.detalles.some(d => !d.producto_id)" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg shadow transition-colors disabled:opacity-50 text-lg">
                                Emitir Factura
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
