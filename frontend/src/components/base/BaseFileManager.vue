<template>
  <template v-if="hasUploader">
    <base-file-manager-uploader
        :disk="currentStorage.path"
        :path="currentPath"
        @upload-complete="() => {doSearch(null, null, table.sortable.order, table.sortable.sort)}"
    ></base-file-manager-uploader>
  </template>

  <div class="flex flex-wrap rounded-lg bg-emerald-300 border mb-3 px-3 py-1 bg-opacity-80">
    <ul class="text-sm flex flex-wrap">
      <li class="my-1 relative">
        <VTransitionFade>
          <loader-circle
              v-if="waitListLoading"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-[calc(100%+.5rem)] h-[calc(100%+.5rem)] -top-1 -left-1"
              spinner-klass="!w-5 !h-5"
          />
        </VTransitionFade>

        <base-floating-drop-down
            :items="Array.isArray(storages) ? storages : [storages]"
            placement="bottom-start"
        >
          <template #button>
            <button
              v-tooltip.top="'فضای ذخیره سازی'"
              class="text-black flex items-center cursor-pointer"
              type="button"
            >
              <ServerStackIcon class="h-5 w-5 inline-block ml-2"/>
              <span class="inline-block">{{ currentStorage.text }}</span>
              <ChevronDownIcon class="h-4 w-4 inline-block mr-2 text-emerald-700"/>
            </button>
          </template>

          <template #item="{item, hide}">
            <button
              class="text-black p-1 rounded-md hover:bg-blue-700 hover:text-white transition"
              type="button"
                @click="storageChange(item, hide)"
            >
              <ServerIcon class="h-5 w-5 inline-block ml-2"/>
              {{ item }}
            </button>
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
            <base-file-manager-folder-creator
                :disk="currentStorage.path"
                :path="currentPath"
                @created="() => {doSearch(null, null, table.sortable.order, table.sortable.sort)}"
            />
          </div>

          <base-datatable
              ref="datatable"
              :columns="table.columns"
              :enable-multi-operation="allowMultiOperation"
              :enable-search-box="hasSearch"
              :has-checkbox="allowMultiOperation"
              :is-hide-paging="true"
              :is-loading="table.isLoading"
              :is-slot-mode="true"
              :messages="table.messages"
              :rows="table.rows"
              :selection-columns="table.selectionColumns"
              :selection-operations="selectionOperations"
              :sortable="table.sortable"
              :total="table.totalRecordCount"
              @do-search="doSearch"
              @row-context-menu="onContextMenu"
              @row-clicked="handleFileSelection"
          >
            <template #image="{value}">
              <partial-show-image
                  :item="value"
                  @click-dir="() => {changeHash(value.full_path)}"
                  @click-image="() => {
                    currentPreviewSlide = previewingItemsIndex.findIndex(item => value.full_path === item)
                    onLightboxShow()
                  }"
              />
            </template>

            <template #name="{value}">
              <div v-if="value.is_dir"
                   class="cursor-pointer hover:text-black transition"
                   @click="() => {changeHash(value.full_path)}">
                {{ value.full_name }}
              </div>
              <div v-else-if="isImageExt(value.extension)"
                   class="hover:text-black transition">
                {{ value.full_name }}
              </div>
            </template>

            <template #created_at="{value}">
              <span v-if="value.created_at">{{ value.created_at }}</span>
              <span v-else>-</span>
            </template>
          </base-datatable>

          <base-file-manager-context-menu
              v-model:show="menuShow"
              :data="menuData"
              :operations="menuOperations"
              :options="menuOptions"
              @copy-click="copyClicked"
              @cut-click="cutClicked"
              @delete-click="deleteClicked"
              @download-click="downloadClicked"
              @rename-click="renameClicked"
              @paste-click="pasteClicked"
          />

          <base-file-manager-tree-directory
              :disk="currentStorage.path"
              :open="treeOpen"
              @close="treeClose"
              @select-change="treeDirectoryChange"
          >
            <template v-if="copiedPath.items.length" #extra>
              <base-animated-button
                  :disabled="batchOperationLoading"
                  class="bg-slate-600 px-5 w-full"
                  @click="doBatchCopyOrMove"
              >
                <VTransitionFade>
                  <loader-circle
                      v-if="batchOperationLoading"
                      big-circle-color="border-transparent"
                      main-container-klass="absolute w-full h-full top-0 left-0"
                  />
                </VTransitionFade>

                <template #icon="{klass}">
                  <ScissorsIcon
                      v-if="copiedPath.action === 'move'"
                      :class="klass"
                      class="w-6 h-6 ml-auto"
                  />
                  <DocumentDuplicateIcon
                      v-else-if="copiedPath.action === 'copy'"
                      :class="klass"
                      class="w-6 h-6 ml-auto"
                  />
                </template>
                <span v-if="copiedPath.action === 'move'" class="ml-auto">انجام جابجایی</span>
                <span v-else-if="copiedPath.action === 'copy'" class="ml-auto">انجام عملیات کپی</span>
              </base-animated-button>
            </template>
          </base-file-manager-tree-directory>

          <base-file-manager-rename
              v-if="renameItem && Object.keys(renameItem).length"
              v-model:open="renameOpen"
              :disk="currentStorage.path"
              :item="renameItem"
              :path="currentPath"
              @success="renameSucceed"
          />
        </template>
      </base-loading-panel>
    </template>
  </partial-card>

  <vue-easy-lightbox
      :imgs="itemsRef"
      :index="currentPreviewSlide"
      :rtl="true"
      :visible="visibleRef"
      @hide="onLightboxHide"
  ></vue-easy-lightbox>
