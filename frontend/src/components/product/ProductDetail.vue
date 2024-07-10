<template>
  <div class="flex flex-col lg:flex-row gap-3 sticky-container">
    <Vue3StickySidebar
      :bottom-spacing="20"
      :min-width="1024"
      :top-spacing="sliderTopSpacing"
      class="w-full lg:w-96 xl:w-[32rem] shrink-0 mx-auto"
      containerSelector=".sticky-container"
      innerWrapperSelector='.sidebar__inner'
    >
      <div class="flex flex-col">
        <div
          v-if="showProductExtraOption && currentMainProduct"
          class="shrink-0 p-2 bg-white shadow rounded-lg ring-1 ring-primary mb-3"
        >
          <div class="flex items-center justify-center gap-4">
            <button
              v-tooltip.left="(currentMainProduct?.is_favorited ? 'حذف از' : 'افزودن به') + ' علاقه‌مندی'"
              :class="{
                'shadow-md': currentMainProduct?.is_favorited
              }"
              class="group relative rounded p-1"
              @click="addToBookmarkHandler"
            >
              <VTransitionFade>
                <loader-circle
                  v-if="favoritingLoading"
                  big-circle-color="border-transparent"
                  main-container-klass="absolute w-full h-full top-0 left-0"
                  spinner-klass="!w-7 !h-7"
                />
              </VTransitionFade>

              <BookmarkIcon
                :class="{
                  'text-blue-600': currentMainProduct?.is_favorited
                }"
                class="w-6 h-6 text-black group-hover:text-blue-600 transition"
              />
            </button>

            <!-- these style needed when adding another options like compare option -->
            <div class="flex items-center gap-4 mr-auto">
              <div>
                <base-dialog v-model:open="isDialogOpen">
                  <template #button="{open}">
                    <button
                      v-tooltip.right="'به اشتراک گذاری'"
                      class="group p-1"
                      @click="open"
                    >
                      <ShareIcon class="w-6 h-6 text-black group-hover:text-opacity-80 transition"/>
                    </button>
                  </template>

                  <template #title>
                    اشتراک گذاری محصول
                  </template>

                  <template #body>
                    <div class="mb-3 font-iranyekan-bold">استفاده از لینک</div>
                    <div class="flex flex-row-reverse gap-2 items-center">
                      <div
                        class="text-slate-500 rounded-lg grow border-2 border-slate-200 py-2.5 px-3 text-left font-sans font-semibold text-lg"
                        dir="ltr"
                      >
                        {{ productShortUrl }}
                      </div>

                      <button
                        v-tooltip.left="'کپی کردن'"
                        class="rounded-lg p-2.5 cursor-pointer border-2 border-emerald-300 bg-emerald-50 h-full hover:bg-emerald-100 hover:border-emerald-500 transition"
                        type="button"
                        @click="copyHandler"
                      >
                        <ClipboardIcon class="size-6"/>
                      </button>
                    </div>

                    <hr class="my-5">

                    <div class="mb-2 font-iranyekan-bold text-center">اشتراک در شبکه‌های اجتماعی</div>
                    <div class="mt-6 flex items-center justify-center gap-2.5">
                      <a
                        v-tooltip.top="'اشتراک گذاری در ' + SOCIAL_NETWORKS.EMAIL.text"
                        :href="emailSharingLink"
                        class="flex items-center justify-center rounded-lg size-10 border-[3px] border-rose-500 text-rose-600 hover:border-black hover:text-black transition"
                        target="_blank"
                        v-html="SOCIAL_NETWORKS.EMAIL.icon"
                      ></a>
                      <a
                        v-tooltip.top="'اشتراک گذاری در ' + SOCIAL_NETWORKS.X.text"
                        :href="twitterSharingLink"
                        class="flex items-center justify-center rounded-lg size-10 border-[3px] border-blue-500 text-blue-600 hover:border-black hover:text-black transition"
                        target="_blank"
                        v-html="SOCIAL_NETWORKS.X.icon"
                      ></a>
                      <a
                        v-tooltip.top="'اشتراک گذاری در ' + SOCIAL_NETWORKS.TELEGRAM.text"
                        :href="telegramSharingLink"
                        class="flex items-center justify-center rounded-lg size-10 border-[3px] border-sky-500 text-sky-600 hover:border-black hover:text-black transition"
                        target="_blank"
                        v-html="SOCIAL_NETWORKS.TELEGRAM.icon"
                      ></a>
                      <a
                        v-tooltip.top="'اشتراک گذاری در ' + SOCIAL_NETWORKS.WHATSAPP.text"
                        :href="whatsappSharingLink"
                        class="flex items-center justify-center rounded-lg size-10 border-[3px] border-green-500 text-green-600 hover:border-black hover:text-black transition"
                        target="_blank"
                        v-html="SOCIAL_NETWORKS.WHATSAPP.icon"
                      ></a>
                    </div>
                  </template>
                </base-dialog>
              </div>
            </div>
          </div>
        </div>

        <div>
          <div class="relative mb-3 mx-auto">
            <template v-if="!currentMainProduct">
              <div class="animate-pulse">
                <div class="absolute flex item-center gap-3 left-3 top-96 -translate-y-[calc(100%+0.625rem)] z-[1]">
                  <div class="flex items-center justify-center bg-white rounded-full p-2">
                    <ChevronRightIcon class="size-6 text-slate-400"/>
                  </div>
                  <div class="flex items-center justify-center bg-white rounded-full p-2">
                    <ChevronLeftIcon class="size-6 text-slate-400"/>
                  </div>
                </div>
                <div class="rounded-lg flex items-center justify-center size-full bg-slate-200 h-96">
                  <PhotoIcon class="size-12 sm:size-16 text-slate-400"/>
                </div>
                <div class="overflow-hidden flex gap-3 mt-3">
                  <div
                    v-for="i in 8"
                    :key="i"
                    class="flex items-center justify-center rounded-lg size-28 min-w-28 bg-slate-200"
                  >
                    <PhotoIcon class="size-10 text-slate-400"/>
                  </div>
                </div>
              </div>
            </template>
            <template v-else>
              <button
                class="absolute shadow-lg top-0 left-0 translate-y-6 translate-x-6 rounded-full border bg-white p-2 w-11 h-11 group z-[2] hover:border-blue-500 hover:bg-blue-50 transition"
                @click="onLightboxShow"
              >
                <MagnifyingGlassPlusIcon class="w-6 h-6 mx-auto group-hover:scale-110 transition"/>
              </button>

              <base-carousel
                v-model="slides"
                v-model:current="currentSlide"
                :breakpoints="mainGallerySettings.breakpoints"
                :class-name="mainGallerySettings.className"
                :effect="mainGallerySettings.effect"
                :has-navigation="mainGallerySettings.hasNavigation"
                :use-thumbnail="mainGallerySettings.useThumbnail"
                :wrap-around="mainGallerySettings.wrapAround"
                @slide-change="(index) => {currentSlide = index}"
              >
                <template #default="{slide}">
                  <div class="bg-white border rounded-lg lg:h-96 xl:h-[32rem]">
                    <base-lazy-image
                      :alt="currentMainProduct.title"
                      :lazy-src="slide.path"
                      :is-local="false"
                      class="rounded-lg"
                    />
                  </div>
                </template>

                <template #thumbSlide="{slide}">
                  <div
                    class="bg-white border rounded-lg cursor-pointer"
                  >
                    <base-lazy-image
                      :alt="currentMainProduct.title"
                      :lazy-src="slide.path"
                      :is-local="false"
                      class="!w-auto !h-28 rounded-lg"
                    />
                  </div>
                </template>
              </base-carousel>
            </template>
          </div>
        </div>
      </div>
    </Vue3StickySidebar>

    <div class="grow">
      <div class="bg-white rounded-lg border px-4 py-6 relative">
        <template v-if="!currentMainProduct">
          <div class="animate-pulse">
            <div class="rounded h-5 w-3/4 bg-slate-200"></div>

            <div class="rounded h-4 w-28 bg-slate-400 mt-3"></div>

            <div class="mt-5 flex flex-wrap items-center gap-4 bg-blue-50 py-3 px-2">
              <div class="rounded h-3 w-20 bg-blue-200"></div>
              <div class="rounded h-3 w-20 bg-blue-200"></div>
            </div>

            <hr class="h-0.5 mx-auto my-5 bg-slate-100 border-0 rounded">

            <div class="mb-6 h-4 rounded bg-slate-400 w-28"></div>

            <ul class="flex flex-col gap-4">
              <li class="flex items-center gap-3">
                <span class="bg-slate-200 h-3 w-28 rounded"></span>
                <span class="bg-slate-300 h-3 w-12 rounded"></span>
              </li>
              <li class="flex items-center gap-3">
                <span class="bg-slate-200 h-3 w-20 rounded"></span>
                <span class="bg-slate-300 h-3 w-28 rounded"></span>
              </li>
              <li class="flex items-center gap-3">
                <span class="bg-slate-200 h-3 w-36 rounded"></span>
                <span class="bg-slate-300 h-3 w-16 rounded"></span>
              </li>
            </ul>

            <hr class="h-0.5 mx-auto my-5 bg-slate-100 border-0 rounded">

            <div class="flex items-center gap-3">
              <template
                v-for="i in 3"
                :key="i"
              >
                <div class="rounded-full flex items-center justify-center p-1 size-8 border-2 border-slate-200">
                  <div class="rounded-full bg-slate-300 size-full"></div>
                </div>
                <div class="rounded bg-slate-200 w-12 h-3"></div>
              </template>
            </div>

            <div class="flex items-center gap-3 mt-5">
              <template
                v-for="i in 3"
                :key="i"
              >
                <div
                  class="rounded flex items-center justify-center p-1 w-16 h-7 border-2 border-indigo-200 bg-indigo-100">
                </div>
              </template>
            </div>

            <div class="mt-5">
              <div class="rounded border-2 border-slate-300 bg-slate-100 h-8 w-40 flex items-center justify-end p-2">
                <ChevronDownIcon class="size-5 text-slate-400"/>
              </div>
            </div>

            <hr class="h-0.5 mx-auto my-5 bg-slate-100 border-0 rounded">

            <div class="flex items-center gap-3">
              <div class="rounded bg-primary/30 w-36 h-10 px-3 flex items-center justify-center">
                <div class="rounded bg-white/50 w-full h-3"></div>
              </div>

              <div class="flex items-center justify-center border-2 border-slate-200 rounded divide-x divide-x-reverse">
                <div class="h-10 flex items-center justify-center px-3">
                  <PlusIcon class="size-5 text-slate-300"/>
                </div>
                <div class="h-10 flex items-center justify-center px-3">
                  <div class="rounded bg-slate-200 w-7 h-2"></div>
                </div>
                <div class="h-10 flex items-center justify-center px-3">
                  <MinusIcon class="size-5 text-slate-300"/>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <div
            v-if="selectedProduct && selectedProduct.is_special"
            class="flex items-center justify-center gap-2 my-3"
          >
            <div class="grow h-0.5 bg-red-400 rounded-full"></div>
            <span class="text-sm text-red-600 shrink-0">فروش ویژه</span>
            <div class="grow h-0.5 bg-red-400 rounded-full"></div>
          </div>

          <div
            v-if="selectedProduct.updated_at"
            class="flex gap-1.5 items-center my-2"
          >
            <span class="text-slate-400 text-xs">آخرین بروزرسانی:</span>
            <span class="text-amber-500 text-sm">{{ selectedProduct.updated_at }}</span>
          </div>

          <h1 class="text-xl leading-loose hyphens-auto break-words font-iranyekan-bold">
            {{ currentMainProduct.title }}
          </h1>

          <template v-if="selectedProduct">
            <div
              v-if="selectedProduct.show_coming_soon"
              class="bg-cyan-500 py-2 px-4 rounded text-sm text-white text-center mt-3"
            >
              این محصول به زودی موجود می‌شود
            </div>
            <div
              v-else-if="selectedProduct.show_call_for_more"
              class="bg-amber-500 py-2 px-4 rounded text-sm text-white text-center mt-3"
            >
              برای اطلاعات بیشتر تماس بگیرید
            </div>
            <div
              v-else-if="!selectedProduct.is_available || selectedProduct.stock_count <= 0"
              class="bg-rose-500 py-2 px-4 rounded text-sm text-white text-center mt-3"
            >
              اتمام موجودی
            </div>
            <template v-else>
              <div class="flex flex-wrap mt-3 items-center">
                <div class="ml-3 text-xl my-1 font-iranyekan-bold">
                  <template v-if="getBuyablePrice === 0">
                    رایگان
                  </template>
                  <template v-else>
                    {{ numberFormat(getBuyablePrice) }}
                    <span class="text-xs text-gray-400">تومان</span>
                  </template>
                </div>

                <template v-if="getBuyablePrice < selectedProduct.price && +selectedProduct.price !== 0">
                  <div class="relative ml-3 my-1">
                  <span
                    class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                    <div class="text-slate-500">
                      {{ numberFormat(selectedProduct.price) }}
                      <span class="text-xs text-gray-400">تومان</span>
                    </div>
                  </div>
                  <div
                    v-if="getPercentageOfPortion(getBuyablePrice, selectedProduct.price, true) > 0"
                    class="rounded-lg bg-rose-500 text-white text-sm py-1 px-2 my-1 flex items-center gap-0.5"
                  >
                    {{ getPercentageOfPortion(getBuyablePrice, selectedProduct.price, true) }}
                    <span class="text-xs">%</span>
                    <span class="text-xs mr-0.5">تخفیف</span>
                    <span v-if="hasFestivalDiscount" class="text-xs">جشنواره</span>
                  </div>
                </template>
              </div>

              <div
                v-if="getDiscountTimer"
                class="mt-3"
              >
                <partial-countdown-show
                  :duration="getDiscountTimer"
                  :hide-after-end="true"
                />
              </div>
            </template>
          </template>
          <div v-else class="text-teal text-sm mt-3">
            برای مشاهده قیمت ابتدا محصول را انتخاب نمایید
          </div>

          <div class="mt-3 flex flex-wrap items-center gap-4 bg-blue-50 py-1 px-2">
            <div class="flex items-center">
              <span class="text-xs ml-2">برند:</span>
              <router-link
                :to="{name: 'search', query: {brand: currentMainProduct.brand_id}}"
                class="text-sm text-blue-500 hover:text-opacity-80 transition"
              >
                {{ currentMainProduct.brand.name }}
              </router-link>
            </div>
            <span class="w-2 h-2 rounded-full bg-gray-300"></span>
            <div class="flex items-center">
              <span class="text-xs ml-2">دسته‌بندی:</span>
              <router-link
                :to="{name: 'search', query: {category: currentMainProduct.category_id}}"
                class="text-sm text-blue-500 hover:text-opacity-80 transition"
              >
                {{ currentMainProduct.category.name }}
              </router-link>
            </div>
          </div>

          <template v-if="currentMainProduct.quick_properties?.length">
            <hr class="h-0.5 mx-auto my-3 bg-slate-100 border-0 rounded">

            <h3 class="mb-2">
              ویژگی‌ها
            </h3>

            <ul class="flex flex-col gap-2 text-slate-500 text-sm">
              <li
                v-for="(property, idx) in currentMainProduct.quick_properties"
                :key="idx"
                class="flex items-center gap-2"
              >
                <span>{{ property.title }} :</span>
                <span class="text-black">{{
                    Array.isArray(property.tags) ? property.tags.join(', ') : property.tags
                  }}</span>
              </li>
            </ul>
          </template>

          <hr class="h-0.5 mx-auto my-3 bg-slate-100 border-0 rounded">

          <div v-if="colorFilteredLength">
            <h4 class="mb-2">
              رنگ:
              <span v-if="selectedColor">{{ selectedColor.color_name }}</span>
            </h4>

            <ul class="flex flex-wrap gap-2">
              <template
                v-for="color in colorFiltered"
                :key="color.id"
              >
                <li
                  v-if="color.color_name && color.color_name?.toString() !== ''"
                  v-tooltip.top="'' + color.color_name + ''"
                  :class="[
                    'relative rounded-full w-10 h-10 border-2 p-1.5 transition',
                    color.active === 'yes'
                    ? 'border-indigo-500 cursor-pointer shadow-lg'
                    : (
                        color.active === 'no'
                        ? 'cursor-default opacity-80'
                        : 'hover:ring-2 cursor-pointer shadow-lg'
                    ),
                    selectedColor?.color_name === color.color_name
                    ? '!border-emerald-500 cursor-pointer shadow-lg !p-1 bg-emerald-50'
                    : ''
                ]"
                  @click="handleColorChange(color)"
                >
                  <div
                    v-if="color.active === 'no'"
                    class="absolute top-1/2 -translate-y-1/2 left-0 w-full h-0.5 rounded-full bg-rose-400 z-[1] -rotate-45"
                  ></div>

                  <div
                    :style="'background-color:' + color.color_hex"
                    class="w-full h-full shadow rounded-full border border-slate-200"
                  ></div>
                </li>
              </template>
            </ul>
          </div>

          <div v-if="sizeFilteredLength" class="mt-3">
            <h4 class="mb-2">
              سایز:
              <span v-if="selectedSize">{{ selectedSize.size }}</span>
            </h4>

            <ul class="flex flex-wrap gap-2">
              <template
                v-for="size in sizeFiltered"
                :key="size.id"
              >
                <li
                  v-if="size.size && size.size?.toString() !== ''"
                  :class="[
                    'relative rounded-lg py-1 px-3 border-2 transition',
                    size.active === 'yes'
                    ? 'border-indigo-500 bg-indigo-50 cursor-pointer'
                    : (
                        size.active === 'no'
                        ? 'cursor-default opacity-60'
                        : 'hover:bg-gray-100 cursor-pointer'
                    ),
                    selectedSize?.size === size.size
                    ? '!border-emerald-500 !bg-emerald-50 cursor-pointer'
                    : ''
                ]"
                  @click="handleSizeChange(size)"
                >
                  <div
                    v-if="size.active === 'no'"
                    class="absolute top-1/2 -translate-y-1/2 left-0 w-full h-0.5 rounded-full bg-rose-400 z-[1] -rotate-45"
                  ></div>

                  {{ size.size }}
                </li>
              </template>
            </ul>
          </div>

          <div v-if="guaranteeFilteredLength" class="mt-3">
            <h4 class="mb-2">
              <span class="flex items-center">
                <CheckBadgeIcon class="h-7 w-7 text-emerald-400 ml-1.5"/>
                گارانتی:
              </span>
              <span v-if="selectedGuarantee" class="text-sm my-1">{{ selectedGuarantee.guarantee }}</span>
            </h4>

            <div class="w-full md:w-2/3">
              <base-select
                :options="guaranteeFiltered"
                :selected="selectedGuarantee"
                options-key="id"
                options-text="guarantee"
                @change="handleGuaranteeChange"
              >
                <template #item="{item, selected}">
                  <div class="flex items-center cursor-pointer">
                    <CheckCircleIcon
                      v-if="item.active === 'yes'"
                      class="w-5 h-5 ml-2 text-emerald-500 shrink-0"
                    />
                    <XCircleIcon
                      v-else-if="item.active === 'no'"
                      class="w-5 h-5 ml-2 text-rose-400 shrink-0"
                    />
                    <span
                      :class="[
                          item.active === 'yes'
                          ? 'text-emerald-500'
                          : (
                              item.active === 'no'
                               ? 'text-rose-400'
                               : 'text-gray-500 grow'
                          ),
                          selectedGuarantee?.guarantee === item.guarantee
                          ? 'bg-emerald-50 border border-emerald-500 rounded p-1 grow'
                          : ''
                      ]"
                    >{{ item.guarantee }}</span>
                  </div>
                </template>
              </base-select>
            </div>
          </div>

          <template v-if="showAddToCart && selectedProduct.stock_count && selectedProduct.max_cart_count">
            <hr class="h-0.5 mx-auto my-3 bg-slate-100 border-0 rounded">

            <div class="flex flex-wrap flex-row-reverse justify-end gap-3">
              <base-spinner
                v-model="currentProductCartCounting"
                :max="selectedProduct?.max_cart_count || 1"
                :min="1"
                class="grow sm:grow-0"
              />

              <base-animated-button
                :disabled="cartStore?.isLoading"
                class="bg-primary text-white px-6 w-full sm:w-auto"
                @click="addToCartHandler"
              >
                <VTransitionFade>
                  <loader-circle
                    v-if="cartStore?.isLoading"
                    big-circle-color="border-transparent"
                    main-container-klass="absolute w-full h-full top-0 left-0"
                  />
                </VTransitionFade>

                <template #icon="{klass}">
                  <ShoppingCartIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                </template>

                <span class="ml-auto">افزودن به سبد خرید</span>
              </base-animated-button>
            </div>

            <div
              v-if="currentProductInCart"
              class="flex flex-wrap items-center gap-1 text-sm mt-3 text-amber-600"
            >
              تعداد
              <span class="text-base underline text-black font-iranyekan-bold">{{
                  currentProductInCart.quantity
                }}</span>
              <span class="text-slate-400">{{ currentProductInCart.product.unit_name }}</span>
              از این محصول در سبد خرید شما موجود می‌باشد.
            </div>
          </template>
        </template>
      </div>
    </div>
  </div>

  <vue-easy-lightbox
    :imgs="itemsRef"
    :index="currentSlide"
    :rtl="true"
    :visible="visibleRef"
    @hide="onLightboxHide"
  ></vue-easy-lightbox>

  <div
    v-if="!currentMainProduct && !relatedProducts?.length"
    class="mt-12"
  >
    <div
      v-if="!currentMainProduct && !relatedProducts?.length"
      class="flex items-center gap-6 overflow-hidden"
    >
      <loader-card
        v-for="i in 5"
        :key="i"
        class="!w-80 rounded-lg shadow-lg"
      />
    </div>
    <template v-else-if="relatedProducts?.length">
      <partial-general-title title="محصولات مرتبط"/>

      <product-carousel :products="relatedProducts"/>
    </template>
  </div>

  <template v-if="!currentMainProduct">
    <div class="mt-12 animate-pulse">
      <div class="bg-white rounded p-2">
        <div class="flex items-center gap-2">
          <div
            v-for="i in 3"
            :key="i"
            class="rounded h-8 w-28 bg-slate-200"
          ></div>
        </div>
      </div>

      <div class="bg-white rounded p-6 mt-3">
        <loader-text/>
      </div>
    </div>
  </template>
  <div
    v-else-if="Object.keys(tabs).length"
    class="mt-12"
  >
    <base-tab-panel
      v-model:tabs="tabs"
      tab-button-extra-class="w-full sm:w-auto sm:grow-0 px-6"
    >
      <template #description>
        <div
          class="p-3 styled-description"
          v-html="currentMainProduct.description"
        ></div>
      </template>

      <template #properties>
        <template
          v-for="(property, idx) in currentMainProduct.properties"
          :key="idx"
        >
          <h1
            :class="idx !== 0 ? 'mt-5' : ''"
            class="text-lg text-indigo-600 mb-2 flex items-center"
          >
            <StopIcon class="w-4 h-4 ml-1 rotate-45 bg-violet-600 rounded bg-opacity-20"/>
            <span>{{ property.title }}</span>
          </h1>

          <div>
            <div
              v-for="(child, idx2) in property.children"
              :key="idx2"
              class="flex flex-col mb-2 shadow md:shadow-none md:mb-0 md:grid md:grid-cols-3"
            >
              <div
                :class="idx2 % 2 === 0 ? 'md:bg-slate-100' : 'md:bg-transparent'"
                class="p-2 text-center text-sm bg-slate-100 text-slate-500  md:text-right md:rounded-l-none md:col-span-1"
              >
                {{ child.title }}
              </div>
              <div
                :class="idx2 % 2 === 0 ? 'md:bg-slate-100' : ''"
                class="grow text-center md:text-right md:col-span-2"
              >
                <div
                  v-for="(subProperty, idx3) in child.tags"
                  :key="idx3"
                  class="p-2"
                >
                  {{ subProperty }}
                </div>
              </div>
            </div>
          </div>
        </template>
      </template>

      <template #comments>
        <product-comment
          :product-slug="currentMainProduct.slug"
          :product-title="currentMainProduct.title"
          :allow-comment-operations="allowCommentOperations"
          :show-add-comment="showAddComment"
          @totalCommentChanged="(total) => {tabs.comments.button.badgeCount = total}"
        />
      </template>
    </base-tab-panel>
  </div>
