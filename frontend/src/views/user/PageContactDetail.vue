<template>
  <partial-card class="border-0 p-3">
    <template #body>
      <base-loading-panel
          :loading="loading"
          type="content"
      >
        <template #content>
          <ul class="grid grid-cols-1 lg:grid-cols-3 gap-3">
            <li class="sm:flex sm:items-center">
              <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">ارسال شده در تاریخ:</span>
              <div class="mt-1 sm:mt-0 sm:inline-block">
                {{ contact?.created_at ?? '-' }}
              </div>
            </li>
          </ul>

          <div class="flex flex-col gap-3 shadow-md bg-white p-6 mt-3 border border-slate-50 leading-loose">
            <h3
                v-if="contact"
                class="iranyekan-bold text-lg"
            >
              {{ contact?.title }}
            </h3>

            <div>{{ contact?.message ?? '-' }}</div>
          </div>

          <div
              v-if="contact?.answer"
              class="flex flex-col gap-3 shadow-md bg-indigo-50 p-6 mt-3 border border-slate-50 leading-loose"
          >
            {{ contact?.answer ?? '-' }}
          </div>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {UserPanelContactAPI} from "@/service/APIUserPanel.js";

const idParam = getRouteParamByKey('id')

const loading = ref(true)
const contact = ref(null)

onMounted(() => {
  UserPanelContactAPI.fetchById(idParam.value, {
    success: (response) => {
      contact.value = response.data
      loading.value = false
    }
  })
})
</script>
