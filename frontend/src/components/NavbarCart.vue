<template>
  <base-popover-side
    btn-class="w-full"
    class="w-full"
    panel-class="py-3 pl-6 pr-3"
    position="left"
  >
    <template #button>
      <div
        class="relative h-[40px] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center">
        <span
          class="ml-2 text-black border-b-2 border-black px-1 min-w-[20px] h-5 z-[1] -top-1 -right-1.5 text-sm">{{
            numberFormat(cartStore.getCartItems?.length || 0)
          }}</span>

        <ShoppingBagIconOutline class="h-6 w-6 text-slate-400"/>
        <ChevronDownIcon class="h-3 w-3 mr-1 text-slate-600"/>
      </div>
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

      <div class="h-[calc(100%-40px)] relative">
        <loader-circle
          v-if="cartStore.isLoading"
          container-bg-color=""
          main-container-klass="absolute -top-1 -bottom-1 -right-1 -left-1 rounded-lg z-10"
        />

        <div
          :class="[
              cartStore.isLoading ? 'opacity-50' : '',
          ]"
          class="flex flex-col relative h-full overflow-y-auto my-custom-scrollbar"
        >
          <div class="flex flex-col">
            <div
              :class="{
                'border-emerald-500': cartStore.isShoppingCartActivated,
            }"
              class="border-2 rounded py-2 px-3 flex items-center cursor-pointer hover:bg-gray-50 transition"
              @click="changeToShoppingCart"
            >
              <ShoppingBagIcon
                :class="[
                  cartStore.isShoppingCartActivated ? 'text-emerald-400' : 'text-gray-400',
              ]"
                class="w-6 h-6 ml-2"
              />
              <span :class="cartStore.isShoppingCartActivated ? 'text-black' : 'text-gray-500'">خرید فعلی</span>
            </div>

            <div
              v-if="cartStore.isShoppingCartActivated && cartStore.getCartItems?.length"
              class="relative border-b"
            >
              <template v-if="!userStore.getUser">
                <div
                  class="absolute w-5/6 h-0.5 bg-rose-300 -rotate-12 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[1]"
                ></div>
                <div
                  class="absolute text-center text-xs w-1/2 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[2]"
                >
                  دسترسی در صورت ورود به پنل کاربری
                </div>
              </template>

              <div
                :class="[
                  !userStore.getUser ? 'opacity-30 pointer-events-none' : ''
              ]"
              >
                <ul class="py-3 flex flex-col gap-2.5">
                  <li
                    class="flex gap-2.5 items-center group cursor-pointer py-1"
                    @click="saveToWishlistCart"
                  >
                    <ArrowDownOnSquareIcon class="size-5 text-slate-500 group-hover:text-black transition"/>
                    <span
                      class="text-sm text-slate-500 group-hover:text-black transition">انتقال به خرید بعدی</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="flex flex-col relative pb-3 border-b">
            <template v-if="!userStore.getUser">
              <div
                class="absolute w-full h-0.5 bg-rose-300 -rotate-12 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[1]"
              ></div>
              <div
                class="absolute text-center text-sm w-1/2 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[2]"
              >
                دسترسی در صورت ورود به پنل کاربری
              </div>
            </template>

            <div
              :class="[
                !userStore.getUser ? 'opacity-30 pointer-events-none' : ''
            ]"
            >
              <div
                :class="{
                  'border-emerald-500': cartStore.isWishlistCartActivated,
              }"
                class="mt-3 border-2 rounded py-2 px-3 flex items-center cursor-pointer hover:bg-gray-50 transition"
                @click="changeToWishlistCart"
              >
                <ShoppingBagIcon
                  :class="[
                    cartStore.isWishlistCartActivated ? 'text-emerald-400' : 'text-gray-400',
                ]"
                  class="w-6 h-6 ml-2"
                />
                <span
                  :class="cartStore.isWishlistCartActivated ? 'text-black' : 'text-gray-500'">خرید بعدی</span>
              </div>

              <ul
                v-if="cartStore.isWishlistCartActivated && cartStore.getCartItems?.length"
                class="py-3 flex flex-col gap-2.5"
              >
                <li
                  class="flex gap-2.5 items-center group cursor-pointer py-1"
                  @click="saveToShoppingCart"
                >
                  <ArrowDownOnSquareIcon class="size-5 text-slate-500 group-hover:text-black transition"/>
                  <span
                    class="text-sm text-slate-500 group-hover:text-black transition">انتقال به خرید فعلی</span>
                </li>
              </ul>
            </div>
          </div>

          <div
            v-if="cartStore.getCartItems?.length"
            class="flex flex-col mt-6"
          >
            <div class="flex flex-col divide-y divide-gray-100 text-center">
              <div
                v-for="item in cartStore.getCartItems"
                :key="item.id"
                class="flex flex-col gap-3 p-3 pl-8 relative"
              >
                <router-link
                  :to="{name: 'product.detail', params: {slug: item.product.slug}}"
                  class="h-24 w-24 rounded-lg overflow-hidden border group shrink-0"
                >
                  <base-lazy-image
                    :alt="item.product.title"
                    :is-local="false"
                    :lazy-src="item.product.image.path"
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
                  class="absolute top-0 left-5 translate-y-1/2"
                  @click="removeItemHandler(item)"
                />
              </div>
            </div>

            <div class="p-3 border-t">
            </div>
          </div>
          <div v-else class="flex flex-col text-center mt-3 p-3">
            <ShoppingCartIcon class="w-16 h-16 text-gray-300 mx-auto"/>
            <h6 class="mt-2 text-gray-400">
              سبد خرید شما خالی می‌باشد
            </h6>
          </div>

          <div class="flex flex-col gap-2.5 mt-auto sticky bottom-0 py-2.5 bg-white">
            <template v-if="cartStore.getCartItems?.length">
              <base-button
                class="flex items-center bg-emerald-500 border-2 border-emerald-500 hover:bg-opacity-90"
                @click="() => {close(); router.push({name: 'cart'});}"
              >
                <span class="mr-auto">ثبت سفارش</span>
                <CheckCircleIcon class="h-7 w-7 mr-auto opacity-60"/>
              </base-button>
              <base-button
                type="button"
                class="!text-rose-600 border-2 border-rose-400 hover:bg-rose-50"
                @click="emptyCart"
              >
                خالی نمودن سبد خرید
              </base-button>
            </template>
            <base-button
              v-else
              class="bg-orange-500 border-orange-700"
              type="button"
              @click="() => {close(); router.push({name: 'search'});}"
            >
              ادامه خرید
            </base-button>
          </div>
        </div>
      </div>
    </template>
  </base-popover-side>
