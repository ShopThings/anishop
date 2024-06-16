<template>
  <app-navigation-header title="بازنگری و پرداخت"/>

  <div v-if="redirectInfo && Object.keys(redirectInfo).length">
    <redirection-gateway-form
      :action="redirectInfo.action"
      :inputs="redirectInfo.inputs"
      :method="redirectInfo.method"
    />
  </div>

  <template v-else>
    <div
      v-if="!savedOrder"
      class="px-3 py-5 flex gap-3 mb-6 border-t border-b bg-white"
    >
      <StarIcon class="w-6 h-6 text-violet-600 shrink-0"/>
      <div class="text-slate-600">
        لطفا قبل از پرداخت، حتما صفحه
        <router-link
          :to="{name: 'pages', params: {url: 'how-payment-works'}}"
          class="mx-1.5 underline underline-offset-8 text-violet-700 hover:text-opacity-90"
          target="_blank"
        >
          نحوه پرداخت و پرداخت‌های چند مرحله‌ای
        </router-link>
        را مطالعه نمایید.
      </div>
    </div>

    <div class="px-3 mb-12">
      <template v-if="user">
        <div
          v-if="savedOrder"
          class=""
        >
          <partial-card class="border-0 p-5">
            <template #body>
              <div class="flex flex-wrap items-center gap-1.5 leading-relaxed">
                <CheckCircleIcon class="size-8 text-emerald-500"/>
                <span class="font-iranyekan-light">سفارش شما با کد</span>
                <span class="tracking-widest font-sans font-semibold" dir="ltr">{{ savedOrder.code }}</span>
                <span class="font-iranyekan-light">برای</span>
                <span class="font-iranyekan-bold">{{ savedOrder.receiver_name }}</span>
                <span class="font-iranyekan-light">با شماره تماس</span>
                <span class="font-iranyekan-bold tracking-widest">{{ savedOrder.receiver_mobile }}</span>
                <span class="font-iranyekan-light">برای استان</span>
                <span class="font-iranyekan-bold">{{ savedOrder.province }}</span>
                <span class="font-iranyekan-light">و شهر</span>
                <span class="font-iranyekan-bold">{{ savedOrder.city }}</span>
                <span class="font-iranyekan-light">به آدرس</span>
                <span class="font-iranyekan-bold">{{ savedOrder.address }}</span>

                <template v-if="savedOrder.postal_code?.toString()?.trim() !== ''">
                  <span class="font-iranyekan-light">به کد پستی</span>
                  <span class="font-iranyekan-bold">{{ savedOrder.postal_code }}</span>
                </template>

                <span class="font-iranyekan-light">برای شما ثبت شد.</span>
              </div>

              <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div class="flex flex-col border border-slate-200 rounded-lg p-3 gap-2">
                  <span class="text-xs text-gray-400">روش ارسال:</span>
                  <div class="text-sm">
                    {{ savedOrder.send_method_title }}
                  </div>
                </div>
                <div class="flex flex-col border border-slate-200 rounded-lg p-3 gap-2">
                  <span class="text-xs text-gray-400">ارسال فاکتور خرید:</span>
                  <div v-if="savedOrder.is_needed_factor">
                    <span class="text-sm py-1 px-3 rounded bg-indigo-100 inline-block">بله</span>
                  </div>
                  <div v-else>
                    <span class="text-sm py-1 px-3 rounded bg-rose-100 inline-block">خیر</span>
                  </div>
                </div>
                <div class="flex flex-col border border-slate-200 rounded-lg p-3 gap-2">
                  <span class="text-xs text-gray-400">مبلغ نهایی سفارش:</span>
                  <div class="flex flex-wrap items-center gap-2">
                    <span class="font-iranyekan-bold">{{ numberFormat(savedOrder.final_price) }}</span>
                    <span class="text-xs text-gray-400">تومان</span>
                  </div>
                </div>
                <div class="flex flex-col border border-slate-200 rounded-lg p-3 gap-2">
                  <span class="text-xs text-gray-400">تاریخ ثبت سفارش:</span>
                  <div class="text-sm">
                    {{ savedOrder.ordered_at }}
                  </div>
                </div>
              </div>

              <div
                v-if="savedOrder.coupon_code"
                class="mt-2 flex flex-col border border-slate-200 rounded-lg p-3 gap-2"
              >
                <span class="text-xs text-gray-400">کوپن تخفیف:</span>

                <div class="flex flex-wrap gap-2 text-sm">
                  <span class="font-iranyekan-light">کوپن تخفیف با کد</span>
                  <span class="font-semibold tracking-wide font-sans">{{ savedOrder.coupon_code }}</span>
                  <span class="font-iranyekan-light">به مبلغ</span>
                  <div class="flex flex-wrap items-center gap-2">
                    <span class="font-iranyekan-bold">{{ numberFormat(savedOrder.coupon_code) }}</span>
                    <span class="text-xs text-gray-400">تومان</span>
                  </div>
                  <span class="font-iranyekan-light">اعمال شده است.</span>
                </div>
              </div>
            </template>
          </partial-card>

          <partial-card class="border-2 border-blue-500 py-3 px-5 mt-3 rounded-lg leading-relaxed">
            <template #body>
              <ul class="list-disc list-inside">
                <li>
                  با استفاده از لینک زیر اقدام به پرداخت نمایید در غیر این صورت، سفارش شما لغو خواهد شد.
                </li>
                <li class="mt-2">
                  لینک پرداخت در
                  <router-link
                    :to="{name: 'user.order.detail', params: {code: savedOrder.code}}"
                    class="text-blue-600 hover:text-opacity-90"
                  >
                    پنل کاربری
                  </router-link>
                  شما نیز قابل دسترسی می‌باشد.
                </li>
              </ul>
            </template>
          </partial-card>

          <partial-card class="border-2 border-yellow-500 py-3 px-5 mt-3 rounded-lg leading-relaxed">
            <template #body>
              <span class="font-iranyekan-bold text-lg">توجه</span>

              <ul class="list-disc list-inside">
                <li class="mt-2 leading-relaxed">
                  تمامی پرداخت‌ها باید در مدت زمان ۲ ساعت تکمیل شود در غیر این صورت، سفارش شما لغو خواهد شد. در این صورت
                  با پشتیبانی سایت برای استرداد وجه پرداخت شده، تماس حاصل نمایید.
                </li>
                <li class="mt-2 leading-relaxed text-rose-600">
                  در صورتی که هیچ پرداختی صورت نگیرد، رزرو کالاها پس مدت زمان نشان داده شده لغو خواهد شد.
                </li>
              </ul>
            </template>
          </partial-card>

          <div class="mt-3 flex justify-end">
            <div
              v-if="countdown && countdown.isStarted"
              class="rounded-lg flex items-center gap-3 py-2 px-4 bg-white border-2 border-emerald-500"
            >
              <span class="text-sm">زمان باقیمانده:</span>
              <div
                ref="timerRef"
                class="min-w-12 text-left text-emerald-700"
              >00:00
              </div>
            </div>

            <div
              v-else
              class="rounded-lg flex items-center gap-3 py-2 px-4 !bg-rose-50 border-2 border-rose-500"
            >
              <span class="text-sm">اتمام زمان پرداخت</span>
            </div>
          </div>

          <div
            v-if="savedOrder?.chunks"
            class="mt-3 flex flex-col gap-3"
          >
            <partial-card
              v-for="(item, idx) in savedOrder.chunks"
              :key="item.id"
              class="border-0 p-3"
            >
              <template #body>
                <partial-pay-card
                  :card-number="idx + 1"
                  :loading="paymentLoadings[id]"
                  :method-title="item.payment_method"
                  :price="item.price"
                  @click="payLinkHandler(item.id)"
                />
              </template>
            </partial-card>
          </div>
        </div>

        <form
          v-else-if="items?.length"
          class="relative"
          @submit.prevent="onSubmit"
        >
          <loader-dot-orbit
            v-if="!canSubmit"
            loading-text="در حال صدور فاکتور و آماده سازی برای پرداخت"
          />

          <div class="flex flex-col lg:flex-row gap-6 cart-checkout-sticky-container">
            <Vue3StickySidebar
              :bottom-spacing="20"
              :min-width="1024"
              :top-spacing="114"
              class="w-full lg:w-1/2"
              containerSelector=".cart-checkout-sticky-container"
              innerWrapperSelector='.sidebar__inner'
            >
              <partial-card class="border-0 p-6 w-full ring-2 ring-inset ring-yellow-300">
                <template #body>
                  <div class="text-center text-slate-400 mb-2">
                    اگر کد تخفیف دارید ، لطفاً آن را در اینجا وارد کنید
                  </div>
                  <div class="flex flex-col sm:flex-row items-end sm:items-center">
                    <div class="p-2 w-full">
                      <base-input
                        :value="couponCode"
                        placeholder="کد را وارد نمایید"
                        name="coupon_code"
                        @input="(code) => {couponCode = code}"
                      />
                    </div>
                    <div class="p-2 w-full sm:w-auto shrink-0">
                      <base-button
                        :disabled="couponCheckLoading"
                        class="bg-primary px-6 w-full"
                        type="button"
                        @click="checkCouponHandler"
                      >
                        <VTransitionFade>
                          <loader-circle
                            v-if="couponCheckLoading"
                            big-circle-color="border-transparent"
                            main-container-klass="absolute w-full h-full top-0 left-0"
                          />
                        </VTransitionFade>

                        <span>بررسی کد تخفیف</span>
                      </base-button>
                    </div>
                  </div>

                  <div
                    v-if="couponDetail"
                    class="mt-4 text-sm"
                  >
                    اعمال کوپن تخفیف
                    <span class="text-secondary">{{ couponDetail.title }}</span>
                    با کد
                    <code class="tracking-widest text-amber-600 mx-1">{{ couponDetail.code }}</code>
                    به مبلغ
                    <div class="flex flex-wrap items-center gap-2">
                      <span class="font-iranyekan-bold">{{ numberFormat(couponDetail.price) }}</span>
                      <span class="text-xs text-gray-400">تومان</span>
                    </div>
                  </div>
                </template>
              </partial-card>

              <partial-card class="border-0 p-6 w-full mt-6">
                <template #body>
                  <div class="flex items-start text-lime-600 gap-3 text-sm mb-3">
                    <InformationCircleIcon class="w-6 h-6 shrink-0"/>
                    <div class="leading-relaxed">
                      در صورت فعال نمودن این گزینه، فاکتور خرید همراه مرسولات برای شما ارسال میگردد.
                    </div>
                  </div>
                  <base-switch
                    :enabled="isNeededFactorStatus"
                    label="فاکتور برای من ارسال شود"
                    name="is_needed_factor"
                    sr-text="فاکتور برای من ارسال شود"
                  />
                </template>
              </partial-card>

              <partial-card class="border-0 p-6 w-full mt-6">
                <template #body>
                  <h2 class="text-slate-400 mb-2">
                    مشخصات خریدار
                  </h2>
                  <div class="flex flex-wrap">
                    <div class="grow p-2 w-full sm:w-1/2">
                      <div class="flex sm:flex-col gap-2">
                        <partial-input-label title="نام:"/>
                        <base-input
                          :in-edit-mode="false"
                          :is-editable="!(!!user?.first_name)"
                          :value="user?.first_name"
                          name="first_name"
                          container-class="grow"
                          placeholder="تنها حروف فارسی"
                        />
                      </div>
                    </div>
                    <div class="grow p-2 w-full sm:w-1/2">
                      <div class="flex sm:flex-col gap-2">
                        <partial-input-label title="نام خانوادگی:"/>
                        <base-input
                          :in-edit-mode="false"
                          :is-editable="!(!!user?.last_name)"
                          :value="user?.last_name"
                          name="last_name"
                          container-class="grow"
                          placeholder="تنها حروف فارسی"
                        />
                      </div>
                    </div>
                    <div class="grow p-2 w-full sm:w-1/2">
                      <div class="flex sm:flex-col gap-2">
                        <partial-input-label title="کد ملی:"/>
                        <base-input
                          :in-edit-mode="false"
                          :is-editable="!(!!user?.national_code)"
                          :value="user?.national_code"
                          name="national_code"
                          container-class="grow"
                          placeholder="از نوع عددی"
                        />
                      </div>
                    </div>
                  </div>
                </template>
              </partial-card>

              <partial-card class="border-0 p-6 w-full mt-6">
                <template #body>
                  <div class="flex items-center gap-3 justify-between">
                    <h2 class="text-slate-400 mb-2 shrink-0">
                      مشخصات گیرنده
                    </h2>

                    <base-dialog container-klass="max-w-4xl w-full">
                      <template #button="{open}">
                        <base-button
                          class="bg-secondary px-6"
                          type="button"
                          @click="open"
                        >
                          <span>انتخاب آدرس</span>
                        </base-button>
                      </template>

                      <template #title>
                        <div class="flex flex-wrap items-center justify-between gap-3 pl-6">
                          <span>انتخاب آدرس گیرنده</span>
                          <base-button
                            class="text-xs !text-black !py-1 border-2 hover:bg-slate-100"
                            type="button"
                            @click="loadUserAddresses"
                          >
                            بارگذاری مجدد آدرس‌ها
                          </base-button>
                        </div>
                      </template>

                      <template #body="{close}">
                        <VTransitionFade v-if="userAddressesLoading">
                          <div class="flex flex-col items-center gap-2 pb-3">
                            <loader-circle
                              container-bg-color=""
                              main-container-klass="relative w-full h-10"
                            />
                            <span class="text-slate-400 text-sm">در حال بارگذاری آدرس‌های شما</span>
                          </div>
                        </VTransitionFade>

                        <partial-empty-rows
                          v-else-if="!userAddresses?.length"
                          image="/images/empty-statuses/empty-address.svg"
                          image-class="w-60"
                          message="هیچ آدرسی ذخیره نشده است"
                        />

                        <div
                          v-else
                          class="flex flex-col gap-6 pb-4"
                        >
                          <div
                            v-for="address in userAddresses"
                            :key="address.id"
                            class="border-[3px] border-indigo-300 border-dashed rounded-lg px-3 pt-3 flex flex-col"
                          >
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                              <partial-card class="p-3 sm:col-span-2">
                                <template #body>
                                  <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 mb-1">نام گیرنده:</span>
                                    <div class="text-sm">
                                      {{ address.full_name }}
                                    </div>
                                  </div>
                                </template>
                              </partial-card>
                              <partial-card class="p-3 sm:col-span-2">
                                <template #body>
                                  <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 mb-1">شماره تماس:</span>
                                    <div class="text-sm tracking-widest">
                                      {{ address.mobile }}
                                    </div>
                                  </div>
                                </template>
                              </partial-card>
                              <partial-card class="p-3">
                                <template #body>
                                  <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 mb-1">استان:</span>
                                    <div class="text-sm">
                                      {{ address.province.name }}
                                    </div>
                                  </div>
                                </template>
                              </partial-card>
                              <partial-card class="p-3">
                                <template #body>
                                  <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 mb-1">شهر:</span>
                                    <div class="text-sm">
                                      {{ address.city.name }}
                                    </div>
                                  </div>
                                </template>
                              </partial-card>
                              <partial-card class="p-3 sm:col-span-2">
                                <template #body>
                                  <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 mb-1">کد پستی:</span>
                                    <div class="text-sm tracking-widest">
                                      {{ address.postal_code }}
                                    </div>
                                  </div>
                                </template>
                              </partial-card>
                              <partial-card class="p-3 sm:col-span-4">
                                <template #body>
                                  <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 mb-1">آدرس:</span>
                                    <div class="text-sm">
                                      {{ address.address }}
                                    </div>
                                  </div>
                                </template>
                              </partial-card>
                            </div>

                            <base-button
                              class="bg-indigo-600 text-sm !py-1 !rounded-full mr-auto translate-y-1/2 flex items-center gap-2"
                              type="button"
                              @click="chooseAddressHandler(close, address)"
                            >
                              <ArrowUpIcon class="size-5 rotate-45"/>
                              <span>انتخاب این آدرس</span>
                            </base-button>
                          </div>
                        </div>
                      </template>
                    </base-dialog>
                  </div>

                  <div class="flex flex-wrap">
                    <div class="p-2 w-full sm:w-1/2 lg:w-full">
                      <base-input
                        label-title="نام"
                        name="receiver_name"
                        placeholder="حروف فارسی"
                        :value="selectedAddress?.full_name"
                      />
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-full">
                      <base-input
                        label-title="شماره تماس"
                        name="receiver_mobile"
                        placeholder="09xxxxxxxxx"
                        :value="selectedAddress?.mobile"
                      />
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-full">
                      <partial-input-label title="انتخاب استان"/>
                      <base-select-searchable
                        :is-loading="provinceLoading"
                        :options="provinces"
                        :selected="selectedProvince"
                        name="province"
                        options-key="id"
                        options-text="name"
                        @change="handleProvinceChange"
                      />
                      <partial-input-error-message :error-message="errors.province"/>
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-full">
                      <partial-input-label title="انتخاب شهرستان"/>
                      <base-select-searchable
                        ref="citySelectRef"
                        :is-loading="cityLoading"
                        :options="cities"
                        :selected="selectedCity"
                        name="city"
                        options-key="id"
                        options-text="name"
                        @change="handleCityChange"
                      />
                      <partial-input-error-message :error-message="errors.city"/>
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-full">
                      <base-input
                        :is-optional="true"
                        label-title="کد پستی"
                        name="postal_code"
                        placeholder="وارد نمایید"
                        :value="selectedAddress?.postal_code"
                      />
                    </div>
                    <div class="p-2 w-full">
                      <base-textarea
                        label-title="آدرس محل سکونت"
                        name="address"
                        :value="selectedAddress?.address"
                      />
                    </div>
                  </div>
                </template>
              </partial-card>
            </Vue3StickySidebar>

            <div class="w-full lg:w-1/2">
              <partial-card class="border-0 p-4">
                <template #body>
                  <partial-general-title container-class="mb-6" title="محصولات انتخاب شده"/>
                  <ul
                    class="flex flex-col divide-y divide-slate-200 max-h-[32rem] my-custom-scrollbar border border-slate-200 bg-slate-50 rounded-lg"
                  >
                    <li
                      v-for="item in items"
                      :key="item.id"
                      class="relative flex flex-col gap-3 py-6 pr-3 pl-10"
                    >
                      <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                        <div class="shrink-0">
                          <router-link
                            :to="{name: 'product.detail', params: {slug: item.product.slug}}"
                            class="inline-block border rounded-lg"
                          >
                            <base-lazy-image
                              :alt="item.product.title"
                              :lazy-src="item.product.image.path"
                              :is-local="false"
                              class="!w-32 !h-32 object-contain hover:scale-95 transition rounded-lg"
                            />
                          </router-link>
                        </div>

                        <div class="flex gap-3 flex-col">
                          <router-link
                            :to="{name: 'product.detail', params: {slug: item.product.slug}}"
                            class="inline-block text-black hover:text-opacity-80 leading-relaxed text-sm"
                          >
                            {{ item.product.title }}
                          </router-link>

                          <div class="flex flex-wrap items-center">
                            <div
                              v-if="+item.price !== +item.actual_price"
                              class="rounded-lg bg-rose-500 text-white py-0.5 px-2 my-1 ml-3 flex items-center justify-center"
                            >
                              <span class="text-xs">%</span>
                              <div class="mr-1 inline-block text-sm">
                                {{ getPercentageOfPortion(+item.price, +item.actual_price) }}
                              </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                              <div class="text-lg font-iranyekan-bold">
                                {{ numberFormat(item.price) }}
                                <span class="text-xs text-gray-400">تومان</span>
                              </div>

                              <div
                                v-if="+item.price !== +item.actual_price"
                                class="relative text-center"
                              >
                              <span
                                class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                                <div class="text-slate-500 text-sm font-iranyekan-bold">
                                  {{ numberFormat(item.actual_price) }}
                                  <span class="text-xs text-gray-400">تومان</span>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="flex flex-wrap gap-2 items-center">
                            <div class="flex items-center gap-1">
                              <span class="text-lg font-iranyekan-bold text-gray-900">{{ item.quantity }}</span>
                              <span class="text-sm text-gray-400">{{ item.product.unit_name }}</span>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div
                        class="shrink-0 text-sm flex flex-col sm:flex-row flex-wrap gap-3 sm:items-center sm:divide-x sm:divide-x-reverse"
                      >
                        <div
                          v-if="item.color_name"
                          class="flex items-center"
                        >
                          <span class="text-gray-600 ml-2 text-xs sm:hidden">رنگ:</span>
                          {{ item.color_name }}
                          <span
                            v-tooltip.top="item.color_hex"
                            class="inline-block w-5 h-5 rounded-full border mr-2"
                            :style="'background-color:' + item.color_hex + ';'"
                          ></span>
                        </div>
                        <div
                          v-if="item.size"
                          class="sm:pr-3"
                        >
                          <span class="text-gray-600 ml-2 text-xs sm:hidden">سایز:</span>
                          {{ item.size }}
                        </div>
                        <div
                          v-if="item.guarantee"
                          class="sm:pr-3"
                        >
                          <span class="text-gray-600 ml-2 text-xs sm:hidden">گارانتی:</span>
                          {{ item.guarantee }}
                        </div>
                      </div>
                    </li>
                  </ul>

                  <div class="flex items-center gap-3 my-4">
                    <div class="grow h-0.5 bg-slate-300"></div>
                    <div class="shrink-0 rounded-full border-2 border-slate-300 w-6 h-6"></div>
                    <div class="grow h-0.5 bg-slate-300"></div>
                  </div>

                  <div class="mt-6">
                    <partial-general-title container-class="mb-6" title="خلاصه سفارش"/>
                    <ul class="flex flex-col divide-y divide-slate-300">
                      <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                        <div class="font-iranyekan-light leading-relaxed grow">
                          تعداد محصولات
                        </div>
                        <div class="shrink-0 mr-auto">
                          <div class="flex items-center rounded-full py-0.5 px-3 bg-violet-300">
                            <span class="text-base font-iranyekan-bold">{{ cartStore.count }}</span>
                            <span class="text-xs mr-2">مورد</span>
                          </div>
                        </div>
                      </li>
                      <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                        <div class="font-iranyekan-light leading-relaxed grow">
                          تعداد مرسولات
                        </div>
                        <div class="shrink-0 mr-auto">
                          <div class="flex items-center rounded-full py-0.5 px-3 bg-violet-300">
                            <span class="text-base font-iranyekan-bold">{{ cartStore.shipmentCount }}</span>
                            <span class="text-xs mr-2">مرسوله</span>
                          </div>
                        </div>
                      </li>
                      <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                        <div class="font-iranyekan-light leading-relaxed grow">
                          قیمت محصولات
                        </div>
                        <div class="shrink-0 mr-auto">
                          <span class="font-iranyekan-bold">{{ numberFormat(cartStore.totalPrice) }}</span>
                          <span class="text-slate-400 text-xs mr-2">تومان</span>
                        </div>
                      </li>
                      <li class="flex flex-wrap justify-between gap-3 py-2 text-sm relative">
                        <VTransitionFade>
                          <loader-circle
                            v-if="sendPriceLoading"
                            big-circle-color="border-transparent"
                            main-container-klass="absolute w-full h-full top-0 left-0"
                            spinner-klass="!h-6 !w-6"
                          />
                        </VTransitionFade>

                        <div class="font-iranyekan-light leading-relaxed grow">
                          هزینه ارسال
                        </div>
                        <div class="shrink-0 mr-auto">
                          <template v-if="sendPrice">{{ numberFormat(sendPrice) }}</template>
                          <template v-else-if="+sendPrice === 0">رایگان</template>
                          <template v-else>محاسبه پس از انتخاب آدرس</template>
                        </div>
                      </li>
                      <li
                        v-if="couponDetail"
                        class="flex flex-wrap justify-between gap-3 py-2 text-sm"
                      >
                        <div class="font-iranyekan-light leading-relaxed grow">
                          کوپن تخفیف
                        </div>
                        <div class="shrink-0 mr-auto">
                          <span class="font-iranyekan-bold" dir="ltr">-{{ numberFormat(couponDetail.price) }}</span>
                          <span class="text-slate-400 text-xs mr-2">تومان</span>
                        </div>
                      </li>

                      <li
                        v-if="totalDiscountPercentage > 0"
                        class="flex flex-wrap justify-between gap-3 py-2 text-sm"
                      >
                        <div class="font-iranyekan-light leading-relaxed grow">
                          درصد تخفیف
                        </div>
                        <div class="shrink-0 mr-auto">
                          <div class="flex items-center rounded-full py-0.5 px-3 bg-rose-200">
                            <span class="text-base font-iranyekan-bold">{{ totalDiscountPercentage }}</span>
                            <span class="text-xs mr-2">درصد</span>
                          </div>
                        </div>
                      </li>
                      <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                        <div class="font-iranyekan-bold leading-relaxed grow">
                          مجموع تخفیف
                        </div>
                        <div class="shrink-0 mr-auto">
                          <span class="font-iranyekan-bold" dir="ltr">-{{ numberFormat(totalDiscount) }}</span>
                          <span class="text-slate-400 text-xs mr-2">تومان</span>
                        </div>
                      </li>
                      <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                        <div class="font-iranyekan-bold leading-relaxed grow">
                          قیمت کل
                        </div>
                        <div class="shrink-0 mr-auto">
                          <span class="font-iranyekan-bold">{{ numberFormat(totalPrice) }}</span>
                          <span class="text-slate-400 text-xs mr-2">تومان</span>
                        </div>
                      </li>
                    </ul>

                    <partial-general-title container-class="my-8" title="انتخاب روش ارسال"/>
                    <partial-input-error-message :error-message="errors?.send_method"/>
                    <ul class="space-y-3">
                      <li
                        v-if="sendMethodsLoading"
                        class="flex items-center gap-2.5 border-2 rounded-md p-2 border-slate-200 animate-pulse"
                      >
                        <div class="size-6 rounded-full border-8 border-slate-200"></div>
                        <div class="rounded size-16 flex items-center justify-center border-2 border-indigo-300">
                          <PhotoIcon class="size-8 text-indigo-300"/>
                        </div>
                        <div class="w-2/3 h-3 rounded bg-slate-200"></div>
                      </li>

                      <li
                        v-else-if="!sendMethods?.length"
                        class="text-orange-300"
                      >
                        هیچ روش ارسالی وجود ندارد! برای اطلاعات بیشتر، با ما در ارتباط باشید.
                      </li>

                      <template
                        v-for="method in sendMethods"
                        v-else
                        :key="method.id"
                      >
                        <li
                          v-if="selectedCity.value?.id && method.only_for_shop_location &&
                          +homeSettingStore.getStoreCity === selectedCity.value.id"
                          class="border-2 rounded-md p-2 border-indigo-300"
                        >
                          <base-radio
                            v-model="sendMethod"
                            :label-title="method.title"
                            :value="method.id"
                            container-class="flex-row-reverse justify-end"
                            label-class="w-full"
                            name="send_method"
                          >
                            <template #text="{title}">
                              <div class="flex items-center gap-3">
                                <img
                                  :alt="method.title"
                                  :src="method.image.path"
                                  class="w-16 h-16 object-contain rounded-lg"
                                />
                                <span>{{ title }}</span>
                              </div>
                              <div
                                v-if="method?.description?.trim() !== ''"
                                class="mt-2 text-slate-500 py-1 px-2.5"
                              >
                                {{ method.description }}
                              </div>
                            </template>
                          </base-radio>
                        </li>
                        <li
                          v-else-if="!method.only_for_shop_location"
                          class="border-2 rounded-md p-2 border-indigo-300"
                        >
                          <base-radio
                            v-model="sendMethod"
                            :label-title="method.title"
                            :value="method.id"
                            container-class="flex-row-reverse justify-end"
                            label-class="w-full"
                            name="send_method"
                          >
                            <template #text="{title}">
                              <div class="flex items-center gap-3">
                                <img
                                  :alt="method.title"
                                  :src="method.image.path"
                                  class="w-16 h-16 object-contain rounded-lg"
                                />
                                <span>{{ title }}</span>
                              </div>
                              <div
                                v-if="method?.description?.trim() !== ''"
                                class="mt-2 text-slate-500 py-1 px-2.5"
                              >
                                {{ method.description }}
                              </div>
                            </template>
                          </base-radio>
                        </li>
                      </template>
                    </ul>

                    <partial-general-title container-class="my-8" title="انتخاب روش پرداخت"/>
                    <partial-input-error-message :error-message="errors?.payment_method"/>
                    <ul class="space-y-3">
                      <li
                        v-if="paymentMethodsLoading"
                        class="flex items-center gap-2.5 border-2 rounded-md p-2 border-slate-200 animate-pulse"
                      >
                        <div class="size-6 rounded-full border-8 border-slate-200"></div>
                        <div class="rounded size-16 flex items-center justify-center border-2 border-indigo-300">
                          <PhotoIcon class="size-8 text-indigo-300"/>
                        </div>
                        <div class="w-2/3 h-3 rounded bg-slate-200"></div>
                      </li>

                      <li
                        v-else-if="!paymentMethods?.length"
                        class="text-orange-300"
                      >
                        هیچ روش پرداختی وجود ندارد! برای اطلاعات بیشتر، با ما در ارتباط باشید.
                      </li>

                      <li
                        v-for="method in paymentMethods"
                        v-else
                        :key="method.id"
                        class="border-2 rounded-md p-2 border-indigo-300"
                      >
                        <base-radio
                          v-model="paymentMethod"
                          container-class="flex-row-reverse justify-end"
                          :label-title="method.title"
                          :value="method.id"
                          name="payment_method"
                          label-class="w-full"
                        >
                          <template #text="{title}">
                            <div class="flex items-center gap-3">
                              <img
                                :alt="method.title"
                                class="w-16 h-16 object-contain rounded-lg"
                                :src="method.image.path"
                              />
                              <span>{{ title }}</span>
                            </div>
                          </template>
                        </base-radio>
                      </li>
                    </ul>

                    <base-button
                      :disabled="!canSubmit"
                      class="bg-emerald-500 w-full mt-6 flex items-center gap-3 justify-center"
                      type="submit"
                    >
                      <VTransitionFade>
                        <loader-circle
                          v-if="!canSubmit"
                          big-circle-color="border-transparent"
                          main-container-klass="absolute w-full h-full top-0 left-0"
                        />
                      </VTransitionFade>

                      <span>ثبت سفارش و پرداخت</span>
                      <CreditCardIcon class="w-6 h-6"/>
                    </base-button>

                    <div
                      v-if="Object.keys(errors)?.length"
                      class="text-left"
                    >
                      <div
                        class="w-full sm:w-auto sm:inline-block text-center text-sm border-2 border-rose-500 bg-rose-50 rounded-full py-1 px-3 mt-2"
                      >
                        (
                        <span>{{ Object.keys(errors)?.length }}</span>
                        )
                        خطا، لطفا بررسی کنید
                      </div>
                    </div>
                  </div>
                </template>
              </partial-card>
            </div>
          </div>
        </form>

        <template v-else>
          <partial-empty-card/>
        </template>
      </template>

      <partial-card v-else class="border-0 p-6 text-slate-500 text-base">
        <template #body>
          ابتدا به پنل کاربری خود
          <router-link :to="{name: 'login'}" class="mx-1 text-orange-500 hover:text-opacity-80">
            وارد شوید
          </router-link>
          و سپس برای پرداخت اقدام نمایید.
        </template>
      </partial-card>
    </div>
  </template>
