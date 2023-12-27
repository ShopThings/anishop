<template>
  <div class="flex flex-col lg:flex-row gap-3 sticky-container">
    <Vue3StickySidebar
        class="w-full lg:w-96 xl:w-[32rem] shrink-0 mx-auto"
        containerSelector=".sticky-container"
        innerWrapperSelector='.sidebar__inner'
        :top-spacing="20"
        :bottom-spacing="20"
        :min-width="1024"
    >
      <div class="flex flex-col">
        <div
            v-if="showProductExtraOption"
            class="shrink-0 p-2 bg-white shadow rounded-lg ring-1 ring-primary mb-3"
        >
          <ul class="flex items-center justify-center">
            <li class="ml-4 flex">
              <button
                  v-tooltip.bottom="'افزودن به علاقه‌مندی'"
                  class="group"
              >
                <HeartIcon class="w-6 h-6 text-black group-hover:text-rose-500 transition"/>
              </button>
            </li>
            <li class="ml-4 flex">
              <button
                  v-tooltip.bottom="'به اشتراک گذاری'"
                  class="group"
              >
                <ShareIcon class="w-6 h-6 text-black group-hover:text-opacity-80 transition"/>
              </button>
            </li>
            <li class="flex">
              <button
                  v-tooltip.bottom="'مقایسه کالا'"
                  class="group"
              >
                <ScaleIcon class="w-6 h-6 text-black group-hover:text-opacity-80 transition"/>
              </button>
            </li>
          </ul>
        </div>

        <div>
          <div class="relative mb-3">
            <button
                class="absolute shadow-lg top-0 left-0 translate-y-6 translate-x-6 rounded-full border bg-white p-2 w-11 h-11 group z-[2] hover:border-blue-500 hover:bg-blue-50 transition"
                @click="onLightboxShow"
            >
              <MagnifyingGlassPlusIcon class="w-6 h-6 mx-auto group-hover:scale-110 transition"/>
            </button>

            <base-carousel
                v-model="slides"
                v-model:current="currentSlide"
                :class-name="mainGallerySettings.className"
                :breakpoints="mainGallerySettings.breakpoints"
                :wrap-around="mainGallerySettings.wrapAround"
                :has-navigation="mainGallerySettings.hasNavigation"
                :effect="mainGallerySettings.effect"
                :use-thumbnail="mainGallerySettings.useThumbnail"
            >
              <template #default="{slide, index}">
                <div class="bg-white border rounded-lg lg:h-96 xl:h-[32rem]">
                  <base-lazy-image
                      :lazy-src="slide.path"
                      :alt="slide.name"
                      class="rounded-lg"
                  />
                </div>
              </template>

              <template #thumbSlide="{slide, index}">
                <div
                    class="bg-white border rounded-lg cursor-pointer"
                >
                  <base-lazy-image
                      :lazy-src="slide.path"
                      :alt="slide.name"
                      class="!w-auto !h-28 rounded-lg"
                  />
                </div>
              </template>
            </base-carousel>
          </div>
        </div>
      </div>
    </Vue3StickySidebar>

    <div class="grow">
      <div class="bg-white rounded-lg border px-4 py-6 relative">
        <div
            v-if="selectedProduct && selectedProduct.is_special"
            class="flex items-center justify-center my-3"
        >
          <div class="grow h-0.5 bg-red-400 rounded-full"></div>
          <span class="mx-2 text-sm text-red-600 shrink-0">فروش ویژه</span>
          <div class="grow h-0.5 bg-red-400 rounded-full"></div>
        </div>

        <h1 class="text-xl leading-loose hyphens-auto break-words font-iranyekan-bold">
          گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام
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
          <div
              v-else
              class="flex flex-wrap mt-3 items-center"
          >
            <div class="ml-3 text-xl my-1 font-iranyekan-bold">
              {{ formatPriceLikeNumber(selectedProduct.discounted_price ?? selectedProduct.price) }}
              <span class="text-xs text-gray-400">تومان</span>
            </div>

            <template
                v-if="selectedProduct.discounted_price && selectedProduct.discounted_price < selectedProduct.price">
              <div class="relative ml-3 my-1">
                                <span
                                    class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                <div class="text-slate-500">
                  {{ formatPriceLikeNumber(selectedProduct.price) }}
                  <span class="text-xs text-gray-400">تومان</span>
                </div>
              </div>
              <div class="rounded-lg bg-rose-500 text-white text-sm py-1 px-2 my-1">
                <span class="text-xs">%</span>
                {{
                  Math.round(((selectedProduct.price - selectedProduct.discounted_price) / selectedProduct.price) * 100)
                }}
                <span class="text-xs mr-1">تخفیف</span>
              </div>
            </template>
          </div>
        </template>
        <div v-else class="text-teal text-sm mt-3">
          برای مشاهده قیمت ابتدا محصول را انتخاب نمایید
        </div>

        <div
            v-if="currentMainProduct"
            class="mt-3 flex flex-wrap items-center bg-blue-50 py-1 px-2"
        >
          <div class="flex items-center">
            <span class="text-xs ml-2">برند:</span>
            <router-link
                :to="{name: 'search', query: {brand: currentMainProduct.brand_id}}"
                class="text-sm text-blue-500 hover:text-opacity-80 transition"
            >
              {{ currentMainProduct.brand.name }}
            </router-link>
          </div>
          <span class="w-2 h-2 rounded-full bg-gray-300 mx-4"></span>
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

        <hr class="h-0.5 mx-auto my-3 bg-slate-100 border-0 rounded">

        <div class="mt-3">
          <h3 class="mb-2">
            ویژگی‌ها
          </h3>

          <ul class="text-slate-500 text-sm">
            <li class="mb-2">
              <span class="ml-2">فناوری صفحه‌نمایش :</span>
              <span class="text-black">Dynamic AMOLED 2X</span>
            </li>
            <li class="mb-2">
              <span class="ml-2">اندازه :</span>
              <span class="text-black">6.8</span>
            </li>
            <li class="mb-2">
              <span class="ml-2">رزولوشن عکس :</span>
              <span class="text-black">200 مگاپیکسل</span>
            </li>
            <li class="mb-2">
              <span class="ml-2">نسخه سیستم عامل :</span>
              <span class="text-black">Android 13</span>
            </li>
            <li class="mb-2">
              <span class="ml-2">اقلام همراه :</span>
              <span class="text-black">دفترچه‌ راهنما</span>
            </li>
          </ul>
        </div>

        <hr class="h-0.5 mx-auto my-3 bg-slate-100 border-0 rounded">

        <div>
          <div>
            <h4 class="mb-2">
              رنگ:
              <span v-if="selectedColor">{{ selectedColor.color_name }}</span>
            </h4>

            <ul class="flex flex-wrap gap-2">
              <li
                  v-for="color in colorFiltered"
                  v-tooltip.top="'' + color.color_name + ''"
                  :class="[
                                    'relative rounded-full w-10 h-10 border-2 ring-4 ring-white ring-inset transition',
                                    color.active === 'yes'
                                    ? 'border-indigo-500 cursor-pointer shadow-lg'
                                    : (
                                        color.active === 'no'
                                        ? 'cursor-default opacity-80'
                                        : 'hover:ring-8 cursor-pointer shadow-lg'
                                    ),
                                    selectedColor && selectedColor.color_name === color.color_name
                                    ? '!border-emerald-500 cursor-pointer shadow-lg ring-8 !ring-emerald-50'
                                    : ''
                                ]"
                  :style="'background-color:' + color.color_hex"
                  @click="handleColorChange(color)"
              >
                <div
                    v-if="color.active === 'no'"
                    class="absolute top-1/2 -translate-y-1/2 left-0 w-full h-0.5 rounded-full bg-rose-400 z-[1] -rotate-45"
                ></div>
              </li>
            </ul>
          </div>

          <div class="mt-3">
            <h4 class="mb-2">
              سایز:
              <span v-if="selectedSize">{{ selectedSize.size }}</span>
            </h4>

            <ul class="flex flex-wrap gap-2">
              <li
                  v-for="size in sizeFiltered"
                  :class="[
                                    'relative rounded-lg py-1 px-3 border-2 transition',
                                    size.active === 'yes'
                                    ? 'border-indigo-500 bg-indigo-50 cursor-pointer'
                                    : (
                                        size.active === 'no'
                                        ? 'cursor-default opacity-60'
                                        : 'hover:bg-gray-100 cursor-pointer'
                                    ),
                                    selectedSize && selectedSize.size === size.size
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
            </ul>
          </div>

          <div class="mt-3">
            <h4 class="mb-2">
              <div class="flex items-center">
                <CheckBadgeIcon class="h-7 w-7 text-emerald-400 ml-1.5"/>
                گارانتی:
              </div>
              <span v-if="selectedGuarantee" class="text-sm my-1">{{ selectedGuarantee.guarantee }}</span>
            </h4>

            <div class="w-full md:w-2/3">
              <base-select
                  options-key="id"
                  options-text="guarantee"
                  :options="guaranteeFiltered"
                  :selected="selectedGuarantee"
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
                                                selectedGuarantee && selectedGuarantee.guarantee === item.guarantee
                                                ? 'bg-emerald-50 border border-emerald-500 rounded p-1 grow'
                                                : ''
                                            ]"
                    >{{ item.guarantee }}</span>
                  </div>
                </template>
              </base-select>
            </div>
          </div>
        </div>

        <template v-if="showAddToCart">
          <hr class="h-0.5 mx-auto my-3 bg-slate-100 border-0 rounded">

          <div class="flex flex-wrap flex-row-reverse justify-end gap-3">
            <div class="flex grow sm:grow-0">
              <base-button
                  class="bg-white !text-gray-500 rounded-l-none hover:border-indigo-500 hover:!text-black shrink-0"
              >
                <PlusIcon class="h-6 w-6"/>
              </base-button>
              <span
                  class="block py-3 px-6 text-gray-900 ring-1 ring-inset ring-gray-300 grow text-center"
              >
                                1
                            </span>
              <base-button
                  class="bg-white !text-gray-500 rounded-r-none hover:border-indigo-500 hover:!text-black shrink-0"
              >
                <MinusIcon class="h-6 w-6"/>
              </base-button>
            </div>

            <base-animated-button
                class="bg-primary text-white px-6 w-full sm:w-auto"
            >
              <template #icon="{klass}">
                <ShoppingCartIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
              </template>

              <span class="ml-auto">افزودن به سبد خرید</span>
            </base-animated-button>
          </div>
        </template>
      </div>
    </div>
  </div>

  <vue-easy-lightbox
      :visible="visibleRef"
      :imgs="imgsRef"
      :index="currentSlide"
      :rtl="true"
      @hide="onLightboxHide"
  ></vue-easy-lightbox>

  <div class="mt-12">
    <partial-general-title title="محصولات مرتبط"/>

    <product-carousel :products="relatedProducts"/>
  </div>

  <div
      v-if="Object.keys(tabs).length"
      class="mt-12"
  >
    <base-tab-panel
        :tabs="tabs"
        tab-button-extra-class="w-full sm:w-auto sm:grow-0 px-6"
    >
      <template #description>
        <div class="p-3 styled-description">
          <h1>
            غول جدید سامسونگ قدرتمند‌تر از همیشه
          </h1>
          <p>
            شاید در نگاه اول به سامسونگ Galaxy S23 Ultra تفاوتی با Galaxy S22 Ultra دیده نشود و سامسونگ به
            طراحی
            برنده خود دست نزده است. اما باید بدانید که این بار این گوشی هوشمند با کوله‌باری از تجربه و برطرف
            کردن مشکلات هرچند اندک گذشته وارد میدان رقابت با پرچمداران قدرتمند برند‌های دیگر شده و با لیاقت
            تمام
            همجون یک پرچمدار مقتدر، عملکرد درخشانی داشته است. شاید ظاهر این گوشی چندان تفاوتی با نسل قبلی
            نداشته
            باشد، اما فریم‌ها حالت خمیدگی کمتری دارند و حس و حال استفاده فریم تخت و جذابی را به شما می‌دهند.
            البته فریم‌های آلومینیومی این گوشی آنقدر تخت نیست که در استفاده طولانی مدت مشکلی برای دست
            به‌همراه
            داشته باشد. سامسونگ تلاش داشته تا از متریال بازیافت‌ پذیر برای این گوشی استفاده کند و می‌توانیم
            بگوییم در این زمینه به نسبت تمام پرچمداران دیگر عملکرد بسیار بهتری داشته است.
          </p>

          <img src="/src/assets/product/g10.jpg" alt="">

          <p>
            نمای رو‌به‌رویی این گوشی صفحه‌نمایش بسیار گسترده‌ای را ارائه کرده است که تقریبا حاشیه‌های
            صفحه‌نمایش
            اصلا به چشم نمی‌آیند و همین امر سبب شده تا ۸۹.۹ درصد از نمای رو‌به‌رویی را به خودش اختصاص دهد.
            طراحی
            نمایش پشتی هم تفاوت چندانی با نسل قبلی ندارد، تنها رینگ‌های در نظر گرفته شده برای سنسور‌های
            دوربین
            بیش از پیش خودنمایی می‌کنند و سعی دارند تا قدرت بالای سنسور‌های دوربین را به رخ بکشند. وزن ۲۳۴
            گرمی
            این گوشی قطعا وزن سبکی نیست و در استفاده طولانی مدت دست را خسته می‌کند. اما همین وزن سبب شده تا
            احساس افتادن در حین استفاده تا میزان بسیار زیادی کاهش پیدا کند و کاملا حس و حال استفاده از یک
            گوشی
            پرچمدار با اصالت را به شما می‌دهد. البته نباید فراموش کنیم که این گوشی در برابر آب هم مقاوم بوده
            و
            پشتیبانی از استاندارد IP68 سبب شده تا توانایی قرار‌گیری در عمق ۱.۵ متری آب و مدت زمان ۳۰ دقیقه
            را
            داشته باشد.
          </p>

          <h1>
            صفحه‌نمایشی چشم نواز که نمی‌خواهید چشم از آن بردارید
          </h1>
          <p>
            در این بخش سامسونگ Galaxy S23 Ultra از مشخصاتی بسیار مشابه با نسل قبلی یعنی Galaxy S22 Ultra
            بهره
            برده است. این گوشی به صفحه‌نمایش با ابعاد ۶.۸ اینچ و رزولوشن ۱۴۴۰×۳۰۸۸ پیکسل از نوع Dynamic
            AMOLED
            2X بهره برده که توانایی نمایش فوق العاده ۵۰۰ پیکسل در هر اینچ را دارد. زنده بودن رنگ‌ها،
            رنگ‌هایی
            عمیق، تفکیک رنگ بی‌نظیر و وضوح تصویر فوق العاده تنها بخشی از قابلیت‌های قدرتمند این صفحه‌نمایش
            است.
            تصورش هم شگفت‌انگیز است که در چنین ابعاد صفحه‌نمایش که ما را به یاد سری‌های نوت این شرکت
            می‌اندازد،
            این میزان رزولوشن و تراکم پیکسل را ارائه می‌کند. بگذارید همینجا خیالتان را راحت کنیم، هر‌آنچه از
            یک
            صفحه‌نمایش جذاب، قدرتمند و با‌کیفیت انتظار دارید را صفحه‌‌نمایش این گوشی در اختیارتان قرار
            می‌دهد.
          </p>
          <p>
            حال می‌رسیم به مابقی مشخصات قدرتمند این صفحه‌نمایش بی‌نظیر. نرخ بروزرسانی ۱۲۰ هرتز سبب شده تا
            صفحه‌نمایش بسیار روان و بدون لگی را شاهد باشید. این قابلیت در اجرای بازی‌ها با گرافیک بالا و
            سنگین و
            البته تماشای ویدیو‌های با‌کیفیت تاثیر بسیار زیادی در ارائه هرچه بهتر شدن کیفیت دارد. البته باید
            بدانید که با توجه به نحوه استفاده شما از گوشی، این صفحه‌نمایش نرخ‌‌های بروزرسانی متغیری را رائه
            می‌کند. برای مثال در حین خواندن یک متن، شما نیازی به نرخ بروزرسانی بالایی ندارید، اما برای اجرای
            بازی قطعا برای ارائه کیفیت بالا، به نرخ بروزرسانی بالایی نیاز دارید. در مقایسه با نسل قبلی،
            صفحه‌نمایش این گوشی توانایی تغییر نرخ بروزرسانی روان‌تری را به نسبت نوع استفاده دارد. حداکثر
            روشنایی
            ۱۷۵۰ نیت (nits) این صفحه‌نمایش هم خیال شما را راحت کرده که نه تنها در شرایط نوری متنوع، حتی زیر
            تابش
            مستقیم نور خورشید هم وضوح تصویر بسیار عالی را از این صفحه‌نمایش شاهد خواهید بود. نسل جدید محافظ
            صفحه‌نمایش Corning Gorilla Glass Victus 2 هم برای این گوشی در نظر گرفته شده است.
          </p>

          <h1>
            سنسور‌ دوربینی که این بار برای قتل عام رقیبان آماده است
          </h1>
          <p>
            اما یکی از اصلی‌ترین تفاوت‌های در نظر گرفته شده برای این گوشی به نسبت نسل قبلی، بدون شک
            سنسور‌های
            دوربین در نظر گرفته شده است. با سنسور دوربین عریض و فوق قدرتمند ۲۰۰ مگاپیکسل این گوشی شروع
            می‌کنیم.
            این سنسور در نور روز عملکرد بی‌نظیری را ارائه می‌کند. چنین میزان رزولوشنی سبب شده تا جزئیات با
            دقت
            بسیار بالایی در تصاویر خروجی ثبت شده نمایش داده شوند. در حالت معمولی این سنسور خروجی تصاویر با
            رزولوشن ۱۲ مگاپیکسل را ارائه می‌کند. شاید از خودتان بپرسید چرا؟ بگذارید توضیح بدهیم. در سری قبلی
            که
            Galaxy S22 Ultra از سنسور دوربین عریض ۱۰۸ مگاپیکسل بهره برده بود، در حالت استاندارد خروجی تصاویر
            با
            رزولوشن ۱۲ مگاپیکسل را شاهد بودیم که هر پیکسل از تجمع ۹ پیکسل تشکیل شده بود همین امر تاثیر بسیار
            بالایی در هرچه بهتر شدن کیفیت تصاویر خروجی داشت. حال تصویر کنید که Galaxy S23 Ultra توانایی
            تجمیع ۱۶
            پیکسل در هر پیکسل را دارد. پس بدون ذره‌ای شک باید بگوییم که با یکی از قدرتمند‌ترین سنسور‌های
            دوربین
            عریض در بین گوشی‌های هوشمند جهان رو‌به‌رو هستید.
          </p>
          <p>
            اما نکته جالب‌تر اینجاست که امکان انتخاب خروجی تصاویر با رزولوشن ۵۰ مگاپیکسل (هر پیکسل از تجمیع
            چهار
            پیکسل) و رزولوشن ۲۰۰ مگاپیکسل هم وجود دارد. این سنسور دوربین به لرزشگیر اپتیکال یا همان OIS هم
            مجهز
            شده که لرزش‌های ناخواسته را تا میزان بسیار زیادی کاهش می‌دهد. البته باید بگوییم که این بار سنسور
            دوربین عریض این گوشی به قابلیت جذاب super steady مجهز شده و این قابلیت لرزش‌های ناخواسته هنگام
            فیلمبرداری را تا میزان بسیار زیادی کاهش می‌دهد. در نور شب هم باید بگوییم که سامسونگ پیشرفت
            چشم‌گیری
            به نسبت نسل قبلی داشته و این بار در سخت‌ترین چالش برای هر گوشی هوشمند که همان عکاسی در نور شب
            است،
            سربلند بیرون آمده است. این سنسور تواناییی ضبط ویدی با حداکثر کیفیت 8K و سرعت ۳۰ فریم در ثانیه را
            دارد. شاید در نگاه اول پیش خودتان بگویید که تغییر توانایی ضبط ویدیو ۲۴ فریم در ثانیه برای نسل
            قبلی
            در مقایسه با ضبط ویدیو با سرعت ۳۰ فریم در ثانیه چندان تفاوتی باهم نداشته باشند. اما باید بگوییم
            که
            تغییر سرعت فریم یکی از اصلی‌ترین دلایل خروجی عملکرد بهتر در فیلمبرداری است. سامسونگ همواره در
            زمینه
            زوم حرف‌های بسیاری برای گفتن داشت و این بار هم دست پر وارد میدان رقابت شده است. توانایی زوم ۳
            برابری
            و ۱۰ برابری اپتیکال (زوم بدون کم شدن کیفیت) سبب شده تا خروجی تصاویر بسیار جذابی را شاهد باشید.
            همانطور که در نمونه تصاویر ثبت شده در قسمت پایین مشاهده می‌کنید، در خروجی تصاویر زوم ۳ برابری و
            ۱۰
            برابری، جزئیات با دقت بسیار بالایی نمایش داده شده‌اند و هیچ چیزی را از دست نمی‌دهید.
          </p>
          <p>
            همانند نسل قبلی این گوشی توانایی زوم ۱۰۰ برابری دیجیتال را هم دارد، اما نکته جالب توجه در بخش
            زوم
            ۱۰۰ برابری، عملکرد بسیار بهتر این گوشی به نسبت نسل قبلی است. این بار بهره بردن از هوش مصنوعی
            قدرتمند‌تر سبب شده تا حتی در زوم ۱۰۰ برابری هم عملکرد بسیار خوبی را از این گوشی هوشمند شاهد
            باشید.
            با نگاهی به تصاویر خروجی ثبت شده مشاهده می‌کنید که این سنسور خروجی تصاویر بسیار خوبی را ارائه
            کرده
            است. در زوم ۱۰۰ برابری زمانی که عکاسی می‌کنید شاید در نگاه اول کیفیت چندان بالایی نداشته باشد،
            اما
            دقیقا همان جایی که از کیفیت خروجی تصاویر ناامید شده‌اید، قابلیت AI وارد میدان می‌شود و با ویرایش
            بسیار مناسب، خروجی تصویر بسیار خوبی را ارائه می‌کند. حتی این هوش مصنوعی به لطف پردازنده قدرتمند،
            می‌تواند با توجه به شاخص تصویر، ویرایشات متفاوتی را در راستای خروجی تصاویر با‌کیفیت ارائه کند.
          </p>
          <p>
            همانطور که پیش از این اشاره کرده بودیم، سنسور‌های دوربین در نظر گرفته شده برای این گوشی، توانایی
            زوم
            ۳ برابری و ۱۰ برابری اپتیکال را دارند. قابلیت زوم ۳ برابری می‌تواند بسیار مناسب برای ثبت تصاویر
            پرتره باشند. با توجه به نمونه تصاویر ثبت شده در قسمت پایین، سوای عملکرد بسیار خوب این سنسور در
            ارائه
            دقیق جزئیات، مشاهده می‌کنید که در حالت پرتره و حتی زوم ۳ برابری اپتکیال، خروجی تصاویر
            فوق‌العاده‌ای
            دارد. خروجی تصاویری که پس‌زمینه به‌خوبی هرچه تمام‌تر بوکه (محو) شده و پرتره چهره هیچ تداخلی با
            پس‌زمینه بوکه شده ندارد. پس با خیال راحت برای ثبت تصاویر پرتره در حد و اندازه دوربین‌های حرفه‌ای
            عکاسی، می‌توانید روی این پرچمدار قدرتمند و جذاب حساب باز کنید.
          </p>
        </div>
      </template>

      <template #properties>
        <template
            v-for="(property, idx) in currentMainProduct.properties"
            :key="idx"
        >
          <h1
              class="text-lg text-indigo-600 mb-2 flex items-center"
              :class="idx !== 0 ? 'mt-5' : ''"
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
                  class="p-2 text-center text-sm bg-slate-100 text-slate-500  md:text-right md:rounded-l-none md:col-span-1"
                  :class="idx2 % 2 === 0 ? 'md:bg-slate-100' : 'md:bg-transparent'"
              >
                {{ child.title }}
              </div>
              <div
                  class="grow text-center md:text-right md:col-span-2"
                  :class="idx2 % 2 === 0 ? 'md:bg-slate-100' : ''"
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
            :product-id="currentMainProduct.id"
            :show-add-comment="showAddComment"
        />
      </template>
    </base-tab-panel>
  </div>
