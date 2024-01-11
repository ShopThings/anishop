<template>
  <div class="p-3">
    <main-slider/>
  </div>

  <div class="px-3 mb-6 lg:mb-8">
    <amazing-offers/>
  </div>

  <div class="px-3 mb-6 lg:mb-8">
    <chosen-categories/>
  </div>

  <div class="mb-6 lg:mb-8">
    <sliders/>
  </div>

  <div class="mb-6 lg:mb-8">
    <popular-brands/>
  </div>

  <div class="mb-6 lg:mb-8">
    <latest-blogs/>
  </div>

  <app-newsletter/>
</template>

<script setup>
import MainSlider from "@/components/main-page/MainSlider.vue";
import AppNewsletter from "@/components/AppNewsletter.vue";
import ChosenCategories from "@/components/main-page/ChosenCategories.vue";
import AmazingOffers from "@/components/main-page/AmazingOffers.vue";
import Sliders from "@/components/main-page/Sliders.vue";
import LatestBlogs from "@/components/main-page/LatestBlogs.vue";
import PopularBrands from "@/components/main-page/PopularBrands.vue";
</script>
