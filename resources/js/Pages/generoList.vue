<script setup>
import { Inertia } from '@inertiajs/inertia'
import { ref } from 'vue'

defineProps({
    generos: Array
})

const desloguear = () => {
    Inertia.post(route('logout'))
}

const mostrarModalEliminar = ref(false)
const generoAEliminar = ref(null)

const confirmarEliminar = (id) => {
    generoAEliminar.value = id
    mostrarModalEliminar.value = true
}

const eliminarGenero = () => {
    Inertia.delete(`/generos/${generoAEliminar.value}`)
    mostrarModalEliminar.value = false
}

const cancelarEliminar = () => {
    mostrarModalEliminar.value = false
    generoAEliminar.value = null
}

const editarGenero = (id) => Inertia.get(`/generos/${id}/editar`)
</script>

<template>
    <div class="container mx-auto p-4 md:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center gap-2">
                <img src="/favicon.ico" alt="Icono" class="w-10 h-10 md:w-16 md:h-16" />
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">Mi Biblioteca</h1>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Lista de Géneros</h1>
            <button @click="desloguear()"
                class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400">
                Cerrar Sesión
            </button>
        </div>

        <div class="flex flex-row justify-between items-center mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm gap-2">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 hidden xs:block">Géneros</h2>
            <div class="flex gap-2 w-full sm:w-auto">
                <button @click="() => Inertia.get('/', { credencial: 'generos' })"
                    class="flex-1 sm:flex-none bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition text-center">
                    Volver
                </button>
                <button @click="() => Inertia.visit('/generos/create')"
                    class="flex-1 sm:flex-none bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition text-center">
                    Crear género
                </button>
            </div>
        </div>

        <div class="hidden md:block overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="p-4 text-gray-700 dark:text-gray-200 font-bold">ID</th>
                        <th class="p-4 text-gray-700 dark:text-gray-200 font-bold">Género Literario</th>
                        <th class="p-4 text-gray-700 dark:text-gray-200 font-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="genero in generos" :key="genero.id"
                        class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 text-gray-800 dark:text-white">
                        <td class="p-4">{{ genero.id }}</td>
                        <td class="p-4 font-medium">{{ genero.generoLit }}</td>
                        <td class="p-4 text-center">
                            <div v-if="genero.generoLit !== 'Desconocido'" class="flex justify-center gap-2">
                                <button @click="editarGenero(genero.id)"
                                    class="bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700 transition text-sm">
                                    Editar
                                </button>
                                <button @click="confirmarEliminar(genero.id)"
                                    class="bg-red-600 text-white px-4 py-1.5 rounded hover:bg-red-700 transition text-sm">
                                    Eliminar
                                </button>
                            </div>
                            <span v-else class="text-gray-400 italic text-sm">Protegido</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 gap-4 md:hidden">
            <div v-for="genero in generos" :key="genero.id" 
                class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border-l-4"
                :class="genero.generoLit === 'Desconocido' ? 'border-gray-400' : 'border-blue-500'">
                
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase">ID: {{ genero.id }}</span>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ genero.generoLit }}</h3>
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <template v-if="genero.generoLit !== 'Desconocido'">
                        <button @click="editarGenero(genero.id)"
                            class="flex-1 bg-blue-600 text-white py-2 rounded-md text-sm font-bold">
                            Editar
                        </button>
                        <button @click="confirmarEliminar(genero.id)"
                            class="flex-1 bg-red-600 text-white py-2 rounded-md text-sm font-bold">
                            Eliminar
                        </button>
                    </template>
                    <span v-else class="w-full text-center py-2 text-gray-400 italic bg-gray-100 dark:bg-gray-700 rounded-md">
                        Elemento protegido
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div v-if="mostrarModalEliminar" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50 p-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-2xl w-full max-w-sm transform transition-all">
            <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-white">¿Eliminar género?</h2>
            <p class="mb-6 text-gray-600 dark:text-gray-300">Esta acción no se puede deshacer. El género desaparecerá de la lista.</p>
            <div class="flex gap-3">
                <button @click="cancelarEliminar"
                    class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 transition">
                    Cancelar
                </button>
                <button @click="eliminarGenero"
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow-lg shadow-red-500/30 transition font-bold">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</template>