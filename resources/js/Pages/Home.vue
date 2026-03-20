<script setup>
import { defineProps, ref, computed } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
  libros: {
    type: Array,
    default: () => []
  },
  nombreBusqueda: {
    type: String,
    default: ''
  },
  credencial: {
    type: String,
    default: ''
  },
  valor: {
    type: [String, Number],
    default: ''
  },
  genero: {
    type: String,
    default: ''
  },
  anhio: {
    type: [String, Number],
    default: ''
  },
  localizacion: {
    type: String,
    default: ''
  }
})

const vistaActual = ref('Inicio')
const nombreBusqueda = ref(props.nombreBusqueda)
const valorBusqueda = ref(props.valor || '')
const generoBusqueda = ref(props.genero || '')
const anhoBusqueda = ref(props.anhio || '')
const localizacionBusqueda = ref(props.localizacion || '')

const menuLateralAbierto = ref(false)

if (props.credencial === 'leido') vistaActual.value = 'Leídos'
if (props.credencial === 'leyendo') vistaActual.value = 'Leyendo'
if (props.credencial === 'pendientes') vistaActual.value = 'Pendientes'
if (props.credencial === 'valorados') vistaActual.value = 'Valoración'
if (props.credencial === 'generos') vistaActual.value = 'Género'
if (props.credencial === 'anhos') vistaActual.value = 'Año'
if (props.credencial === 'localizacion') vistaActual.value = 'Localización'

const cambiarVista = (vista) => {
  vistaActual.value = vista
  menuLateralAbierto.value = false

  if (vista === 'Añadir') {
    window.location.href = '/libros'
    return
  }

  let params = {}
  if (vista === 'Leídos') params.credencial = 'leido'
  if (vista === 'Leyendo') params.credencial = 'leyendo'
  if (vista === 'Pendientes') params.credencial = 'pendientes'
  if (vista === 'Valoración') params.credencial = 'valorados'
  if (vista === 'Género') params.credencial = 'generos'
  if (vista === 'Año') params.credencial = 'anhos'
  if (vista === 'Localización') params.credencial = 'localizacion'

  Inertia.get('/', params)
}

const mostrarInputNombre = computed(() =>
  ['Inicio', 'Leídos', 'Leyendo', 'Pendientes'].includes(vistaActual.value)
)

const buscar = () => {
  let params = {}

  if (['Inicio', 'Leídos', 'Leyendo', 'Pendientes'].includes(vistaActual.value)) {
    if (nombreBusqueda.value) {
      params.nombre = nombreBusqueda.value
    }
  }

  if (vistaActual.value === 'Leídos') params.credencial = 'leido'
  if (vistaActual.value === 'Leyendo') params.credencial = 'leyendo'
  if (vistaActual.value === 'Pendientes') params.credencial = 'pendientes'

  if (vistaActual.value === 'Valoración') {
    params.credencial = 'valorados'
    if (valorBusqueda.value) params.valor = valorBusqueda.value
  }

  if (vistaActual.value === 'Género') {
    params.credencial = 'generos'
    if (generoBusqueda.value) params.genero = generoBusqueda.value
  }

  if (vistaActual.value === 'Año') {
    params.credencial = 'anhos'
    if (anhoBusqueda.value) params.anhio = anhoBusqueda.value
  }

  if (vistaActual.value === 'Localización') {
    params.credencial = 'localizacion'
    if (localizacionBusqueda.value) params.localizacion = localizacionBusqueda.value
  }

  Inertia.get('/', params)
}

const editarLibro = (id) => {
  Inertia.visit(`/libros/${id}/editar`, {
    data: {
      from: window.location.href
    }
  })
}

const mostrarModalEliminar = ref(false)
const libroAEliminar = ref(null)
const confirmarEliminar = (id) => {
  libroAEliminar.value = id
  mostrarModalEliminar.value = true
}
const eliminarLibro = () => {
  Inertia.delete(`/libros/${libroAEliminar.value}`)
  mostrarModalEliminar.value = false
}
const cancelarEliminar = () => {
  mostrarModalEliminar.value = false
  libroAEliminar.value = null
}

const desloguear = () => Inertia.post(route('logout'))

const verMasLibro = ref(false)
const libroSeleccionado = ref(null)
const mostrarLibro = (libro) => {
  libroSeleccionado.value = libro
  verMasLibro.value = true
}
const cerrarModal = () => {
  verMasLibro.value = false
  libroSeleccionado.value = null
}

const mostrarInputValor = computed(() => vistaActual.value === 'Valoración')
const mostrarInputGenero = computed(() => vistaActual.value === 'Género')
const mostrarInputAnho = computed(() => vistaActual.value === 'Año')

