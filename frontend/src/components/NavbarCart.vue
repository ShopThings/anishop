<template>
  <BaseMenu
    :open="open"
    btnClass="relative h-[40px] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center"
    @open="() => emit('open')"
  >
    <template #button>
      <span
        class="ml-2 text-black border-b-2 border-black px-1 min-w-[20px] h-5 z-[1] -top-1 -right-1.5 text-sm">{{
          numberFormat(cartStore.getCartItems?.length || 0)
        }}</span>

      <ShoppingBagIconOutline class="h-6 w-6 text-slate-400"/>
      <ChevronDownIcon class="h-3 w-3 mr-1 text-slate-600"/>
    </template>
    <template #items>
      <MenuItems
        :class="[
              position === 'right' ? 'right-0 origin-top-left' : 'left-0 origin-top-right',
          ]"
        class="absolute z-[10] mt-3 w-full sm:w-[22rem] rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
      >
        <div
          v-if="cartStore.getCartItems?.length"
          class="flex flex-col relative"
        >
          <loader-circle
            v-if="isLoading"
            main-container-klass="absolute w-full h-full"
          />

          <div class="flex flex-col divide-y divide-gray-100 text-center max-h-64 pl-2 my-custom-scrollbar">
            <div
              v-for="item in cartStore.getCartItems"
              :key="item.id"
              class="flex gap-3 p-3 pl-8 relative"
            >
              <router-link
                :to="{name: 'product.detail', params: {slug: item.product.slug}}"
                class="h-24 w-[5rem] rounded-lg overflow-hidden border group shrink-0"
              >
                <base-lazy-image
                  :alt="item.product.title"
                  :lazy-src="item.product.image.path"
                  :is-local="false"
                  class="w-full h-full object-contain group-hover:scale-110 transition"
                />
              </router-link>
              <div class="flex flex-col grow text-right">
                <router-link
                  :to="{name: 'product.detail', params: {slug: item.product.slug}}"
                  class="mt-2 text-sm hover:text-indigo-600 transition"
                >
                  {{ item.product.title }}
                </router-link>

                <div class="flex flex-wrap justify-between items-center gap-2 my-2">
                  <div class="flex items-center gap-2">
                    <span class="font-iranyekan-bold text-black text-lg">{{
                        numberFormat(cartStore.subtotalDiscountedPriceFor(item.code))
                      }}</span>
                    <span class="text-xs text-slate-400">تومان</span>
                  </div>
                  <div class="px-2 flex items-center gap-1 text-xs text-slate-500">
                    تعداد:
                    <div class="flex items-center gap-1">
                      <span class="text-base text-slate-600 font-iranyekan-bold underline">{{
                          numberFormat(item.quantity)
                        }}</span>
                      <span class="text-slate-400">{{ item.product.unit_name }}</span>
                    </div>
                  </div>
                </div>

                <base-accordion
                  btn-class="!px-2 !py-1 bg-white border-2 border-slate-200 hover:shadow-md focus-visible:ring-blue-800"
                  btn-icon-class="!mt-0"
                  open-btn-class="!border-blue-400"
                  panel-class="!p-2.5 mt-1 shadow-md rounded-lg"
                >
                  <template #button>
                    مشخصات
                  </template>

                  <template #panel>
                    <ul class="w-full text-xs flex flex-col gap-2.5">
                      <li
                        v-if="item.color_name"
                        class="flex items-start gap-1.5"
                      >
                        رنگ:
                        <span :style="'background-color:' + item.color_hex + ';'"
                              class="w-4 h-4 rounded-full inline-block border"></span>
                        <span class="text-slate-500">{{ item.color_name }}</span>
                      </li>
                      <li
                        v-if="item.size"
                        class="flex items-start gap-1"
                      >
                        سایز:
                        <span class="text-rose-500">{{ item.size }}</span>
                      </li>
                      <li
                        v-if="item.guarantee"
                        class="flex items-start gap-1"
                      >
                        گارانتی:
                        <span class="text-slate-500">{{ item.guarantee }}</span>
                      </li>
                    </ul>
                  </template>
                </base-accordion>
              </div>
              <base-button-close
                v-tooltip.right="'حذف از سبد خرید'"
                class="absolute top-0 left-1 translate-y-1/2"
                @click="removeItemHandler(item)"
              />
            </div>
          </div>

          <div class="p-3 border-t">
            <MenuItem v-slot="{close}">
              <button
                class="w-full flex items-center py-3 px-3 rounded-md bg-emerald-500 text-white text-center hover:bg-opacity-90 transition"
                @click="() => {close(); router.push({name: 'cart'});}"
              >
                <span class="mr-auto">ثبت سفارش</span>
                <CheckCircleIcon class="h-7 w-7 mr-auto opacity-60"/>
              </button>
            </MenuItem>
          </div>
        </div>
        <div v-else class="flex flex-col text-center p-3">
          <ShoppingBagIcon class="w-16 h-16 text-gray-300 mx-auto"/>
          <h6 class="mt-2 text-gray-400">
            سبد خرید شما خالی می‌باشد
          </h6>
          <router-link
            :to="{name: 'search'}"
            class="mt-4 border rounded-md px-2 py-3 bg-orange-500 border-orange-700 text-white transition hover:opacity-90"
          >
            ادامه خرید
          </router-link>
        </div>

        <base-popover-side
          class="w-full"
          btn-class="w-full"
          panel-class="py-3 pl-6 pr-3"
          position="left"
        >
          <template #button>
            <base-button class="border-amber-300 !border-2 !text-black text-sm w-full rounded-none hover:bg-amber-200">
              مدیریت سبد‌های خرید
            </base-button>
          </template>

          <template #panel="{close}">
            <div class="mb-3 flex flex-row-reverse items-center">
              <span class="mr-auto text-sm text-gray-400">سبد‌های خرید</span>
              <button
                class="w-[40px] h-[40px] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all"
                type="button"
                @click="close">
                <XMarkIcon class="h-6 w-6"/>
              </button>
            </div>

            <panel-carts-user/>
          </template>
        </base-popover-side>

        <MenuItem v-slot="{close}" class="w-full">
          <base-button
            class="bg-slate-100 border-none !text-black text-sm w-full rounded-t-none"
            @click="close"
          >
            بستن
          </base-button>
        </MenuItem>
      </MenuItems>
    </template>
  </BaseMenu>
