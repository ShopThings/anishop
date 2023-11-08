<template>
    <base-accordion
        :open="open"
        panel-class="border-b border-slate-100 !p-4"
        btn-class="font-iranyekan-bold text-black hover:bg-opacity-60 hover:bg-slate-50 focus-visible:ring-black/50 py-5 border-b border-slate-100 rounded-none"
    >
        <template #button>
            <slot></slot>
        </template>

        <template #panelClosed>
            <template v-if="slots['panelClosed']">
                <slot name="panelClosed"></slot>
            </template>
            <template v-else>
                <div
                    v-if="showSelectedItems && displayingSelectedItems && displayingSelectedItems.length > 0"
                    class="px-3 pb-2 mt-[-2px] bg-white flex flex-wrap gap-1.5 border-b border-slate-100"
                >
                    <div
                        v-for="(i, key, idx) in (displayingSelectedItems.selectedItems)"
                        :key="idx"
                        class="text-xs text-slate-400"
                    >
                        <span>{{ i[itemTextKey] }}</span>
                        <span
                            v-if="idx < (displayingSelectedItems.length - 1)"
                            class="mx-0.5">،</span>
                    </div>
                </div>
            </template>
        </template>

        <template #panel>
            <template v-if="slots['panel']">
                <slot name="panel"></slot>
            </template>

            <ul
                v-else
                :class="panelContainerClass"
            >
                <li
                    v-for="(item, idx) in items"
                    :key="item?.id || idx"
                    :class="itemContainerClass"
                    class="flex items-center gap-3"
                >
                    <div
                        v-if="['multi', 'single'].indexOf(type) !== -1"
                        class="shrink-0"
                    >
                        <base-radio
                            v-if="type === 'single'"
                            :id="`${itemUniqueKeyText}-item${item?.id || idx}`"
                            :value="item[itemKey]"
                            :name="`rd-${itemUniqueKeyText}`"
                            v-model="selectedItems"
                        />
                        <base-checkbox
                            v-else-if="type === 'multi'"
                            :id="`${itemUniqueKeyText}-item${item?.id || idx}`"
                            :name="`chk${idx}-${itemUniqueKeyText}`"
                            v-model="selectedItems[item?.id || idx]"
                        />
                    </div>
                    <template v-else>
                        <slot name="type" :item="item"></slot>
                    </template>

                    <label
                        :for="`${itemUniqueKeyText}-item${item?.id || idx}`"
                        class="grow cursor-pointer"
                        :class="[!slots['item'] ? 'py-2 px-3 hover:bg-slate-50 transition rounded-full my-1' : 'p-0 m-0']"
                    >
                        <slot name="item" :item="item">{{ item[itemTextKey] || '' }}</slot>
                    </label>
                </li>
            </ul>
        </template>
    </base-accordion>
</template>

<script setup>
import BaseAccordion from "../../base/BaseAccordion.vue";
import {computed, useSlots} from "vue";
import BaseCheckbox from "../../base/BaseCheckbox.vue";
import BaseRadio from "../../base/BaseRadio.vue";

const props = defineProps({
    panelContainerClass: String,
    itemContainerClass: String,
    type: {
        type: String,
        default: 'custom',
        validator: (value) => {
            return ['multi', 'single', 'custom'].indexOf(value) !== -1
        },
    },
    items: Array,
    itemKey: String,
    itemTextKey: String,
    itemUniqueKeyText: {
        type: String,
        required: true,
    },
    showSelectedItems: Boolean,
    displayingSelectedItems: {
        type: Object,
        validator: (value) => {
            return "length" in value && "selectedItems" in value
        },
    },
    selectedItems: {
        type: [Object, Array, String, Number],
        default: () => {
            return {}
        },
    },
    // accordion settings
    open: {
        type: Boolean,
        default: false,
    },
})
const emit = defineEmits(['update:items', 'update:selectedItems'])
const slots = useSlots()

const items = computed({
    get() {
        return props.items
    },
    set(value) {
        emit('update:items', value)
    },
})

const selectedItems = computed({
    get() {
        return props.selectedItems
    },
    set(value) {
        emit('update:selectedItems', value)
    },
})
</script>

<style scoped>

</style>
