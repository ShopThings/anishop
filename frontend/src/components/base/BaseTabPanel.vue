<template>
    <TabGroup :defaultIndex="defaultIndex" @change="changeTab">
        <TabList class="flex space-x-1 rounded-xl bg-blue-900/20 p-1">
            <Tab
                v-for="(tab, idx) in tabs"
                as="template"
                :key="idx"
                :disabled="!!tab.disabled"
                v-slot="{ selected }"
            >
                <slot name="button">
                    <button
                        :class="[
                        'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-700',
                        'ring-white ring-opacity-60 ring-offset-2 ring-offset-indigo-400 focus:outline-none focus:ring-2',
                        selected
                            ? 'bg-white shadow'
                            : 'text-secondary hover:bg-white/[0.12] hover:text-blue-600',
                    ]"
                        v-html="tab.text"
                    >
                    </button>
                </slot>
            </Tab>
        </TabList>

        <TabPanels class="mt-2">
            <TabPanel
                v-for="tab in Object.keys(tabs)"
                :key="tab"
                :class="[
                    'rounded-xl bg-white p-3',
                    'ring-white ring-opacity-60 ring-offset-2 ring-offset-indigo-300 focus:outline-none focus:ring-2 border',
                  ]"
            >
                <slot :name="tab"></slot>
            </TabPanel>
        </TabPanels>
    </TabGroup>
</template>

<script setup>
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue"

const props = defineProps({
    tabs: {
        type: Object,
        required: true,
    },
    defaultIndex: {
        default: 0,
    },
})
const emit = defineEmits(['change'])

function changeTab(index) {
    if (props.defaultIndex.value) props.defaultIndex.value = index
    emit('change', index)
}
</script>

<style scoped>

</style>
