<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Recibimos las facturas desde el Controlador
defineProps({
    facturas: Array,
});

// Función para formatear dinero a pesos chilenos
const formatPrecio = (valor) => {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
    }).format(valor);
};

// Función para formatear la fecha a formato local
const formatFecha = (fecha) => {
    return new Intl.DateTimeFormat('es-CL').format(new Date(fecha));
};

// Función para confirmar la eliminación
const confirmDelete = (e) => {
    if (!window.confirm('¿Estás seguro de eliminar esta factura? Esta acción no se puede deshacer.')) {
        e.preventDefault(); // Si el usuario cancela, detenemos la petición
    }
};
</script>

<template>

    <Head title="Facturas" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Facturas</h2>

                <Link :href="route('facturas.create')"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded transition-colors">
                    + Nueva Factura
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Folio</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cliente</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="factura in facturas" :key="factura.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{
                                            factura.numero ||
                                            'Borrador' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{
                                            formatFecha(factura.fecha_emision) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ factura.cliente.nombre
                                        }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ factura.estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{
                                            formatPrecio(factura.total) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a :href="route('facturas.download', factura.id)"
                                                class="text-indigo-600 hover:text-indigo-900 font-bold">
                                                📥 Descargar PDF
                                            </a>
                                            <Link :href="route('facturas.destroy', factura.id)" method="delete"
                                                as="button" class="text-red-600 hover:text-red-900 font-bold ml-4"
                                                @before="confirmDelete">
                                                Eliminar
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="facturas.length === 0">
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            Aún no has emitido ninguna factura. ¡Haz clic en el botón verde de arriba
                                            para
                                            empezar!
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
