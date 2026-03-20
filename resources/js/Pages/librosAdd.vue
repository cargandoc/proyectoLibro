<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    generos: Array,
    localizaciones: Array,
})

const preview = ref(null)

const form = useForm({
    titulo: '',
    autor: '',
    genero: 'Desconocido',
    anho_publicacion: '',
    editorial: '',
    paginas: '',
    calificacion: '',
    inicio_lectura: '',
    fin_lectura: '',
    resenha: '',
    imagen: null,
    localizacion: 'Desconocido',
    estado: 'pendiente',
})

const mostrarPreview = (event) => {
    const file = event.target.files[0]
    if (!file) return

    form.imagen = file
    preview.value = URL.createObjectURL(file)
}

const erroresFecha = ref('')

const minInicioLectura = computed(() => {
    return form.anho_publicacion
        ? `${form.anho_publicacion}-01-01`
        : '1900-01-01'
})

const minFinLectura = computed(() => {
    if (!form.inicio_lectura && form.anho_publicacion) {
        return `${form.anho_publicacion}-01-01`
    }
    return form.inicio_lectura || (form.anho_publicacion ? `${form.anho_publicacion}-01-01` : '1900-01-01')
})

watch([() => form.anho_publicacion, () => form.inicio_lectura, () => form.fin_lectura], () => {
    erroresFecha.value = ''
    if (form.inicio_lectura && form.anho_publicacion && form.inicio_lectura < `${form.anho_publicacion}-01-01`) {
        erroresFecha.value = 'La fecha de inicio no puede ser anterior al año de publicación'
    }
    if (form.fin_lectura && form.inicio_lectura && form.fin_lectura < form.inicio_lectura) {
        erroresFecha.value = 'La fecha de finalización no puede ser anterior a la fecha de inicio'
    }
    if (form.fin_lectura && form.anho_publicacion && form.fin_lectura < `${form.anho_publicacion}-01-01`) {
        erroresFecha.value = 'La fecha de finalización no puede ser anterior al año de publicación'
    }
})

const submit = () => {
    if (erroresFecha.value) return alert(erroresFecha.value)

    form.post('/libros', {
        forceFormData: true,
        onSuccess: () => {
            form.reset()
            preview.value = null
        },
        onError: (errors) => console.log(errors)
    })
}

const vistaActual = ref('Añadir')

const cambiarVista = (vista) => {
    vistaActual.value = vista

    let credencial = null

    if (vista === 'Leídos') credencial = 'leido'
    if (vista === 'Leyendo') credencial = 'leyendo'
    if (vista === 'Pendientes') credencial = 'pendiente'
    if (vista === 'Valoración') credencial = 'valorados'
    if (vista === 'Género') credencial = 'generos'
    if (vista === 'Año') credencial = 'anhos'
    if (vista === 'Localización') credencial = 'localizacion'

    if (vista === 'Inicio') {
        Inertia.get(route('libro'))
        return
    }

    if (vista === 'Añadir') {
        window.location.href = '/libros'
        return
    }

    Inertia.get(route('libro'), { credencial })
}

const desloguear = () => {
    Inertia.post(route('logout'))
}
</script>

<template>
    <div class="container mx-auto p-4 md:p-6">
        <div class="flex justify-between items-center mb-6 gap-2">
            <div class="flex items-center gap-2">
                <img src="/favicon.ico" alt="Icono" class="w-12 h-12 md:w-20 md:h-20" />
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Mi Biblioteca</h1>
            </div>

            <div class="flex items-center gap-2">
                <button @click="menuLateralAbierto = true"
                    class="block md:hidden px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-white">
                    Menú
                </button>
                <button @click="desloguear()"
                    class="hidden md:block text-sm font-medium text-red-600 hover:text-red-500">
                    Cerrar Sesión
                </button>
            </div>
        </div>

        <div v-if="menuLateralAbierto" class="fixed inset-0 z-50 md:hidden">
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="menuLateralAbierto = false"></div>
            <div
                class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 p-6 shadow-xl flex flex-col overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold dark:text-white">Menú</h2>
                    <button @click="menuLateralAbierto = false"
                        class="text-gray-500 dark:text-gray-300 text-2xl">✕</button>
                </div>
                <div class="flex flex-col gap-2">
                    <button
                        v-for="boton in ['Inicio', 'Leídos', 'Leyendo', 'Pendientes', 'Valoración', 'Género', 'Año', 'Localización', 'Añadir']"
                        :key="boton" @click="cambiarVista(boton)" :class="[
                            'px-4 py-3 rounded-lg text-left transition',
                            vistaActual === boton ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 dark:text-gray-200'
                        ]">
                        {{ boton }}
                    </button>
                </div>
            </div>
        </div>

        <div class="hidden md:flex flex-wrap gap-2 mb-6">
            <button
                v-for="boton in ['Inicio', 'Leídos', 'Leyendo', 'Pendientes', 'Valoración', 'Género', 'Año', 'Localización', 'Añadir']"
                :key="boton" @click="cambiarVista(boton)" :class="[
                    'px-4 py-2 rounded transition',
                    vistaActual === boton ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300'
                ]">
                {{ boton }}
            </button>
        </div>

        <form @submit.prevent="submit"
            class="bg-gray-100 dark:bg-gray-800 p-4 md:p-6 rounded-lg shadow-md grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

            <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Título:</label>
                <input type="text" v-model="form.titulo"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Autor:</label>
                <input type="text" v-model="form.autor"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Género:</label>
                <select v-model="form.genero"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
                    <option v-for="g in generos" :key="g.id" :value="g.generoLit">
                        {{ g.generoLit }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Año de publicación:</label>
                <input type="number" v-model.number="form.anho_publicacion"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Editorial:</label>
                <input type="text" v-model="form.editorial"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Número de páginas:</label>
                <input type="number" v-model.number="form.paginas"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />
            </div>

            <div v-if="form.estado === 'leido'">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Calificación:</label>
                <input type="number" v-model.number="form.calificacion" min="0" max="5"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Localización:</label>
                <select v-model="form.localizacion"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">
                    <option v-for="l in localizaciones" :key="l.id" :value="l.lugar">
                        {{ l.lugar }}
                    </option>
                </select>
            </div>

            <div v-if="form.estado === 'leyendo' || form.estado === 'leido'">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Inicio de lectura:</label>
                <input type="date" v-model="form.inicio_lectura" :min="minInicioLectura"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />
            </div>

            <div v-if="form.estado === 'leido'">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Fin de lectura:</label>
                <input type="date" v-model="form.fin_lectura" :min="minFinLectura"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />
            </div>

            <div v-if="form.estado === 'leido'" class="col-span-1 sm:col-span-2">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Reseña:</label>
                <textarea v-model="form.resenha" rows="3"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600"></textarea>
            </div>

            <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                <label class="block mb-1 font-medium text-gray-700 dark:text-gray-200">Portada:</label>
                <input type="file" accept=".heic,.jpg,.jpeg,.png,.tif,.tiff,.webp" @change="mostrarPreview"
                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />
                <img v-if="preview" :src="preview" class="w-24 md:w-32 mt-3 rounded shadow mx-auto sm:mx-0" />
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

            <p v-if="erroresFecha" class="col-span-1 sm:col-span-2 lg:col-span-4 text-red-600 font-semibold text-sm">{{
                erroresFecha }}</p>

            <button type="submit"
                class="col-span-1 sm:col-span-2 lg:col-span-4 py-3 px-4 bg-green-600 text-white rounded-md hover:bg-green-700 transition font-bold">
                Añadir libro
            </button>
        </form>
    </div>
</template>