<template>
  <base-dialog container-klass="max-w-4xl">
    <template #button="{open}">
      <button
        v-tooltip.bottom-start="'اعلانات'"
        class="relative h-[40px] rounded-lg py-2 px-2 border-0 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center"
        type="button"
        @click="open"
      >
        <Square3Stack3DIcon
          :class="{
            'animate-bounce text-pink-700': notificationStore.hasNewNotification,
          }"
          class="size-6"
        />

        <span v-if="notificationStore.hasNewNotification"
              class="absolute rounded-full bg-pink-400 w-2 h-2 z-[1] -top-1 -right-1"></span>
      </button>
    </template>

    <template #title>
      <div class="flex flex-wrap items-center justify-between gap-3 pl-6">
        <span>اعلانات</span>

        <base-button
          :disabled="notificationOperationLoading"
          class="text-xs !text-black !py-1 border-2 hover:bg-slate-100"
          type="button"
          @click="loadNotificationsHandler"
        >
          <VTransitionFade>
            <loader-circle
              v-if="notificationOperationLoading"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-full h-full top-0 left-0"
              spinner-klass="!w-6 !h-6"
            />
          </VTransitionFade>

          <span>بارگذاری مجدد اعلانات</span>
        </base-button>
      </div>
    </template>

    <template #body="{close}">
      <base-paginator
        ref="notificationPaginatorRef"
        v-model:items="notifications"
        :path="getPath"
        :number-of-loaders="5"
        :per-page="50"
        container-class="flex flex-col gap-3"
        pagination-theme="modern"
        pagination-container-class="bg-white sticky bottom-0"
        @items-loaded.once="itemsLoadedHandler"
      >
        <template #empty>
          <partial-empty-rows
            image="/images/empty-statuses/empty-notification.svg"
            image-class="w-60"
            message="هیچ اعلانی برای شما وجود ندارد"
          />
        </template>

        <template #item="{item}">
          <partial-card
            :class="{
              '!bg-indigo-100 border-indigo-400': !item.read_at
            }"
            class="p-3 text-sm border-2"
          >
            <template #body>
              <div class="flex flex-wrap items-center gap-2.5 relative pr-2.5">
                <div
                  :style="'background-color:' + getPriorityColor(item.data) + ';'"
                  class="absolute rounded-full w-1 h-5/6 top-1/2 -translate-y-1/2 -right-1"
                ></div>

                <h1 class="text-black font-iranyekan-bold">
                  {{ item.data.type_value }}
                </h1>
                <p class="text-slate-700 text-sm styling-paragraph leading-relaxed">
                  {{ item.data.message }}
                </p>
                <span class="text-xs text-slate-400 mr-auto">{{ item.created_at }}</span>
              </div>
            </template>
          </partial-card>
        </template>

        <template #loading>
          <div
            class="bg-white py-6 px-6 md:py-4 space-y-4 border border-slate-200 divide-y divide-slate-200 rounded-lg shadow animate-pulse dark:divide-slate-700 dark:border-slate-700"
            role="status"
          >
            <div class="flex items-center justify-between gap-2.5">
              <div class="h-2.5 bg-slate-400 rounded-full dark:bg-slate-600 w-24"></div>
              <div class="w-32 h-2 bg-slate-300 rounded-full dark:bg-slate-700"></div>
            </div>
            <span class="sr-only">در حال بارگذاری...</span>
          </div>
        </template>
      </base-paginator>
    </template>
  </base-dialog>
</template>

<script setup>
import {inject, ref} from "vue";
import BaseDialog from "@/components/base/BaseDialog.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {Square3Stack3DIcon} from "@heroicons/vue/24/outline/index.js";
import {NotificationAPI} from "@/service/APINotification.js";
import BasePaginator from "@/components/base/BasePaginator.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {apiRoutes} from "@/router/api-routes.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";

const notificationStore = inject('notificationStore')

const notificationPaginatorRef = ref(null)
const notificationOperationLoading = ref(false)
const getPath = apiRoutes.admin.notification.index
const notifications = ref([])

function loadNotificationsHandler() {
  if (notificationPaginatorRef.value) {
    if (
      notificationPaginatorRef.value?.isLoading.value ||
      notificationOperationLoading.value
    ) return

    notificationOperationLoading.value = true

    if (notificationPaginatorRef.value?.goToPage) {
      notificationPaginatorRef.value.goToPage(0)
    }

    notificationOperationLoading.value = false
  }
}

function itemsLoadedHandler() {
  NotificationAPI.markAllAsRead({
    success() {
      return false
    },
    error() {
      return false
    },
  })
}

const priorityColors = {
  very_high: '#ef4444',
  high: '#f59e0b',
  normal: '#06b6d4',
  low: '#22c55e',
}

function getPriorityColor(item) {
  return !item.priority || +item.priority === 0
    ? priorityColors.normal
    : (
      item.priority < 0
        ? priorityColors.low
        : (
          item.priority > 0 && item.priority < 5
            ? priorityColors.high
            : priorityColors.high
        )
    )
}
</script>

<style scoped>
.styling-paragraph span,
.styling-paragraph strong {
  margin-left: 0.5rem /* 8px */;
  margin-right: 0.5rem /* 8px */;
}

.styling-paragraph span {
  letter-spacing: 0.1em;
}

.styling-paragraph strong {
  white-space: nowrap;

  padding-left: 0.5rem /* 8px */;
  padding-right: 0.5rem /* 8px */;

  border-radius: 0.25rem /* 4px */;

  font-size: 0.875rem /* 14px */;
  line-height: 1.25rem /* 20px */;

  font-family: 'IRANYekanWeb Bold', Arial, sans-serif !important;

  --tw-bg-opacity: 1;
  border: 2px solid rgb(129 140 248 / var(--tw-bg-opacity));
  background-color: rgb(129 140 248 / 0.1);
}
</style>