</template>

<script setup>
import {computed, ref, watchEffect} from "vue";
import VueEasyLightbox from 'vue-easy-lightbox'
import Vue3StickySidebar from "vue3-sticky-sidebar";
import {
  MagnifyingGlassPlusIcon,
  CheckBadgeIcon,
  ShoppingCartIcon,
  PlusIcon,
  MinusIcon,
  HeartIcon,
  ShareIcon,
  ScaleIcon,
  XCircleIcon,
  CheckCircleIcon,
  StopIcon,
} from "@heroicons/vue/24/outline/index.js"
import BaseCarousel from "./../base/BaseCarousel.vue";
import BaseLazyImage from "./../base/BaseLazyImage.vue";
import BaseAnimatedButton from "./../base/BaseAnimatedButton.vue";
import BaseButton from "./../base/BaseButton.vue";
import BaseSelect from "./../base/BaseSelect.vue";
import ProductCarousel from "./ProductCarousel.vue";
import BaseTabPanel from "../base/BaseTabPanel.vue";
import ProductComment from "./ProductComment.vue";
import PartialGeneralTitle from "../partials/PartialGeneralTitle.vue";
import {formatPriceLikeNumber} from "../../composables/helper.js";

defineProps({
  showProductExtraOption: {
    type: Boolean,
    default: true,
  },
  showAddToCart: {
    type: Boolean,
    default: true,
  },
  showAddComment: {
    type: Boolean,
    default: true,
  },
})

