<template>
  <svg aria-hidden="true" class="absolute w-full h-full -z-10 inset-0 stroke-[#e8eef7]">
    <defs>
      <pattern id="83fd4e5a-9d52-42fc-97b6-718e5d7ee527" height="200" patternUnits="userSpaceOnUse" width="200" x="50%"
               y="-64">
        <path d="M100 200V.5M.5 .5H200" fill="none"></path>
      </pattern>
    </defs>
    <svg class="overflow-visible fill-[#ebf2fc]" x="50%" y="-64">
      <path
        d="M-100.5 0h201v201h-201Z M699.5 0h201v201h-201Z M499.5 400h201v201h-201Z M299.5 800h201v201h-201Z"
        stroke-width="0"></path>
    </svg>
    <rect fill="url(#83fd4e5a-9d52-42fc-97b6-718e5d7ee527)" height="100%" stroke-width="0" width="100%"></rect>
  </svg>

  <app-navigation-header container-class="text-center" title="پرسش‌های متداول"/>

  <div class="mx-auto max-w-3xl mb-8 p-3">
    <QuestionMarkCircleIcon class="w-14 h-14 mx-auto text-cyan-500"/>

    <h1 class="text-xl text-center px-3 pt-3 pb-8 font-iranyekan-light">
      جستجوی موضوع پرسش
    </h1>

    <form @submit.prevent="searchFaqHandler">
      <div class="flex sm:items-center flex-col sm:flex-row gap-2">
        <base-input
          :value="searchText"
          class="w-full"
          name="search"
          placeholder="موضوع مورد جستجو را وارد نمایید"
          @cleared="clearSearchHandler"
          @input="(text) => {searchText = text}"
        />
        <base-button
          class="bg-cyan-500 border-cyan-500 px-5 shrink-0 flex items-center gap-3"
          type="submit"
        >
          <span class="mx-auto">جستجو</span>
          <MagnifyingGlassIcon class="w-6 h-6"/>
        </base-button>
      </div>
    </form>
  </div>

  <div class="p-3 mb-12">
    <base-paginator
      ref="paginatorCom"
      v-model:items="faqs"
      v-model:search-text="searchText"
      v-model:total="totalFaqs"
      :is-loading="loadingFaqs"
      :local-search-filter-handler="searchFilterHandler"
      :per-page="10"
      container-class="flex flex-col gap-3"
      pagination-theme="modern"
    >
      <template #empty>
        <partial-empty-rows
          image="/images/empty-statuses/empty-note.svg"
          image-class="w-60"
          message="هیچ پرسش و پاسخی ثبت نشده است"
        />
      </template>

      <template #item="{item}">
        <base-accordion
          btn-class="text-black bg-white hover:bg-opacity-60 hover:shadow-sm focus-visible:ring-black/50 py-5 rounded-none"
          panel-class="bg-white border-t border-t-slate-100"
        >
          <template #button="{isOpen}">
            <div :class="{'text-indigo-600': isOpen}" class="leading-relaxed">
              {{ item.question }}
            </div>
          </template>

          <template #panel>
            <div class="p-3 text-black leading-loose" v-html="item.answer"></div>
          </template>
        </base-accordion>
      </template>

      <template #loading>
        <loader-list-single/>
      </template>
    </base-paginator>
  </div>
</template>

<script setup>
import {nextTick, onMounted, ref} from "vue";
import {MagnifyingGlassIcon, QuestionMarkCircleIcon} from "@heroicons/vue/24/outline/index.js";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import BasePaginator from "@/components/base/BasePaginator.vue";
import LoaderListSingle from "@/components/base/loader/LoaderListSingle.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";

const loadingFaqs = ref(true)
const paginatorCom = ref(null)

const searchFilterHandler = (item, text) => {
  if (text.trim() === '') return true
  return (
    item.question.indexOf(text) !== -1 ||
    item.keywords.filter(k => k.indexOf(text) !== -1).length
  )
}

const searchText = ref('')
const faqs = ref([])
const totalFaqs = ref(0)

function searchFaqHandler() {
  if (!paginatorCom.value) return
  paginatorCom.value.goToPage(1)
}

function clearSearchHandler() {
  searchText.value = ''
  nextTick(() => {
    searchFaqHandler()
  })
}

onMounted(() => {
  HomeMainPageAPI.fetchAllFaqs({
    success(response) {
      faqs.value = response.data
      totalFaqs.value = faqs.value.length || 0
    },
    finally() {
      loadingFaqs.value = false
    },
  })
})
</script>
