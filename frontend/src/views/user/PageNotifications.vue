<template>
  <base-paginator
    v-model:total="totalNotifications"
    :path="getPath"
    :per-page="10"
    container-class="flex flex-col gap-3"
    pagination-theme="modern"
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
        :class="[
          !item.read_at ? 'border !bg-violet-100 border-violet-400' : 'border-0',
        ]"
        class="p-3 text-sm"
      >
        <template #body>
          <div class="flex flex-wrap items-center gap-2.5 relative pr-2.5">
            <div
              :style="'background-color:' + getPriorityColor(item.data) + ';'"
              class="absolute rounded-full w-1 h-3/4 top-1/2 -translate-y-1/2 -right-1"
            ></div>

            <h1 class="text-black font-iranyekan-bold">
              {{ item.data.type_value }}
            </h1>
            <p
              class="text-slate-700 text-sm styling-paragraph leading-relaxed"
              v-html="item.data.message"
            >
            </p>
            <span class="text-xs text-slate-400 mr-auto">{{ item.created_at }}</span>
          </div>
        </template>
      </partial-card>
    </template>

    <template #loading>
      <div
        class="bg-white py-6 px-6 md:py-4 space-y-4 border border-slate-200 divide-y divide-slate-200 rounded-lg shadow animate-pulse"
        role="status"
      >
        <div class="flex items-center justify-between gap-2.5">
          <div class="h-2.5 bg-slate-200 rounded-full w-24"></div>
          <div class="w-32 h-2 bg-slate-200 rounded-full"></div>
        </div>
        <span class="sr-only">در حال بارگذاری...</span>
      </div>
    </template>
  </base-paginator>
</template>

<script setup>
import {inject, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BasePaginator from "@/components/base/BasePaginator.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import {apiRoutes} from "@/router/api-routes.js";
import {UserNotificationAPI} from "@/service/APINotification.js";

const notificationsStore = inject('notificationStore')

const getPath = apiRoutes.user.info.notification.index
const totalNotifications = ref(0)

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

function itemsLoadedHandler() {
  UserNotificationAPI.markAllAsRead({
    silent: true,
    success() {
      notificationsStore.$reset()
    },
  })
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