</template>

<script setup>
import {computed, onMounted, ref, watchEffect} from "vue";
import VueEasyLightbox from 'vue-easy-lightbox'
import Vue3StickySidebar from "vue3-sticky-sidebar";
import {
  BookmarkIcon,
  CheckBadgeIcon,
  CheckCircleIcon,
  ChevronDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  ClipboardIcon,
  MagnifyingGlassPlusIcon,
  MinusIcon,
  PhotoIcon,
  PlusIcon,
  ShareIcon,
  ShoppingCartIcon,
  StopIcon,
  XCircleIcon,
} from "@heroicons/vue/24/outline/index.js"
import BaseCarousel from "@/components/base/BaseCarousel.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import ProductCarousel from "./ProductCarousel.vue";
import BaseTabPanel from "@/components/base/BaseTabPanel.vue";
import ProductComment from "./ProductComment.vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import {getPercentageOfPortion, getRouteParamByKey, numberFormat} from "@/composables/helper.js";
import {useLightbox} from "@/composables/lightbox-view.js";
import {HomeProductAPI} from "@/service/APIHomePages.js";
import {UserPanelFavoriteProductAPI} from "@/service/APIUserPanel.js";
import BaseSpinner from "@/components/base/BaseSpinner.vue";
import LoaderCard from "@/components/base/loader/LoaderCard.vue";
import LoaderText from "@/components/base/loader/LoaderText.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseDialog from "@/components/base/BaseDialog.vue";
import {useToast} from "vue-toastification";
import {useClipboard} from "@vueuse/core";
import {SOCIAL_NETWORKS} from "@/composables/constants.js";
import PartialCountdownShow from "@/components/partials/PartialCountdownShow.vue";
import {apiRoutes} from "@/router/api-routes.js";
import {useCartStore} from "@/store/StoreUserCart.js";

