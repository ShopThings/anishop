<template>
    <template v-if="hasUploader">
        <base-file-manager-uploader
            @upload-complete=""
            :disk="currentStorage.path"
            :path="currentPath"
        ></base-file-manager-uploader>
    </template>

    <div class="flex flex-wrap rounded-lg bg-emerald-300 border mb-3 px-3 py-1 bg-opacity-80">
        <ul class="text-sm flex flex-wrap">
            <li class="my-1">
                <base-floating-drop-down
                    placement="bottom-start"
                    :items="storages"
                >
                    <template #button>
                        <button type="button" v-tooltip.top="'فضای ذخیره سازی'"
                                class="text-black flex items-center cursor-pointer">
                            <ServerStackIcon class="h-5 w-5 inline-block ml-2"/>
                            <span class="inline-block">{{ currentStorage.text }}</span>
                            <ChevronDownIcon class="h-4 w-4 inline-block mr-2 text-emerald-700"/>
                        </button>
                    </template>

                    <template #item="{item, hide}">
                        <div
                            @click="storageChange(item, hide)"
                            class="text-black p-1 rounded-md hover:bg-blue-700 hover:text-white transition cursor-pointer"
                        >
                            <ServerIcon class="h-5 w-5 inline-block ml-2"/>
                            {{ item }}
                        </div>
                    </template>
                </base-floating-drop-down>
            </li>
            <li v-for="(p, idx) in pathBreadcrumb" :key="idx"
                class="my-1">
                <div class="flex items-center">
                    <ChevronLeftIcon class="w-5 h-5 p-1 text-gray-700"/>
                    <a :class="idx <= Object.keys(pathBreadcrumb).length - 1 ? 'text-black' : 'text-emerald-700'"
                       :href="`#${p.path}`">
                        {{ p.text }}
                    </a>
                </div>
            </li>
        </ul>
    </div>

    <partial-card ref="tableContainer">
        <template #body>
            <base-loading-panel :loading="loading" type="table">
                <template #content>
                    <div v-if="hasCreateFolder"
                         class="p-3">
                        <base-file-manager-folder-creator></base-file-manager-folder-creator>
                    </div>

                    <base-datatable
                        ref="datatable"
                        :has-checkbox="allowMultiOperation"
                        :enable-multi-operation="allowMultiOperation"
                        :enable-search-box="hasSearch"
                        :selection-operations="selectionOperations"
                        :selection-columns="table.selectionColumns"
                        :is-slot-mode="true"
                        :is-loading="table.isLoading"
                        :columns="table.columns"
                        :rows="table.rows"
                        :total="table.totalRecordCount"
                        :sortable="table.sortable"
                        :is-hide-paging="true"
                        :messages="table.messages"
                        @do-search="doSearch"
                        @row-context-menu="onContextMenu"
                    >
                        <template #full_name></template>
                    </base-datatable>

                    <base-file-manager-context-menu
                        v-model:show="menuShow"
                        :options="menuOptions"
                        :operations="menuOperations"
                        :data="menuData"
                        @copy-click="copyClicked"
                        @cut-click="cutClicked"
                        @delete-click="deleteClicked"
                        @download-click="downloadClicked"
                        @rename-click="renameClicked"
                        @paste-click="pasteClicked"
                    />

                    <base-file-manager-tree-directory
                        :path="currentPath"
                        :disk="currentStorage.path"
                        :open="treeOpen"
                        @close="() => (treeOpen.value = false)"
                    >
                        <template #extra>

                        </template>
                    </base-file-manager-tree-directory>
                </template>
            </base-loading-panel>
        </template>
    </partial-card>
</template>

<script setup>
import {ChevronLeftIcon, ServerIcon, ChevronDownIcon, ServerStackIcon} from '@heroicons/vue/24/outline';
import BaseFileManagerUploader from "./filemanager/BaseFileManagerUploader.vue";
import BaseFileManagerFolderCreator from "./filemanager/BaseFileManagerFolderCreator.vue";
import BaseDatatable from "./BaseDatatable.vue";
import {useToast} from "vue-toastification";
import {reactive, ref, watch, watchEffect} from "vue";
import {useRequest} from "../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../router/api-routes.js";
import PartialCard from "../partials/PartialCard.vue";
import ContextMenu from '@imengyu/vue3-context-menu'
import BaseFileManagerContextMenu from "./filemanager/BaseFileManagerContextMenu.vue";
import BaseLoadingPanel from "./BaseLoadingPanel.vue";
import {useRoute} from "vue-router";
import BaseFloatingDropDown from "./BaseFloatingDropDown.vue";
import isArray from "lodash.isarray";
import {FileSizes} from "../../composables/file-list.js";
import {useConfirmToast} from "../../composables/toast-confirm.js";
import {useClipboard} from "@vueuse/core";
import BaseFileManagerTreeDirectory from "./filemanager/BaseFileManagerTreeDirectory.vue";

