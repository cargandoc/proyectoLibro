<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    libro: Object,
    generos: Array,
    localizaciones: Array,
    from: String,
});

const preview = ref(props.libro?.imagen ? `/storage/${props.libro.imagen}` : '/storage/images/preSet.png');

const form = useForm({
    titulo: props.libro?.titulo || '',
    autor: props.libro?.autor || '',
    genero: props.libro?.genero || 'Desconocido',
    anho_publicacion: props.libro?.anho_publicacion || '',
    editorial: props.libro?.editorial || '',
    paginas: props.libro?.paginas || '',
    calificacion: props.libro?.calificacion || '',
    inicio_lectura: props.libro?.inicio_lectura || '',
    fin_lectura: props.libro?.fin_lectura || '',
    resenha: props.libro?.resenha || '',
    imagen: null,
    localizacion: props.libro?.localizacion || 'Desconocido',
    estado: props.libro?.estado || 'pendiente',
});

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.imagen = file;
        preview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    const formData = new FormData();
    for (const key in form) {
        if (form[key] !== null) formData.append(key, form[key]);
    }
    formData.append('_method', 'PUT');

    Inertia.post(`/libros/${props.libro.id}`, formData, {
        onSuccess: () => volver(),
    });
};

const cancelar = () => volver();
const desloguear = () => Inertia.post(route('logout'));

const volver = () => {
    if (props.from) {
        Inertia.visit(props.from)
    } else {
        Inertia.visit('/')
    }
}
</script>

<template>
    <div class="container mx-auto p-4 md:p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center gap-2">
                <img src="/favicon.ico" alt="Icono" class="w-10 h-10 md:w-16 md:h-16" />
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">Mi Biblioteca</h1>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-blue-600 dark:text-blue-400">Editar Libro</h1>
            <button @click="desloguear()"
                class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 transition border border-red-200 dark:border-red-900/30 px-3 py-1 rounded-md">
                Cerrar Sesión
            </button>
        </div>

        <form @submit.prevent="submit"
            class="bg-gray-100 dark:bg-gray-800 p-4 md:p-6 rounded-lg shadow-md grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Título:</label>
                <input type="text" v-model="form.titulo"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Autor:</label>
                <input type="text" v-model="form.autor"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Género:</label>
                <select v-model="form.genero"
                        class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
                    <option v-for="g in generos" :key="g.id" :value="g.generoLit">
                        {{ g.generoLit || 'Desconocido' }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Año de publicación:</label>
                <input type="number" v-model.number="form.anho_publicacion"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Editorial:</label>
                <input type="text" v-model="form.editorial"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Número de páginas:</label>
                <input type="number" v-model.number="form.paginas"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
            </div>

            <div v-if="form.estado === 'leido'">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Calificación:</label>
                <input type="number" v-model.number="form.calificacion" min="0" max="5"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Localización:</label>
                <select v-model="form.localizacion"
                        class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
                    <option v-for="l in localizaciones" :key="l.id" :value="l.lugar">
                        {{ l.lugar || 'Desconocido' }}
                    </option>
                </select>
            </div>

            <div v-if="form.estado === 'leyendo' || form.estado === 'leido'">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Inicio de lectura:</label>
                <input type="date" v-model="form.inicio_lectura"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
            </div>

            <div v-if="form.estado === 'leido'">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Fin de lectura:</label>
                <input type="date" v-model="form.fin_lectura"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
            </div>

            <div v-if="form.estado === 'leido'" class="col-span-1 sm:col-span-2">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Reseña:</label>
                <textarea v-model="form.resenha" rows="3"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600"></textarea>
            </div>

            <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Portada:</label>
                <input type="file" accept=".heic,.jpg,.jpeg,.png,.tif,.tiff,.webp" @change="onFileChange"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
                <div class="flex justify-center sm:justify-start">
                    <img v-if="preview" :src="preview" class="w-24 md:w-32 mt-3 rounded shadow-lg border-2 border-white dark:border-gray-600">
                </div>
            </div>

            <div class="col-span-1 sm:col-span-2 lg:col-span-4">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Estado:</label>
                <select v-model="form.estado"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
                    <option value="pendiente">Pendiente</option>
                    <option value="leyendo">Leyendo</option>
                    <option value="leido">Leído</option>
                </select>
            </div>

            <div class="col-span-1 sm:col-span-2 lg:col-span-4 flex flex-col sm:flex-row gap-3 mt-2">
                <button @click="cancelar" type="button"
                    class="w-full sm:w-1/2 py-3 px-4 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition font-semibold order-2 sm:order-1">
                    Cancelar cambios
                </button>

                <button type="submit"
                    class="w-full sm:w-1/2 py-3 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition font-bold order-1 sm:order-2">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</template>