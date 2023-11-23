<template>
    <partial-general-title
        type="side"
        title="دسته‌بندی‌های منتخب"
        title-size="text-xl"
        container-class="mb-5 mt-6 p-2"
    />

    <ul class="flex flex-wrap items-center justify-center gap-8">
        <li
            v-for="category in categories"
            :key="category.id"
        >
            <router-link
                to="#"
                class="flex flex-col items-center gap-3"
            >
                <base-lazy-image
                    :lazy-src="category.image.path"
                    alt=""
                    class="w-32 h-auto"
                />

                <h1 class="font-iranyekan-bold text-sm">
                    {{ category.name }}
                </h1>
            </router-link>
        </li>
    </ul>
</template>

<script setup>
import {ref} from "vue";
import PartialGeneralTitle from "../partials/PartialGeneralTitle.vue";
import BaseLazyImage from "../base/BaseLazyImage.vue";

const categories = ref([
    {
        id: 1,
        name: 'موبایل',
        image: {
            path: '/src/assets/categories/c1.png',
        },
    },
    {
        id: 2,
        name: 'کالای دیجیتال',
        image: {
            path: '/src/assets/categories/c2.png',
        },
    },
    {
        id: 3,
        name: 'خانه و آشپزخانه',
        image: {
            path: '/src/assets/categories/c3.png',
        },
    },
    {
        id: 4,
        name: 'مد و پوشاک',
        image: {
            path: '/src/assets/categories/c4.png',
        },
    },
    {
        id: 5,
        name: 'اسباب بازی، کودک و نوزاد',
        image: {
            path: '/src/assets/categories/c5.png',
        },
    },
    {
        id: 6,
        name: 'زیبایی و سلامت',
        image: {
            path: '/src/assets/categories/c6.png',
        },
    },
])
</script>

<style scoped>

</style>
