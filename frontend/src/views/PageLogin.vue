<template>
    <!-- ====== Forms Section Start -->
    <section class="py-10">
        <div class="md:container md:mx-auto">
            <div class="flex flex-wrap justify-center items-center min-h-screen">
                <div class="w-full">
                    <div
                        class="relative mx-auto max-w-sm overflow-hidden rounded-lg bg-white shadow-xl shadow-gray-200 py-6 px-10 text-center"
                    >
                        <div class="mb-6 text-center">
                            <UserCircleIcon class="w-24 h-24 mx-auto text-slate-400"/>
                            <h1 class="text-xl text-black">
                                ورود به پنل
                            </h1>
                        </div>
                        <form @submit.prevent="loginCheck">
                            <div class="mb-3">
                                <base-input name="username"
                                            placeholder="نام کاربری"
                                            label-title="نام کاربری:">
                                    <template #icon>
                                        <UserIcon class="w-6 h-6 text-gray-400"/>
                                    </template>
                                </base-input>
                            </div>
                            <div>
                                <base-input name="password" type="password"
                                            placeholder="کلمه عبور" label-title="کلمه عبور:">
                                    <template #icon>
                                        <KeyIcon class="w-6 h-6 text-gray-400"/>
                                    </template>
                                </base-input>
                            </div>
                            <div class="mt-6 text-right">
                                <router-link :to="{name: 'forget_password.step1'}"
                                             class="mr-1 text-primary hover:text-opacity-80 transition">
                                    کلمه عبور خود را فراموش کرده‌ام!
                                </router-link>
                            </div>
                            <div class="border-t mb-6 mt-2"></div>
                            <div class="mb-2">
                                <v-captcha/>
                            </div>
                            <div class="mb-6">
                                <base-input name="captcha" placeholder="کد تصویر" label-title="کد تصویر:">
                                    <template #icon>
                                        <QrCodeIcon class="w-6 h-6 text-gray-400"/>
                                    </template>
                                </base-input>
                            </div>
                            <div class="mb-3">
                                <base-button type="submit"
                                             class="w-full flex justify-center group bg-primary border-indigo-700 text-white">
                                    <span class="mr-auto">وارد شوید</span>
                                    <ArrowLeftIcon
                                        class="h-6 w-6 text-white opacity-60 mr-auto group-hover:-translate-x-1.5 transition-all"/>
                                </base-button>
                            </div>
                            <div class="mb-6">
                                <span class="text-sm text-gray-500">حساب کاربری ندارد؟</span>
                                <router-link :to="{name: 'signup'}" class="mr-1 text-orange-500">
                                    ثبت نام کنید
                                </router-link>
                            </div>
                        </form>

                        <div>
                            <span class="absolute top-1 right-1">
                                <svg
                                    width="40"
                                    height="40"
                                    viewBox="0 0 40 40"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                <circle
                    cx="1.39737"
                    cy="38.6026"
                    r="1.39737"
                    transform="rotate(-90 1.39737 38.6026)"
                    fill="#3056D3"
                />
                <circle
                    cx="1.39737"
                    cy="1.99122"
                    r="1.39737"
                    transform="rotate(-90 1.39737 1.99122)"
                    fill="#3056D3"
                />
                <circle
                    cx="13.6943"
                    cy="38.6026"
                    r="1.39737"
                    transform="rotate(-90 13.6943 38.6026)"
                    fill="#3056D3"
                />
                <circle
                    cx="13.6943"
                    cy="1.99122"
                    r="1.39737"
                    transform="rotate(-90 13.6943 1.99122)"
                    fill="#3056D3"
                />
                <circle
                    cx="25.9911"
                    cy="38.6026"
                    r="1.39737"
                    transform="rotate(-90 25.9911 38.6026)"
                    fill="#3056D3"
                />
                <circle
                    cx="25.9911"
                    cy="1.99122"
                    r="1.39737"
                    transform="rotate(-90 25.9911 1.99122)"
                    fill="#3056D3"
                />
                <circle
                    cx="38.288"
                    cy="38.6026"
                    r="1.39737"
                    transform="rotate(-90 38.288 38.6026)"
                    fill="#3056D3"
                />
                <circle
                    cx="38.288"
                    cy="1.99122"
                    r="1.39737"
                    transform="rotate(-90 38.288 1.99122)"
                    fill="#3056D3"
                />
                <circle
                    cx="1.39737"
                    cy="26.3057"
                    r="1.39737"
                    transform="rotate(-90 1.39737 26.3057)"
                    fill="#3056D3"
                />
                <circle
                    cx="13.6943"
                    cy="26.3057"
                    r="1.39737"
                    transform="rotate(-90 13.6943 26.3057)"
                    fill="#3056D3"
                />
                <circle
                    cx="25.9911"
                    cy="26.3057"
                    r="1.39737"
                    transform="rotate(-90 25.9911 26.3057)"
                    fill="#3056D3"
                />
                <circle
                    cx="38.288"
                    cy="26.3057"
                    r="1.39737"
                    transform="rotate(-90 38.288 26.3057)"
                    fill="#3056D3"
                />
                <circle
                    cx="1.39737"
                    cy="14.0086"
                    r="1.39737"
                    transform="rotate(-90 1.39737 14.0086)"
                    fill="#3056D3"
                />
                <circle
                    cx="13.6943"
                    cy="14.0086"
                    r="1.39737"
                    transform="rotate(-90 13.6943 14.0086)"
                    fill="#3056D3"
                />
                <circle
                    cx="25.9911"
                    cy="14.0086"
                    r="1.39737"
                    transform="rotate(-90 25.9911 14.0086)"
                    fill="#3056D3"
                />
                <circle
                    cx="38.288"
                    cy="14.0086"
                    r="1.39737"
                    transform="rotate(-90 38.288 14.0086)"
                    fill="#3056D3"
                />
              </svg>
                            </span>
                            <span class="absolute left-1 bottom-1">
                                <svg
                                    width="29"
                                    height="40"
                                    viewBox="0 0 29 40"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                <circle
                    cx="2.288"
                    cy="25.9912"
                    r="1.39737"
                    transform="rotate(-90 2.288 25.9912)"
                    fill="#3056D3"
                />
                <circle
                    cx="14.5849"
                    cy="25.9911"
                    r="1.39737"
                    transform="rotate(-90 14.5849 25.9911)"
                    fill="#3056D3"
                />
                <circle
                    cx="26.7216"
                    cy="25.9911"
                    r="1.39737"
                    transform="rotate(-90 26.7216 25.9911)"
                    fill="#3056D3"
                />
                <circle
                    cx="2.288"
                    cy="13.6944"
                    r="1.39737"
                    transform="rotate(-90 2.288 13.6944)"
                    fill="#3056D3"
                />
                <circle
                    cx="14.5849"
                    cy="13.6943"
                    r="1.39737"
                    transform="rotate(-90 14.5849 13.6943)"
                    fill="#3056D3"
                />
                <circle
                    cx="26.7216"
                    cy="13.6943"
                    r="1.39737"
                    transform="rotate(-90 26.7216 13.6943)"
                    fill="#3056D3"
                />
                <circle
                    cx="2.288"
                    cy="38.0087"
                    r="1.39737"
                    transform="rotate(-90 2.288 38.0087)"
                    fill="#3056D3"
                />
                <circle
                    cx="2.288"
                    cy="1.39739"
                    r="1.39737"
                    transform="rotate(-90 2.288 1.39739)"
                    fill="#3056D3"
                />
                <circle
                    cx="14.5849"
                    cy="38.0089"
                    r="1.39737"
                    transform="rotate(-90 14.5849 38.0089)"
                    fill="#3056D3"
                />
                <circle
                    cx="26.7216"
                    cy="38.0089"
                    r="1.39737"
                    transform="rotate(-90 26.7216 38.0089)"
                    fill="#3056D3"
                />
                <circle
                    cx="14.5849"
                    cy="1.39761"
                    r="1.39737"
                    transform="rotate(-90 14.5849 1.39761)"
                    fill="#3056D3"
                />
                <circle
                    cx="26.7216"
                    cy="1.39761"
                    r="1.39737"
                    transform="rotate(-90 26.7216 1.39761)"
                    fill="#3056D3"
                />
              </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import {ref} from "vue";
import BaseInput from '../components/base/BaseInput.vue';
import BaseButton from "../components/base/BaseButton.vue";
import VCaptcha from "../components/base/VCaptcha.vue";
import {ArrowLeftIcon} from '@heroicons/vue/24/solid'
import {UserCircleIcon, UserIcon, KeyIcon, QrCodeIcon} from '@heroicons/vue/24/outline'

const canSubmit = ref(true);

function loginCheck() {
    if (canSubmit.value) {
        canSubmit.value = false;

        console.log('Login Checking!');
    }
}
</script>

<style scoped>

</style>
