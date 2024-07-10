<template>
  <base-dialog
    v-model:open="isDialogOpen"
    container-klass="max-w-lg overflow-hidden"
    dialog-container-class="items-start"
    @close="() => emit('close')"
    @open="() => emit('open')"
  >
    <template #button="{open}">
      <button
        class="w-[40px] h-[40px] border-0 py-2 px-2 rounded-lg bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all"
        type="button"
        @click="open"
      >
        <MagnifyingGlassIcon class="h-6 w-6 text-slate-400"/>
      </button>
    </template>

    <template #title>
      <div class="mb-2">
        جستجو در بلاگ
      </div>
    </template>

    <template #body="{close}">
      <form
        class="relative"
        @submit.prevent
      >
        <div class="sticky top-20 z-[1] bg-white py-3">
          <base-input
            name="search"
            placeholder="متن جستجو را وارد نمایید..."
            :value="searchingText"
            @mount="focusOnInput"
            @input="onInputHandler"
          >
            <template #icon>
              <MagnifyingGlassCircleIcon class="h-6 w-6 text-gray-300"/>
            </template>
          </base-input>
        </div>

        <div
          v-if="enteredSearching"
          class="flex flex-col mt-6"
        >
          <router-link
            :to="{name: 'blog.search', query: {q: searchingText}}"
            class="flex items-center gap-2.5 text-blue-600 mr-auto text-sm font-iranyekan-bold mb-3 group hover:opacity-80 transition"
            @click="cleanupBeforeNavigating(close)"
          >
            <span>نمایش نتایج بیشتر</span>
            <ArrowLongLeftIcon class="size-5 group-hover:-translate-x-1 transition"/>
          </router-link>

          <div v-if="searchStore.getCategories?.length">
            <partial-search-result-categories @navigating="cleanupBeforeNavigating(close)"/>
            <hr class="my-5 border-slate-200">
          </div>
          <div>
            <partial-search-results @navigating="cleanupBeforeNavigating(close)"/>
          </div>
        </div>
      </form>
    </template>
  </base-dialog>
</template>

<script setup>
import {computed, ref} from "vue";
import {ArrowLongLeftIcon, MagnifyingGlassCircleIcon, MagnifyingGlassIcon} from '@heroicons/vue/24/outline';
import BaseDialog from "../../base/BaseDialog.vue";
import BaseInput from "../../base/BaseInput.vue";
import PartialSearchResultCategories from "@/components/blog/global-search/PartialSearchResultCategories.vue";
import PartialSearchResults from "@/components/blog/global-search/PartialSearchResults.vue";
import {SearchAPI} from "@/service/APISearch.js";
import {useDebounceFn} from "@vueuse/core";
import {useBlogSearchStore} from "@/store/StoreBlogSearch.js";

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  }
})
const emit = defineEmits(['update:open', 'open', 'close'])

const searchStore = useBlogSearchStore()

const isDialogOpen = computed({
  get() {
    return props.open
  },
  set(value) {
    emit('update:open', value)
  },
})

function focusOnInput(input) {
  input.focus()
}

const searchLoading = ref(false)
const searchingText = ref('')

const enteredSearching = ref(false)

function searchForResult() {
  if (searchingText.value.trim() === '') return

  enteredSearching.value = true
  searchLoading.value = true

  SearchAPI.blogs(searchingText.value, {
    success(response) {
      if (searchLoading.value) {
        searchStore.setCategories(response.data?.categories)
        searchStore.setBlogs(response.data?.blogs)
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

function cleanupBeforeNavigating(close) {
  searchingText.value = ''
  close()
}
</script>
