<template>
    <partial-dialog
        v-model:open="isOpen"
        @close="$emit('close')"
    >
        <template #title>
            <div class="flex items-center">
                <FolderIcon class="h-8 w-8 ml-2"/>
                پوشه‌ها
            </div>
            <div dir="ltr" class="text-xs text-gray-400">
                {{ path }}
            </div>
        </template>

        <template #body>
            <div class="mb-4 flex justify-between">
                <slot name="extra"></slot>

                <div>
                    <base-animated-button>
                        <template #icon="{klass}">

                        </template>
                    </base-animated-button>
                </div>
            </div>

            <partial-tree-directory
                :items="items"
                :disk="disk"
            />
        </template>
    </partial-dialog>
</template>

<script setup>
import {ref, watchEffect} from "vue";
import {FolderIcon} from '@heroicons/vue/24/outline';
import PartialDialog from "../../partials/PartialDialog.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";
import PartialTreeDirectory from "../../partials/filemanager/PartialTreeDirectory.vue";
import BaseAnimatedButton from "../BaseAnimatedButton.vue";

const props = defineProps({
    open: Boolean,
    path: {
        type: String,
        required: true,
    },
    disk: String,
})

const emit = defineEmits(['close'])

const isOpen = ref(props.open)

const items = ref({})

watchEffect(() => {
    useRequest(apiRoutes.admin.files.tree, {
        params: {
            path: props.path,
            disk: props.disk
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
})
</script>

<style scoped>

</style>
