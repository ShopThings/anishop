<template>
    <context-menu
        v-model:show="menuShow"
        :options="options"
    >
        <context-menu-item v-if="hasOperation('preview')"
                           @click="menuItemClick('preview')">
            <template #icon>
                <EyeIcon class="h-4 w-4"/>
            </template>
            <template #label>
                <span class="text-sm">پیش‌نمایش</span>
            </template>
        </context-menu-item>
        <context-menu-separator v-if="hasOperation('preview')"/>
        <context-menu-item v-if="hasOperation('download')"
                           @click="menuItemClick('download')">
            <template #icon>
                <ArrowDownTrayIcon class="h-4 w-4 text-emerald-500"/>
            </template>
            <template #label>
                <span class="text-sm text-emerald-500">دانلود</span>
            </template>
        </context-menu-item>
        <context-menu-separator v-if="hasOperation('download')"/>
        <context-menu-item v-if="hasOperation('paste')"
                           @click="menuItemClick('paste')">
            <template #icon>
                <ScissorsIcon class="h-4 w-4"/>
            </template>
            <template #label>
                <span class="text-sm">چسباندن (paste)</span>
            </template>
        </context-menu-item>
        <context-menu-item v-if="hasOperation('cut')"
                           @click="menuItemClick('cut')">
            <template #icon>
                <ScissorsIcon class="h-4 w-4"/>
            </template>
            <template #label>
                <span class="text-sm">جابجا کردن</span>
            </template>
        </context-menu-item>
        <context-menu-item v-if="hasOperation('copy')"
                           @click="menuItemClick('copy')">
            <template #icon>
                <DocumentDuplicateIcon class="h-4 w-4"/>
            </template>
            <template #label>
                <span class="text-sm">کپی کردن</span>
            </template>
        </context-menu-item>
        <context-menu-item v-if="hasOperation('rename')"
                           @click="menuItemClick('rename')">
            <template #icon>
                <PencilSquareIcon class="h-4 w-4"/>
            </template>
            <template #label>
                <span class="text-sm">تغییر نام</span>
            </template>
        </context-menu-item>
        <context-menu-item v-if="hasOperation('delete')"
                           class="text-rose-500"
                           @click="menuItemClick('delete')">
            <template #icon>
                <TrashIcon class="h-4 w-4 text-rose-500"/>
            </template>
            <template #label>
                <span class="text-sm text-rose-500">حذف</span>
            </template>
        </context-menu-item>
    </context-menu>
</template>

<script setup>
import {computed, isProxy, toRaw} from "vue";
import {ContextMenu, ContextMenuItem, ContextMenuSeparator} from "@imengyu/vue3-context-menu";
import {
    ScissorsIcon, DocumentDuplicateIcon, TrashIcon,
    PencilSquareIcon, EyeIcon, ArrowDownTrayIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    options: {
        type: Object,
        default: () => {
            return {
                zIndex: 3,
                minWidth: 145,
                x: 0,
                y: 0,
            }
        },
    },
    operations: {
        type: Array,
        // ['cut', 'copy', 'delete', 'rename', 'download', 'paste', 'preview', 'properties',]
        default: () => ['cut', 'copy', 'delete', 'rename', 'download'],
    },
    data: Object,
})

const emit = defineEmits([
    'update:show', 'copy-click', 'cut-click', 'delete-click',
    'rename-click', 'preview-click', 'download-click', 'paste-click',
])

const menuShow = computed({
    get() {
        return props.show
    },
    set(value) {
        emit('update:show', value)
    }
})

function hasOperation(operation) {
    return props.operations.indexOf(operation) !== -1;
}

function menuItemClick(operation) {
    emit(operation + '-click', isProxy(props.data) ? toRaw(props.data) : props.data)
}
</script>

<style scoped>

</style>
