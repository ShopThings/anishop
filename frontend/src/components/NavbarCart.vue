<template>
    <BaseMenu :open="open" @open="() => emit('open')"
              btnClass="relative w-[45] h-[45] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center">
        <template #button>
            <span
                class="ml-2 text-black border-b-2 border-black px-1 min-w-[20px] h-5 z-[1] -top-1 -right-1.5 text-sm">2</span>

            <ShoppingBagIconOutline class="h-6 w-6 text-slate-400"/>
            <ChevronDownIcon class="h-3 w-3 mr-1 text-slate-600"/>
        </template>
        <template #items>
            <MenuItems
                class="absolute z-[10] mt-3 w-full sm:w-[22rem] rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                :class="[
                    position === 'right' ? 'right-0 origin-top-left' : 'left-0 origin-top-right',
                ]"
            >
                <div v-if="dummy" class="flex flex-col">
                    <div class="flex flex-col divide-y divide-gray-100 text-center max-h-72 pl-2 my-custom-scrollbar">
                        <div class="flex p-3 pl-10 relative">
                            <router-link to="#"
                                         class="h-24 w-[5rem] rounded-lg overflow-hidden border group shrink-0">
                                <base-lazy-image
                                    class="w-full h-full object-contain group-hover:scale-110 transition"
                                    lazy-src="/src/assets/products/p1.jpg"
                                    alt="product 1"
                                />
                            </router-link>
                            <div class="flex flex-col grow text-right">
                                <router-link to="#" class="mr-3 mt-2 text-sm hover:opacity-75 transition">
                                    کیبورد خیلی خفن گیمینگ
                                </router-link>
                                <ul class="mr-3 mt-4 w-full text-xs flex items-center divide-x divide-x-reverse">
                                    <li class="pl-2">
                                        تعداد:
                                        <span class="text-rose-500">2 عدد</span>
                                    </li>
                                    <li class="flex pr-2">
                                        رنگ:
                                        <span class="w-4 h-4 mr-1 rounded-full inline-block border shadow-sm"
                                              style="background-color: #0000ff"></span>
                                        <span class="text-rose-500 mr-1">آبی</span>
                                    </li>
                                </ul>
                            </div>
                            <base-button-close class="absolute top-0 left-0 translate-x-1/4 translate-y-3/4"/>
                        </div>
                        <div class="flex p-3 pl-10 relative">
                            <router-link to="#"
                                         class="h-24 w-[5rem] rounded-lg overflow-hidden border group shrink-0">
                                <base-lazy-image
                                    class="w-full h-full object-contain group-hover:scale-110 transition"
                                    lazy-src="/src/assets/products/p2.jpg"
                                    alt="product 2"
                                />
                            </router-link>
                            <div class="flex flex-col grow text-right">
                                <router-link to="#" class="mr-3 mt-2 text-sm hover:opacity-75 transition">
                                    کیبورد خیلی خفن گیمینگ
                                </router-link>
                                <ul class="mr-3 mt-4 w-full text-xs flex items-center divide-x divide-x-reverse">
                                    <li class="pl-2">
                                        تعداد:
                                        <span class="text-rose-500">9 عدد</span>
                                    </li>
                                    <li class="flex pr-2">
                                        رنگ:
                                        <span class="w-4 h-4 mr-1 rounded-full inline-block border shadow-sm"
                                              style="background-color: #ffffff"></span>
                                        <span class="text-rose-500 mr-1">بدون رنگ</span>
                                    </li>
                                </ul>
                            </div>
                            <base-button-close class="absolute top-0 left-0 translate-x-1/4 translate-y-3/4"/>
                        </div>
                        <div class="flex p-3 pl-10 relative">
                            <router-link to="#"
                                         class="h-24 w-[5rem] rounded-lg overflow-hidden border group shrink-0">
                                <base-lazy-image
                                    class="w-full h-full object-contain group-hover:scale-110 transition"
                                    lazy-src="/src/assets/products/p3.jpg"
                                    alt="product 3"
                                />
                            </router-link>
                            <div class="flex flex-col grow text-right">
                                <router-link to="#" class="mr-3 mt-2 text-sm hover:opacity-75 transition">
                                    کیبورد خیلی خفن گیمینگ که این متن توی این کادر شاید جاش نشه!
                                </router-link>
                                <ul class="mr-3 mt-4 w-full text-xs flex items-center divide-x divide-x-reverse">
                                    <li class="pl-2">
                                        تعداد:
                                        <span class="text-rose-500">۵ عدد</span>
                                    </li>
                                    <li class="flex pr-2">
                                        رنگ:
                                        <span class="w-4 h-4 mr-1 rounded-full inline-block border shadow-sm"
                                              style="background-color: #ff00ff"></span>
                                        <span class="text-rose-500 mr-1">صورتی</span>
                                    </li>
                                </ul>
                            </div>
                            <base-button-close class="absolute top-0 left-0 translate-x-1/4 translate-y-3/4"/>
                        </div>
                        <div class="flex p-3 pl-10 relative">
                            <router-link to="#"
                                         class="h-24 w-[5rem] rounded-lg overflow-hidden border group shrink-0">
                                <base-lazy-image
                                    class="w-full h-full object-contain group-hover:scale-110 transition"
                                    lazy-src="/src/assets/products/p4.jpg"
                                    alt="product 4"
                                />
                            </router-link>
                            <div class="flex flex-col grow text-right">
                                <router-link to="#" class="mr-3 mt-2 text-sm hover:opacity-75 transition">
                                    کیبورد خیلی خفن گیمینگ
                                </router-link>
                                <ul class="mr-3 mt-4 w-full text-xs flex items-center divide-x divide-x-reverse">
                                    <li class="pl-2">
                                        تعداد:
                                        <span class="text-rose-500">۵ عدد</span>
                                    </li>
                                    <li class="flex pr-2">
                                        رنگ:
                                        <span class="w-4 h-4 mr-1 rounded-full inline-block border shadow-sm"
                                              style="background-color: #ff00ff"></span>
                                        <span class="text-rose-500 mr-1">صورتی</span>
                                    </li>
                                </ul>
                            </div>
                            <base-button-close class="absolute top-0 left-0 translate-x-1/4 translate-y-3/4"/>
                        </div>
                        <div class="flex p-3 pl-10 relative">
                            <router-link to="#"
                                         class="h-24 w-[5rem] rounded-lg overflow-hidden border group shrink-0">
                                <base-lazy-image
                                    class="w-full h-full object-contain group-hover:scale-110 transition"
                                    lazy-src="/src/assets/products/p5.jpg"
                                    alt="product 5"
                                />
                            </router-link>
                            <div class="flex flex-col grow text-right">
                                <router-link to="#" class="mr-3 mt-2 text-sm hover:opacity-75 transition">
                                    کیبورد خیلی خفن گیمینگ
                                </router-link>
                                <ul class="mr-3 mt-4 w-full text-xs flex items-center divide-x divide-x-reverse">
                                    <li class="pl-2">
                                        تعداد:
                                        <span class="text-rose-500">۵ عدد</span>
                                    </li>
                                    <li class="flex pr-2">
                                        رنگ:
                                        <span class="w-4 h-4 mr-1 rounded-full inline-block border shadow-sm"
                                              style="background-color: #ff00ff"></span>
                                        <span class="text-rose-500 mr-1">صورتی</span>
                                    </li>
                                </ul>
                            </div>
                            <base-button-close class="absolute top-0 left-0 translate-x-1/4 translate-y-3/4"/>
                        </div>
                    </div>

                    <div class="p-3 border-t">
                        <router-link :to="{name: 'cart'}"
                                     class="flex items-center py-3 px-3 rounded-md bg-emerald-500 text-white text-center hover:bg-opacity-90 transition">
                            <span class="mr-auto">ثبت سفارش</span>
                            <CheckCircleIcon class="h-7 w-7 mr-auto opacity-60"/>
                        </router-link>
                    </div>
                </div>
                <div v-else class="flex flex-col text-center p-3">
                    <ShoppingBagIcon class="w-16 h-16 text-gray-300 mx-auto"/>
                    <h6 class="mt-2 text-gray-300">
                        سبد خرید شما خالی می‌باشد
                    </h6>
                    <router-link :to="{name: 'search'}"
                                 class="mt-4 border rounded-md px-2 py-3 bg-orange-500 border-orange-700 text-white transition hover:opacity-90">
                        ادامه خرید
                    </router-link>
                </div>

                <base-popover-side
                    panel-class="py-3 pl-6 pr-3"
                    btn-class="w-full"
                    position="left"
                >
                    <template #button>
                        <base-button class="bg-slate-100 !text-black rounded-t-none w-full text-sm">
                            سایر سبد‌های خرید
                        </base-button>
                    </template>

                    <template #panel="{close}">
                        <div class="mb-3 flex flex-row-reverse items-center">
                            <span class="mr-auto text-sm text-gray-400">
                                سبد‌های خرید
                            </span>
                            <button @click="close" type="button"
                                    class="w-[45] h-[45] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all">
                                <XMarkIcon class="h-6 w-6"/>
                            </button>
                        </div>

                        <panel-carts-user/>
                    </template>
                </base-popover-side>
            </MenuItems>
        </template>
    </BaseMenu>
</template>

<script setup>
import {ref} from "vue"
import {MenuItems} from '@headlessui/vue'
import {ChevronDownIcon, ShoppingBagIcon} from '@heroicons/vue/24/solid'
import {ShoppingBagIcon as ShoppingBagIconOutline, CheckCircleIcon} from '@heroicons/vue/24/outline'
import BaseMenu from "./base/BaseMenu.vue"
import BaseButtonClose from "./base/BaseButtonClose.vue"
import BaseButton from "./base/BaseButton.vue";
import BasePopoverSide from "./base/BasePopoverSide.vue";
import PanelCartsUser from "./user/PanelCartsUser.vue";
import {XMarkIcon} from "@heroicons/vue/24/solid/index.js";
import BaseLazyImage from "./base/BaseLazyImage.vue";

defineProps({
    open: {
        type: Boolean,
        default: false,
    },
    position: {
        type: String,
        default: 'left',
        validator: (value) => {
            return ['right', 'left'].indexOf(value) !== -1
        },
    },
})
const emit = defineEmits(['open'])

const dummy = ref(true)
</script>

<style scoped>

</style>
