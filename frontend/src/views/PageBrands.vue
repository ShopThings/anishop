<template>
  <div
      class="bg-indigo-200 relative px-3"
  >
    <app-navigation-header
        title="برندهای ما"
        class="text-center text-shadow"
    />

    <div class="-translate-x-1/2 w-1/2 h-1 rounded-full bg-indigo-500"></div>
  </div>


  <div class="px-6 pt-12">
    <div class="flex flex-wrap items-center">
      <div
          v-for="i in 9"
          :key="i"
          class="px-6 py-12 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4"
      >
        <base-tilt-card class="bg-slate-300 rounded-xl h-52 relative flex items-end justify-center group">
          <router-link
              :to="{name: 'search', query: {brand: i}}"
              class="absolute w-[82%] h-[95%] mx-auto -top-1/4 rounded-2xl bg-white shadow-md group-hover:bg-opacity-80 group-hover:shadow-2xl transition"
          >
            <base-lazy-image
                :lazy-src="'/src/assets/brands/b' + i + '.png'"
                alt=""
                class="w-full h-full rounded-2xl p-3"
            />
          </router-link>

          <div class="px-3 py-4 font-iranyekan-bold text-lg text-center">
            برند شماره
            {{ i }}
          </div>
        </base-tilt-card>
      </div>
    </div>
  </div>

  <app-newsletter/>
</template>

<script setup>
import AppNavigationHeader from "../components/AppNavigationHeader.vue";
import BaseTiltCard from "../components/base/BaseTiltCard.vue";
import BaseLazyImage from "../components/base/BaseLazyImage.vue";
import AppNewsletter from "../components/AppNewsletter.vue";
</script>

<style scoped>

</style>