const props = defineProps({
  showProductExtraOption: {
    type: Boolean,
    default: true,
  },
  showAddToCart: {
    type: Boolean,
    default: true,
  },
  allowCommentOperations: {
    type: Boolean,
    default: true,
  },
  showAddComment: {
    type: Boolean,
    default: true,
  },
  sliderTopSpacing: {
    type: Number,
    default: 20,
  },
})
const emit = defineEmits(['product-loaded'])

const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const isDialogOpen = ref(false)

const productCartCounting = ref([])
const currentProductCartCounting = computed({
  get() {
    let idx = productCartCounting.value.findIndex(item => item.code === selectedProduct.value?.code)
    return idx !== -1 ? productCartCounting.value[idx].quantity : 0
  },
  set(value) {
    let idx = productCartCounting.value.findIndex(item => item.code === selectedProduct.value?.code)

    if (idx !== -1) {
      let v = parseInt(value, 10)
      v = isNaN(v) || v <= 0 ? 1 : v

      if (v > selectedProduct.value.max_cart_count) {
        v = selectedProduct.value.max_cart_count
      }

      productCartCounting.value[idx].quantity = v
    }
  }
})

const currentProductInCart = computed(() => {
  if (!selectedProduct.value?.code) return null

  let idx = productCartCounting.value.findIndex(item => item.code === selectedProduct.value.code)
  if (idx !== -1) {
    let item = cartStore.findItemFromLocalCart(selectedProduct.value?.code)
    return item && item.quantity > 0 ? item : 0
  }
  return null
})
const isExceedCurrentProductInCart = computed(() => {
  let currentInCart = currentProductInCart.value
  return currentInCart && currentInCart.quantity >= currentInCart.max_cart_count
})

