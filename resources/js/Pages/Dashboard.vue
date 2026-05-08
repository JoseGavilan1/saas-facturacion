<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Recibimos las estadísticas que nos mandó la ruta
defineProps({
    estadisticas: Object,
});

// Función para formatear el dinero
const formatPrecio = (valor) => {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP'
    }).format(valor || 0);
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500 transition-transform hover:scale-105 duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Facturado</div>
                                <div class="mt-2 text-3xl font-bold text-gray-900">{{ formatPrecio(estadisticas.total_facturado) }}</div>
                            </div>
                            <div class="p-3 bg-indigo-50 rounded-full">
                                💰
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500 transition-transform hover:scale-105 duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Facturas Emitidas</div>
                                <div class="mt-2 text-3xl font-bold text-gray-900">{{ estadisticas.facturas_count }}</div>
                            </div>
                            <div class="p-3 bg-emerald-50 rounded-full">
                                📄
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-amber-500 transition-transform hover:scale-105 duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Clientes Registrados</div>
                                <div class="mt-2 text-3xl font-bold text-gray-900">{{ estadisticas.clientes_count }}</div>
                            </div>
                            <div class="p-3 bg-amber-50 rounded-full">
                                👥
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">¡Hola, {{ $page.props.auth.user.name }}! 👋</h3>
                        <p class="text-gray-600">
                            Bienvenido a tu centro de control. Desde aquí puedes monitorear el rendimiento general de tus ventas.
                            Utiliza el menú de navegación superior para administrar tu catálogo, agregar nuevos contactos y emitir facturas en segundos.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
