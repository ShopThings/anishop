<template>
    <base-paginator
        container-class="flex flex-col gap-3"
        pagination-theme="modern"
        v-model:items="notifications"
        :per-page="10"
        :path="getPath"
    >
        <template #empty>
            <partial-empty-rows
                image="/empty-statuses/empty-notification.svg"
                image-class="w-60"
                message="هیچ اعلانی برای شما وجود ندارد"
            />
        </template>

        <template #item>
            <partial-card class="border-0 p-3 text-sm">
                <template #body>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-black font-iranyekan-bold">
                            پیگیری سفارش
                        </h1>
                        <p class="text-slate-700 text-sm">
                            سفارش با کد
                            <span class="tracking-widest mx-2">861247269716759</span>
                            به وضعیت
                            <span class="bg-yellow-300 text-sm mx-2 rounded px-2 font-iranyekan-bold">تحویل به پست</span>
                            تغییر یافت.
                        </p>
                    </div>
                </template>
            </partial-card>
        </template>

        <template #loading>
            <loader-list-single/>
        </template>
    </base-paginator>
</template>

<script setup>
import PartialCard from "../../components/partials/PartialCard.vue";
import {computed, ref} from "vue";
import {apiRoutes} from "../../router/api-routes.js";
import BasePaginator from "../../components/base/BasePaginator.vue";
import PartialEmptyRows from "../../components/partials/PartialEmptyRows.vue";
import LoaderListSingle from "../../components/base/loader/LoaderListSingle.vue";

const loading = ref(false)
const getPath = computed(() => {
    // return apiRoutes.user.info.notification
    return ''
})
const notifications = ref([])
</script>

<style scoped>

</style>