</template>

<script setup>
import {computed, inject, onMounted, reactive, ref, watch} from "vue";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialEmptyCard from "@/components/partials/pages/PartialEmptyCard.vue";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import {
  ArrowUpIcon,
  CheckCircleIcon,
  CreditCardIcon,
  InformationCircleIcon,
  PhotoIcon,
  StarIcon,
} from "@heroicons/vue/24/outline/index.js";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseRadio from "@/components/base/BaseRadio.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseDialog from "@/components/base/BaseDialog.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import {ProvinceAPI} from "@/service/APIShop.js";
import {UserPanelAddressAPI} from "@/service/APIUserPanel.js";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import isFunction from "lodash.isfunction";
import {HomeCheckoutAPI, HomeCouponAPI, HomePaymentMethodAPI, HomeSendMethodAPI} from "@/service/APIHomePages.js";
import {findItemByKey, getPercentageOfPortion, numberFormat} from "@/composables/helper.js";
import {useToast} from "vue-toastification";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {useCountdown} from "@/composables/countdown-timer.js";
import PartialPayCard from "@/components/partials/pages/PartialPayCard.vue";
import RedirectionGatewayForm from "@/components/RedirectionGatewayForm.vue";
import {useCartStore} from "@/store/StoreUserCart.js";

const toast = useToast()

