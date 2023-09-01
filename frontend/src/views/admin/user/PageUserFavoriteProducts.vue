<template>
    <base-loading-panel
        :loading="loading"
        type="content"
    >
        <template #content>
            <div class="bg-white mb-3 rounded-lg border p-3">
                نمایش محصولات مورد علاقه کاربر -
                <span
                    v-if="user?.id"
                    class="text-teal-600"
                >{{
                        (user?.first_name || user?.last_name) ? (user?.first_name + ' ' + user?.last_name).trim() : user.username
                    }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <partial-card>
                    <template #body>
                        <div class="flex items-center md:flex-col">
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="p-2 shrink-0"
                            >
                                <base-lazy-image
                                    alt="تصویر محصول"
                                    lazy-src="/src/assets/products/p1.jpg"
                                    class="!w-20 ml-3 mb-0 h-auto hover:scale-95 md:!w-full md:mb-3 md:ml-0 transition"
                                />
                            </router-link>
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="px-3 py-2 text-primary hover:text-opacity-90 md:border-t"
                            >
                                لپتاپ خیلی باحال و کاربردی عمو فردوس
                            </router-link>
                        </div>
                    </template>
                </partial-card>
                <partial-card>
                    <template #body>
                        <div class="flex items-center md:flex-col">
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="p-2 shrink-0"
                            >
                                <base-lazy-image
                                    alt="تصویر محصول"
                                    lazy-src="/src/assets/products/p2.jpg"
                                    class="!w-20 ml-3 mb-0 h-auto hover:scale-95 md:!w-full md:mb-3 md:ml-0 transition"
                                />
                            </router-link>
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="px-3 py-2 text-primary hover:text-opacity-90 md:border-t"
                            >
                                لپتاپ خیلی باحال و کاربردی عمو فردوس
                            </router-link>
                        </div>
                    </template>
                </partial-card>
                <partial-card>
                    <template #body>
                        <div class="flex items-center md:flex-col">
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="p-2 shrink-0"
                            >
                                <base-lazy-image
                                    alt="تصویر محصول"
                                    lazy-src="/src/assets/products/p3.jpg"
                                    class="!w-20 ml-3 mb-0 h-auto hover:scale-95 md:!w-full md:mb-3 md:ml-0 transition shrink-0"
                                />
                            </router-link>
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="px-3 py-2 text-primary hover:text-opacity-90 md:border-t"
                            >
                                لپتاپ خیلی باحال و کاربردی عمو فردوس که قابلیت بهره‌گیری در بازی‌ها با گرافیک بسیار زیاد را دارا می‌باشد.
                            </router-link>
                        </div>
                    </template>
                </partial-card>
                <partial-card>
                    <template #body>
                        <div class="flex items-center md:flex-col">
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="p-2 shrink-0"
                            >
                                <base-lazy-image
                                    alt="تصویر محصول"
                                    lazy-src="/src/assets/products/p4.jpg"
                                    class="!w-20 ml-3 mb-0 h-auto hover:scale-95 md:!w-full md:mb-3 md:ml-0 transition"
                                />
                            </router-link>
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="px-3 py-2 text-primary hover:text-opacity-90 md:border-t"
                            >
                                لپتاپ خیلی باحال و کاربردی عمو فردوس
                            </router-link>
                        </div>
                    </template>
                </partial-card>
                <partial-card>
                    <template #body>
                        <div class="flex items-center md:flex-col">
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="p-2 shrink-0"
                            >
                                <base-lazy-image
                                    alt="تصویر محصول"
                                    lazy-src="/src/assets/products/p5.jpg"
                                    class="!w-20 ml-3 mb-0 h-auto hover:scale-95 md:!w-full md:mb-3 md:ml-0 transition"
                                />
                            </router-link>
                            <router-link
                                :to="{name: 'admin.product.detail', params: {id: 1}}"
                                class="px-3 py-2 text-primary hover:text-opacity-90 md:border-t"
                            >
                                لپتاپ خیلی باحال و کاربردی عمو فردوس
                            </router-link>
                        </div>
                    </template>
                </partial-card>
            </div>
        </template>
    </base-loading-panel>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import BaseLazyImage from "../../../components/base/BaseLazyImage.vue";

const loading = ref(false)

const route = useRoute()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const user = ref(null)

onMounted(() => {
    useRequest(apiReplaceParams(apiRoutes.admin.users.show, {user: idParam.value}), null, {
        success: (response) => {
            user.value = response.data
        },
    })
})
</script>

<style scoped>

</style>
<script setup>
</script>
