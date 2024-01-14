<template>
  <partial-card class="border-0 p-3">
    <template #body>
      <base-loading-panel
        :loading="loading"
        type="content"
      >
        <template #content>
          <ul class="flex items-center flex-wrap gap-3">
            <li class="sm:flex sm:items-center">
              <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">عنوان:</span>
              <span class="block mt-1 sm:mt-0 sm:inline-block">{{ contact?.title ?? '-' }}</span>
            </li>
            <li class="sm:flex sm:items-center">
              <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">ارسال شده در تاریخ:</span>
              <span class="block mt-1 sm:mt-0 sm:inline-block">{{ contact?.created_at ?? '-' }}</span>
            </li>
          </ul>

          <div class="rounded bg-gray-100 p-3 mt-3 border leading-loose">
            {{ contact?.description ?? '-' }}
          </div>
        </template>
      </base-loading-panel>
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
const contact = ref(null)

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.contacts.show, {contact: idParam.value}), null, {
  //     success: (response) => {
  //         contact.value = response.data
  //
  //         loading.value = false
  //     }
  // })
})
</script>
