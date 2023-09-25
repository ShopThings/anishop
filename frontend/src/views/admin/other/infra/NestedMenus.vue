<template>
    <draggable
        item-key="id"
        tag="ul"
        :animation="200"
        :list="menus"
        :group="{ name: 'menus' }"
        handle=".handle"
        ghost-class="ghost"
        @start="isDragged = true"
        @end="isDragged = false"
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

                    <div class="p-2 w-full sm:w-1/2 lg:w-4/12">
                        <base-input
                            label-title="نام منو"
                            placeholder="وارد نمایید"
                            :name="'title' + element.id"
                            :value="element.title"
                            @input="(v) => {element.title = v}"
                        >
                            <template #icon>
                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                            </template>
                        </base-input>
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-6/12">
                        <base-input
                            label-title="لینک"
                            placeholder="وارد نمایید"
                            :name="'link' + element.id"
                            :value="element.link"
                            @input="(v) => {element.link = v}"
                        >
                            <template #icon>
                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                            </template>
                        </base-input>
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-2/12">
                        <base-input
                            label-title="اولویت"
                            placeholder="وارد نمایید"
                            type="number"
                            :min="0"
                            :name="'priority' + element.id"
                            :value="element.priority"
                            @input="(v) => {element.priority = v}"
                        >
                            <template #icon>
                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                            </template>
                        </base-input>
                    </div>
                    <div class="p-2 w-full sm:w-auto">
                        <base-switch
                            label="نمایش زیر منو"
                            :name="'is_published' + element.id"
                            :enabled="element.is_published"
                            sr-text="نمایش/عدم نمایش زیر منو"
                            @change="(status) => {element.is_published=status}"
                        />
                    </div>
                </div>

                <div class="my-3">
                    <base-button
                        v-if="level < maxLevel"
                        class="!text-orange-600 border-orange-400 w-full sm:w-auto flex items-center hover:bg-orange-50 mr-auto"
                        @click="handleNewSubMenuClick(element)"
                    >
                        <span class="mr-auto text-sm">ساخت زیر منو</span>
                        <PlusIcon class="h-6 w-6 mr-auto sm:mr-2"/>
                    </base-button>
                </div>

                <nested-menus
                    v-if="element.menus && element.menus.length"
                    :menus="element.menus"
                    :level="level + 1"
                    :max-level="maxLevel"
                />
            </li>
        </template>
    </draggable>
</template>

<script setup>
import {ref} from "vue";
import draggable from "vuedraggable";
import {
    Bars2Icon,
    PlusIcon,
    TrashIcon,
    ArrowLeftCircleIcon,
} from "@heroicons/vue/24/outline/index.js"
import BaseButton from "../../../../components/base/BaseButton.vue";
import BaseInput from "../../../../components/base/BaseInput.vue";
import BaseSwitch from "../../../../components/base/BaseSwitch.vue";
import uniqueId from "lodash.uniqueid";

const props = defineProps({
    menus: {
        type: Array,
        required: true,
    },
    maxLevel: {
        type: Number,
        default: 2,
    },
    level: {
        type: Number,
        default: 1,
    },
})

const isDragged = ref(false)

function removeMenuHandler(idx) {
    if (props.menus[idx])
        props.menus.splice(idx, 1)
}

function handleNewSubMenuClick(menu) {
    if (!menu.menus) menu.menus = []

    menu.menus.push({
        id: parseInt(uniqueId()),
        parent_id: null,
        title: '',
        link: '',
        priority: menu.menus.length + 1 + '',
        can_have_children: true,
        is_published: true,
        menus: [],
    })
}
</script>

<style scoped>
.ghost {
    opacity: 0.5;
    background: rgb(226 232 240 / var(--tw-bg-opacity));
    border-radius: .5rem;
}
</style>
