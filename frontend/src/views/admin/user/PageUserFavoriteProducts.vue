<template>
  <base-loading-panel
      :loading="loading"
      type="content"
  >
    <template #content>
      <div class="bg-white mb-3 rounded-lg border p-3">
        نمایش محصولات مورد علاقه کاربر -
        <span
            v-if="user?.id"
            class="text-slate-400 text-base"
        ><partial-username-label v-if="user" :user="user"/></span>
      </div>

      <base-paginator
          v-model:items="favoriteProducts"
          :path="getPath"
          :path-replacement-params="{user: user?.id}"
          :per-page="10"
          container-class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3"
      >
        <template #item="{item}">
          <partial-card class="h-full">
            <template #body>
              <div class="flex items-center md:flex-col">
                <router-link
                    :to="{name: 'admin.product.detail', params: {slug: item.slug}}"
                    class="p-2 shrink-0"
                    target="_blank"
                >
                  <base-lazy-image
                      :alt="item.title"
                      :lazy-src="item.image.path"
                      :is-local="false"
                      class="!w-20 ml-3 mb-0 h-auto hover:scale-95 md:!w-full md:mb-3 md:ml-0 transition shrink-0"
                  />
                </router-link>
                <router-link
                    :to="{name: 'admin.product.detail', params: {slug: item.slug}}"
                    class="px-3 py-2 text-blue-600 hover:text-opacity-90 md:border-t"
                    target="_blank"
                >
                  {{ item.title }}
                </router-link>
              </div>
            </template>
          </partial-card>
        </template>

        <template #loading>
          <loader-card/>
        </template>
      </base-paginator>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {apiRoutes} from "@/router/api-routes.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BasePaginator from "@/components/base/BasePaginator.vue";
import LoaderCard from "@/components/base/loader/LoaderCard.vue";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {UserAPI} from "@/service/APIUser.js";

const idParam = getRouteParamByKey('id')

const loading = ref(true)
const user = ref(null)

const getPath = apiRoutes.admin.users.favoriteProducts
const favoriteProducts = ref([])

onMounted(() => {
  UserAPI.fetchById(idParam.value, {
    success: (response) => {
      user.value = response.data
      loading.value = false
    },
  })
})
</script>
