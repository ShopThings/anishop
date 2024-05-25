<template>
  <div class="relative bg-white">
    <footer class="pt-20 pb-10 lg:pt-[120px] lg:pb-20 relative z-[1] max-w-7xl mx-auto w-full">
      <div class="px-6">
        <div class="flex flex-wrap">
          <div class="w-full px-4 sm:w-full lg:w-3/12">
            <div class="mb-8 w-full">
              <router-link
                  :to="{name: 'home'}"
                  class="mb-6 inline-block max-w-[160px]"
              >
                <img
                    alt="لوگو"
                    class="mt-[10px] max-w-full"
                    src="/logo-with-type.png"
                >
              </router-link>

              <p
                  v-if="homeSettingStore.getFooterDescription?.length !== ''"
                  class="text-body-color mb-7 text-base leading-7"
              >
                {{ homeSettingStore.getFooterDescription }}
              </p>

              <div
                  v-if="getPhones"
                  class="flex flex-col gap-4 sm:flex-row sm:flex-wrap"
              >
                <div
                    v-for="(phone, idx) in getPhones"
                    :key="idx"
                    class="text-dark flex items-center text-sm font-medium"
                >
                  <div class="text-primary ml-3 inline-block">
                    <DevicePhoneMobileIcon
                        v-if="phone?.name && isValidPersianMobile(phone?.phone?.replace(/\s*/g, ''))"
                        class="size-6 shrink-0"
                    />
                    <PhoneIcon v-else class="size-6 shrink-0"/>
                  </div>
                  <div class="flex flex-wrap gap-2.5 items-center">
                    <a
                        :href="'tel:' + obfuscateNumber(phone.phone)"
                        class="tracking-widest"
                        dir="ltr"
                    >
                      {{ obfuscateNumber(phone.phone) }}
                    </a>
                    <span v-if="phone?.name" class="text-sm text-secondary">{{ phone?.name }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div
              v-for="idx in 2"
              v-if="loadingMenu"
              :key="idx"
              class="w-full px-4 sm:w-1/2 lg:w-2/12 animate-pulse"
          >
            <div class="mb-8 w-full">
              <h4 class="w-2/3 h-3.5 bg-slate-300 rounded-md mb-8"></h4>
              <ul>
                <li
                    v-for="idx2 in 4"
                    :key="idx2"
                >
                  <div class="h-3 mb-3 inline-block w-full rounded-md bg-slate-200"></div>
                </li>
              </ul>
            </div>
          </div>
          <div
              v-for="menu in menus"
              v-else
              :key="menu.id"
              class="w-full px-4 sm:w-1/2 lg:w-2/12"
          >
            <div class="mb-8 w-full">
              <h4 class="text-dark mb-6 text-lg font-iranyekan-bold">{{ menu.title }}</h4>
              <ul>
                <li
                    v-for="item in menu.items"
                    :key="item.id"
                >
                  <a
                      :href="item.link"
                      class="hover:text-primary mb-2 inline-block text-base leading-loose"
                  >
                    {{ item.title }}
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="w-full px-4 sm:w-full lg:w-5/12 flex flex-col-reverse lg:flex-col">
            <div
                v-if="homeSettingStore.getSocials?.length"
                class="mb-8 w-full text-center lg:text-right"
            >
              <h4 class="text-dark mb-6 text-lg font-iranyekan-bold">ما را دنبال کنید</h4>
              <div class="mb-6 flex items-center justify-center lg:justify-start">
                <template
                    v-for="social in homeSettingStore.getSocials"
                    :key="social.id"
                >
                  <a
                      v-if="findItemByKey(SOCIAL_NETWORKS, 'value', social.type, true) || false"
                      :href="social.type === SOCIAL_NETWORKS.EMAIL.value ? obfuscateEmail(social.link) : social.link"
                      class="text-dark hover:bg-primary hover:border-primary mr-3 flex h-8 w-8 items-center justify-center rounded-full border border-[#E5E5E5] hover:text-white sm:mr-4 lg:mr-3 xl:mr-4"
                      target="_blank"
                  >
                    {{ findItemByKey(SOCIAL_NETWORKS, 'value', social.type, true)?.icon }}
                  </a>
                </template>
              </div>
            </div>

            <div
                v-if="homeSettingStore.getFooterNamads?.length"
                class="mb-8 w-full"
            >
              <div class="flex flex-wrap justify-center lg:justify-start">
                <div
                    v-for="namad in homeSettingStore.getFooterNamads"
                    :key="namad.id"
                    class="rounded-lg bg-white p-1 mr-2 my-1 border border-cool shadow-lg text-center w-[6rem] h-[7rem] overflow-hidden mb-5 lg:w-[5rem] lg:h-[6rem] *:inline-block *:w-full *:h-full"
                >
                  {{ namad.link }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <footer
        v-if="homeSettingStore.getFooterCopyright?.toString() !== ''"
        class="bg-gradient-to-b from-white to-sky-100 p-2 pb-8 text-center z-[1]"
    >
      <div class="max-w-7xl mx-auto w-full">
        <p class="text-zinc-800 text-sm">
          &copy;
          {{ homeSettingStore.getFooterCopyright }}
        </p>
      </div>
    </footer>

    <div>
      <span class="absolute left-0 bottom-0 z-0">
      <svg
          fill="none"
          height="229"
          viewBox="0 0 217 229"
          width="217"
          xmlns="http://www.w3.org/2000/svg"
      >
        <path
            d="M-64 140.5C-64 62.904 -1.096 1.90666e-05 76.5 1.22829e-05C154.096 5.49924e-06 217 62.904 217 140.5C217 218.096 154.096 281 76.5 281C-1.09598 281 -64 218.096 -64 140.5Z"
            fill="url(#paint0_linear_1179_5)"
        />
        <defs>
          <linearGradient
              id="paint0_linear_1179_5"
              gradientUnits="userSpaceOnUse"
              x1="76.5"
              x2="76.5"
              y1="281"
              y2="1.22829e-05"
          >
            <stop stop-color="#3056D3" stop-opacity="0.08"/>
            <stop offset="1" stop-color="#C4C4C4" stop-opacity="0"/>
          </linearGradient>
        </defs>
      </svg>
    </span>
      <span class="absolute top-10 right-10 z-0">
      <svg
          fill="none"
          height="75"
          viewBox="0 0 75 75"
          width="75"
          xmlns="http://www.w3.org/2000/svg"
      >
        <path
            d="M37.5 -1.63918e-06C58.2107 -2.54447e-06 75 16.7893 75 37.5C75 58.2107 58.2107 75 37.5 75C16.7893 75 -7.33885e-07 58.2107 -1.63918e-06 37.5C-2.54447e-06 16.7893 16.7893 -7.33885e-07 37.5 -1.63918e-06Z"
            fill="url(#paint0_linear_1179_4)"
        />
        <defs>
          <linearGradient
              id="paint0_linear_1179_4"
              gradientUnits="userSpaceOnUse"
              x1="-1.63917e-06"
              x2="75"
              y1="37.5"
              y2="37.5"
          >
            <stop stop-color="#13C296" stop-opacity="0.31"/>
            <stop offset="1" stop-color="#C4C4C4" stop-opacity="0"/>
          </linearGradient>
        </defs>
      </svg>
    </span>
    </div>
  </div>
</template>

<script setup>
import {computed, inject, onMounted, ref} from "vue";
import {DevicePhoneMobileIcon, PhoneIcon} from "@heroicons/vue/24/outline/index.js";
import {MENU_PLACES, SOCIAL_NETWORKS} from "@/composables/constants.js";
import {findItemByKey, obfuscateEmail, obfuscateNumber} from "@/composables/helper.js";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";
import {isValidPersianMobile} from "@/validation/index.js";

const homeSettingStore = inject('homeSettingStore')
const getPhones = computed(() => {
  let phones = homeSettingStore.getPhones

  if (!Array.isArray(phones) || !phones.length) return null

  let definedPhones = []

  for (let phone in phones) {
    let splatted = phone.split(' ')

    if (splatted?.length < 2) {
      definedPhones.push({
        phone: phone
      })
    }

    definedPhones.push({
      phone: splatted[0],
      name: splatted.slice(1).join(' '),
    })
  }
})

const loadingMenu = ref(true)
const menus = ref([])

onMounted(() => {
  HomeMainPageAPI.fetchMenu(MENU_PLACES.FOOTER.value, {
    success(response) {
      menus.value = response.data
      return false
    },
    error() {
      return false
    },
    finally() {
      loadingMenu.value = false
    },
  })
})
</script>