const props = defineProps({
    hasCreateFolder: {
        type: Boolean,
        default: false,
    },
    hasSearch: {
        type: Boolean,
        default: false,
    },
    hasUploader: {
        type: Boolean,
        default: true,
    },
    allowMultiOperation: {
        type: Boolean,
        default: false,
    },
    allowRename: {
        type: Boolean,
        default: false,
    },
    allowMove: {
        type: Boolean,
        default: false,
    },
    allowCopy: {
        type: Boolean,
        default: false,
    },
    allowDownload: {
        type: Boolean,
        default: false,
    },
    allowDelete: {
        type: Boolean,
        default: false,
    },
    storages: {
        type: [Array, String],
        default: () => ['public', 'local'],
        validator(value) {
            if (isArray(value)) {
                for (const v of value) {
                    if (['public', 'local'].indexOf(v.toLowerCase()) === -1)
                        return false
                }
                return true
            } else {
                return ['public', 'local'].indexOf(value.toLowerCase()) !== -1
            }
        },
    },
})

const toast = useToast()
const route = useRoute()

const currentStorage = ref({
    path: 'public',
    text: 'public',
})

function storageChange(storage, hide) {
    hide()
    if (currentStorage.value.path !== storage) {
        currentStorage.value.path = storage
        currentStorage.value.text = storage

        doSearch(null, null, table.sortable.order, table.sortable.sort, table.searchText)
    }
}

const currentPath = ref('/')
const pathBreadcrumb = ref([])

function checkHash() {
    currentPath.value = route.hash

    if (currentPath.value.trim() === '') currentPath.value = '/'

    const pathArr = currentPath.value.split('/')
    for (let i of pathArr) {
        pathBreadcrumb.value.push({
            text: i,
            path: pathArr.slice(0, pathArr.indexOf(i)).join('/'),
        })
    }

    doSearch(null, null, table.sortable.order, table.sortable.sort, table.searchText)
}

watch(() => route.hash, () => {
    checkHash()
})

const copiedPath = ref({
    type: '',
    items: [],
})
const treeOpen = ref(false)

const datatable = ref(null)
const tableContainer = ref(null)
const loading = ref(true)
const table = reactive({
    isLoading: false,
    selectionColumns: [
        {
            label: "",
            field: "full_name",
            columnStyles: "width: 2%;",
            isKey: true,
            sortable: false,
            columnClasses: 'hidden',
        },
        {
            label: "نام",
            field: "name",
            sortable: true,
            columnClasses: 'whitespace-nowrap',
        },
        {
            label: "اندازه",
            field: "size",
            sortable: true,
        },
        {
            label: "مسیر",
            field: "path",
            sortable: true,
        },
        {
            label: "تاریخ ایجاد",
            field: "created_at",
            sortable: true,
            columnClasses: 'whitespace-nowrap',
        },
    ],
    columns: [
        {
            label: "",
            field: "full_name",
            columnStyles: "width: 2%;",
            isKey: true,
            sortable: false,
            columnClasses: 'hidden',
        },
        {
            label: "نام",
            field: "name",
            sortable: true,
            columnClasses: 'whitespace-nowrap',
        },
        {
            label: "اندازه",
            field: "size",
            sortable: true,
        },
        {
            label: "مسیر",
            field: "path",
            sortable: true,
        },
        {
            label: "تاریخ ایجاد",
            field: "created_at",
            sortable: true,
            columnClasses: 'whitespace-nowrap',
        },
    ],
    rows: [],
    totalRecordCount: 0,
    sortable: {
        order: "name",
        sort: "asc",
    },
    searchText: '',
    messages: {
        pagingInfo: 'نمایش' + " <span class=\"text-blue-500\">" + "{0}" + "</span>"
            + "-<span class=\"text-blue-500\">" + "{1}" + "</span> "
            + 'از مجموع' + " <span class=\"text-blue-500\">" + "{2}" + "</span> " + 'رکورد',
        pageSizeChangeLabel: "تعداد نمایش در هر صفحه:",
        gotoPageLabel: "رفتن به صفحه:",
        noDataAvailable: "هیچ فایلی وجود ندارد.",
        loading: "در حال بارگذاری",
    },
})

function getSelectedItemsProperty(items, property) {
    const properties = []
    for (const item in items) {
        if (items.hasOwnProperty(item)) {
            if (items[item][property])
                properties.push(items[item][property])
        }
    }

    if (!properties.length) {
        toast.info('ابتدا آیتم‌های مورد نیاز را انتخاب کنید و سپس دوباره تلاش نمایید.')
        return
    }

    return properties
}

