<template>
    <div class="flex flex-wrap gap-3">
        <div class="grow">
            <partial-card-navigation
                :to="{name: 'admin.user.addresses', params: {id: idParam}}"
                bg-color="bg-gradient-to-r from-cyan-500 to-indigo-500"
            >
                <span class="text-white text-lg grow">آدرس‌ها</span>
                <BookOpenIcon class="h-12 w-12 text-white text-opacity-50 mr-3"/>
            </partial-card-navigation>
        </div>
        <div class="grow">
            <partial-card-navigation
                :to="{name: 'admin.user.purchases', params: {id: idParam}}"
                bg-color="bg-gradient-to-r from-purple-500 to-cyan-500"
            >
                <span class="text-white text-lg grow">سفارشات</span>
                <ShoppingBagIcon class="h-12 w-12 text-white text-opacity-50 mr-3"/>
            </partial-card-navigation>
        </div>
        <div class="grow">
            <partial-card-navigation
                :to="{name: 'admin.user.carts', params: {id: idParam}}"
                bg-color="bg-gradient-to-r from-pink-500 to-purple-500"
            >
                <span class="text-white text-lg grow">سبد خرید</span>
                <ShoppingCartIcon class="h-12 w-12 text-white text-opacity-50 mr-3"/>
            </partial-card-navigation>
        </div>
        <div class="grow">
            <partial-card-navigation
                :to="{name: 'admin.user.favorite_products', params: {id: idParam}}"
                bg-color="bg-gradient-to-r from-fuchsia-500 to-pink-500"
            >
                <span class="text-white text-lg grow">محصولات مورد علاقه</span>
                <BookmarkSquareIcon class="h-12 w-12 text-white text-opacity-50 mr-3"/>
            </partial-card-navigation>
        </div>
    </div>

    <partial-card class="mt-3">
        <template #body>
            <div class="p-3">
                <base-loading-panel :loading="loading" type="content">
                    <template #content>
                        <base-tab-panel :tabs="tabs">
                            <template #info>
                                <form-user-update-info
                                    v-model:user="user"
                                    v-model:initialRoles="initialRoles"
                                />
                            </template>

                            <template #password>
                                <form-user-update-password/>
                            </template>

                            <template #status>
                                <div class="px-2 py-3">
                                    <base-animated-button
                                        type="submit"
                                        class="bg-red-500 text-white mr-auto w-full sm:w-auto !py-1 px-3"
                                        @click="deleteUser"
                                    >
                                        <template #icon="{klass}">
                                            <TrashIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                                        </template>

                                        <span class="ml-auto">حذف کاربر</span>
                                    </base-animated-button>
                                </div>

                                <form-user-update-status
                                    v-if="currentUser.id !== idParam.value"
                                    v-model:user="user"
                                />
                            </template>
                        </base-tab-panel>
                    </template>
                </base-loading-panel>
            </div>
        </template>
    </partial-card>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {
    BookOpenIcon,
    ShoppingCartIcon,
    ShoppingBagIcon,
    TrashIcon,
    BookmarkSquareIcon,
} from "@heroicons/vue/24/outline";
import PartialCardNavigation from "../../../components/partials/PartialCardNavigation.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import BaseTabPanel from "../../../components/base/BaseTabPanel.vue";
import {useConfirmToast} from "../../../composables/toast-confirm.js";
import {useToast} from "vue-toastification";
import FormUserUpdateInfo from "./forms/FormUserUpdateInfo.vue";
import FormUserUpdatePassword from "./forms/FormUserUpdatePassword.vue";
import FormUserUpdateStatus from "./forms/FormUserUpdateStatus.vue";
import {useAdminAuthStore} from "../../../store/StoreUserAuth.js";
import {UserAPI} from "../../../service/APIUser.js";

const router = useRouter()
const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const userStore = useAdminAuthStore()
const currentUser = userStore.getUser

const loading = ref(true)
const tabs = {
    info: {
        text: 'مشخصات کاربر'
    },
    password: {
        text: 'تغییر کلمه عبور',
    },
}
const user = ref(null)
const initialRoles = ref(null)

if (currentUser.id !== idParam.value) {
    tabs.status = {
        text: 'ویرایش وضعیت‌ها',
    }
}

function deleteUser() {
    useConfirmToast(
        () => {
            UserAPI.deleteById(idParam.value, {
                success: () => {
                    toast.success('عملیات با موفقیت انجام شد.')
                    router.push({name: 'admin.users'})
                    return false
                }
            })
        },
        'انتقال کاربر به سطل زباله؟'
        , 'در صورت حذف، کاربر به سطل زباله انتقال پیدا می‌کند و می‌توانید آن را در صورت نیاز بازگردانی نمایید.'
    )
}

onMounted(() => {
    UserAPI.fetchById(idParam.value, {
        success(response) {
            user.value = response.data

            const retrievedRoles = []
            for (let o in response.data.roles) {
                if (response.data.roles.hasOwnProperty(o)) {
                    retrievedRoles.push({
                        name: response.data.roles[o],
                        value: o,
                    })
                }
            }
            initialRoles.value = retrievedRoles

            loading.value = false
        },
    })
})
</script>

<style scoped>

</style>
