<template>
  <TabGroup :defaultIndex="defaultIdx" @change="changeTab">
    <TabList class="flex flex-wrap space-x-1 rounded-md bg-blue-900/20 p-1">
      <Tab
        v-for="[tabKey, tab] in Object.entries(tabs)"
        :key="tabKey"
        v-slot="{ selected }"
        :disabled="!!tab?.disabled"
        as="template"
      >
        <slot name="button">
          <button
            :class="[
                  'grow rounded p-2.5 text-sm font-medium leading-5 text-blue-700 cursor-pointer',
                  'ring-white ring-opacity-60 ring-offset-2 ring-offset-indigo-400 focus:outline-none focus:ring-2',
                  selected
                      ? 'bg-white shadow'
                      : 'text-secondary hover:bg-white/[0.2] hover:text-blue-600',
                  tabButtonExtraClass,
              ]"
          >
            <span class="flex items-center justify-between">
              <span>{{ tab?.text }}</span>
              <span
                v-if="tab?.button?.badgeCount"
                :class="[
                      'py-0.5 px-1 rounded-full mr-4 min-w-7 min-h-7',
                      selected ? 'border-2 border-primary text-blue-600' : 'border-2 border-white bg-rose-500 shadow text-white'
                  ]"
              >{{ tab?.button?.badgeCount }}</span>
            </span>
          </button>
        </slot>
      </Tab>
    </TabList>

    <TabPanels class="mt-2">
      <TabPanel
        v-for="[tabKey, tab] in Object.entries(tabs)"
        :key="tabKey"
        :class="[
            tabPanelExtraClass,
            'rounded-md bg-white p-3 border',
            'ring-white ring-opacity-60 ring-offset-2 ring-offset-indigo-300 focus:outline-none focus:ring-2',
          ]"
      >
        <slot :name="tabKey" :tab="tab"></slot>
      </TabPanel>
    </TabPanels>
  </TabGroup>
</template>

<script setup>
import {computed} from "vue";
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue"

const props = defineProps({
  tabs: {
    type: Object,
    required: true,
  },
  defaultIndex: {
    type: [Number, String],
    default: 0,
  },
  tabButtonExtraClass: String,
  tabPanelExtraClass: String,
})
const emit = defineEmits(['change', 'update:tabs', 'update:defaultIndex'])

const tabs = computed({
  get() {
    return props.tabs
  },
  set(value) {
    emit('update:tabs', value)
  },
})
const defaultIdx = computed({
  get() {
    return props.defaultIndex
  },
  set(value) {
    emit('update:defaultIndex', value)
  },
})

function changeTab(index) {
  emit('change', index, Object.values(tabs.value)[index] || null)
}
</script>