const store = useUserAuthStore()
const user = store.getUser
const homeSettingStore = inject('homeSettingStore')
const cartStore = useCartStore()

const items = cartStore.getCartItems

const isNeededFactorStatus = ref(false)
const provinces = ref([])
const provinceLoading = ref(true)
const selectedProvince = ref(null)

const sendMethods = ref(null)
const sendMethod = ref('')
const sendMethodsLoading = ref(true)

const paymentMethods = ref(null)
const paymentMethod = ref('')
const paymentMethodsLoading = ref(true)

//------------------------------------------
// City operations
//------------------------------------------
const cities = ref([])
const cityLoading = ref(false)
const selectedCity = ref(null)
const citySelectRef = ref(null)

function loadCities(clearSelection, onSuccess) {
  if (selectedProvince.value && selectedProvince.value?.id) {
    if (citySelectRef.value && clearSelection !== false) {
      citySelectRef.value.removeSelectedItems()
    }
    cityLoading.value = true

    ProvinceAPI.fetchCities(selectedProvince.value.id, {
      success: (response) => {
        cities.value = response.data
        cityLoading.value = false

        if (isFunction(onSuccess)) {
          onSuccess()
        }
      },
    })
  }
}

function handleProvinceChange(selected) {
  selectedProvince.value = selected
  loadCities(true)
}