const mainGallerySettings = {
  className: 'detail-slider',
  effect: 'slide',
  wrapAround: true,
  hasNavigation: true,
  useThumbnail: true,
  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    480: {
      slidesPerView: 1.5,
    },
    768: {
      slidesPerView: 2,
    },
    1024: {
      slidesPerView: 1,
    },
  },
}

const slides = ref([])
const currentSlide = ref(0)

const imageUrl = computed(() => {
  return import.meta.env.VITE_API_BASE_URL + apiRoutes.showFile + '?file='
})
const previewingItems = ref([])
const {visibleRef, itemsRef, onLightboxShow, onLightboxHide} = useLightbox(previewingItems)

const currentMainProduct = ref(null)
const relatedProducts = ref([])
const products = ref([])
const selectedProduct = ref(null)
const selectedColor = ref(null)
const selectedSize = ref(null)
const selectedGuarantee = ref(null)

watchEffect(() => {
  if (selectedColor.value && selectedSize.value && selectedGuarantee.value) {
    for (let i of products.value) {
      if (i.id === selectedColor.value.id)
        selectedProduct.value = i
    }
  }
})

//------------------------------------------------------------
// Discount checking
//------------------------------------------------------------
const hasGeneralDiscount = computed(() => {
  const currentDate = new Date()
  const seconds = currentDate.getTime() / 1000

  return (
    (
      !selectedProduct.value.discounted_from_in_seconds &&
      selectedProduct.value.discounted_until_in_seconds &&
      selectedProduct.value.discounted_until_in_seconds >= seconds
    ) ||
    (
      selectedProduct.value.discounted_from_in_seconds &&
      !selectedProduct.value.discounted_until_in_seconds &&
      selectedProduct.value.discounted_from_in_seconds <= seconds
    ) ||
    (
      selectedProduct.value.discounted_from_in_seconds &&
      selectedProduct.value.discounted_until_in_seconds &&
      selectedProduct.value.discounted_until_in_seconds >= seconds &&
      selectedProduct.value.discounted_from_in_seconds <= seconds
    )
  )
})

