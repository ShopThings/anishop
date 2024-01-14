<template>
  <new-creation-guide-top route-name="user.address.add">
    <template #text>
      شما می‌توانید تا
      <span class="rounded bg-violet-200 px-2 text-violet-800 shadow-lg mx-1">حداکثر ۳ آدرس</span>
      در پنل خود ذخیره نمایید.
    </template>
    <template #buttonText>
      <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
      <span class="mx-auto">افزودن آدرس جدید</span>
    </template>
  </new-creation-guide-top>

  <div class="mt-6">
    <h2 class="text-slate-400 mb-1">
      آدرس‌های من
    </h2>

    <base-loading-panel
      type="list"
      :loading="loading"
    >
      <template #content>
        <div class="flex flex-col gap-6">
          <div
            v-if="addresses.length"
            v-for="(address, idx) in addresses"
            :key="idx"
            class="grid grid-cols-1 sm:grid-cols-4 gap-3"
          >
            <partial-card class="border-0 p-3 sm:col-span-2">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">نام گیرنده:</span>
                  <div class="text-sm">
                    محمد مهدی دهقان منشادی
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3 sm:col-span-2">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">شماره تماس:</span>
                  <div class="text-sm tracking-widest">
                    09179516271
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">استان:</span>
                  <div class="text-sm">
                    فارس
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">شهر:</span>
                  <div class="text-sm">
                    آباده
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3 sm:col-span-2">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">کد پستی:</span>
                  <div class="text-sm tracking-widest">
                    1234567891
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3 sm:col-span-4">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">آدرس:</span>
                  <div class="text-sm">
                    بلوار ۴۵ متری شهید چمران، خیابان جام جم کوچه سوم
                  </div>
                </div>
              </template>
            </partial-card>

            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:col-span-4">
              <base-button
                type="link"
                :to="{name: 'user.address.edit', params: {id: 1}}"
                class="text-sm !text-orange-500 border-2 border-orange-400 px-4 flex gap-2 items-center hover:bg-orange-50 !py-1"
              >
                <span class="mx-auto">ویرایش</span>
                <PencilIcon class="h-5 w-5"/>
              </base-button>

              <base-button
                type="button"
                class="text-sm bg-rose-500 border-2 border-rose-500 px-4 flex gap-2 items-center !py-1"
              >
                <span class="mx-auto">حذف</span>
                <TrashIcon class="h-5 w-5"/>
              </base-button>
            </div>

            <hr
              v-if="idx !== (addresses.length - 1)"
              class="w-48 h-1 mx-auto my-4 bg-slate-200 border-0 rounded md:my-10 dark:bg-gray-700 col-span-4"
            >
          </div>

          <partial-empty-rows
            v-else
            image="/empty-statuses/empty-address.svg"
            image-class="w-60"
            message="هیچ آدرسی ذخیره نشده است"
          />
        </div>
      </template>
    </base-loading-panel>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {PencilIcon, PlusIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue";

const loading = ref(false)
const addresses = ref([])

onMounted(() => {
  // useRequest(apiRoutes.user.addresses.index, null, {
  //     success: (response) => {
  //         addresses.value = response.data
  //
  //         loading.value = false
  //     },
  // })
})
</script>