</template>

<script setup>
import {onMounted} from "vue"
import {ChevronDownIcon, ShoppingBagIcon} from '@heroicons/vue/24/solid'
import {CheckCircleIcon, ShoppingBagIcon as ShoppingBagIconOutline, ShoppingCartIcon} from '@heroicons/vue/24/outline'
import BaseButtonClose from "./base/BaseButtonClose.vue"
import BasePopoverSide from "./base/BasePopoverSide.vue";
import {XMarkIcon} from "@heroicons/vue/24/solid/index.js";
import BaseLazyImage from "./base/BaseLazyImage.vue";
import {numberFormat} from "@/composables/helper.js";
import {useConfirmToast} from "@/composables/toast-helper.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {useCartStore} from "@/store/StoreUserCart.js";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import {useRouter} from "vue-router";
import {ArrowDownOnSquareIcon} from "@heroicons/vue/24/outline/index.js";
import {useToast} from "vue-toastification";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import BaseButton from "@/components/base/BaseButton.vue";

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
const toast = useToast()

const userStore = useUserAuthStore()

const cartStore = useCartStore()

function removeItemHandler(item) {
  if (!item?.code) return

  useConfirmToast(() => {
    cartStore.removeItem(item.code)
  }, 'محصول از سبد خرید حذف گردد؟')
}

onMounted(() => {
  cartStore.fetchAllLocal()
})

//-------------------------------------------
// Shopping cart operations
//-------------------------------------------
function changeToShoppingCart() {
  cartStore.changeToShoppingCart()
}

function saveToShoppingCart() {
  if (!userStore.getUser) {
    toast.warning('ابتدا به پنل کاربری خود وارد شوید.')
    return
  }

  cartStore.save({}, cartStore.getShoppingCartName())
}

//-------------------------------------------
// Wishlist cart operations
//-------------------------------------------
function changeToWishlistCart() {
  if (!userStore.getUser) {
    toast.warning('ابتدا به پنل کاربری خود وارد شوید.')
    return
  }

  cartStore.changeToWishlistCart()
}

function saveToWishlistCart() {
  if (!userStore.getUser) {
    toast.warning('ابتدا به پنل کاربری خود وارد شوید.')
    return
  }

  cartStore.save({}, cartStore.getWishlistCartName())

}

//-------------------------------------------
function emptyCart() {
  cartStore.empty()
}
</script>
