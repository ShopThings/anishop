<template>
  <ul
      v-if="categoriesLoading || !categories?.length"
      class="flex flex-wrap items-center justify-center gap-8"
  >
    <li
        v-for="i in 6"
        :key="i"
        class="flex flex-col items-center gap-3 animate-pulse"
    >
      <div class="rounded-full size-32 bg-slate-200"></div>
      <div class="h-3 w-full rounded bg-slate-200"></div>
    </li>
  </ul>
  <template v-else>
    <partial-general-title
        container-class="mb-5 mt-6 p-2"
        title="دسته‌بندی‌های منتخب"
        title-size="text-xl"
        type="side"
    />

    <ul class="flex flex-wrap items-center justify-center gap-8">
      <li
          v-for="category in categories"
          :key="category.id"
      >
        <router-link
            :to="{name: 'search', query: {category: category.id}}"
            class="flex flex-col items-center gap-3"
        >
          <base-lazy-image
              :alt="category.name"
              :lazy-src="category.image.path"
              :size="FileSizes.LARGE"
              class="w-32 h-auto"
          />

          <h1 class="font-iranyekan-bold text-sm">
            {{ category.name }}
          </h1>
        </router-link>
      </li>
    </ul>
  </template>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";
import {FileSizes} from "@/composables/file-list.js";

const emit = defineEmits(['loaded'])

const categories = ref(null)
const categoriesLoading = ref(true)

onMounted(() => {
  HomeMainPageAPI.fetchSliderChosenCategories({
    success(response) {
      categories.value = response.data
    },
    error() {
      return false
    },
    finally() {
      categoriesLoading.value = false
      emit('loaded', !!categories.value?.length)
    },
  })
})
</script>