</template>

<script setup>
import {
  ChevronLeftIcon, ServerIcon, ChevronDownIcon, ServerStackIcon,
  ScissorsIcon, DocumentDuplicateIcon,
} from '@heroicons/vue/24/outline/index.js';
import BaseFileManagerUploader from "./filemanager/BaseFileManagerUploader.vue";
import BaseFileManagerFolderCreator from "./filemanager/BaseFileManagerFolderCreator.vue";
import BaseDatatable from "./BaseDatatable.vue";
import {useToast, TYPE} from "vue-toastification";
import {reactive, ref, watchEffect} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import ContextMenu from '@imengyu/vue3-context-menu'
import BaseFileManagerContextMenu from "./filemanager/BaseFileManagerContextMenu.vue";
import BaseLoadingPanel from "./BaseLoadingPanel.vue";
import {useRoute, useRouter} from "vue-router";
import BaseFloatingDropDown from "./BaseFloatingDropDown.vue";
import {useFileList} from "@/composables/file-list.js";
import {useConfirmToast, useLoadingToast} from "@/composables/toast-helper.js";
import BaseFileManagerTreeDirectory from "./filemanager/BaseFileManagerTreeDirectory.vue";
import BaseAnimatedButton from "./BaseAnimatedButton.vue";
import BaseFileManagerRename from "./filemanager/BaseFileManagerRename.vue";
import {FilemanagerAPI} from "@/service/APIFilemanager.js";
import LoaderCircle from "./loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseLoadingToast from "./toast/BaseLoading.vue";
import VueEasyLightbox from "vue-easy-lightbox";
import {useLightbox} from "@/composables/lightbox-view.js";
import {apiRoutes} from "@/router/api-routes.js";
import {trimChar} from "@/composables/helper.js";
import {watchImmediate} from "@vueuse/core";
import PartialShowImage from "@/components/partials/filemanager/PartialShowImage.vue";

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
      if (Array.isArray(value)) {
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
  extensions: {
    type: Array,
    default: () => [],
  },
  selectableFiles: Boolean,
})

const emit = defineEmits(['file-selected'])

const toast = useToast()
const router = useRouter()
const route = useRoute()

const {isImageExt} = useFileList()

const currentStorage = ref({
  path: 'public',
  text: 'public',
})

const waitListLoading = ref(false)

const currentPath = ref('/')
const pathBreadcrumb = ref([])

// -----------------------------------
// Lightbox preview for images(for now)
// -----------------------------------
const currentPreviewSlide = ref(0)
const previewingItems = ref([])
const previewingItemsIndex = ref([])
const {visibleRef, itemsRef, onLightboxShow, onLightboxHide} = useLightbox(previewingItems)

// -----------------------------------

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
      label: "",
      field: "image",
      sortable: false,
      columnStyles: "width: 130px;",
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
      columnClasses: 'whitespace-nowrap',
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
      label: "",
      field: "image",
      sortable: false,
      columnStyles: "width: 130px;",
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
      columnDir: 'ltr',
      columnClasses: 'whitespace-nowrap',
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

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true
  waitListLoading.value = true

  table.searchText = text

  FilemanagerAPI.fetchList({
    path: currentPath.value,
    disk: currentStorage.value.path,
    search: text,
    // size: FileSizes.ORIGINAL,
    order,
    sort,
    extensions: props.extensions,
  }, {
    success(response) {
      previewingItems.value = []
      previewingItemsIndex.value = []

      table.rows = response.data
      table.totalRecordCount = response.data.length

      const d = response.data
      for (const file in d) {
        if (d.hasOwnProperty(file)) {
          if (!d[file].is_dir && isImageExt(d[file].extension ?? '')) {
            const imgPreview = import.meta.env.VITE_API_BASE_URL + apiRoutes.showFile + '?file=' + d[file].full_path
            previewingItems.value.push(imgPreview)
            previewingItemsIndex.value.push(d[file].full_path)
          }
        }
      }

      return false
    },
    error() {
      table.rows = []
      table.totalRecordCount = 0
    },
    finally() {
      loading.value = false
      table.isLoading = false
      table.sortable.order = order
      table.sortable.sort = sort

      waitListLoading.value = false

      if (tableContainer.value && tableContainer.value.card)
        tableContainer.value.card.scrollIntoView({behavior: "smooth"})
    },
  })
}