function handleCityChange(selected) {
  selectedCity.value = selected
}

//------------------------------------------
// Send price operation
//------------------------------------------
const sendPrice = ref(null)
const sendPriceLoading = ref(false)

watch([selectedCity, sendMethod], () => {
  if (sendPriceLoading.value || !canSubmit.value) return

  if (
    !selectedProvince.value?.id ||
    !selectedCity.value?.id
  ) {
    // if any send method is selected, check if it has independent post price
    if (sendMethod.value) {
      const sendMethodItem = findItemByKey(sendMethods.value, 'id', sendMethod.value)

      // here we check if it has independent post price
      if (sendMethodItem?.id && !sendMethodItem.determine_price_by_shop_location) {
        sendPrice.value = sendMethodItem.apply_number_of_shipments_on_price
          ? sendMethodItem.price * cartStore.shipmentCount
          : sendMethodItem.price
      } else {
        sendPrice.value = null
      }
    } else {
      sendPrice.value = null
    }
    return
  }

  sendPriceLoading.value = true
  HomeCheckoutAPI.calculateSendPrice({
    cart_name: cartStore.getActiveCart,
    items: cartStore.getCartItems,
    province: selectedProvince.value.id,
    city: selectedCity.value.id,
    send_method: sendMethod.value,
  }, {
    success(response) {
      if (response?.data === null) {
        toast.info('برای محاسبه شدن هزینه ارسال، ابتدا شهر و استان را انتخاب کنید.')
      }

      sendPrice.value = response?.data
    },
    finally() {
      sendPriceLoading.value = false
    },
  })
})

