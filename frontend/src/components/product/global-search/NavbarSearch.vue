<template>
  <div
    :class="searchResultsShowing ? 'z-[15]' : ''"
    class="relative"
  >
    <base-input
      :klass="[
        'bg-slate-100 focus:bg-white !ring-0 rounded-xl',
        searchResultsShowing ? '!bg-white' : ''
      ]"
      :value="searchingText"
      name="search"
      placeholder="جستجو..."
      @click="searchResultsShowing = true"
      @input="onInputHandler"
    >
      <template #icon>
        <MagnifyingGlassIcon class="w-6 h-6 text-slate-400"/>
      </template>
    </base-input>

    <VTransitionFade>
      <div
        v-if="searchResultsShowing && enteredSearching"
        class="absolute top-full translate-y-1.5 bg-white rounded-lg shadow-lg p-4 w-full max-h-96 overflow-auto my-custom-scrollbar"
      >
        <VTransitionFade>
          <loader-circle
            v-if="searchLoading"
            big-circle-color="border-transparent"
            container-bg-color=""
            main-container-klass="relative w-full h-10 mb-3"
          />
        </VTransitionFade>

        <div class="flex flex-col">
          <router-link
            :to="{name: 'search', query: {q: searchingText}}"
            class="flex items-center gap-2.5 text-blue-600 mr-auto text-sm font-iranyekan-bold mb-3 group hover:opacity-80 transition"
            @click="cleanupBeforeNavigating"
          >
            <span>نمایش نتایج بیشتر</span>
            <ArrowLongLeftIcon class="size-5 group-hover:-translate-x-1 transition"/>
          </router-link>

          <div v-if="searchStore.getBrands?.length">
            <partial-search-result-brands @navigating="cleanupBeforeNavigating"/>
            <hr class="my-5 border-slate-200">
          </div>

          <div v-if="searchStore.getCategories?.length">
            <partial-search-result-categories @navigating="cleanupBeforeNavigating"/>
            <hr class="my-5 border-slate-200">
          </div>

          <div>
            <partial-search-results @navigating="cleanupBeforeNavigating"/>
          </div>
        </div>
      </div>
    </VTransitionFade>
  </div>

  <VTransitionFade>
    <div
      v-if="searchResultsShowing"
      class="hidden md:block fixed z-[11] w-[100vw] h-[100vh] bg-black/30 top-0 left-0"
      @click="hideSearchResults"
    ></div>
  </VTransitionFade>
</template>

<script setup>
import {ref} from "vue";
import {ArrowLongLeftIcon, MagnifyingGlassIcon} from "@heroicons/vue/24/outline/index.js";
import BaseInput from "@/components/base/BaseInput.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import PartialSearchResultBrands from "@/components/product/global-search/PartialSearchResultBrands.vue";
import PartialSearchResultCategories from "@/components/product/global-search/PartialSearchResultCategories.vue";
import PartialSearchResults from "@/components/product/global-search/PartialSearchResults.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {useDebounceFn} from "@vueuse/core";
import {SearchAPI} from "@/service/APISearch.js";
import {useProductSearchStore} from "@/store/StoreProductSearch.js";

const searchStore = useProductSearchStore()

const searchResultsShowing = ref(false)

function hideSearchResults() {
  searchResultsShowing.value = false
}

const searchLoading = ref(false)
const searchingText = ref('')

const enteredSearching = ref(false)

function searchForResult() {
  if (searchingText.value.trim() === '') {
    return
  }

  enteredSearching.value = true
  searchLoading.value = true

  SearchAPI.products(searchingText.value, {
    success(response) {
      if (searchLoading.value) {
        searchStore.setBrands(response.data?.brands)
        searchStore.setCategories(response.data?.categories)
        searchStore.setProducts(response.data?.products)
      }
    },
    finally() {
      searchLoading.value = false
    },
  })
}

const callSearch = useDebounceFn(() => {
  searchForResult()
}, 300)

function onInputHandler(text) {
  searchingText.value = text
  callSearch()
}

function cleanupBeforeNavigating() {
  searchingText.value = ''
  hideSearchResults()
}
</script>