const mainGallerySettings = {
  className: 'detail-slider',
  effect: 'fade',
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

const currentSlide = ref(0)

const slides = ref([
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g1.jpg',
  },
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g2.jpg',
  },
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g3.jpg',
  },
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g4.jpg',
  },
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g5.jpg',
  },
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g6.jpg',
  },
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g7.jpg',
  },
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g8.jpg',
  },
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g9.jpg',
  },
  {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g10.jpg',
  },
])

const visibleRef = ref(false)
const imgsRef = ref([
  '/src/assets/product/g1.jpg',
  '/src/assets/product/g2.jpg',
  '/src/assets/product/g3.jpg',
  '/src/assets/product/g4.jpg',
  '/src/assets/product/g5.jpg',
  '/src/assets/product/g6.jpg',
  '/src/assets/product/g7.jpg',
  '/src/assets/product/g8.jpg',
  '/src/assets/product/g9.jpg',
  '/src/assets/product/g10.jpg',
])

const onLightboxShow = () => (visibleRef.value = true)
const onLightboxHide = () => (visibleRef.value = false)

const currentMainProduct = ref({
  id: 1,
  brand_id: 2,
  category_id: 5,
  title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
  brand: {
    name: 'سامسونگ',
  },
  category: {
    name: 'موبایل',
  },
  image: {
    name: 'samsung_s23_ultra',
    path: '/src/assets/product/g1.jpg',
  },
  festivals: [
    {
      festival_id: 1,
      product_id: 1,
      discount_percentage: 2,
    },
  ],
  description: 'hi!',
  properties: [
    {
      title: 'مشخصات',
      children: [
        {
          title: 'ظرفیت',
          tags: ['1 ترابایت', '2 ترابایت'],
        },
        {
          title: 'نوع حافظه NAND',
          tags: ['3D NAND'],
        },
        {
          title: 'رابط دستگاه',
          tags: ['SATA 3.0(6Gb/s)'],
        },
        {
          title: 'فرم فاکتور',
          tags: ['2.5 اینچ'],
        },
        {
          title: 'سرعت خواندن ترتیبی اطلاعات',
          tags: ['تا 550 مگابایت در ثانیه'],
        },
        {
          title: 'سرعت نوشتن ترتیبی اطلاعات',
          tags: ['تا 500 مگابایت در ثانیه'],
        },
        {
          title: 'مقاوم در برابر لرزش',
          tags: ['دارد'],
        },
        {
          title: 'قابلیت پشتیبانی از TRIM',
          tags: ['دارد'],
        },
        {
          title: 'قابلیت پشتیبانی از NCQ',
          tags: ['دارد'],
        },
        {
          title: 'پشتیبانی از S.M.A.R.T',
          tags: ['دارد'],
        },
      ],
    },
  ],
  products: [
    {
      id: 1,
      color_name: 'مشکی',
      color_hex: '#000000',
      size: 'XL',
      guarantee: 'گارانتی ۱۸ ماهه شرکتی',
      price: 49500000,
      discounted_price: 48500000,
      stock_count: 8,
      max_cart_count: 4,
      is_available: true,
      is_special: false,
      show_coming_soon: false,
      show_call_for_more: false,
    },
    {
      id: 2,
      color_name: 'مشکی',
      color_hex: '#000000',
      size: 'XLL',
      guarantee: 'گارانتی ۱۸ ماهه شرکتی',
      price: 49500000,
      discounted_price: 48500000,
      stock_count: 10,
      max_cart_count: 4,
      is_available: true,
      is_special: false,
      show_coming_soon: true,
      show_call_for_more: false,
    },
    {
      id: 3,
      color_name: 'کرم',
      color_hex: '#F3ECE2',
      size: 'L',
      guarantee: 'گارانتی ۱۸ ماهه شرکتی',
      price: 49700000,
      discounted_price: 48700000,
      stock_count: 5,
      max_cart_count: 3,
      is_available: true,
      is_special: true,
      show_coming_soon: false,
      show_call_for_more: false,
    },
    {
      id: 4,
      color_name: 'بنفش',
      color_hex: '#E7DBE5',
      size: 'L',
      guarantee: 'گارانتی ۲۴ ماهه شرکتی',
      price: 49950000,
      discounted_price: 48950000,
      stock_count: 5,
      max_cart_count: 2,
      is_available: true,
      is_special: false,
      show_coming_soon: false,
      show_call_for_more: false,
    },
  ],
  related: [
    {
      id: 1,
      brand_id: 2,
      category_id: 5,
      title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'موبایل',
      },
      image: {
        name: 'samsung_s23_ultra',
        path: '/src/assets/product/g1.jpg',
      },
      festivals: [
        {
          festival_id: 1,
          product_id: 1,
          discount_percentage: 2,
        },
      ],
      products: [
        {
          id: 1,
          color_name: 'مشکی',
          color_hex: '#000000',
          size: null,
          guarantee: null,
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 8,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
      ],
    },
    {
      id: 2,
      brand_id: 2,
      category_id: 5,
      title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'موبایل',
      },
      image: {
        name: 'samsung_s23_ultra',
        path: '/src/assets/product/g2.jpg',
      },
      festivals: [
        {
          festival_id: 1,
          product_id: 1,
          discount_percentage: 2,
        },
      ],
      products: [
        {
          id: 1,
          color_name: 'مشکی',
          color_hex: '#000000',
          size: 'XL',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 8,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
        {
          id: 2,
          color_name: 'مشکی',
          color_hex: '#000000',
          size: 'XLL',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 10,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: true,
          show_call_for_more: false,
        },
        {
          id: 3,
          color_name: 'کرم',
          color_hex: '#F3ECE2',
          size: 'L',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49700000,
          discounted_price: 48700000,
          stock_count: 5,
          max_cart_count: 3,
          is_available: true,
          is_special: true,
          show_coming_soon: false,
          show_call_for_more: false,
        },
        {
          id: 4,
          color_name: 'بنفش',
          color_hex: '#E7DBE5',
          size: 'L',
          guarantee: 'گارانتی ۲۴ ماهه شرکتی',
          price: 49950000,
          discounted_price: 48950000,
          stock_count: 5,
          max_cart_count: 2,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
      ],
    },
    {
      id: 3,
      brand_id: 2,
      category_id: 5,
      title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'موبایل',
      },
      image: {
        name: 'samsung_s23_ultra',
        path: '/src/assets/product/g3.jpg',
      },
      festivals: [
        {
          festival_id: 1,
          product_id: 1,
          discount_percentage: 2,
        },
      ],
      products: [
        {
          id: 1,
          color_name: 'مشکی',
          color_hex: '#000000',
          size: 'XL',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 8,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
        {
          id: 2,
          color_name: 'مشکی',
          color_hex: '#000000',
          size: 'XLL',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 10,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: true,
          show_call_for_more: false,
        },
        {
          id: 3,
          color_name: 'کرم',
          color_hex: '#F3ECE2',
          size: 'L',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49700000,
          discounted_price: 48700000,
          stock_count: 5,
          max_cart_count: 3,
          is_available: true,
          is_special: true,
          show_coming_soon: false,
          show_call_for_more: false,
        },
        {
          id: 4,
          color_name: 'بنفش',
          color_hex: '#E7DBE5',
          size: 'L',
          guarantee: 'گارانتی ۲۴ ماهه شرکتی',
          price: 49950000,
          discounted_price: 48950000,
          stock_count: 5,
          max_cart_count: 2,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
      ],
    },
    {
      id: 4,
      brand_id: 2,
      category_id: 5,
      title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'موبایل',
      },
      image: {
        name: 'samsung_s23_ultra',
        path: '/src/assets/product/g4.jpg',
      },
      festivals: [
        {
          festival_id: 1,
          product_id: 1,
          discount_percentage: 2,
        },
      ],
      products: [
        {
          id: 1,
          color_name: 'مشکی',
          color_hex: '#000000',
          size: null,
          guarantee: null,
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 8,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
      ],
    },
    {
      id: 5,
      brand_id: 2,
      category_id: 5,
      title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'موبایل',
      },
      image: {
        name: 'samsung_s23_ultra',
        path: '/src/assets/product/g5.jpg',
      },
      festivals: [
        {
          festival_id: 1,
          product_id: 1,
          discount_percentage: 2,
        },
      ],
      products: [
        {
          id: 1,
          color_name: null,
          color_hex: null,
          size: 'XL',
          guarantee: null,
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 8,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: true,
        },
        {
          id: 2,
          color_name: 'مشکی',
          color_hex: '#000000',
          size: 'XLL',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 0,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
        {
          id: 3,
          color_name: 'کرم',
          color_hex: '#F3ECE2',
          size: 'L',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49700000,
          discounted_price: 48700000,
          stock_count: 5,
          max_cart_count: 3,
          is_available: true,
          is_special: true,
          show_coming_soon: false,
          show_call_for_more: false,
        },
        {
          id: 4,
          color_name: 'بنفش',
          color_hex: '#E7DBE5',
          size: 'L',
          guarantee: 'گارانتی ۲۴ ماهه شرکتی',
          price: 49950000,
          discounted_price: 48950000,
          stock_count: 5,
          max_cart_count: 2,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
      ],
    },
    {
      id: 6,
      brand_id: 2,
      category_id: 5,
      title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'موبایل',
      },
      image: {
        name: 'samsung_s23_ultra',
        path: '/src/assets/product/g6.jpg',
      },
      festivals: [
        {
          festival_id: 1,
          product_id: 1,
          discount_percentage: 2,
        },
      ],
      products: [
        {
          id: 1,
          color_name: 'مشکی',
          color_hex: '#000000',
          size: 'XL',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 8,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
        {
          id: 2,
          color_name: 'مشکی',
          color_hex: '#000000',
          size: 'XLL',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49500000,
          discounted_price: 48500000,
          stock_count: 10,
          max_cart_count: 4,
          is_available: true,
          is_special: false,
          show_coming_soon: true,
          show_call_for_more: false,
        },
        {
          id: 3,
          color_name: 'کرم',
          color_hex: '#F3ECE2',
          size: 'L',
          guarantee: 'گارانتی ۱۸ ماهه شرکتی',
          price: 49700000,
          discounted_price: 48700000,
          stock_count: 5,
          max_cart_count: 3,
          is_available: true,
          is_special: true,
          show_coming_soon: false,
          show_call_for_more: false,
        },
        {
          id: 4,
          color_name: 'بنفش',
          color_hex: '#E7DBE5',
          size: 'L',
          guarantee: 'گارانتی ۲۴ ماهه شرکتی',
          price: 49950000,
          discounted_price: 48950000,
          stock_count: 5,
          max_cart_count: 2,
          is_available: true,
          is_special: false,
          show_coming_soon: false,
          show_call_for_more: false,
        },
      ],
    },
  ],
})
const relatedProducts = ref(currentMainProduct.value.related)
const products = ref(currentMainProduct.value.products)
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
    badgeCount: 10,
  },
}
//--------------------------------

//--------------------------------
// Filter product process
//--------------------------------
const colorFiltered = computed(() => {
  const filtered = []
  for (let i of products.value) {
    if (!hasItemInArray('color_name', i.color_name, filtered)) {
      filtered.push({
        id: i.id,
        color_name: i.color_name.trim(),
        color_hex: i.color_hex,
        active: null,
        sizes: [
          i.size.trim(),
        ],
        guarantees: [
          i.guarantee.trim(),
        ],
      })
    } else {
      for (let j of filtered) {
        if (i.color_name.trim() === j.color_name.trim()) {
          if (j.sizes.indexOf(i.size.trim()) === -1)
            j.sizes.push(i.size.trim())
          if (j.guarantees.indexOf(i.guarantee.trim()) === -1)
            j.guarantees.push(i.guarantee.trim())
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
    if (!hasItemInArray('size', i.size, filtered)) {
      filtered.push({
        id: i.id,
        size: i.size.trim(),
        active: null,
        colors: [
          i.color_name.trim(),
        ],
        guarantees: [
          i.guarantee.trim(),
        ],
      })
    } else {
      for (let j of filtered) {
        if (i.size.trim() === j.size.trim()) {
          if (j.colors.indexOf(i.color_name.trim()) === -1)
            j.colors.push(i.color_name.trim())
          if (j.guarantees.indexOf(i.guarantee.trim()) === -1)
            j.guarantees.push(i.guarantee.trim())
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
    if (!hasItemInArray('guarantee', i.guarantee, filtered)) {
      filtered.push({
        id: i.id,
        guarantee: i.guarantee.trim(),
        active: null,
        colors: [
          i.color_name.trim(),
        ],
        sizes: [
          i.size.trim(),
        ],
      })
    } else {
      for (let j of filtered) {
        if (i.guarantee.trim() === j.guarantee.trim()) {
          if (j.sizes.indexOf(i.size.trim()) === -1)
            j.sizes.push(i.size.trim())
          if (j.colors.indexOf(i.color_name.trim()) === -1)
            j.colors.push(i.color_name.trim())
          break
        }
      }
    }
  }
  return filtered
})

function hasItemInArray(key, value, arr) {
  let counter = 0
  for (let i of arr) {
    if (i[key] !== value) {
      counter++
    }
  }
  if (counter !== arr.length) return true
  return false
}

//--------------------------------

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

selectedProduct.value = products.value[0]
setCurrentProductProperties()
</script>

<style scoped>
@import "../../assets/css/skeleton/normalize.css";
@import "../../assets/css/skeleton/skeleton.css";
</style>