const hasFestivalDiscount = computed(() => {
  const currentDate = new Date()
  const seconds = currentDate.getTime() / 1000

  return (
    (
      !selectedProduct.value.festival_discounted_from_in_seconds &&
      selectedProduct.value.festival_discounted_until_in_seconds &&
      selectedProduct.value.festival_discounted_until_in_seconds >= seconds
    ) ||
    (
      selectedProduct.value.festival_discounted_from_in_seconds &&
      !selectedProduct.value.festival_discounted_until_in_seconds &&
      selectedProduct.value.festival_discounted_from_in_seconds <= seconds
    ) ||
    (
      selectedProduct.value.festival_discounted_from_in_seconds &&
      selectedProduct.value.festival_discounted_until_in_seconds &&
      selectedProduct.value.festival_discounted_until_in_seconds >= seconds &&
      selectedProduct.value.festival_discounted_from_in_seconds <= seconds
    )
  )
})

//------------------------------------------------------------
// Discount timer operations
//------------------------------------------------------------
const getDiscountTimer = computed(() => {
  if (hasFestivalDiscount.value) {
    if (selectedProduct.value.festival_discounted_until_in_seconds !== null) {
      return selectedProduct.value.festival_discounted_until_in_seconds
    }
    return 0
  }

  // Check if product have general discount
  if (hasGeneralDiscount.value) {
    if (selectedProduct.value.discounted_until_in_seconds !== null) {
      return selectedProduct.value.discounted_until_in_seconds
    }
    return 0
  }

  return null
})

