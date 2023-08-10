<template>
    <partial-card>
        <template #header>
            افزودن کاربر جدید
        </template>

        <template #body>
            <div class="p-3">
                <base-loading-panel :loading="loading" type="form">
                    <template #content>
                        <div class="flex flex-wrap">
                            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                <base-input label-title="نام کاربری"
                                            placeholder=" (شماره تلفن  همراه می‌باشد)"
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
                    </template>
                </base-loading-panel>
            </div>
        </template>
    </partial-card>
</template>

<script setup>
import {
    UserIcon, ArrowLeftCircleIcon, CheckIcon, LockClosedIcon, HashtagIcon
} from '@heroicons/vue/24/outline'
import BaseInput from "../../../components/base/BaseInput.vue"
import PartialCard from "../../../components/partials/PartialCard.vue"
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue"
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue"
import {ref} from "vue";

const loading = ref(false)

</script>

<style scoped>

</style>