</template>

<script setup>
import {onMounted, ref} from "vue"
import {MenuItem, MenuItems} from '@headlessui/vue'
import {ChevronDownIcon, ShoppingBagIcon} from '@heroicons/vue/24/solid'
import {CheckCircleIcon, ShoppingBagIcon as ShoppingBagIconOutline} from '@heroicons/vue/24/outline'
import BaseMenu from "./base/BaseMenu.vue"
import BaseButtonClose from "./base/BaseButtonClose.vue"
import BaseButton from "./base/BaseButton.vue";
import BasePopoverSide from "./base/BasePopoverSide.vue";
import PanelCartsUser from "./user/PanelCartsUser.vue";
import {XMarkIcon} from "@heroicons/vue/24/solid/index.js";
import BaseLazyImage from "./base/BaseLazyImage.vue";
import {numberFormat} from "@/composables/helper.js";
import {useConfirmToast} from "@/composables/toast-helper.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {useCartStore} from "@/store/StoreUserCart.js";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import {useRouter} from "vue-router";

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

const router = useRouter()

const cartStore = useCartStore()
const isLoading = ref(false)

function removeItemHandler(item) {
  if (!item?.code) return

  useConfirmToast(() => {
    isLoading.value = true

    cartStore.removeItem(item.code)

    setTimeout(() => {
      isLoading.value = false
    }, 800)
  }, 'محصول از سبد خرید حذف گردد؟')
}

onMounted(() => {
  cartStore.fetchAllLocal()
})
</script>
