<template>
    <partial-card>
        <template #header>
            افزودن کاربر جدید
        </template>

        <template #body>
            <div class="p-3">
                <base-loading-panel :loading="loading" type="form">
                    <template #content>
                        <form @submit.prevent="onSubmit">
                            <div class="flex flex-wrap">
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input label-title="نام کاربری"
                                                placeholder=" (شماره تلفن همراه می‌باشد)"
                                                name="username">
                                        <template #icon>
                                            <UserIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input label-title="کلمه عبور"
                                                type="password"
                                                placeholder="شامل حروف و اعداد"
                                                name="password">
                                        <template #icon>
                                            <LockClosedIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input label-title="تکرار کلمه عبور"
                                                type="password"
                                                placeholder="شامل حروف و اعداد"
                                                name="password_confirmation">
                                        <template #icon>
                                            <LockClosedIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <partial-input-label title="نقش کاربر"/>
                                    <base-select-searchable
                                        :options="roles"
                                        options-key="name"
                                        name="roles"
                                        :multiple="true"
                                    />
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="flex flex-wrap">
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input label-title="نام"
                                                placeholder="حروف فارسی"
                                                name="first_name">
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input label-title="نام خانوادگی"
                                                placeholder="حروف فارسی"
                                                name="last_name">
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input label-title="کد ملی"
                                                placeholder="فقط شامل اعداد"
                                                name="national_code">
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input label-title="شماره شبا"
                                                is-optional
                                                placeholder="xxxxxxxxxxxxxxxx"
                                                name="shaba_number">
                                        <template #icon>
                                            <HashtagIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                            </div>

                            <div class="px-2 py-3">
                                <base-animated-button
                                    class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto">
                                    <template #icon="{klass}">
                                        <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                                    </template>

                                    <span class="ml-auto">ایجاد کاربر</span>
                                </base-animated-button>
                            </div>
                        </form>
                    </template>
                </base-loading-panel>
            </div>
        </template>
    </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {
    UserIcon, ArrowLeftCircleIcon, CheckIcon, LockClosedIcon, HashtagIcon
} from "@heroicons/vue/24/outline";
import BaseInput from "../../../components/base/BaseInput.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import {useRouter} from "vue-router";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseSelectSearchable from "../../../components/base/BaseSelectSearchable.vue";

const loading = ref(true)
const canSubmit = ref(true)
const roles = ref({})

const router = useRouter()

const {handleSubmit} = useForm({
    validationSchema: yup.object().shape({
        // username: yup.string().required('نام کاربری اجباری می‌باشد.'),
        // password: yup.string().required('کلمه عبور اجباری می‌باشد.'),

    }),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return

    canSubmit.value = false

    useRequest(apiRoutes.admin.users.store, {
        method: 'POST',
        data: values,
    }, {
        success: () => {
            actions.resetForm();
            router.push({name: 'admin.users'})
        },
        error: (error) => {
            actions.resetField('password')
            actions.resetField('password_confirmation')

            if (error.errors && Object.keys(error.errors).length > 1)
                actions.setErrors(error.errors)

            return false
        },
        finally: function () {
            canSubmit.value = true
        },
    })
})

onMounted(() => {
    useRequest(apiRoutes.admin.roles, null, {
        success: (response) => {
            roles.value = response.data
            loading.value = false
        },
    })
})
</script>

<style scoped>

</style>