const batchOperationLoading = ref(false)
const copiedPath = ref({
  action: '',
  items: [],
})
const treeOpen = ref(false)
const selectedTreeDirectory = ref(null)

function treeClose() {
  treeOpen.value = false
}

const renameItem = ref(null)
const renameOpen = ref(false)

function treeDirectoryChange(item) {
  selectedTreeDirectory.value = item.full_path
}

function renameSucceed() {
  renameItem.value = null
  datatable.value?.refresh()
}

function doBatchCopyOrMove() {
  if (!copiedPath.value.items.length) return

  if (selectedTreeDirectory.value === null) {
    toast.warning('ابتدا پوشه‌ی مقصد را انتخاب نمایید.')
    return
  }

  let apiCall = null
  let toastTitle, successTitle;

  if (copiedPath.value.action === 'move') {
    apiCall = FilemanagerAPI.move
    toastTitle = 'در حال جابجایی فایل(ها). لطفا کمی صبر کنید...'
    successTitle = 'جابجایی فایل(ها) با موفقیت انجام شد.'
  } else if (copiedPath.value.action === 'copy') {
    apiCall = FilemanagerAPI.copy
    toastTitle = 'در حال کپی کردن فایل(ها). لطفا کمی صبر کنید...'
    successTitle = 'کپی فایل(ها) با موفقیت انجام شد.'
  }

  if (!apiCall) return

  batchOperationLoading.value = true
  const doingStuff = useLoadingToast(toastTitle, true)

  apiCall({
    files: copiedPath.value.items,
    destination: selectedTreeDirectory.value || '/',
    disk: currentStorage.value.path,
  }, {
    success() {
      copiedPath.value.action = ''
      copiedPath.value.items = []
      selectedTreeDirectory.value = null

      treeClose()
      datatable.value?.resetSelection()

      toast.update(doingStuff, {
        content: {
          component: BaseLoadingToast,
          props: {
            title: successTitle,
            isLoading: false,
          },
        },
        options: {
          type: 'success',
          timeout: 5000,
          toastClassName: '',
        },
      })

      datatable.value?.refresh()

      return false
    },
    error(error) {
      toast.update(doingStuff, {
        content: {
          component: BaseLoadingToast,
          props: {
            title: error.message ?? 'خطا در انجام عملیات!',
            isLoading: false,
          },
        },
        options: {
          type: 'error',
          timeout: 5000,
          toastClassName: '',
        },
      })

      return false
    },

    finally() {
      batchOperationLoading.value = false
    },
  })
}

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

        copiedPath.value.action = 'copy'
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

        copiedPath.value.action = 'move'
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
        const names = getSelectedItemsProperty(items, 'full_name')

        let subtitle = ''
        if (items.some((item) => item.is_dir)) {
          subtitle = 'توجه داشته باشید، تمام فایل‌ها و پوشه‌های داخل پوشه‌ها حذف خواهند شد و امکان بازگردانی وجود نخواهد داشت.'
        }

        useConfirmToast(() => {
          FilemanagerAPI.deleteFiles({
            files: names,
            path: currentPath.value,
            disk: currentStorage.value.path,
          }, {
            success() {
              toast.success('عملیات با موفقیت انجام شد.')
              datatable.value?.refresh()
              datatable.value?.resetSelection()

              return false
            }
          })
        }, null, subtitle)
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

  const downloadIdx = menuOperations.value.indexOf('download')
  if (!data.is_dir) {
    if (props.allowDownload && downloadIdx === -1) menuOperations.value.push('download')
  } else {
    if (downloadIdx !== -1) menuOperations.value.splice(downloadIdx, 1)
  }

  const pasteIdx = menuOperations.value.indexOf('paste')
  if (data.is_dir && copiedPath.value.items.length) {
    // in case of allowing move/copy, paste should be shown to user
    if ((props.allowMove || props.allowCopy) && pasteIdx === -1) menuOperations.value.push('paste')
  } else {
    if (pasteIdx !== -1) menuOperations.value.splice(pasteIdx, 1)
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
  copiedPath.value.action = 'copy'
  copiedPath.value.items = []
  copiedPath.value.items.push(item.full_path)

  toast('کپی انجام شد.', {
    type: TYPE.DEFAULT,
  })
}