const getBuyablePrice = computed(() => {
  let price = +selectedProduct.value.buyable_price

  if (getDiscountTimer.value) {
    price = +selectedProduct.value.price
  }

  return price
})

//------------------------------------------------------------
function setCurrentProductProperties() {
  if (selectedProduct.value) {
    for (let i of colorFiltered.value) {
      if (selectedProduct.value.id === i.id) {
        selectedColor.value = i
        break
      }
    }
    for (let i of sizeFiltered.value) {
      if (selectedProduct.value.id === i.id) {
        selectedSize.value = i
        break
      }
    }
    for (let i of guaranteeFiltered.value) {
      if (selectedProduct.value.id === i.id) {
        selectedGuarantee.value = i
        break
      }
    }
  }
}

//--------------------------------
// Extra info tab
//--------------------------------
const tabs = {}

function infoTabsCreation() {
  if (currentMainProduct.value && currentMainProduct.value?.description.length) {
    tabs['description'] = {
      text: 'توضیحات',
      hash: 'description',
    }
  }
  if (
    currentMainProduct.value &&
    currentMainProduct.value?.properties.length
  ) {
    tabs['properties'] = {
      text: 'مشخصات',
      hash: 'properties',
    }
  }

  tabs['comments'] = {
    text: 'دیدگاه کاربران',
    hash: 'comments',
    button: {
      badgeCount: 0,
    },
  }
}

