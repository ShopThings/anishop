<template>
  <draggable
      :animation="200"
      :group="{ name: 'menus' }"
      :list="menus"
      ghost-class="ghost"
      handle=".handle"
      item-key="tmp_id"
      tag="ul"
  >
    <template #item="{ element, index }">
      <li class="pt-6 px-2 border-2 border-dashed border-slate-300 rounded-lg mb-3">
        <div class="flex flex-wrap items-end mt-2 relative mb-4">
          <base-button
              class="!absolute top-0 left-0 -translate-y-8 -translate-x-1/4 bg-rose-500 !p-1 z-[1]"
              @click="removeMenuHandler(index)"
          >
            <TrashIcon class="h-5 w-5"/>
          </base-button>

          <base-button
              v-tooltip.left="'برای جابجایی بکشید'"
              :class="[
                  'handle cursor-grab active:cursor-grabbing !px-8 sm:!px-10 !rounded-t-none !rounded-br-none',
                  '!absolute top-0 right-0 -translate-y-8 translate-x-2 bg-gray-100 !py-1 z-[1]',
                  'border-b-2 border-l-2 !border-t-none !border-r-none',
              ]"
          >
            <Bars2Icon class="h-6 w-6 text-gray-500"/>
          </base-button>

          <div class="p-2 w-full sm:w-1/2 lg:w-5/12">
            <base-input
                :name="'title' + element.id"
                :value="element.title"
                label-title="نام منو"
                placeholder="وارد نمایید"
                @input="(v) => {element.title = v}"
            >
              <template #icon>
                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
              </template>
            </base-input>
          </div>
          <div class="p-2 w-full sm:w-1/2 lg:w-7/12">
            <base-input
                :name="'link' + element.id"
                :value="element.link"
                label-title="لینک"
                placeholder="وارد نمایید"
                @input="(v) => {element.link = v}"
            >
              <template #icon>
                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
              </template>
            </base-input>
          </div>
          <div class="p-2 w-full sm:w-auto">
            <base-switch
                :enabled="element.is_published"
                :name="'is_published' + element.id"
                label="نمایش زیر منو"
                sr-text="نمایش/عدم نمایش زیر منو"
                @change="(status) => {element.is_published=status}"
            />
          </div>
        </div>

        <div class="my-3">
          <base-button
              v-if="level < maxLevel && element.can_have_children"
              class="!text-orange-600 border-orange-400 w-full sm:w-auto flex items-center hover:bg-orange-50 mr-auto"
              @click="handleNewSubMenuClick(element)"
          >
            <span class="mr-auto text-sm">ساخت زیر منو</span>
            <PlusIcon class="h-6 w-6 mr-auto sm:mr-2"/>
          </base-button>
        </div>

        <nested-menus
            v-if="element.children && element.children.length"
            :level="level + 1"
            :max-level="maxLevel"
            :menus="element.children"
        />
      </li>
    </template>
  </draggable>
</template>

<script setup>
import draggable from "vuedraggable";
import {
  Bars2Icon,
  PlusIcon,
  TrashIcon,
  ArrowLeftCircleIcon,
} from "@heroicons/vue/24/outline/index.js"
import BaseButton from "@/components/base/BaseButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import uniqueId from "lodash.uniqueid";

const props = defineProps({
  menus: {
    type: Array,
    required: true,
  },
  maxLevel: {
    type: Number,
    default: 3,
  },
  level: {
    type: Number,
    default: 1,
  },
})

function removeMenuHandler(idx) {
  if (props.menus[idx])
    props.menus.splice(idx, 1)
}

function handleNewSubMenuClick(menu) {
  if (!menu.children) menu.children = []

  menu.children.push({
    tmp_id: parseInt(uniqueId()),
    id: null,
    parent_id: null,
    title: '',
    link: '',
    can_have_children: true,
    is_published: true,
    children: [],
  })
}
</script>

<style scoped>
.ghost {
  background: rgb(226 232 240 / .5);
  border-radius: .5rem;
}
</style>
