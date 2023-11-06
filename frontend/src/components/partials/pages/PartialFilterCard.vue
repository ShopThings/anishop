<template>
  <base-accordion
      :open="open"
      panel-class="bg-white border-t border-t-slate-100"
      btn-class="font-iranyekan-bold text-black bg-white hover:bg-opacity-60 hover:shadow-sm focus-visible:ring-black/50 py-5 rounded-none"
  >
    <template #button>
      <slot></slot>
    </template>

    <template #panel>
      <ul :class="panelContainerClass">
        <li
            v-for="(item, idx) in items"
            :key="item?.id || idx"
            :ref="() => {
                        selectItems[idx] = false
                        forLabel = uniqueId('item' + idx)
                    }"
            :class="itemContainerClass"
        >
          <label
              :for="forLabel"
              class="grow m-0 p-0"
          >
            <slot name="item" :item="item">{{ itemTextKey || '' }}</slot>
          </label>

          <div
              v-if="['multi', 'single'].indexOf(type) !== -1"
              class="shrink-0"
          >
            <base-radio
                v-if="type === 'single'"
                :id="forLabel"
                :value="item[itemKey]"
                :name="'chk' + idx"
                v-model="selectItems[idx]"
            />
            <base-checkbox
                v-else-if="type === 'multi'"
                :id="forLabel"
                :name="'chk' + idx"
                v-model="selectItems[idx]"
            />
          </div>

          <template v-else>
            <slot name="type" :item="item"></slot>
          </template>
        </li>
      </ul>
    </template>
  </base-accordion>
</template>

<script setup>
import BaseAccordion from "../../base/BaseAccordion.vue";
import {computed, ref} from "vue";
import BaseCheckbox from "../../base/BaseCheckbox.vue";
import BaseRadio from "../../base/BaseRadio.vue";
import uniqueId from "lodash.uniqueid";

const props = defineProps({
  panelContainerClass: String,
  itemContainerClass: {
    type: String,
    default: 'flex items-center gap-3',
  },
  type: {
    type: String,
    default: 'custom',
    validator: (value) => {
      return ['multi', 'single', 'custom'].indexOf(value) !== -1
    },
  },
  items: {
    type: Array,
    required: true,
  },
  itemKey: {
    type: String,
    required: true,
  },
  itemTextKey: String,
  // accordion settings
  open: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['update:items'])

const items = computed({
  get() {
    return props.items
  },
  set(value) {
    emit('update:items', value)
  },
})

const selectItems = ref([])
let forLabel = ''
</script>

<style scoped>

</style>
