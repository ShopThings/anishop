<template>
    <partial-filter-card
        item-key="id"
        item-text-key="name"
        :items="colors"
        panel-container-class="flex flex-wrap items-center gap-2"
    >
        <template #default>رنگ</template>

        <template #item="{item}">
            <div
                class="flex flex-col items-center cursor-pointer"
                @click.capture="toggleColorSelection"
            >
                <div class="p-1 flex items-center justify-center border-2 rounded-lg">
                    <div
                        :style="'background-color:' + item.hex + ';'"
                        class="w-8 h-8 rounded-md shadow-md"
                    ></div>
                </div>
                <span class="text-xs">{{ item.name }}</span>
            </div>
        </template>
    </partial-filter-card>

    <partial-filter-toggle/>
</template>

<script setup>
import {ref} from "vue";
import PartialFilterCard from "../partials/pages/PartialFilterCard.vue";
import PartialFilterToggle from "../partials/pages/PartialFilterToggle.vue";

//----------------------------
// Color Filter
//----------------------------
const colors = ref([
    {
        id: 1,
        name: 'طوسی',
        hex: '#d2d6e0',
    },
    {
        id: 2,
        name: 'مشکی',
        hex: '#000',
    },
    {
        id: 3,
        name: 'نقره‌ای',
        hex: '#efefef',
    },
    {
        id: 4,
        name: 'آبی',
        hex: '#2f62e2',
    },
    {
        id: 5,
        name: 'سبز',
        hex: '#36db5d',
    },
    {
        id: 6,
        name: 'بنفش',
        hex: '#9a23ea',
    },
    {
        id: 7,
        name: 'قرمز',
        hex: '#e44444',
    },
])

function toggleColorSelection(evt) {
    const target = evt.target
    if (!target.classList.contains('color-activation')) {
        target.classList.add('color-activation', 'border-indigo-400', 'text-indigo-600')
    } else {
        target.classList.remove('color-activation', 'border-indigo-400', 'text-indigo-600')
    }
}

//----------------------------
</script>

<style scoped>
.color-activation {
}
</style>
