<template>
    <form @submit.prevent="onSubmit">
        <div class="flex flex-wrap">
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                <base-input
                    label-title="نام کاربری"
                    placeholder="(معمولا شماره تلفن همراه می‌باشد)"
                    name="username"
                    :has-edit-mode="false"
                    :is-editable="false"
                    :value="user?.username"
                >
                    <template #icon>
                        <UserIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                </base-input>
            </div>
        </div>

        <hr class="my-3">

        <div class="flex flex-wrap">
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                <base-input
                    label-title="نام"
                    placeholder="حروف فارسی"
                    name="first_name"
                    :has-edit-mode="false"
                    :is-editable="!(!!user?.first_name)"
                    :value="user?.first_name"
                >
                    <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                </base-input>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                <base-input
                    label-title="نام خانوادگی"
                    placeholder="حروف فارسی"
                    name="last_name"
                    :has-edit-mode="false"
                    :is-editable="!(!!user?.last_name)"
                    :value="user?.last_name"
                >
                    <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                </base-input>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                <base-input
                    label-title="کد ملی"
                    placeholder="فقط شامل اعداد"
                    name="national_code"
                    :has-edit-mode="false"
                    :is-editable="!(!!user?.national_code)"
                    :value="user?.national_code"
                >
                    <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                </base-input>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                <base-input
                    label-title="شماره شبا"
                    is-optional
                    placeholder="xxxxxxxxxxxxxxxx"
                    name="shaba_number"
                    :has-edit-mode="false"
                    :value="user?.shaba_number"
                >
                    <template #icon>
                        <HashtagIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                </base-input>
            </div>
        </div>

        <div class="px-2 py-3">
            <base-animated-button
                type="submit"
                class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                :disabled="isSubmitting"
            >
                <VTransitionFade>
                    <loader-circle
                        v-if="isSubmitting"
                        main-container-klass="absolute w-full h-full top-0 left-0"
                        big-circle-color="border-transparent"
                    />
                </VTransitionFade>

                <template #icon="{klass}">
                    <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                </template>

                <span class="ml-auto">ویرایش اطلاعات</span>
            </base-animated-button>
        </div>
    </form>
</template>

<script setup>
import {ArrowLeftCircleIcon, CheckIcon, HashtagIcon, UserIcon} from "@heroicons/vue/24/outline/index.js";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import {ref} from "vue";
import {useForm} from "vee-validate";
import yup, {transformNumbersToEnglish} from "../../../validation/index.js";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";
import {useAdminAuthStore, useUserAuthStore} from "../../../store/StoreUserAuth.js";

// const store = useUserAuthStore()
const store = useAdminAuthStore()
const user = store.getUser

const canSubmit = ref(true)

const {handleSubmit, isSubmitting, errors} = useForm({
    validationSchema: yup.object().shape({
        first_name: yup.string().persian('نام باید از حروف فارسی باشد.').required('نام اجباری می‌باشد.'),
        last_name: yup.string().persian('نام خانوادگی باید از حروف فارسی باشد.').required('نام خانوادگی اجباری می‌باشد.'),
        national_code: yup.string()
            .transform(transformNumbersToEnglish)
            .persianNationalCode('کد ملی نامعتبر است.').required('کد ملی اجباری می‌باشد.'),
        shaba_number: yup.string()
            .transform(transformNumbersToEnglish)
            .optional().nullable(),
    }),
    keepValuesOnUnmount: true,
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return

    canSubmit.value = false

    useRequest(apiRoutes.user.info.info, {
        method: 'PUT',
        data: values,
    }, {
        success: (response) => {
            toast.success('ویرایش اطلاعات با موفقیت انجام شد.')

            user.value = response.data

            return false
        },
        error: (error) => {
            if (error.errors && Object.keys(error.errors).length >= 1)
                actions.setErrors(error.errors)

            return false
        },
        finally: function () {
            canSubmit.value = true
        },
    })
})
</script>

<style scoped>

</style>
