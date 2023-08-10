<template>
    <base-floating-drop-down
        placement="right-start"
        :shift="false"
        :container="container"
        :items="items"
    >
        <template #button>
            <button type="button"
                    class="text-black bg-white p-1 rounded-md ring-1 ring-cyan-300 transition hover:ring-cyan-500">
                <outline.Bars3Icon class="h-5 w-5"/>
            </button>
        </template>

        <template #item="{item, hide}">
            <router-link
                v-if="item.link.href"
                @click="itemClick(item, hide)"
                :to="item.link.href"
                class="flex items-center w-full p-2 text-sm transition hover:bg-gray-100 rounded-md block"
                :class="item.link.class"
            >
                <component v-if="item.link.icon" :is="outline[item.link.icon]" class="h-5 w-5"/>
                <span :class="item.link.icon ? 'mr-2' : ''">{{ item.link.text }}</span>
            </router-link>
            <a
                v-else
                @click.prevent="itemClick(item, hide)"
                href="javascript:void(0)"
                class="flex items-center w-full p-2 text-sm transition hover:bg-gray-100 rounded-md block"
                :class="item.link.class"
            >
                <component v-if="item.link.icon" :is="outline[item.link.icon]" class="h-5 w-5"/>
                <span :class="item.link.icon ? 'mr-2' : ''">{{ item.link.text }}</span>
            </a>
        </template>
    </base-floating-drop-down>
</template>

<script setup>
import * as outline from '@heroicons/vue/24/outline';
import {isProxy, toRaw} from "vue";
import BaseFloatingDropDown from "../BaseFloatingDropDown.vue";

const props = defineProps({
    container: {
        type: [Object, String],
        default: 'body',
    },
    items: {
        type: Array,
        required: true,
    },
    data: Object,
})

function itemClick(item, hide) {
    if (item?.event?.click) {
        if (item.link?.closeOnClick !== false)
            hide()
        item.event.click(isProxy(props.data) ? toRaw(props.data) : props.data)
    }
}
</script>

<style scoped>

</style>
