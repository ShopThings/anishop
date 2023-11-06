<template>
    <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]"
         aria-hidden="true">
        <div
            class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]"
            style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
    </div>

    <div
        style="background-image: url('/images/contact-us.jpg')"
        class="w-full relative overflow-hidden bg-fixed bg-cover"
    >
        <div
            class="w-full h-full bg-black bg-opacity-30 flex flex-col items-center justify-center text-white text-center p-3 py-20">
            <h1 class="text-3xl pb-2 bg-white bg-opacity-20 py-2 px-6 rounded-full border border-yellow-400 text-yellow-400 text-shadow-sm shadow-black supports-[backdrop-filter]:backdrop-blur-sm">
                راه‌های ارتباطی
            </h1>
            <div class="mt-8 font-iranyekan-light leading-relaxed text-shadow-lg shadow-black">
                برای ارتباط با ما از راه‌های ارتباطی زیر می‌توانید استفاده نمایید
            </div>
            <span
                class="my-3  border border-white w-10 h-10 p-1.5 bg-white bg-opacity-20 rounded-full">یا</span>
            <div class="font-iranyekan-light leading-relaxed text-shadow-lg shadow-black">
                با استفاده از فرم زیر پیام خود را ارسال نمایید
            </div>
        </div>
    </div>

    <app-navigation-header title="ارتباط با ما"/>

    <div class="px-3 pt-3 mb-8">
        <partial-card class="border-0 p-3">
            <template #body>
                <h2 class="text-slate-400 mb-1">
                    ارسال پیام
                </h2>

                <div class="my-3 text-sm py-4 border-b">
                    لطفاً پیش از ارسال ایمیل یا تماس تلفنی، ابتدا
                    <router-link
                        :to="{name: 'faq'}"
                        class="text-blue-600 hover:text-opacity-80 transition"
                    >
                        پرسش‌‌های متداول
                    </router-link>
                    را مشاهده کنید.
                </div>

                <form @submit.prevent="onSubmit">
                    <div class="flex flex-col sm:flex-row flex-wrap">
                        <div class="p-2 w-full sm:w-1/2">
                            <partial-input-label title="موضوع"/>
                            <base-select
                                options-text="text"
                                options-key="value"
                                :options="contactTypes"
                                @change="(selected) => {selectedContactType = selected}"
                            />
                        </div>
                        <div class="w-full"></div>
                        <div class="p-2 w-full sm:w-1/2">
                            <base-input
                                label-title="نام"
                                placeholder="حروف فارسی"
                                name="name"
                            >
                                <template #icon>
                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                </template>
                            </base-input>
                        </div>
                        <div class="p-2 w-full sm:w-1/2">
                            <base-input
                                label-title="موبایل"
                                placeholder="09xxxxxxxxx"
                                name="name"
                                klass="tracking-widest"
                            >
                                <template #icon>
                                    <DevicePhoneMobileIcon class="h-6 w-6 text-gray-400"/>
                                </template>
                            </base-input>
                        </div>
                        <div class="p-2 w-full">
                            <base-textarea
                                label-title="توضیحات"
                                placeholder="متن پیام"
                                name="description"
                            >
                                <template #icon>
                                    <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                                </template>
                            </base-textarea>
                        </div>
                        <div class="w-full flex flex-wrap items-end">
                            <div class="p-2 w-full sm:w-1/2">
                                <div class="mb-2">
                                    <v-captcha/>
                                </div>
                                <div>
                                    <base-input name="captcha" placeholder="کد تصویر" label-title="کد تصویر:">
                                        <template #icon>
                                            <QrCodeIcon class="w-6 h-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                            </div>
                            <div class="px-2 py-3 w-full sm:w-1/2">
                                <base-button
                                    type="submit"
                                    class="bg-primary text-white mr-auto px-6 w-full sm:w-auto flex items-center gap-3 group"
                                    :disabled="isSubmitting"
                                >
                                    <VTransitionFade>
                                        <loader-circle
                                            v-if="isSubmitting"
                                            main-container-klass="absolute w-full h-full top-0 left-0"
                                            big-circle-color="border-transparent"
                                        />
                                    </VTransitionFade>

                                    <span class="mx-auto">ارسال پیام</span>
                                    <ArrowLeftIcon class="h-6 w-6 group-hover:-translate-x-1 transition"/>
                                </base-button>
                            </div>
                        </div>
                    </div>
                </form>
            </template>
        </partial-card>
    </div>

    <div class="w-full mb-6 p-3">
        <ul class="flex flex-col sm:flex-row gap-6">
            <li class="rounded-lg bg-slate-200 py-5 pl-5 pr-8 w-full">
                <div class="flex flex-col items-center gap-2">
                    <div class="p-2 rounded-full bg-blue-500">
                        <DevicePhoneMobileIcon class="w-8 h-8 text-blue-300"/>
                    </div>
                    <span class="font-iranyekan-light">شماره تماس</span>
                </div>
                <div class="mt-4 text-sm">
                    <div class="flex justify-center items-center gap-3">
                        <span class="tracking-widest font-iranyekan-bold">09179516271</span>
                        <span class="text-slate-500 text-sm">پورنسب</span>
                    </div>
                </div>
            </li>
            <li class="rounded-lg bg-slate-200 py-5 pl-5 pr-8 w-full">
                <div class="flex flex-col items-center gap-2">
                    <div class="p-2 rounded-full bg-teal-500">
                        <EnvelopeIcon class="w-8 h-8 text-teal-300"/>
                    </div>
                    <span class="font-iranyekan-light">ایمیل فروشگاه</span>
                </div>
                <div class="mt-4 font-iranyekan-bold text-sm text-center">
                    info@iriakala.ir
                </div>
            </li>
        </ul>
    </div>

    <div class="p-3 mb-12">
        <partial-card class="border-0 overflow-hidden shadow-xl">
            <template #body>
                <div class="m-2 p-3 border-r-4 border-rose-500">
                    <div class="flex items-center gap-2">
                        <MapPinIcon class="w-7 h-7 text-rose-500"/>
                        <span class="font-iranyekan-light">آدرس فروشگاه</span>
                    </div>
                    <div class="mt-4 font-iranyekan-bold leading-relaxed text-sm">
                        فارس، آباده ۴۵ شهید چمران خیابان ۱۷ شهریور
                    </div>
                </div>

                <base-loading-panel
                    :loading="mapLoading"
                >
                    <template #loader>
                        <div
                            class="px-3 py-6 h-96 flex justify-center items-center flex-col gap-3 animate-pulse">
                            <MapIcon class="h-16 w-16 text-slate-400"/>
                            <span class="text-orange-300">در حال بارگذاری نقشه</span>
                        </div>
                    </template>
                    <template #content>
                        <base-map
                            v-model:center="mapSettings.center"
                            v-model:zoom="mapSettings.zoom"
                        >
                            <template #markerPopup>
                                <div class="text-right">
                                    <div class="flex items-center gap-3 justify-center">
                                        <img
                                            src="/logo.png"
                                            alt="آیریا کالا"
                                            class="object-contain h-8 w-auto"
                                        >
                                        <h1 class="shrink-0 text-lg font-iranyekan-bold">آیریا کالا</h1>
                                    </div>
                                    <div class="flex items-center gap-2 mt-3 justify-end">
                                        <span class="text-xs text-slate-400">شماره تماس</span>
                                        <span class="tracking-widest font-iranyekan-bold">09179516271</span>
                                    </div>
                                    <div class="mt-1 text-left">
                                        پورنسب
                                    </div>
                                </div>
                            </template>
                        </base-map>
                    </template>
                </base-loading-panel>
            </template>
        </partial-card>
    </div>
