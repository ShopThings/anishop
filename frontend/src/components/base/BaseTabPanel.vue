<template>
    <TabGroup :defaultIndex="defaultIdx" @change="changeTab">
        <TabList class="flex flex-wrap space-x-1 rounded-md bg-blue-900/20 p-1">
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
                        'grow rounded p-2.5 text-sm font-medium leading-5 text-blue-700',
                        'ring-white ring-opacity-60 ring-offset-2 ring-offset-indigo-400 focus:outline-none focus:ring-2',
                        selected
                            ? 'bg-white shadow'
                            : 'text-secondary hover:bg-white/[0.2] hover:text-blue-600',
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
                    'rounded-md bg-white p-3',
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
import {computed} from "vue";

const props = defineProps({
    tabs: {
        type: Object,
        required: true,
    },
    defaultIndex: {
        default: 0,
    },
})
const emit = defineEmits(['change', 'update:defaultIndex'])

const defaultIdx = computed({
    get() {
        return props.defaultIndex
    },
    set(value) {
        emit('update:defaultIndex', value)
    },
})

function changeTab(index) {
    emit('change', index)
}
</script>

<style scoped>

</style>
