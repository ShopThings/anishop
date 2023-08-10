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
                @click="clearSelectedItems"
                v-tooltip.top="'حذف انتخاب‌ها'"
                class="cursor-pointer rounded-md border py-2 px-3 rounded-r-lg rounded-l-none transition hover:bg-opacity-90 bg-slate-300 border-slate-400 !text-black text-sm mt-3"
            >
                <outline.BackspaceIcon class="h-6 w-6"/>
            </base-button>

            <base-dialog container-klass="overflow-auto">
                <template #button="{open}">
                    <base-button
                        @click="open"
                        v-tooltip.top="'مشاهده موارد انتخاب شده'"
                        class="cursor-pointer rounded-md border py-2 px-3 transition hover:bg-opacity-90 bg-slate-300 border-slate-400 !text-black text-sm mt-3"
                        :class="props.operations.length ? 'rounded-none' : 'rounded-l-lg'"
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
                    <slot name="selectedItems" :items="props.items" :close="close"></slot>
                </template>
            </base-dialog>

            <template v-for="(operation, key) in props.operations">
                <base-floating-drop-down
                    placement="bottom-end"
                    :shift="false"
                    :items="operation.children ?? {}"
                >
                    <template #button>
                        <base-button
                            @click="operationClicked(operation)"
                            v-tooltip.top="operation.btn.tooltip ? operation.btn.tooltip : ''"
                            class="text-sm mt-3 flex items-center"
                            :class="[
                                key != props.operations.length - 1 ? 'rounded-none' : 'rounded-l-lg rounded-r-none',
                                operation.btn.class ? operation.btn.class : '',
                            ]"
                        >
                            <component v-if="operation.btn.icon"
                                       :is="outline[operation.btn.icon]"
                                       class="h-6 w-6"
                                       :class="operation.btn.text ? 'ml-2' : ''"
                            />
                            <span v-if="operation.btn.text">{{ operation.btn.text }}</span>
                        </base-button>
                    </template>

                    <template #item="{item, hide}">
                        <div
                            @click="operationClicked(item, hide)"
                            class="flex items-center w-full p-2 text-sm transition hover:bg-gray-100 rounded-md cursor-pointer"
                            :class="item.btn.class ? item.btn.class : ''"
                        >
                            <component v-if="item.btn.icon"
                                       :is="outline[item.btn.icon]"
                                       class="h-5 w-5"
                                       :class="item.btn.text ? 'ml-2' : ''"
                            />
                            <span class="grow" v-if="item.btn.text">{{ item.btn.text }}</span>
                        </div>
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

<style scoped>

</style>
