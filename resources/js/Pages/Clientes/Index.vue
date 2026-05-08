<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';


// Recibimos los clientes que nos mandó el Controlador
defineProps({
    clientes: Array,
});

// Preparamos el formulario
const form = useForm({
    nombre: '',
    rut: '',
    email: '',
    telefono: '',
    direccion: '',
    giro: '',
});

const formatRut = (value) => {
    // Quitamos todo lo que no sea número o la letra K
    let cleanValue = value.replace(/[^0-9kK]/g, '').toUpperCase();
    if (!cleanValue) return '';

    // Si solo hay un número, lo devolvemos tal cual
    if (cleanValue.length <= 1) return cleanValue;

    // Separamos el cuerpo del dígito verificador
    let body = cleanValue.slice(0, -1);
    let dv = cleanValue.slice(-1);

    // Le agregamos los puntos al cuerpo cada 3 números
    body = body.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    // Retornamos el RUT armado con su guion
    return `${body}-${dv}`;
};

// Función que se ejecuta cada vez que el usuario teclea en el campo
const handleRutInput = (e) => {
    form.rut = formatRut(e.target.value);
};

// Función para enviar los datos
const submit = () => {
    form.post(route('clientes.store'), {
        onSuccess: () => form.reset(), // Si se guarda bien, limpiamos el formulario
    });
};
</script>

<template>

    <Head title="Clientes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Clientes</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-8">
                <div v-if="$page.props.errors.cliente_relacion"
                    class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error de seguridad</p>
                    <p>{{ $page.props.errors.cliente_relacion }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Agregar Nuevo Cliente</h3>

                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre / Razón Social *</label>
                            <input v-model="form.nombre" type="text"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                            <span v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre
                            }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">RUT *</label>
                            <input v-model="form.rut" @input="handleRutInput" type="text" placeholder="Ej: 12.345.678-9"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required maxlength="12">
                            <span v-if="form.errors.rut" class="text-red-500 text-xs mt-1">{{ form.errors.rut }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input v-model="form.email" type="email"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <span v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email
                            }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input v-model="form.telefono" type="text"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Giro (Actividad Comercial)</label>
                            <input v-model="form.giro" type="text"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-4">
                            <button type="submit" :disabled="form.processing"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition-colors disabled:opacity-50">
                                Guardar Cliente
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Lista de Clientes</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            RUT</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Giro</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="cliente in clientes" :key="cliente.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{
                                            cliente.nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ cliente.rut }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ cliente.email || '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ cliente.giro || '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <Link :href="route('clientes.edit', cliente.id)"
                                                class="text-indigo-600 hover:text-indigo-900 font-bold">
                                                Editar
                                            </Link>
                                            <Link :href="route('clientes.destroy', cliente.id)" method="delete"
                                                as="button" class="text-red-600 hover:text-red-900 font-bold">
                                                Eliminar
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="clientes.length === 0">
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Aún no tienes clientes registrados.
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
