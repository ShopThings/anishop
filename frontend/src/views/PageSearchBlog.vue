<template>
  <app-navigation-header title="جستجوی بلاگ"/>

  <div class="mb-6 p-3">
    <div class="flex flex-col lg:flex-row-reverse gap-6 sticky-container">
      <div class="grow">
        <base-paginator
            ref="blogPaginatorRef"
            v-model:items="blogs"
            :path="getSearchPath"
            :order="blogOrder"
            :per-page="blogPerPage"
            :extra-search-params="searchParams"
            :show-pagination-detail="true"
            :show-search="true"
            :search-text="route.query?.q || ''"
            :scroll-margin-top="-130"
            container-class="flex flex-wrap"
            item-container-class="w-full xl:w-1/2 ml-[-1px] mt-[-1px]"
            pagination-theme="modern"
            @order-changed="orderChangeHandler"
        >
          <template #empty>
            <partial-empty-rows
              image="/images/empty-statuses/empty-blog.svg"
                image-class="w-60"
                message="هیچ نوشته‌ای پیدا نشد!"
            />
          </template>

          <template #item="{item}">
            <blog-card
                :blog="item"
                container-class=""
            />
          </template>

          <template #loading>
            <div class="border-b">
              <loader-list-single-blog size="large"/>
            </div>
          </template>
        </base-paginator>
      </div>

      <Vue3StickySidebar
          v-if="hasPopularCategories || hasBlogArchives"
          :bottom-spacing="20"
          :min-width="1024"
          :top-spacing="114"
          class="shrink-0 lg:w-80"
          containerSelector=".sticky-container"
          innerWrapperSelector='.sidebar__inner'
      >
        <div class="flex flex-col gap-6">
          <partial-card
              v-if="hasPopularCategories"
              class="border-0 flex flex-col"
          >
            <template #body>
              <partial-general-title
                  title="دسته‌بندی‌های پرطرفدار"
                  type="side"
              />

              <app-side-categories-blog @loaded="(hasData) => {hasPopularCategories = hasData}"/>
            </template>
          </partial-card>

          <partial-card
              v-if="hasBlogArchives"
              class="border-0 flex flex-col"
          >
            <template #body>
              <partial-general-title
                  title="آرشیو نوشته‌ها"
                  type="side"
              />

              <blog-side-archives @loaded="(hasData) => {hasBlogArchives = hasData}"/>
            </template>
          </partial-card>
        </div>
      </Vue3StickySidebar>
    </div>
  </div>
</template>

<script setup>
import {computed, inject, onMounted, ref} from "vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import AppSideCategoriesBlog from "@/components/AppSideCategoriesBlog.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import BasePaginator from "@/components/base/BasePaginator.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import BlogCard from "@/components/blog/BlogCard.vue";
import LoaderListSingleBlog from "@/components/base/loader/LoaderListSingleBlog.vue";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import {BLOG_ORDER_TYPES} from "@/composables/constants.js";
import BlogSideArchives from "@/components/blog/BlogSideArchives.vue";
import {useRoute, useRouter} from "vue-router";
import {apiRoutes} from "@/router/api-routes.js";

const homeSettingStore = inject('homeSettingStore')

const blogPerPage = computed(() => {
  let bpp = +homeSettingStore.getBlogEachPage
  return !isNaN(bpp) ? bpp : 12
})
const getSearchPath = computed(() => {
  return apiRoutes.blogs.index
})

const blogPaginatorRef = ref(null)

const hasPopularCategories = ref(true)
const hasBlogArchives = ref(true)

//----------------------------
// Search Blogs
//----------------------------
const router = useRouter()
const route = useRoute()

const blogOrder = []
const searchParams = ref({})

// create orders
let counter = 1
for (const t in BLOG_ORDER_TYPES) {
  if (BLOG_ORDER_TYPES.hasOwnProperty(t)) {
    blogOrder.push({
      id: counter++,
      key: BLOG_ORDER_TYPES[t].value,
      text: BLOG_ORDER_TYPES[t].text,
    })
    searchParams.value.order = BLOG_ORDER_TYPES[t].value
  }
}
//

const blogs = ref([])

function orderChangeHandler(selected) {
  searchParams.value.order = selected.key
  router.push({query: Object.assign({}, route.query, searchParams.value)})
}

onMounted(() => {
  searchParams.value.category = route.query?.category
  searchParams.value.archive = route.query?.archive
  searchParams.value.tag = route.query?.tag
})
</script>
