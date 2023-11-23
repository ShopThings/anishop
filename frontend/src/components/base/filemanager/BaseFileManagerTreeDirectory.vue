<template>
    <partial-dialog
        v-model:open="isOpen"
        @close="$emit('close')"
    >
        <template #title>
            <div class="flex items-center">
                <FolderIcon class="h-6 w-6 ml-2"/>
                پوشه‌ها
            </div>
            <div class="flex flex-wrap justify-between items-center">
                <div class="text-xs text-gray-400 mt-2 grow">
                    <span class="inline-block ml-2">مسیر:</span>
                    <span dir="ltr" class="inline-block">{{ path }}</span>
                </div>
                <div class="text-left mt-2 shrink-0">
                    <base-animated-button
                        v-tooltip.bottom="'بارگذاری مجدد'"
                        class="bg-gray-200 !text-black !p-1"
                    >
                        <template #icon="{klass}">
                            <ArrowPathIcon class="h-4 w-4" :class="klass"/>
                        </template>
                    </base-animated-button>
                </div>
            </div>
        </template>

        <template #body>
            <partial-tree-directory-search
                v-if="items.length"
                @search="search"
                @clear-filter="clearSearch"
            />

            <div
                v-if="slots['extra']"
                class="mb-4 flex flex-row-reverse justify-between"
            >
                <slot name="extra"></slot>
            </div>

            <partial-tree-directory
                v-if="items.length"
                :items="items"
                :disk="disk"
                @selection-change="changeSelectedDirectory"
            />
            <div
                v-else
                class="text-gray-400"
            >
                هیچ پوشه‌ای وجود ندارد!
            </div>
        </template>
    </partial-dialog>
</template>

<script setup>
import {ref, useSlots, watchEffect} from "vue";
import {FolderIcon, ArrowPathIcon} from '@heroicons/vue/24/outline';
import PartialDialog from "../../partials/PartialDialog.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";
import PartialTreeDirectory from "../../partials/filemanager/PartialTreeDirectory.vue";
import BaseAnimatedButton from "../BaseAnimatedButton.vue";
import PartialTreeDirectorySearch from "../../partials/filemanager/PartialTreeDirectorySearch.vue";

const props = defineProps({
    open: Boolean,
    path: {
        type: String,
        required: true,
    },
    disk: String,
})

const emit = defineEmits(['close', 'select-change'])
const slots = useSlots()

const isOpen = ref(props.open)
const items = ref({})

function getTree() {
    useRequest(apiRoutes.admin.files.tree, {
        params: {
            path: props.path,
            disk: props.disk,
        },
    }, {
        success: (response) => {
            items.value = response.data
            return false
        },
        error: () => {
            return false
        },
    })
}

watchEffect(() => {
    getTree()
})

function changeSelectedDirectory(item) {
    emit('select-change', item)
}

function search(value) {
    useRequest(apiRoutes.admin.files.tree, {
        params: {
            path: props.path,
            disk: props.disk,
            search: value,
        },
    }, {
        success: (response) => {
            items.value = response.data
            return false
        },
        error: () => {
            return false
        },
    })
}

function clearSearch(resetField, name, value) {
    resetField(name)
    getTree()
}
</script>

<style scoped>

</style>