const selectedOperationChildren = []
if (props.allowCopy) {
    selectedOperationChildren.push({
        btn: {
            icon: 'DocumentDuplicateIcon',
            text: 'کپی کردن',
        },
        event: {
            click: (items) => {
                const paths = getSelectedItemsProperty(items, 'full_path')
                copiedPath.value.type = 'copy'
                copiedPath.value.items = []
                for (const i of paths) {
                    copiedPath.value.items.push(i)
                }
                treeOpen.value = true
            },
        },
    })
}
if (props.allowMove) {
    selectedOperationChildren.push({
        btn: {
            icon: 'ScissorsIcon',
            text: 'جابجا کردن',
        },
        event: {
            click: (items) => {
                const paths = getSelectedItemsProperty(items, 'full_path')
                copiedPath.value.type = 'move'
                copiedPath.value.items = []
                for (const i of paths) {
                    copiedPath.value.items.push(i)
                }
                treeOpen.value = true
            },
        },
    })
}
if (props.allowDelete) {
    selectedOperationChildren.push({
        btn: {
            icon: 'TrashIcon',
            text: 'حذف',
            class: 'text-rose-500',
        },
        event: {
            click: (items) => {
                const names = getSelectedItemsProperty(items, 'name')
                useConfirmToast(() => {
                    useRequest(apiRoutes.admin.files.batchDestroy, {
                        method: 'DELETE',
                        data: {
                            files: names,
                            path: currentPath.value,
                            disk: currentStorage.value.path,
                        },
                    }, {
                        success: () => {
                            toast.success('عملیات با موفقیت انجام شد.')
                            datatable.value?.refresh()
                            datatable.value?.resetSelection()

                            return false
                        }
                    })
                })
            },
        },
    })
}

const selectionOperations = []
if (selectedOperationChildren.length) {
    selectionOperations.push(
        {
            btn: {
                tooltip: 'نمایش عملیات قابل انجام',
                icon: 'EllipsisVerticalIcon',
                class: 'bg-slate-600 border-slate-700',
            },
            children: selectedOperationChildren,
        }
    )
}

const menuOptions = ref({
    zIndex: 3,
    minWidth: 145,
    x: 0,
    y: 0,
})
const menuOperations = ref([])
const menuShow = ref(false)
const menuData = ref({})

watchEffect(() => {
    menuOperations.value = []
    if (props.allowDownload)
        menuOperations.value.push('download')
    if (props.allowMove)
        menuOperations.value.push('cut')
    if (props.allowCopy)
        menuOperations.value.push('copy')
    if (props.allowRename)
        menuOperations.value.push('rename')
    if (props.allowDelete)
        menuOperations.value.push('delete')
})

const onContextMenu = (e, data) => {
    //prevent the browser's default menu
    e.preventDefault()

    if (data.is_dir && copiedPath.value.items.length) {
        if (props.allowDelete)
            menuOperations.value.push('paste')
    } else {
        menuOperations.value.splice(menuOperations.value.indexOf('paste'), 1)
    }

    menuData.value = data
    menuShow.value = true

    menuOptions.value.x = e.x
    menuOptions.value.y = e.y + 10

    //show menu
    ContextMenu.showContextMenu(menuOptions.value)
}

// -----------------------------------
// context menu events
// -----------------------------------

function copyClicked(item) {

}

function cutClicked(item) {

}

function deleteClicked(item) {
    useConfirmToast(() => {
        useRequest(apiReplaceParams(apiRoutes.admin.files.destroy, {file: item.name}), {
            method: 'DELETE',
            data: {
                path: currentPath.value,
                disk: currentStorage.value.path,
            },
        }, {
            success: () => {
                doSearch(null, null, table.sortable.order, table.sortable.sort, table.searchText)
            }
        })
    })
}

function downloadClicked(item) {
    useRequest(apiReplaceParams(apiRoutes.admin.files.download, {file: item.name}), {
        params: {
            path: currentPath.value,
            disk: currentStorage.value.path,
        }
    })
}

function renameClicked(item) {

}

function pasteClicked(item) {

}

// -----------------------------------

const doSearch = (offset, limit, order, sort, text) => {
    table.isLoading = true
    text = text || ''

    table.searchText = text

    useRequest(apiRoutes.admin.files.list, {
        params: {
            path: currentPath.value,
            disk: currentStorage.value.path,
            search: text,
            size: FileSizes.SMALL,
            order,
            sort,
        },
    }, {
        success: (response) => {
            table.rows = response.data
            table.totalRecordCount = response.data.length

            return false
        },
        error: () => {
            table.rows = []
            table.totalRecordCount = 0
        },
        finally: () => {
            loading.value = false
            table.isLoading = false
            table.sortable.order = order
            table.sortable.sort = sort

            if (tableContainer.value && tableContainer.value.card)
                tableContainer.value.card.scrollIntoView({behavior: "smooth"})
        },
    })
}

doSearch(0, -1, 'id', 'desc')
</script>

<style scoped>

</style>
<script setup>
</script>
