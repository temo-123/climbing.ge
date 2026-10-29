<script setup>
import { isRouteLoading } from '../../../store/routeLoader.js'
</script>

<template>
  <transition name="cg-fade" type="transition">
    <div v-if="isRouteLoading" class="cg-routeloader" role="progressbar" aria-busy="true">
      <div class="cg-routeloader__bar"></div>
    </div>
  </transition>
</template>
