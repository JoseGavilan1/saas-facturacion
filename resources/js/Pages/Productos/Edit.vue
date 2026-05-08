<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({ producto: Object });

const form = useForm({
    nombre: props.producto.nombre,
    codigo: props.producto.codigo || '',
    descripcion: props.producto.descripcion || '',
    precio: props.producto.precio,
});

const submit = () => {
    form.put(route('productos.update', props.producto.id));
};
</script>

<template>
    <Head title="Editar Producto" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Producto</h2>
                <Link :href="route('productos.index')" class="text-gray-600 hover:text-gray-900 underline text-sm">Volver</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <input v-model="form.nombre" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código</label>
                            <input v-model="form.codigo" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio Neto *</label>
                            <input v-model="form.precio" type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea v-model="form.descripcion" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                        <div class="md:col-span-2 flex justify-end mt-4">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold">Actualizar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
