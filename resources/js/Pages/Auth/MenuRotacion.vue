<script setup>
import { router } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
  seccionActual: String
});

const irASeccion = (seccion) => {
  router.get(`/${seccion}`);
};
</script>

<template>
  <div class="ml-6 flex flex-col justify-start gap-2 sticky top-6">
      <button @click="irASeccion('home')" 
              :class="{'bg-blue-600': seccionActual==='home', 'bg-blue-500': seccionActual!=='home'}"
              class="px-3 py-2 text-white rounded hover:bg-blue-700 transition">Inicio</button>

      <button @click="irASeccion('leidos')" 
              :class="{'bg-green-600': seccionActual==='leidos', 'bg-green-500': seccionActual!=='leidos'}"
              class="px-3 py-2 text-white rounded hover:bg-green-700 transition">Leídos</button>

      <button @click="irASeccion('leyendo')" 
              :class="{'bg-yellow-600': seccionActual==='leyendo', 'bg-yellow-500': seccionActual!=='leyendo'}"
              class="px-3 py-2 text-white rounded hover:bg-yellow-700 transition">Leyendo</button>

      <button @click="irASeccion('pendientes')" 
              :class="{'bg-red-600': seccionActual==='pendientes', 'bg-red-500': seccionActual!=='pendientes'}"
              class="px-3 py-2 text-white rounded hover:bg-red-700 transition">Pendientes</button>

      <button @click="irASeccion('valorados')" 
              :class="{'bg-purple-600': seccionActual==='valorados', 'bg-purple-500': seccionActual!=='valorados'}"
              class="px-3 py-2 text-white rounded hover:bg-purple-700 transition">Valorados</button>

      <button @click="irASeccion('generos')" 
              :class="{'bg-indigo-600': seccionActual==='generos', 'bg-indigo-500': seccionActual!=='generos'}"
              class="px-3 py-2 text-white rounded hover:bg-indigo-700 transition">Géneros</button>

      <button @click="irASeccion('anhios')" 
              :class="{'bg-pink-600': seccionActual==='anhios', 'bg-pink-500': seccionActual!=='anhios'}"
              class="px-3 py-2 text-white rounded hover:bg-pink-700 transition">Años</button>
  </div>
</template>