//--------------------------------
// Filter product process
//--------------------------------
const colorFiltered = computed(() => {
  const filtered = []
  for (let i of products.value) {
    if (
      i.color_name &&
      i.color_name?.toString() !== '' &&
      !hasItemInArray('color_name', i.color_name, filtered)
    ) {
      filtered.push({
        id: i.id,
        color_name: i.color_name?.trim(),
        color_hex: i.color_hex,
        active: null,
        sizes: [
          i.size?.trim(),
        ],
        guarantees: [
          i.guarantee?.trim(),
        ],
      })
    } else {
      for (let j of filtered) {
        if (i.color_name?.trim() === j.color_name?.trim()) {
          if (j.sizes.indexOf(i.size?.trim()) === -1)
            j.sizes.push(i.size?.trim())
          if (j.guarantees.indexOf(i.guarantee?.trim()) === -1)
            j.guarantees.push(i.guarantee?.trim())
          break
        }
      }
    }
  }

  return filtered
})
const sizeFiltered = computed(() => {
  const filtered = []
  for (let i of products.value) {
    if (i.size?.toString() !== '' && !hasItemInArray('size', i.size, filtered)) {
      filtered.push({
        id: i.id,
        size: i.size?.trim(),
        active: null,
        colors: [
          i.color_name?.trim(),
        ],
        guarantees: [
          i.guarantee?.trim(),
        ],
      })
    } else {
      for (let j of filtered) {
        if (i.size?.trim() === j.size?.trim()) {
          if (j.colors.indexOf(i.color_name?.trim()) === -1)
            j.colors.push(i.color_name?.trim())
          if (j.guarantees.indexOf(i.guarantee?.trim()) === -1)
            j.guarantees.push(i.guarantee?.trim())
          break
        }
      }
    }
  }
  return filtered
})
const guaranteeFiltered = computed(() => {
  const filtered = []
  for (let i of products.value) {
    if (i.guarantee?.toString() !== '' && !hasItemInArray('guarantee', i.guarantee, filtered)) {
      filtered.push({
        id: i.id,
        guarantee: i.guarantee?.trim(),
        active: null,
        colors: [
          i.color_name?.trim(),
        ],
        sizes: [
          i.size?.trim(),
        ],
      })
    } else {
      for (let j of filtered) {
        if (i.guarantee?.trim() === j.guarantee?.trim()) {
          if (j.sizes.indexOf(i.size?.trim()) === -1)
            j.sizes.push(i.size?.trim())
          if (j.colors.indexOf(i.color_name?.trim()) === -1)
            j.colors.push(i.color_name?.trim())
          break
        }
      }
    }
  }
  return filtered
})

const colorFilteredLength = computed(() => {
  return colorFiltered.value.filter(item => item.color_name && item.color_name?.toString() !== '').length
})
const sizeFilteredLength = computed(() => {
  return sizeFiltered.value.filter(item => item.size && item.size?.toString() !== '').length
})
const guaranteeFilteredLength = computed(() => {
  return guaranteeFiltered.value.filter(item => item.guarantee && item.guarantee?.toString() !== '').length
})

function hasItemInArray(key, value, arr) {
  let counter = 0
  for (let i of arr) {
    if (i[key] !== value) {
      counter++
    }
  }
  return counter !== arr.length;
}

//--------------------------------
// Select product process
//--------------------------------
function handleColorChange(color) {
  selectedColor.value = color

  if (color.active !== 'yes') {
    selectedSize.value = null
    selectedGuarantee.value = null

    // reset all other properties active status to "no"
    changeActiveInArray('no', sizeFiltered.value)
    changeActiveInArray('no', guaranteeFiltered.value)

    // filter all other properties to "yes" if it has current property in their object
    changeActiveInArrayWhere('yes', sizeFiltered.value, (item) => {
      return color.sizes.indexOf(item.size) !== -1
    })
    changeActiveInArrayWhere('yes', guaranteeFiltered.value, (item) => {
      return color.guarantees.indexOf(item.guarantee) !== -1
    })
  } else {
    if (selectedSize.value && !selectedGuarantee.value) {
      // reset active status of none selected property
      changeActiveInArray('no', guaranteeFiltered.value)
      // make active status of other property to "yes" if it is in specific property and current property
      changeActiveInArrayWhere('yes', guaranteeFiltered.value, (item) => {
        return (
          selectedSize.value.guarantees.indexOf(item.guarantee) !== -1 &&
          color.guarantees.indexOf(item.guarantee) !== -1
        )
      })
    } else if (selectedGuarantee.value && !selectedSize.value) {
      changeActiveInArray('no', sizeFiltered.value)
      changeActiveInArrayWhere('yes', sizeFiltered.value, (item) => {
        return (
          selectedGuarantee.value.sizes.indexOf(item.size) !== -1 &&
          color.sizes.indexOf(item.size) !== -1
        )
      })
    }
  }

  // make current property's active status to "yes" to show it is selected product
  changeActiveInArray('no', colorFiltered.value)
  changeActiveOfItemInArray('yes', 'id', color.id, colorFiltered.value)
}

function handleSizeChange(size) {
  selectedSize.value = size

  if (size.active !== 'yes') {
    selectedColor.value = null
    selectedGuarantee.value = null

    changeActiveInArray('no', colorFiltered.value)
    changeActiveInArray('no', guaranteeFiltered.value)

    changeActiveInArrayWhere('yes', colorFiltered.value, (item) => {
      return size.colors.indexOf(item.color_name) !== -1
    })
    changeActiveInArrayWhere('yes', guaranteeFiltered.value, (item) => {
      return size.guarantees.indexOf(item.guarantee) !== -1
    })
  } else {
    if (selectedColor.value && !selectedGuarantee.value) {
      changeActiveInArray('no', guaranteeFiltered.value)
      changeActiveInArrayWhere('yes', guaranteeFiltered.value, (item) => {
        return (
          selectedColor.value.guarantees.indexOf(item.guarantee) !== -1 &&
          size.guarantees.indexOf(item.guarantee) !== -1
        )
      })
    } else if (selectedGuarantee.value && !selectedColor.value) {
      changeActiveInArray('no', colorFiltered.value)
      changeActiveInArrayWhere('yes', colorFiltered.value, (item) => {
        return (
          selectedGuarantee.value.colors.indexOf(item.color_name) !== -1 &&
          size.colors.indexOf(item.color_name) !== -1
        )
      })
    }
  }

  changeActiveInArray('no', sizeFiltered.value)
  changeActiveOfItemInArray('yes', 'id', size.id, sizeFiltered.value)
}

