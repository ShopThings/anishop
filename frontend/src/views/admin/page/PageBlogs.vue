<template>
  <new-creation-guide-top route-name="admin.blog.add">
    <template #text>
      با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش بلاگ نمایید
    </template>
    <template
      v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG, PERMISSIONS.CREATE)"
      #buttonText
    >
      <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
      افزودن بلاگ جدید
    </template>
  </new-creation-guide-top>

  <partial-card ref="tableContainer">
    <template #header>
      لیست بلاگ‌ها
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
            ref="datatable"
            :columns="table.columns"
            :enable-multi-operation="true"
            :enable-search-box="true"
            :has-checkbox="true"
            :is-loading="table.isLoading"
            :is-slot-mode="true"
            :rows="table.rows"
            :selection-columns="table.selectionColumns"
            :selection-operations="selectionOperations"
            :sortable="table.sortable"
            :total="table.totalRecordCount"
            @do-search="doSearch"
          >
            <template v-slot:id="{value, index}">
              {{ index }}
            </template>

            <template v-slot:image="{value}">
              <partial-show-image :item="value.image"/>
            </template>

            <template v-slot:writer="{value}">
              <router-link
                :to="{name: 'admin.user.profile', params: {id: value.created_by.id}}"
                class="text-blue-600 hover:text-opacity-80"
              >
                <partial-username-label :user="value.created_by"/>
              </router-link>
            </template>

            <template v-slot:category="{value}">
              {{ value.category.name }}
            </template>

            <template v-slot:is_published="{value}">
              <partial-badge-publish :publish="value.is_published"/>
            </template>

            <template v-slot:keywords="{value}">
              <partial-table-keywords :keywords="value.keywords"/>
            </template>

            <template v-slot:updated_at="{value}">
              <span v-if="value.updated_at" class="text-xs">{{ value.updated_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:op="{value}">
              <base-datatable-menu
                :container="getMenuContainer"
                :data="value"
                :items="operations"
                :removals="calcRemovals(value)"
              />
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {computed, reactive, ref} from "vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {MinusIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue";
import {BlogAPI} from "@/service/APIBlog.js";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";
import PartialShowImage from "@/components/partials/filemanager/PartialShowImage.vue";
import PartialTableKeywords from "@/components/partials/PartialTableKeywords.vue";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import {PERMISSION_PLACES, PERMISSIONS, useAdminAuthStore} from "@/store/StoreUserAuth.js";

const router = useRouter()
const toast = useToast()

const userStore = useAdminAuthStore()

const datatable = ref(null)
const tableContainer = ref(null)
const loading = ref(true)
const table = reactive({
  isLoading: false,
  selectionColumns: [
    {
      label: "#",
      field: "id",
      columnStyles: "width: 3%;",
      sortable: true,
      isKey: true,
    },
    {
      label: "تصویر",
      field: "image",
    },
    {
      label: "موضوع",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "نویسنده",
      field: "writer",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "دسته‌بندی",
      field: "category",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      sortable: true,
    },
    {
      label: "کلمات کلیدی",
      field: "keywords",
      sortable: false,
    },
    {
      label: "تاریخ ویرایش",
      field: "updated_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ ایجاد",
      field: "created_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
  ],
  columns: [
    {
      label: "#",
      field: "id",
      columnStyles: "width: 3%;",
      sortable: true,
      isKey: true,
    },
    {
      label: "تصویر",
      field: "image",
    },
    {
      label: "موضوع",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "نویسنده",
      field: "writer",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "دسته‌بندی",
      field: "category",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      sortable: true,
    },
    {
      label: "کلمات کلیدی",
      field: "keywords",
      sortable: false,
    },
    {
      label: "تاریخ ویرایش",
      field: "updated_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ ایجاد",
      field: "created_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: 'عملیات',
      field: 'op',
      width: '7%',
      show: (
        userStore.hasPermission(PERMISSION_PLACES.BLOG, [PERMISSIONS.UPDATE, PERMISSIONS.DELETE]) ||
        userStore.hasPermission(PERMISSION_PLACES.BLOG_COMMENT, PERMISSIONS.READ)
      )
    },
  ],
  rows: [],
  totalRecordCount: 0,
  sortable: {
    order: "id",
    sort: "desc",
  },
})

function calcRemovals(row) {
  let removals = []

  if (!userStore.hasPermission(PERMISSION_PLACES.BLOG, PERMISSIONS.DELETE)) {
    removals.push(['delete'])
  }
  if (!userStore.hasPermission(PERMISSION_PLACES.BLOG, PERMISSIONS.UPDATE)) {
    removals.push(['edit'])
  }
  if (!userStore.hasPermission(PERMISSION_PLACES.BLOG_COMMENT, PERMISSIONS.READ)) {
    removals.push(['showComments'])
  }

  return removals
}

const getMenuContainer = computed(() => {
  return datatable.value?.tableContainer ?? 'body'
})

const operations = [
  {
    id: 'edit',
    link: {
      text: 'ویرایش',
      icon: 'PencilIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.blog.edit',
          params: {
            slug: data.slug,
          }
        })
      },
    },
  },
  {
    id: 'showComments',
    link: {
      text: 'مشاهده دیدگاه‌ها',
      icon: 'ChatBubbleLeftRightIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.blog.comments',
          params: {
            slug: data.slug,
          }
        })
      },
    },
  },
  {
    id: 'delete',
    link: {
      text: 'حذف',
      icon: 'TrashIcon',
      class: 'text-rose-500',
    },
    event: {
      click: (data) => {
        hideAllPoppers()
        toast.clear()

        useConfirmToast(() => {
          BlogAPI.deleteByIds(data.slug, {
            success: () => {
              toast.success('عملیات با موفقیت انجام شد.')
              datatable.value?.refresh()
              datatable.value?.resetSelectionItem(data)

              return false
            },
          })
        })
      },
    },
  },
]

const selectionOperations = [
  {
    btn: {
      tooltip: 'حذف موارد انتخاب شده',
      icon: 'TrashIcon',
      class: 'bg-rose-500 border-rose-600',
    },
    event: {
      click: (items) => {
        const ids = []
        for (const item in items) {
          if (items.hasOwnProperty(item)) {
            if (items[item].slug)
              ids.push(items[item].slug)
          }
        }

        if (!ids.length) {
          toast.info('ابتدا آیتم‌های مورد نیاز را انتخاب کنید و سپس دوباره تلاش نمایید.')
          return
        }

        toast.clear()

        useConfirmToast(() => {
          BlogAPI.deleteByIds(ids, {
            success: () => {
              toast.success('عملیات با موفقیت انجام شد.')
              datatable.value?.refresh()
              datatable.value?.resetSelection()

              return false
            },
          })
        })
      },
    },
  },
]

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  BlogAPI.fetchAll({limit, offset, order, sort, text}, {
    success: (response) => {
      table.rows = response.data
      table.totalRecordCount = response.meta.total

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

doSearch(0, 15, 'id', 'desc')
</script>
