<template>
  <div
      v-if="showSlider"
      class="p-3"
  >
    <main-slider @loaded="(hasData) => {showSlider = hasData}"/>
  </div>

  <div
      v-if="showOffers"
      class="px-3 mb-6 lg:mb-8"
  >
    <amazing-offers @loaded="(hasData) => {showOffers = hasData}"/>
  </div>

  <div
      v-if="showCategories"
      class="px-3 mb-6 lg:mb-8"
  >
    <chosen-categories @loaded="(hasData) => {showCategories = hasData}"/>
  </div>

  <div
      v-if="showSliders"
      class="mb-6 lg:mb-8"
  >
    <sliders @loaded="(hasData) => {showSliders = hasData}"/>
  </div>

  <div
      v-if="showPopularBrands"
      class="mb-6 lg:mb-8"
  >
    <popular-brands @loaded="(hasData) => {showPopularBrands = hasData}"/>
  </div>

  <div
      v-if="showLatestBlogs"
      class="mb-6 lg:mb-8"
  >
    <latest-blogs @loaded="(hasData) => {showLatestBlogs = hasData}"/>
  </div>

  <app-newsletter/>
</template>

<script setup>
import {ref} from "vue";
import MainSlider from "@/components/main-page/MainSlider.vue";
import AppNewsletter from "@/components/AppNewsletter.vue";
import ChosenCategories from "@/components/main-page/ChosenCategories.vue";
import AmazingOffers from "@/components/main-page/AmazingOffers.vue";
import Sliders from "@/components/main-page/Sliders.vue";
import LatestBlogs from "@/components/main-page/LatestBlogs.vue";
import PopularBrands from "@/components/main-page/PopularBrands.vue";

const showSlider = ref(true)
const showOffers = ref(true)
const showCategories = ref(true)
const showSliders = ref(true)
const showPopularBrands = ref(true)
const showLatestBlogs = ref(true)
</script>
