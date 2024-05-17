<template>
  <div
      class="bg-indigo-200 relative px-3"
  >
    <app-navigation-header
        class="text-center text-shadow"
        title="برندهای ما"
    />

    <div class="-translate-x-1/2 w-1/2 h-1 rounded-full bg-indigo-500"></div>
  </div>

  <div class="px-6 pt-12">
    <base-paginator
        v-model:items="brands"
        :path="getPath"
        :per-page="24"
        container-class="flex flex-wrap items-center"
        item-container-class="px-6 py-12 w-full sm:w-1/2 lg:w-1/3 xl:w-1/4"
        pagination-theme="modern"
    >
      <template #empty>
        <partial-empty-rows
            image="/empty-statuses/empty-data.svg"
            message="هیچ برندی وجود ندارد!"
        />
      </template>

      <template #item="{item}">
        <base-tilt-card class="bg-slate-300 rounded-xl h-52 relative flex items-end justify-center group">
          <router-link
              :to="{name: 'search', query: {brand: item.id}}"
              class="absolute w-[82%] h-[95%] mx-auto -top-1/4 rounded-2xl bg-white shadow-md group-hover:bg-opacity-80 group-hover:shadow-2xl transition"
          >
            <base-lazy-image
                :alt="item.name"
                :lazy-src="item.image.path"
                :is-local="false"
                class="w-full h-full rounded-2xl p-3"
            />
          </router-link>

          <div class="px-3 py-4 font-iranyekan-bold text-lg text-center">
            {{ item.name }}
          </div>
        </base-tilt-card>
      </template>

      <template #loading>
        <loader-brand/>
      </template>
    </base-paginator>
  </div>

  <app-newsletter/>
</template>

<script setup>
import {ref} from "vue";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import BaseTiltCard from "@/components/base/BaseTiltCard.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import AppNewsletter from "@/components/AppNewsletter.vue";
import {apiRoutes} from "@/router/api-routes.js";
import BasePaginator from "@/components/base/BasePaginator.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import LoaderBrand from "@/components/base/loader/LoaderBrand.vue";

const brands = ref([])
const getPath = apiRoutes.brands
</script>
