<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

const form = useForm({
    lugar: ''
})

const submit = () => {
    form.post('/localizaciones', {
        onSuccess: () => form.reset()
    })
}
const desloguear = () => {
    Inertia.post(route('logout'))
}

const cancelar = () => {
    Inertia.visit('/localizaciones');
};
</script>

<template>
    <div class="container mx-auto p-4 md:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
            <div class="flex items-center gap-2">
                <div class="p-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <img src="/favicon.ico" alt="Icono" class="w-10 h-10 md:w-14 md:h-14" />
                </div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white tracking-tight">Mi Biblioteca</h1>
            </div>
            
            <h1 class="text-2xl md:text-3xl font-extrabold text-green-600 dark:text-green-400">Nueva Ubicación</h1>
            
            <button @click="desloguear()"
                class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 transition border border-red-200 dark:border-red-900/30 px-3 py-1 rounded-md">
                Cerrar Sesión
            </button>
        </div>

        <div class="max-w-xl mx-auto">
            <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2 mb-6 border-b pb-4 dark:border-gray-700">
                    <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                        Registrar Localización
                    </h2>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                            Nombre de la localización
                        </label>

                        <input 
                            type="text" 
                            v-model="form.lugar" 
                            placeholder="Ej: Estantería principal, Kindle, Oficina..."
                            class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all placeholder-gray-400" 
                        />

                        <div v-if="form.errors.lugar" class="text-red-500 text-sm mt-2 flex items-center gap-1 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ form.errors.lugar }}
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-2">
                        <button 
                            type="button" 
                            @click="cancelar"
                            class="w-full sm:w-1/2 py-3 px-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition order-2 sm:order-1"
                        >
                            Volver a localizaciones
                        </button>

                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="w-full sm:w-1/2 py-3 px-4 bg-green-600 text-white font-black rounded-xl hover:bg-green-700 shadow-lg shadow-green-500/30 transition-all transform active:scale-[0.98] order-1 sm:order-2 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Guardando...' : 'Guardar ubicación' }}
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center mt-6 text-sm text-gray-500 dark:text-gray-400">
                Podrás asignar esta ubicación a tus libros una vez guardada.
            </p>
        </div>
    </div>
</template>