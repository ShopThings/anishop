<template>
    <div class="mb-6 p-3">
        <div class="flex flex-col lg:flex-row-reverse gap-6 sticky-container">
            <div class="grow">
                <base-paginator
                    container-class="flex flex-wrap"
                    item-container-class="w-full sm:w-1/2 md:w-1/3 lg:w-1/2 xl:w-1/3 ml-[-1px] mt-[-1px]"
                    pagination-theme="modern"
                    :per-page="16"
                    :show-search="true"
                    :order="productOrder"
                    v-model:items="products"
                    :is-local="true"
                >
                    <template #empty>
                        <partial-empty-rows
                            image="/empty-statuses/empty-product.svg"
                            image-class="w-60"
                            message="هیچ محصولی پیدا نشد!"
                        />
                    </template>

                    <template #item="{item}">
                        <product-card
                            :product="item"
                            container-class=""
                        />
                    </template>

                    <template #loading>
                        <div class="border-b">
                            <loader-card/>
                        </div>
                    </template>
                </base-paginator>
            </div>

            <Vue3StickySidebar
                class="shrink-0 hidden lg:block lg:w-80"
                containerSelector=".sticky-container"
                innerWrapperSelector='.sidebar__inner'
                :top-spacing="84"
                :bottom-spacing="20"
                :min-width="1024"
            >
                <div class="flex flex-col gap-6">
                    <partial-card
                        class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80">
                        <template #body>
                            <div class="flex items-center p-3 gap-3 justify-between">
                                <span class="font-iranyekan-bold text-xl">فیلترها</span>
                                <button
                                    type="button"
                                    class="p-1 text-cyan-600 text-sm hover:text-opacity-80 transition"
                                    @click="clearAllFilters"
                                >
                                    حذف فیلترها
                                </button>
                            </div>

                            <div class="mt-4">
                                <product-search-filters/>
                            </div>
                        </template>
                    </partial-card>
                </div>
            </Vue3StickySidebar>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import BasePaginator from "../components/base/BasePaginator.vue";
import PartialEmptyRows from "../components/partials/PartialEmptyRows.vue";
import PartialCard from "../components/partials/PartialCard.vue";
import LoaderCard from "../components/base/loader/LoaderCard.vue";
import ProductCard from "../components/product/ProductCard.vue";
import ProductSearchFilters from "../components/product/ProductSearchFilters.vue";

function clearAllFilters() {
    // ...
}