function cutClicked(item) {
  copiedPath.value.action = 'move'
  copiedPath.value.items = []
  copiedPath.value.items.push(item.full_path)

  toast('فایل/پوشه برای جابجایی آماده می‌باشد.', {
    type: TYPE.DEFAULT,
  })
}

function deleteClicked(item) {
  let subtitle = ''
  if (item.is_dir) {
    subtitle = 'توجه داشته باشید، تمام فایل‌ها و پوشه‌های داخلی حذف خواهند شد و امکان بازگردانی وجود نخواهد داشت.'
  }

  useConfirmToast(() => {
    FilemanagerAPI.deleteFile({
      path: trimChar(currentPath.value, '/') + '/' + trimChar(item.full_name, '/'),
      disk: currentStorage.value.path,
    }, {
      success() {
        datatable.value?.refresh()
      },
    })
  }, null, subtitle)
}

function downloadClicked(item) {
  FilemanagerAPI.downloadFile(item.name, {
    path: currentPath.value,
    disk: currentStorage.value.path,
  })
}

function renameClicked(item) {
  renameItem.value = item
  renameOpen.value = true
}

function pasteClicked(item) {
  let apiCall = null
  let toastTitle, successTitle, subTitle;

  if (copiedPath.value.action === 'move') {
    apiCall = FilemanagerAPI.move
    subTitle = 'جابجایی '
    toastTitle = 'در حال جابجایی فایل/پوشه. لطفا کمی صبر کنید...'
    successTitle = 'جابجایی فایل/پوشه با موفقیت انجام شد.'
  } else if (copiedPath.value.action === 'copy') {
    apiCall = FilemanagerAPI.copy
    subTitle = 'کپی '
    toastTitle = 'در حال کپی کردن فایل/پوشه. لطفا کمی صبر کنید...'
    successTitle = 'کپی فایل/پوشه با موفقیت انجام شد.'
  }

  subTitle += 'فایل/پوشه به مسیر "' + item.full_name + '"'

  if (!apiCall) {
    toast.info('ابتدا فایل/پوشه مورد نظر را برای جابجایی/کپی انتخاب کنید.')
    return
  }

  useConfirmToast(() => {
    const doingStuff = useLoadingToast(toastTitle, true)

    apiCall({
      files: copiedPath.value.items,
      destination: item.full_path || '/',
      disk: currentStorage.value.path,
    }, {
      success() {
        copiedPath.value.action = ''
        copiedPath.value.items = []

        toast.update(doingStuff, {
          content: {
            component: BaseLoadingToast,
            props: {
              title: successTitle,
              isLoading: false,
            },
          },
          options: {
            type: 'success',
            timeout: 5000,
            toastClassName: '',
          },
        })

        datatable.value?.refresh()

        return false
      },
      error(error) {
        toast.update(doingStuff, {
          content: {
            component: BaseLoadingToast,
            props: {
              title: error.message ?? 'خطا در انجام عملیات!',
              isLoading: false,
            },
          },
          options: {
            type: 'error',
            timeout: 5000,
            toastClassName: '',
          },
        })

        return false
      },
    })
  }, null, subTitle)
}

// -----------------------------------
function storageChange(storage, hide) {
  if (waitListLoading.value) return

  waitListLoading.value = true

  // hide the dropdown
  hide()

  if (currentStorage.value.path !== storage) {
    currentStorage.value.path = storage
    currentStorage.value.text = storage

    datatable.value?.refresh()
  }
}

function resetPathBreadcrumb() {
  pathBreadcrumb.value = [
    {
      text: 'root',
      path: '',
    },
  ]
}

function checkHash() {
  resetPathBreadcrumb()

  currentPath.value = route.hash.substring(1)

  if (currentPath.value.trim() === '') {
    currentPath.value = '/'
    pathBreadcrumb.value = []
  }

  const pathArr = currentPath.value.split('/')

  let path = ''
  for (let i of pathArr) {
    if (['', '.', '..'].indexOf(i.trim()) === -1) {
      path += i.trim() + '/'
      pathBreadcrumb.value.push({
        text: i,
        path: trimChar(path, '/'),
      })
    }
  }

  datatable.value?.refresh()
}

watchImmediate(() => route.hash, () => {
  checkHash()
})

function changeHash(to) {
  router.push({hash: '#' + trimChar(to.replace(/[\\]*/, '/'), '/')})
}

// -----------------------------------
doSearch(0, -1, 'id', 'desc')

function handleFileSelection(file, row) {
  if (datatable.value && props.selectableFiles && !file.is_dir && row) {
    datatable.value.table.querySelector('.file-selection')?.classList.remove('file-selection')
    row.classList.add('file-selection')

    emit('file-selected', file)
  }
}
</script>

<style>
.file-selection {
  background-color: rgba(42, 223, 229, 0.14) !important;
}
</style>
