<template>
  <div class="px-3 pb-3 flex flex-wrap items-center content-stretch justify-between">
    <div class="mt-3 ml-3">
      تعداد موارد انتخاب شده:
      <span class="bg-sky-200 text-sky-700 rounded-md py-1 px-2 inline-block my-1">
          <span class="mr-2 inline-block">{{ props.items.length }}</span>
          مورد
      </span>
    </div>
    <div class="flex">
      <base-button
        v-tooltip.top="'حذف انتخاب‌ها'"
        class="cursor-pointer rounded-md border py-2 px-3 rounded-r-lg rounded-l-none transition hover:bg-slate-200 bg-slate-100 border-slate-400 !text-black text-sm mt-3"
        @click="clearSelectedItems"
      >
        <outline.BackspaceIcon class="h-6 w-6"/>
      </base-button>

      <base-dialog container-klass="layout-max-w overflow-hidden">
        <template #button="{open}">
          <base-button
            v-tooltip.top="'مشاهده موارد انتخاب شده'"
            :class="props.operations.length ? 'rounded-none' : 'rounded-l-lg'"
            class="cursor-pointer rounded-md border py-2 px-3 transition hover:bg-slate-200 bg-slate-100 border-slate-400 !text-black text-sm mt-3"
            @click="open"
          >
            <outline.QueueListIcon class="h-6 w-6"/>
          </base-button>
        </template>

        <template #title>
          <div class="mb-2">
            مشاهده موارد انتخاب شده
          </div>
        </template>

        <template #body="{close}">
          <slot :close="close" :items="props.items" name="selectedItems"></slot>
        </template>
      </base-dialog>

      <template v-for="(operation, key) in props.operations">
        <base-floating-drop-down
          :items="operation.children ?? {}"
          :shift="false"
          placement="bottom-end"
        >
          <template #button>
            <base-button
              v-tooltip.top="operation.btn.tooltip ? operation.btn.tooltip : ''"
              :class="[
                  key !== props.operations.length - 1 ? 'rounded-none' : 'rounded-l-lg rounded-r-none',
                  operation.btn.class ? operation.btn.class : '',
              ]"
              class="text-sm mt-3 flex gap-2 items-center"
              @click="operationClicked(operation)"
            >
              <component
                :is="outline[operation.btn.icon]"
                v-if="operation.btn.icon"
                class="h-6 w-6"
              />
              <span v-if="operation.btn.text">{{ operation.btn.text }}</span>
            </base-button>
          </template>

          <template #item="{item, hide}">
            <button
              class="flex gap-2 items-center w-full p-2 text-sm transition hover:bg-gray-100 rounded-md"
              :class="item.btn.class ? item.btn.class : ''"
              type="button"
              @click="operationClicked(item, hide)"
            >
              <component
                :is="outline[item.btn.icon]"
                v-if="item.btn.icon"
                class="h-5 w-5"
              />
              <span v-if="item.btn.text" class="grow text-right">{{ item.btn.text }}</span>
            </button>
          </template>
        </base-floating-drop-down>
      </template>
    </div>
  </div>
</template>

<script setup>
import * as outline from "@heroicons/vue/24/outline/index.js";
import BaseButton from "../BaseButton.vue";
import {isProxy, toRaw} from "vue";
import BaseDialog from "../BaseDialog.vue";
import BaseFloatingDropDown from "../BaseFloatingDropDown.vue";

const props = defineProps({
  items: {
    type: Array,
    default: () => {
      return []
    },
  },
  operations: {
    type: Object,
    default: () => {
      return {}
    },
  },
})

const emit = defineEmits(['clear-selected-items'])

function clearSelectedItems() {
  emit('clear-selected-items')
}

function operationClicked(operation, hide) {
  if (operation?.event?.click) {
    if (hide && operation.btn?.closeOnClick !== false)
      hide()
    operation.event.click(isProxy(props.items) ? toRaw(props.items) : props.items)
  }
}
</script>