//------------------------------------------
// Coupon operation
//------------------------------------------
const couponCode = ref(null)
const couponDetail = ref(null)
const couponCheckLoading = ref(false)

function checkCouponHandler() {
  if (couponCheckLoading.value || !canSubmit.value) return

  couponCheckLoading.value = true

  HomeCouponAPI.checkCoupon(couponCode.value, {
    cart_name: cartStore.getActiveCart,
    items: cartStore.getCartItems,
  }, {
    success(response) {
      couponDetail.value = response?.data
    },
    finally() {
      couponCheckLoading.value = false
    },
  })
}

//------------------------------------------
// Address operation
//------------------------------------------
const userAddresses = ref(null)
const userAddressesLoading = ref(false)
const selectedAddress = ref(null)

function loadUserAddresses() {
  if (userAddressesLoading.value || !canSubmit.value) return

  userAddressesLoading.value = true

  UserPanelAddressAPI.fetchAll({}, {
    success(response) {
      userAddresses.value = response.data
    },
    finally() {
      userAddressesLoading.value = false
    },
  })
}

function chooseAddressHandler(close, item) {
  selectedAddress.value = item

  selectedProvince.value = item.province
  loadCities(true, () => {
    selectedCity.value = item.city
  })

  close()
}

//------------------------------------------
const totalDiscount = computed(() => {
  let discount = cartStore.totalPrice - cartStore.totalDiscountedPrice

  if (couponDetail.value?.price) {
    discount += couponDetail.value.price
  }

  return discount
})

