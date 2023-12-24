<template>
  <svg class="absolute w-full h-full -z-10 inset-0 stroke-[#e8eef7]" aria-hidden="true">
    <defs>
      <pattern id="83fd4e5a-9d52-42fc-97b6-718e5d7ee527" width="200" height="200" x="50%" y="-64"
               patternUnits="userSpaceOnUse">
        <path d="M100 200V.5M.5 .5H200" fill="none"></path>
      </pattern>
    </defs>
    <svg x="50%" y="-64" class="overflow-visible fill-[#ebf2fc]">
      <path
          d="M-100.5 0h201v201h-201Z M699.5 0h201v201h-201Z M499.5 400h201v201h-201Z M299.5 800h201v201h-201Z"
          stroke-width="0"></path>
    </svg>
    <rect width="100%" height="100%" stroke-width="0" fill="url(#83fd4e5a-9d52-42fc-97b6-718e5d7ee527)"></rect>
  </svg>

  <app-navigation-header container-class="text-center" title="پرسش‌های متداول"/>

  <div class="mx-auto max-w-3xl mb-8 p-3">
    <QuestionMarkCircleIcon class="w-14 h-14 mx-auto text-cyan-500"/>

    <h1 class="text-xl text-center px-3 pt-3 pb-8 font-iranyekan-light">
      جستجوی موضوع پرسش
    </h1>

    <form @submit.prevent="searchFaqHandler">
      <div class="flex sm:items-center flex-col sm:flex-row gap-2">
        <div class="relative w-full flex items-center">
          <base-input
              name="search"
              placeholder="موضوع مورد جستجو را وارد نمایید"
              class="w-full"
              klass="pl-11"
              :value="searchText"
              @input="(text) => {searchText = text}"
          />
          <div
              class="absolute z-[1] left-0 top-0 h-full px-2.5 group flex items-center justify-center cursor-pointer"
              @click="clearSearchHandler"
          >
            <XMarkIcon
                v-if="searchText.length"
                class="w-6 h-6 text-slate-400 group-hover:text-slate-600 transition"

            />
          </div>

        </div>
        <base-button
            type="submit"
            class="bg-cyan-500 border-cyan-500 px-5 shrink-0 flex items-center gap-3"
        >
          <span class="mx-auto">جستجو</span>
          <MagnifyingGlassIcon class="w-6 h-6"/>
        </base-button>
      </div>
    </form>
  </div>

  <div class="p-3 mb-12">
    <base-paginator
        container-class="flex flex-col gap-3"
        pagination-theme="modern"
        :per-page="10"
        v-model:search-text="searchText"
        v-model:items="faqs"
        :is-local="true"
        :local-search-filter-handler="searchFilterHandler"
        ref="paginatorCom"
    >
      <template #empty>
        <partial-empty-rows
            image="/empty-statuses/empty-note.svg"
            image-class="w-60"
            message="هیچ پرسشی ثبت نشده است"
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
import {nextTick, ref} from "vue";
import {
  MagnifyingGlassIcon,
  QuestionMarkCircleIcon,
  XMarkIcon,
} from "@heroicons/vue/24/outline/index.js";
import AppNavigationHeader from "../components/AppNavigationHeader.vue";
import BaseAccordion from "../components/base/BaseAccordion.vue";
import BasePaginator from "../components/base/BasePaginator.vue";
import LoaderListSingle from "../components/base/loader/LoaderListSingle.vue";
import PartialEmptyRows from "../components/partials/PartialEmptyRows.vue";
import BaseInput from "../components/base/BaseInput.vue";
import BaseButton from "../components/base/BaseButton.vue";

const paginatorCom = ref(null)

const searchFilterHandler = (item, text) => {
  if (!text.trim()) return true
  return (
      item.question.indexOf(text) !== -1 ||
      item.keywords.filter(k => k.indexOf(text) !== -1).length
  )
}

const searchText = ref('')
const faqs = ref([
  {
    id: 1,
    question: 'چطور می‌توانم سفارشم را پیگیری کنم؟',
    answer: 'وارد سایت دیجی‌کالا شوید. روی گزینه سفارش‌های من کلیک کنید. در این قسمت با کلیک روی جزییات می‌توانید سفارش خود را ببینید. می‌توانید در این قسمت روند آماده‌سازی و مراحل ارسال سفارش خود را پیگیری کنید.',
    keywords: ['سفارش', 'آماده سازی', 'پیگیری سفارش'],
  },
  {
    id: 2,
    question: 'هزینه روش‌های ارسال با اشتراک دیجی‌پلاس چقدر است؟',
    answer: 'اعضای دیجی‌پلاس می‌توانند به ازای هر ماه اشتراک، چهار ارسال رایگان بر روی مرسوله های عادی داشته باشند، به جز ارسال توسط فروشنده ، باربری و سفارش های سوپر مارکتی زیر ۸۰ هزار تومان.',
    keywords: ['اشتراک', 'دیجی‌پلاس'],
  },
  {
    id: 3,
    question: 'چطور میتوانم سفارشم را لغو کنم ؟',
    answer: 'شما میتوانید با مراجعه به پروفایل خود سفارش یا مرسوله ایی که از ارسال آن منصرف شدید را لغو نمایید. میتوانید برای مشاهده روند لغو سفارش به توضیحات تکمیلی مراجعه کنید.',
    keywords: ['لغو', 'لغو سفارش', 'انصراف از سفارش', 'روند انصراف'],
  },
  {
    id: 4,
    question: 'میتوانم سفارشم را بصورت اقساطی ( اعتباری ) پرداخت کنم؟',
    answer: 'بله، دیجی کالا تسهیلاتی را فراهم کرده، شما میتوانید تا سقف 20 میلیون تومان به صورت اقساط با بازپرداخت 6 ، 9 و 12 ماهه از سایت دیجی کالا خرید کنید.',
    keywords: ['اقساط', 'قسطی'],
  },
  {
    id: 5,
    question: 'هزینه ی ارسال در دیجی کالا چگونه محاسبه میشود؟',
    answer: 'هزینه ارسال بر اساس شیوه ارسال متفاوت است و در زمان ثبت سفارش نمایش داده می شود.',
    keywords: ['هزینه ارسال', 'هزینه'],
  },
])

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
</script>

<style scoped>

</style>