const añadirGenero = () => {
  window.location.href = '/generos/create'
}

const añadirLocalizacion = () => {
  window.location.href = '/localizaciones/create'
}

const botonesMenu = ['Inicio', 'Leídos', 'Leyendo', 'Pendientes', 'Valoración', 'Género', 'Año', 'Localización', 'Añadir']
</script>

<template>
  <div class="container mx-auto p-4 md:p-6">
    <div class="flex justify-between items-center mb-4 gap-2">
      <div class="flex items-center gap-2">
        <img src="/favicon.ico" alt="Icono" class="w-12 h-12 md:w-20 md:h-20" />
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Mi Biblioteca</h1>
      </div>
      
      <button @click="menuLateralAbierto = true" class="block md:hidden px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-white z-10">
        Menú
      </button>

      <button @click="desloguear()"
        class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 transition hidden md:block">
        Cerrar Sesión
      </button>
    </div>

    <div v-if="menuLateralAbierto" class="fixed inset-0 z-50 md:hidden">
      <div class="fixed inset-0 bg-black bg-opacity-50" @click="menuLateralAbierto = false"></div>
      
      <div class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 p-6 shadow-xl flex flex-col overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold dark:text-white">Menú</h2>
          <button @click="menuLateralAbierto = false" class="text-gray-500 dark:text-gray-300 text-2xl">✕</button>
        </div>
        
        <div class="flex flex-col gap-2 flex-grow">
          <button
            v-for="boton in botonesMenu"
            :key="boton + '-mobile'" @click="cambiarVista(boton)" :class="[
              'px-4 py-3 rounded-lg transition text-left w-full',
              vistaActual === boton ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600'
            ]">
            {{ boton }}
          </button>
        </div>

        <button @click="desloguear()"
          class="mt-6 w-full text-center py-2 px-4 bg-red-100 text-red-700 rounded-lg font-medium">
          Cerrar Sesión
        </button>
      </div>
    </div>

    <div class="hidden md:flex flex-wrap gap-2 mb-6">
      <button
        v-for="boton in botonesMenu"
        :key="boton + '-desktop'" @click="cambiarVista(boton)" :class="[
          'px-4 py-2 rounded transition',
          vistaActual === boton ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600'
        ]">
        {{ boton }}
      </button>
    </div>

    <div class="mb-6 flex gap-2 flex-wrap">
      <input v-if="mostrarInputNombre" type="text" v-model="nombreBusqueda" placeholder="Buscar por nombre"
        class="p-2 border rounded-md w-full sm:w-64 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600" />

      <input v-if="mostrarInputValor" type="number" v-model="valorBusqueda" min="0" max="5" placeholder="Calificación"
        class="p-2 border rounded-md w-full sm:w-32 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">

      <input v-if="mostrarInputGenero" type="text" v-model="generoBusqueda" placeholder="Género"
        class="p-2 border rounded-md w-full sm:w-32 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">

      <input v-if="mostrarInputAnho" type="number" v-model="anhoBusqueda" placeholder="Año finalización"
        class="p-2 border rounded-md w-full sm:w-32 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">

      <input v-if="vistaActual === 'Localización'" type="text" v-model="localizacionBusqueda" placeholder="Localización"
        class="p-2 border rounded-md w-full sm:w-32 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600">

      <button @click="buscar" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition w-full sm:w-auto">
        Buscar
      </button>
      <button v-if="mostrarInputGenero" @click="añadirGenero"
        class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition w-full sm:w-auto">
        Añadir Genero
      </button>
      <button v-if="vistaActual === 'Localización'" @click="añadirLocalizacion"
        class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition w-full sm:w-auto">
        Añadir Localizacion
      </button>
    </div>

    <p class="mb-6 text-gray-600 dark:text-gray-300">{{ libros.length }} libros disponibles</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div v-for="libro in libros" :key="libro.id"
        class="dark:bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-200 bg-gray-800">
        <img :src="`/storage/${libro.imagen}`" :alt="libro.titulo" class="w-full h-48 object-cover" />
        <div class="p-4">
          <small class="flex justify-end"><a @click="mostrarLibro(libro)"
              class="dark:text-gray-800 text-white cursor-pointer underline">ver más</a></small>
          <h2 class="text-xl font-semibold dark:text-gray-800 text-white"><span v-for="letra in 23">{{
            libro.titulo.charAt(letra - 1) }}<span
                v-if="letra == 23 && libro.titulo.charAt(letra - 1) != ''">...</span></span></h2>
          <p class="dark:text-gray-600 text-gray-300">{{ libro.autor }}</p>
          <p class="text-sm dark:text-gray-500 text-gray-400">{{ libro.genero }} | {{ libro.anho_publicacion }}</p>
          <p class="text-sm dark:text-gray-500 text-gray-400">Editorial: <span v-for="letra in 25">{{
            libro.editorial.charAt(letra - 1) }}<span
                v-if="letra == 25 && libro.editorial.charAt(letra - 1) != ''">...</span></span></p>
          <p class="text-sm dark:text-gray-500 text-gray-400">Páginas: {{ libro.paginas }}</p>
          <p class="text-sm dark:text-gray-500 text-gray-400">Calificación: {{ libro.calificacion }}/5</p>
          <p class="text-sm dark:text-gray-500 text-gray-400">Localización: {{ libro.localizacion }}</p>
          <p class="text-sm dark:text-gray-500 text-gray-400">Estado: {{ libro.estado }}</p>
          <p v-if="libro.resenha" class="mt-2 dark:text-gray-700 text-gray-300 text-sm italic">
            Reseña:
            <span v-for="letra in 50">{{ libro.resenha.charAt(letra - 1) }}<span
                v-if="letra == 50 && libro.resenha.charAt(letra - 1) != ''">...
              </span></span>
          </p>
          <p v-else>
            <br>
            <br>
          </p>
          <div class=" mt-3 flex gap-2">
            <button @click="editarLibro(libro.id)"
              class="w-1/2 py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">
              Editar
            </button>

            <button @click="confirmarEliminar(libro.id)"
              class="w-1/2 py-2 px-4 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm">
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div v-if="mostrarModalEliminar" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
      <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Confirmar eliminación</h2>
      <p class="mb-6 text-gray-600 dark:text-gray-300">¿Seguro que quieres eliminar este libro?</p>
      <div class="flex justify-end gap-3">
        <button @click="cancelarEliminar"
          class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancelar</button>
        <button @click="eliminarLibro"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</button>
      </div>
    </div>
  </div>

  <div v-if="verMasLibro" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
      <button @click="cerrarModal"
        class="absolute top-2 right-2 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white text-2xl z-10">✕</button>

      <div class="flex flex-col md:flex-row gap-6">

        <div class="hidden md:flex md:w-1/3 justify-center items-start">
          <img :src="libroSeleccionado?.imagen ? `/storage/${libroSeleccionado.imagen}` : '/storage/images/preSet.png'"
            alt="Portada del libro" class="w-full max-w-[200px] h-auto rounded shadow-md object-contain border dark:border-gray-700">
        </div>

        <div class="w-full md:w-2/3">
          <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white pr-6">
            {{ libroSeleccionado?.titulo }}
          </h2>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-gray-600 dark:text-gray-300 text-sm">
            <p><strong>Autor:</strong> {{ libroSeleccionado?.autor }}</p>
            <p><strong>Género:</strong> {{ libroSeleccionado?.genero }}</p>
            <p><strong>Editorial:</strong> {{ libroSeleccionado?.editorial }}</p>
            <p><strong>Año:</strong> {{ libroSeleccionado?.anho_publicacion }}</p>

            <p v-if="libroSeleccionado?.paginas">
              <strong>Páginas:</strong> {{ libroSeleccionado.paginas }}
            </p>

            <p v-if="libroSeleccionado?.localizacion">
              <strong>Localización:</strong> {{ libroSeleccionado.localizacion }}
            </p>

            <p v-if="libroSeleccionado?.inicio_lectura">
              <strong>Inicio Lectura:</strong> {{ libroSeleccionado.inicio_lectura }}
            </p>

            <p v-if="libroSeleccionado?.fin_lectura">
              <strong>Fin Lectura:</strong> {{ libroSeleccionado.fin_lectura }}
            </p>

            <div v-if="libroSeleccionado?.calificacion" class="col-span-1 sm:col-span-2 flex items-center gap-2">
              <strong>Calificación:</strong>
              <div class="flex text-lg">
                <span v-for="i in 5" :key="i"
                  :class="i <= libroSeleccionado.calificacion ? 'text-yellow-400' : 'text-gray-300'">
                  ★
                </span>
              </div>
            </div>
          </div>

          <div v-if="libroSeleccionado?.resenha" class="mt-5 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border dark:border-gray-600">
            <strong class="text-gray-800 dark:text-white block mb-2">Reseña:</strong>
            <p class="text-gray-700 dark:text-gray-200 text-sm italic leading-relaxed">{{ libroSeleccionado.resenha }}</p>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>