<template>
  <partial-category-guest-mobile
      :categories="categories"
      :is-loading="loadingCategories"
  />
  <partial-category-guest
      :categories="categories"
      :is-loading="loadingCategories"
  />
</template>

<script setup>
import {onMounted, ref} from "vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";
import PartialCategoryGuest from "@/components/partials/PartialCategoryGuest.vue";
import PartialCategoryGuestMobile from "@/components/partials/PartialCategoryGuestMobile.vue";

const categories = ref(null)
const loadingCategories = ref(true)

onMounted(() => {
  HomeMainPageAPI.fetchCategoriesMain({
    with_children: true,
    level: 0,
  }, {
    success(response) {
      categories.value = response.data
      return false
    },
    error() {
      return false
    },
    finally() {
      loadingCategories.value = false
    },
  })
})
</script>
