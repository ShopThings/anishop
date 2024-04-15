<template>
  <div class="h-[calc(100%-50px)]">
    <loader-circle
      v-if="cartStore.isLoading"
      container-bg-color=""
      main-container-klass="relative h-10"
      spinner-klass="!w-6 !h-6"
    />

    <div
      :class="[
            cartStore.isLoading ? 'h-[calc(100%-2.5rem)] opacity-50' : 'h-full',
        ]"
      class="flex flex-col relative"
    >
      <div
        v-if="cartStore.isLoading"
        class="absolute -top-1 -bottom-1 -right-1 -left-1 rounded-lg bg-primary/10 z-10"
      ></div>

      <div class="flex flex-col border-b">
        <div
          :class="{
                'border-emerald-500': cartStore.isShoppingCartActivated,
            }"
          class="border-2 rounded p-3 flex items-center cursor-pointer hover:bg-gray-50 transition"
          @click="changeToShoppingCart"
        >
          <ShoppingBagIcon
            :class="[
                  cartStore.isShoppingCartActivated ? 'text-emerald-400' : 'text-gray-400',
              ]"
            class="w-6 h-6 ml-2"
          />
          <span :class="[cartStore.isShoppingCartActivated ? 'text-black' : 'text-gray-600']">سبد خرید پیش فرض</span>
        </div>

        <div class="relative">
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
                @click="() => {fetchCarts('shopping')}"
              >
                <ArrowDownOnSquareIcon
                  class="size-5 text-slate-500 group-hover:text-black transition"/>
                <span
                  class="text-sm text-slate-500 group-hover:text-black transition">بارگذاری سبد خرید از پنل</span>
              </li>
              <li
                class="flex gap-2.5 items-center group cursor-pointer py-1"
                @click="saveToShoppingCart"
              >
                <PlusIcon class="size-5 text-slate-500 group-hover:text-black group-hover:rotate-90 transition"/>
                <span
                  class="text-sm text-slate-500 group-hover:text-black transition">ذخیره سبد خرید در این محل</span>
              </li>
              <li
                class="flex gap-2.5 items-center group cursor-pointer py-1"
                @click="removeFromShoppingCart"
              >
                <TrashIcon class="size-5 text-rose-500 group-hover:text-rose-600 transition"/>
                <span class="text-sm text-rose-500 group-hover:text-rose-600 transition">حذف سبد خرید</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="flex flex-col relative">
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
            class="mt-3 border-2 rounded p-3 flex items-center cursor-pointer hover:bg-gray-50 transition"
            @click="changeToWishlistCart"
          >
            <ShoppingBagIcon
              :class="[
                    cartStore.isWishlistCartActivated ? 'text-emerald-400' : 'text-gray-400',
                ]"
              class="w-6 h-6 text-gray-400 ml-2"
            />
            <span :class="[cartStore.isWishlistCartActivated ? 'text-black' : 'text-gray-600']">سبد خرید پشتیبان</span>
          </div>

          <ul class="py-3 flex flex-col gap-2.5">
            <li
              class="flex gap-2.5 items-center group cursor-pointer py-1"
              @click="() => {fetchCarts('wishlist')}"
            >
              <ArrowDownOnSquareIcon
                class="size-5 text-slate-500 group-hover:text-black transition"/>
              <span
                class="text-sm text-slate-500 group-hover:text-black transition">بارگذاری سبد خرید از پنل</span>
            </li>
            <li
              class="flex gap-2.5 items-center group cursor-pointer py-1"
              @click="saveToWishlistCart"
            >
              <PlusIcon class="size-5 text-slate-500 group-hover:text-black group-hover:rotate-90 transition"/>
              <span class="text-sm text-slate-500 group-hover:text-black transition">ذخیره سبد خرید در این محل</span>
            </li>
            <li
              class="flex gap-2.5 items-center group cursor-pointer py-1"
              @click="removeFromWishlistCart"
            >
              <TrashIcon class="size-5 text-rose-500 group-hover:text-rose-600 transition"/>
              <span class="text-sm text-rose-500 group-hover:text-rose-600 transition">حذف سبد خرید</span>
            </li>
          </ul>
        </div>
      </div>

      <div
        class="mt-auto text-rose-600 text-center border-2 border-rose-400 rounded p-3 cursor-pointer hover:bg-rose-50 transition"
        @click="emptyCart"
      >
        خالی نمودن سبد خرید
      </div>
    </div>
  </div>
</template>

<script setup>
import {inject} from "vue";
import {ArrowDownOnSquareIcon, PlusIcon, ShoppingBagIcon, TrashIcon,} from "@heroicons/vue/24/outline";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {useToast} from "vue-toastification";
import {useConfirmToast} from "@/composables/toast-helper.js";

const toast = useToast()

const userStore = useUserAuthStore()
const cartStore = inject('cartStore')

//-------------------------------------------
// Shopping cart operations
//-------------------------------------------
function changeToShoppingCart() {
  if (cartStore.isLoading) return
  cartStore.changeToShoppingCart()
}

function saveToShoppingCart() {
  if (cartStore.isLoading) return

  if (!userStore.getUser) {
    toast.error('ابتدا به پنل کاربری خود وارد شوید.')
    return
  }

  changeToShoppingCart()
  cartStore.save()
}

function removeFromShoppingCart() {
  if (cartStore.isLoading) return

  if (!userStore.getUser) {
    toast.error('ابتدا به پنل کاربری خود وارد شوید.')
    return
  }

  changeToShoppingCart()
  cartStore.remove()
}

//-------------------------------------------
// Wishlist cart operations
//-------------------------------------------
function changeToWishlistCart() {
  if (cartStore.isLoading) return

  if (!userStore.getUser) {
    toast.error('ابتدا به پنل کاربری خود وارد شوید.')
    return
  }

  cartStore.changeToWishlistCart()
}

function saveToWishlistCart() {
  if (cartStore.isLoading) return

  if (!userStore.getUser) {
    toast.error('ابتدا به پنل کاربری خود وارد شوید.')
    return
  }

  changeToWishlistCart()
  cartStore.save()

}

function removeFromWishlistCart() {
  if (cartStore.isLoading) return

  if (!userStore.getUser) {
    toast.error('ابتدا به پنل کاربری خود وارد شوید.')
    return
  }

  changeToWishlistCart()
  cartStore.remove()
}

//-------------------------------------------
function emptyCart() {
  if (cartStore.isLoading) return

  if (!userStore.isLoading) {
    changeToShoppingCart()
  }

  cartStore.empty()
}

function fetchCarts(type) {
  if (cartStore.isLoading) return

  if (!userStore.isLoading) {
    toast.error('ابتدا به پنل کاربری خود وارد شوید.')
    return
  }

  useConfirmToast(
    () => {
      if (type === 'shopping') {
        cartStore.fetchShopping({
          success() {
            cartStore.loadToLocalShopping()
          },
        })
      } else if (type === 'wishlist') {
        cartStore.fetchWishlist({
          success() {
            cartStore.loadToLocalWishlist()
          },
        })
      }
    },
    'آیا مطمئن هستید؟',
    'سبد خرید از پنل بارگذاری شده و به سبد خرید انتخاب شده انتقال می‌باشد. اینکار آیتم‌های سبد خرید شما را خالی و با اطلاعات پنل جایگزین می‌نماید.'
  )
}
</script>