function handleGuaranteeChange(selected) {
  selectedGuarantee.value = selected

  if (!selected) return

  if (selected.active !== 'yes') {
    selectedColor.value = null
    selectedSize.value = null

    changeActiveInArray('no', colorFiltered.value)
    changeActiveInArray('no', sizeFiltered.value)

    changeActiveInArrayWhere('yes', colorFiltered.value, (item) => {
      return selected.colors.indexOf(item.color_name) !== -1
    })
    changeActiveInArrayWhere('yes', sizeFiltered.value, (item) => {
      return selected.sizes.indexOf(item.size) !== -1
    })
  } else {
    if (selectedColor.value && !selectedSize.value) {
      changeActiveInArray('no', sizeFiltered.value)
      changeActiveInArrayWhere('yes', sizeFiltered.value, (item) => {
        return (
          selectedColor.value.sizes.indexOf(item.size) !== -1 &&
          selected.sizes.indexOf(item.size) !== -1
        )
      })
    } else if (selectedSize.value && !selectedColor.value) {
      changeActiveInArray('no', colorFiltered.value)
      changeActiveInArrayWhere('yes', colorFiltered.value, (item) => {
        return (
          selectedSize.value.colors.indexOf(item.color_name) !== -1 &&
          selected.colors.indexOf(item.color_name) !== -1
        )
      })
    }
  }

  changeActiveInArray('no', guaranteeFiltered.value)
  changeActiveOfItemInArray('yes', 'id', selected.id, guaranteeFiltered.value)
}

function changeActiveOfItemInArray(newActive, key, value, arr) {
  for (let i of arr) {
    if (i[key] === value) {
      i.active = newActive
    }
  }
}

function changeActiveInArrayWhere(newActive, arr, where) {
  for (let i of arr) {
    if (where.call(null, i))
      i.active = newActive
  }
}

function changeActiveInArray(newActive, arr) {
  for (let i of arr) {
    i.active = newActive
  }
}

//--------------------------------
// Clipboard Stuffs
//--------------------------------
const host = window.location.host + '/product'
const productShortUrl = computed(() => {
  if (!currentMainProduct.value?.id) return '';
  return host + '?id=' + currentMainProduct.value.id
})
const {copy} = useClipboard()

const emailSharingLink = computed(() => {
  const emailSubject = 'این محصول رو مشاهده کن'
  const emailBody = 'به نظرم اومد این محصول برات جالب باشه' + ' ' + productShortUrl.value

  return `mailto:?subject=${encodeURIComponent(emailSubject)}&body=${encodeURIComponent(emailBody)}`
})
const twitterSharingLink = computed(() => {
  return `https://twitter.com/intent/tweet?url=${encodeURIComponent(productShortUrl.value)}&text=${encodeURIComponent(currentMainProduct.value?.title || '')}`
})
const telegramSharingLink = computed(() => {
  return `https://telegram.me/share/url?url=${encodeURIComponent(productShortUrl.value)}&text=${encodeURIComponent(currentMainProduct.value?.title || '')}`
})
const whatsappSharingLink = computed(() => {
  return `https://wa.me/?text=${encodeURIComponent(currentMainProduct.value?.title || '')}%3A%20${encodeURIComponent(productShortUrl.value)}`
})

function copyHandler() {
  copy(productShortUrl.value).then(() => {
    toast.success('لینک کپی شد.')
  })
}

//--------------------------------
const cartStore = useCartStore()

function addToCartHandler() {
  if (!props.showAddToCart) return

  if (!selectedProduct.value?.code) {
    toast.warning('ابتدا محصول را انتخاب نمایید سپس دوباره تلاش کنید.')
    return
  }
  if (isExceedCurrentProductInCart.value) {
    toast.warning('امکان افزودن بیشتر این محصول در یک خرید امکان‌پذیر نمی‌باشد.')
    return
  }

  cartStore.addItem(selectedProduct.value.code, currentProductCartCounting.value)
}

const favoritingLoading = ref(false)

function addToBookmarkHandler() {
  if (!currentMainProduct.value?.id || favoritingLoading.value) return

  favoritingLoading.value = true

  UserPanelFavoriteProductAPI.create({
    product: currentMainProduct.value.id,
  }, {
    success(response) {
      const op = response.operation
      if (+op === 1) { // added
        currentMainProduct.value.is_favorited = true
      } else if (+op === 2) { // removed
        currentMainProduct.value.is_favorited = false
      }
    },
    finally() {
      favoritingLoading.value = false
    },
  })
}

onMounted(() => {
  HomeProductAPI.fetchById(slugParam.value, {
    success(response) {
      currentMainProduct.value = response.data

      relatedProducts.value = currentMainProduct.value.related_products
      products.value = currentMainProduct.value.items

      // Assign gallery images to slide show
      slides.value = currentMainProduct.value.gallery_images

      if (!slides.value?.length) {
        slides.value.push({
          path: currentMainProduct.value.image.path,
        })
      }

      // assign them to image preview too
      previewingItems.value = slides.value.map(item => {
        return imageUrl.value + item.path
      })

      //-----

      infoTabsCreation()

      if (products.value?.length) {
        selectedProduct.value = products.value[0]
        setCurrentProductProperties()

        for (let p of products.value) {
          productCartCounting.value.push({
            code: p.code,
            max: p.max_cart_count,
            quantity: 1,
          })
        }
      }

      emit('product-loaded', response.data)
    },
  })
})
</script>

<style scoped>
@import "../../assets/css/skeleton/normalize.css";
@import "../../assets/css/skeleton/skeleton.css";
</style>