//----------------------------
// Search Products
//----------------------------
const productOrder = [
    {
        id: 1,
        key: 'newest',
        text: 'جدیدترین',
        sort: 'desc',
    },
    {
        id: 2,
        key: 'most_seen',
        text: 'پربازدیدترین',
        sort: 'asc',
    },
]
const products = ref([
    {
        id: 1,
        brand_id: 2,
        category_id: 5,
        title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
        brand: {
            name: 'سامسونگ',
        },
        category: {
            name: 'موبایل',
        },
        image: {
            name: 'samsung_s23_ultra',
            path: '/src/assets/product/g1.jpg',
        },
        festivals: [
            {
                festival_id: 1,
                product_id: 1,
                discount_percentage: 2,
            },
        ],
        products: [
            {
                id: 1,
                color_name: 'مشکی',
                color_hex: '#000000',
                size: null,
                guarantee: null,
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 8,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
        ],
    },
    {
        id: 2,
        brand_id: 2,
        category_id: 5,
        title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
        brand: {
            name: 'سامسونگ',
        },
        category: {
            name: 'موبایل',
        },
        image: {
            name: 'samsung_s23_ultra',
            path: '/src/assets/product/g2.jpg',
        },
        festivals: [
            {
                festival_id: 1,
                product_id: 1,
                discount_percentage: 2,
            },
        ],
        products: [
            {
                id: 1,
                color_name: 'مشکی',
                color_hex: '#000000',
                size: 'XL',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 8,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
            {
                id: 2,
                color_name: 'مشکی',
                color_hex: '#000000',
                size: 'XLL',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 10,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: true,
                show_call_for_more: false,
            },
            {
                id: 3,
                color_name: 'کرم',
                color_hex: '#F3ECE2',
                size: 'L',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49700000,
                discounted_price: 48700000,
                stock_count: 5,
                max_cart_count: 3,
                is_available: true,
                is_special: true,
                show_coming_soon: false,
                show_call_for_more: false,
            },
            {
                id: 4,
                color_name: 'بنفش',
                color_hex: '#E7DBE5',
                size: 'L',
                guarantee: 'گارانتی ۲۴ ماهه شرکتی',
                price: 49950000,
                discounted_price: 48950000,
                stock_count: 5,
                max_cart_count: 2,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
        ],
    },
    {
        id: 3,
        brand_id: 2,
        category_id: 5,
        title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
        brand: {
            name: 'سامسونگ',
        },
        category: {
            name: 'موبایل',
        },
        image: {
            name: 'samsung_s23_ultra',
            path: '/src/assets/product/g3.jpg',
        },
        festivals: [
            {
                festival_id: 1,
                product_id: 1,
                discount_percentage: 2,
            },
        ],
        products: [
            {
                id: 1,
                color_name: 'مشکی',
                color_hex: '#000000',
                size: 'XL',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 8,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
            {
                id: 2,
                color_name: 'مشکی',
                color_hex: '#000000',
                size: 'XLL',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 10,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: true,
                show_call_for_more: false,
            },
            {
                id: 3,
                color_name: 'کرم',
                color_hex: '#F3ECE2',
                size: 'L',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49700000,
                discounted_price: 48700000,
                stock_count: 5,
                max_cart_count: 3,
                is_available: true,
                is_special: true,
                show_coming_soon: false,
                show_call_for_more: false,
            },
            {
                id: 4,
                color_name: 'بنفش',
                color_hex: '#E7DBE5',
                size: 'L',
                guarantee: 'گارانتی ۲۴ ماهه شرکتی',
                price: 49950000,
                discounted_price: 48950000,
                stock_count: 5,
                max_cart_count: 2,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
        ],
    },
    {
        id: 4,
        brand_id: 2,
        category_id: 5,
        title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
        brand: {
            name: 'سامسونگ',
        },
        category: {
            name: 'موبایل',
        },
        image: {
            name: 'samsung_s23_ultra',
            path: '/src/assets/product/g4.jpg',
        },
        festivals: [
            {
                festival_id: 1,
                product_id: 1,
                discount_percentage: 2,
            },
        ],
        products: [
            {
                id: 1,
                color_name: 'مشکی',
                color_hex: '#000000',
                size: null,
                guarantee: null,
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 8,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
        ],
    },
    {
        id: 5,
        brand_id: 2,
        category_id: 5,
        title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
        brand: {
            name: 'سامسونگ',
        },
        category: {
            name: 'موبایل',
        },
        image: {
            name: 'samsung_s23_ultra',
            path: '/src/assets/product/g5.jpg',
        },
        festivals: [
            {
                festival_id: 1,
                product_id: 1,
                discount_percentage: 2,
            },
        ],
        products: [
            {
                id: 1,
                color_name: null,
                color_hex: null,
                size: 'XL',
                guarantee: null,
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 8,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: true,
            },
            {
                id: 2,
                color_name: 'مشکی',
                color_hex: '#000000',
                size: 'XLL',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 0,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
            {
                id: 3,
                color_name: 'کرم',
                color_hex: '#F3ECE2',
                size: 'L',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49700000,
                discounted_price: 48700000,
                stock_count: 5,
                max_cart_count: 3,
                is_available: true,
                is_special: true,
                show_coming_soon: false,
                show_call_for_more: false,
            },
            {
                id: 4,
                color_name: 'بنفش',
                color_hex: '#E7DBE5',
                size: 'L',
                guarantee: 'گارانتی ۲۴ ماهه شرکتی',
                price: 49950000,
                discounted_price: 48950000,
                stock_count: 5,
                max_cart_count: 2,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
        ],
    },
    {
        id: 6,
        brand_id: 2,
        category_id: 5,
        title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
        brand: {
            name: 'سامسونگ',
        },
        category: {
            name: 'موبایل',
        },
        image: {
            name: 'samsung_s23_ultra',
            path: '/src/assets/product/g6.jpg',
        },
        festivals: [
            {
                festival_id: 1,
                product_id: 1,
                discount_percentage: 2,
            },
        ],
        products: [
            {
                id: 1,
                color_name: 'مشکی',
                color_hex: '#000000',
                size: 'XL',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 8,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
            {
                id: 2,
                color_name: 'مشکی',
                color_hex: '#000000',
                size: 'XLL',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49500000,
                discounted_price: 48500000,
                stock_count: 10,
                max_cart_count: 4,
                is_available: true,
                is_special: false,
                show_coming_soon: true,
                show_call_for_more: false,
            },
            {
                id: 3,
                color_name: 'کرم',
                color_hex: '#F3ECE2',
                size: 'L',
                guarantee: 'گارانتی ۱۸ ماهه شرکتی',
                price: 49700000,
                discounted_price: 48700000,
                stock_count: 5,
                max_cart_count: 3,
                is_available: true,
                is_special: true,
                show_coming_soon: false,
                show_call_for_more: false,
            },
            {
                id: 4,
                color_name: 'بنفش',
                color_hex: '#E7DBE5',
                size: 'L',
                guarantee: 'گارانتی ۲۴ ماهه شرکتی',
                price: 49950000,
                discounted_price: 48950000,
                stock_count: 5,
                max_cart_count: 2,
                is_available: true,
                is_special: false,
                show_coming_soon: false,
                show_call_for_more: false,
            },
        ],
    },
])
//----------------------------
</script>

<style scoped>

</style>