const totalPrice = computed(() => {
  let total = cartStore.totalPrice - totalDiscount.value

  if (sendPrice.value) {
    total += sendPrice.value
  }

  return total
})

const totalDiscountPercentage = computed(() => {
  let total = cartStore.totalPrice

  if (sendPrice.value) {
    total += sendPrice.value
  }

  return getPercentageOfPortion(total - totalDiscount.value, total)
})

//------------------------------------------
const savedOrder = ref(null)
const timerRef = ref(null)
const countdown = ref(null)
const redirectInfo = ref(null)

const paymentLoadings = reactive({})

function payLinkHandler(id) {
  if (
    !savedOrder.value?.code ||
    paymentLoadings[id] === true
  ) return

  paymentLoadings[id] = true

  HomeCheckoutAPI.payOrder(id, savedOrder.value.code, {
    success(response) {
      redirectInfo.value = response.data
    },
    finally() {
      paymentLoadings[id] = false
    },
  })
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    first_name: yup.string().persian('نام باید از حروف فارسی باشد.').required('نام را وارد نمایید.'),
    last_name: yup.string().persian('نام خانوادگی باید از حروف فارسی باشد.').required('نام خانوادگی را وارد نمایید.'),
    national_code: yup.string()
      .transform(transformNumbersToEnglish)
      .persianNationalCode('کد ملی نامعتبر است.')
      .required('کد ملی را وارد نمایید.'),
    receiver_name: yup.string().persian('نام گیرنده باید از حروف فارسی باشد.').required('نام گیرنده را وارد نمایید.'),
    receiver_mobile: yup.string()
      .transform(transformNumbersToEnglish)
      .persianMobile('شماره موبایل گیرنده نامعتبر است.')
      .required('موبایل گیرنده را وارد نمایید.'),
    postal_code: yup.number().optional(),
    address: yup.string().required('آدرس خود را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!selectedProvince.value?.id) {
    actions.setFieldError('province', 'استان را انتخاب نمایید.')
    return
  }

  if (!selectedCity.value?.id) {
    actions.setFieldError('city', 'شهر را انتخاب نمایید.')
    return
  }

  if (!sendMethod.value) {
    actions.setFieldError('send_method', 'روش ارسال را انتخاب نمایید.')
    return
  }

  if (!paymentMethod.value) {
    actions.setFieldError('payment_method', 'روش پرداخت را انتخاب نمایید.')
    return
  }

  canSubmit.value = false

  HomeCheckoutAPI.placeOrder({
    cart_name: cartStore.getActiveCart,
    //
    first_name: values.first_name,
    last_name: values.last_name,
    national_code: values.national_code,
    receiver_name: values.receiver_name,
    receiver_mobile: values.receiver_mobile,
    postal_code: values.postal_code,
    address: values.address,
    //
    province: selectedProvince.value.id,
    city: selectedCity.value.id,
    send_method: sendMethod.value,
    payment_method: paymentMethod.value,
    coupon: couponDetail.value?.code,
    //
    is_needed_factor: isNeededFactorStatus.value,
  }, {
    success(response) {
      savedOrder.value = response.data
      countdown.value = useCountdown(response.data.reservation_time || 0, timerRef)

      countdown.value.start(() => {
        countdown.value.stop()
      })
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})

//------------------------------------------
onMounted(() => {
  cartStore.fetchAllLocal()
  loadUserAddresses()

  ProvinceAPI.fetchAll({
    success: (response) => {
      provinces.value = response.data
      provinceLoading.value = false
    },
  })

  HomePaymentMethodAPI.fetchAll({
    success(response) {
      paymentMethods.value = response.data

      if (response.data?.length) {
        paymentMethod.value = response.data[0]?.id
      }
    },
    finally() {
      paymentMethodsLoading.value = false
    },
  })

  HomeSendMethodAPI.fetchAll({
    success(response) {
      sendMethods.value = response.data

      if (response.data?.length) {
        sendMethod.value = response.data[0]?.id
      }
    },
    finally() {
      sendMethodsLoading.value = false
    },
  })
})
</script>
