<template>
  <partial-card>
    <template #header>
      جزئیات شکایت -
      <span
          v-if="complaint?.id"
          class="text-slate-400 text-base"
      >{{ complaint?.title }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="content"
        >
          <template #content>
            <ul class="grid grid-cols-1 lg:grid-cols-3 gap-3">
              <li class="sm:flex sm:items-center">
                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">ارسال شده توسط:</span>
                <div class="mt-1 sm:mt-0 sm:inline-block">
                  <template v-if="complaint?.created_by">
                    <router-link
                        :to="{name: 'admin.user.profile', params: {id: complaint?.created_by.id}}"
                        class="text-blue-600 hover:text-opacity-80"
                    >
                      <partial-username-label :user="complaint?.created_by"/>
                    </router-link>
                    -
                    ({{ complaint?.name ?? '-' }})
                  </template>
                  <template v-else>
                    {{ complaint?.name ?? '-' }}
                  </template>
                </div>
              </li>
              <li class="sm:flex sm:items-center">
                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">ارسال شده در تاریخ:</span>
                <div class="mt-1 sm:mt-0 sm:inline-block">
                  {{ complaint?.created_at ?? '-' }}
                </div>
              </li>
              <li class="sm:flex sm:items-center">
                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">موبایل:</span>
                <div class="mt-1 sm:mt-0 sm:inline-block">
                  {{ complaint?.mobile ?? '-' }}
                </div>
              </li>
            </ul>

            <div class="flex flex-col gap-3 shadow-md bg-white p-6 mt-3 border border-slate-50 leading-loose">
              <h3
                  v-if="complaint"
                  class="iranyekan-bold text-lg"
              >
                {{ complaint?.title }}
              </h3>

              <div>{{ complaint?.description ?? '-' }}</div>
            </div>
          </template>
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {ComplaintAPI} from "@/service/APIPage.js";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";

const idParam = getRouteParamByKey('id')

const loading = ref(true)
const complaint = ref(null)

onMounted(() => {
  ComplaintAPI.fetchById(idParam.value, {
    success: (response) => {
      setFormFields(response.data)

      // make complaint as seen
      ComplaintAPI.updateById(idParam.value, {
        is_seen: true,
      }, {
        success(response2) {
          setFormFields(response2.data)
          return false
        },
        error() {
          return false
        },
      })

      loading.value = false
    }
  })
})

function setFormFields(item) {
  complaint.value = item
}
</script>
