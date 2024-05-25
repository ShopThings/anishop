<template>
  <div class="relative border-t-2">
    <footer class="p-2 pb-6 text-center z-[1] max-w-7xl mx-auto w-full">
      <router-link
          :to="{name: 'home'}"
          class="mb-3 inline-block max-w-[150px]"
      >
        <img
            alt="لوگو"
            class="mt-[10px] max-w-full"
            src="/logo-with-type.png"
        >
      </router-link>

      <div v-if="homeSettingStore.getFooterCopyright?.toString() !== ''">
        <p class="text-zinc-800 text-sm">
          &copy;
          {{ homeSettingStore.getFooterCopyright }}
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import {inject} from "vue";

const homeSettingStore = inject('homeSettingStore')
</script>