</template>

<script setup>
import {reactive, ref} from "vue";
import {
    MapIcon,
    DevicePhoneMobileIcon,
    EnvelopeIcon,
    MapPinIcon,
    QrCodeIcon,
    ArrowLeftCircleIcon,
    InformationCircleIcon,
    ArrowLeftIcon,
} from "@heroicons/vue/24/outline/index.js";
import BaseMap from "../components/base/BaseMap.vue";
import AppNavigationHeader from "../components/AppNavigationHeader.vue";
import BaseLoadingPanel from "../components/base/BaseLoadingPanel.vue";
import BaseInput from "../components/base/BaseInput.vue";
import BaseTextarea from "../components/base/BaseTextarea.vue";
import VCaptcha from "../components/base/VCaptcha.vue";
import PartialCard from "../components/partials/PartialCard.vue";
import VTransitionFade from "../transitions/VTransitionFade.vue";
import BaseButton from "../components/base/BaseButton.vue";
import LoaderCircle from "../components/base/loader/LoaderCircle.vue";
import {useForm} from "vee-validate";
import yup from "../validation/index.js";
import BaseSelect from "../components/base/BaseSelect.vue";
import PartialInputLabel from "../components/partials/PartialInputLabel.vue";

const mapLoading = ref(false)
const mapSettings = reactive({
    center: [31.875060, 54.338216],
    zoom: 14,
})

const contactTypes = [
    {
        text: 'پیشنهاد',
        value: 'suggest',
    },
    {
        text: 'انتقاد یا شکایت',
        value: 'complaint',
    },
    {
        text: 'پیگیری سفارش',
        value: 'order_follow_up',
    },
    {
        text: 'سایر موضوعات',
        value: 'other',
    },
]
const selectedContactType = ref(null)

const canSubmit = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})
</script>

<style scoped>

</style>
