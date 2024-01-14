<template>
  <partial-card>
    <template #header>
      جزئیات شکایت -
      <span
        v-if="complaint?.id"
        class="text-teal-600"
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
                <span class="block mt-1 sm:mt-0 sm:inline-block">{{ complaint?.user ?? '-' }}</span>
              </li>
              <li class="sm:flex sm:items-center">
                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">ارسال شده در تاریخ:</span>
                <span class="block mt-1 sm:mt-0 sm:inline-block">{{ complaint?.created_at ?? '-' }}</span>
              </li>
              <li class="sm:flex sm:items-center">
                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">موبایل:</span>
                <span class="block mt-1 sm:mt-0 sm:inline-block">{{ complaint?.mobile ?? '-' }}</span>
              </li>
            </ul>

            <div class="rounded bg-gray-100 p-3 mt-3 border leading-loose">
              {{ complaint?.description ?? '-' }}
            </div>
          </template>
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useRoute, useRouter} from "vue-router";

const router = useRouter()
const route = useRoute()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const loading = ref(false)
const complaint = ref(null)

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.complaints.show, {complaint: idParam.value}), null, {
  //     success: (response) => {
  //         complaint.value = response.data
  //
  //         loading.value = false
  //     }
  // })
})
</script>
