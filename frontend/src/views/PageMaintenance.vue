<template>
  <div class="relative flex items-center justify-center h-screen gradient-background">
    <div class="max-w-md w-full bg-transparent p-8">
      <div class="flex items-center justify-center">
        <router-link :to="{name: 'home'}">
          <img
            :alt="homeSettingStore.getTitle"
            class="h-12 mb-4 lg:hidden"
            src="/logo-with-type-light.png"
          />
        </router-link>
      </div>

      <div
        class="p-8 rounded-lg bg-white/50 supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80">
        <div
          class="bg-gradient-to-r from-blue-500 to-blue-900 rounded-full p-2 w-16 h-16 flex items-center justify-center mx-auto"
        >
          <WrenchScrewdriverIcon class="size-9 text-white"/>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 text-center mt-4 mb-2">
          در دست تعمیر
        </h1>

        <p class="text-gray-700 text-center leading-relaxed">
          سایت در حال حاضر در دسترس نمی‌باشد، لطفا صبر کنید...
        </p>

        <div
          v-if="getSiteFirstPhone || getSiteMail"
          class="text-sm text-slate-700"
        >
          <div class="my-6 h-[2px] rounded-full bg-white/20"></div>

          <p class="leading-relaxed">
            در صورت ضرورت ارتباط، با استفاده از راه‌های ارتباطی زیر اقدام نمایید:
          </p>

          <ul class="flex flex-col gap-4 mt-6">
            <li v-if="getSiteFirstPhone">
              <div class="text-sm">
                <div class="flex items-center gap-3">
                  <div class="p-2 rounded-full bg-black">
                    <DevicePhoneMobileIcon class="size-6 text-blue-400"/>
                  </div>
                  <a
                    :href="'tel:' + getSiteFirstPhone?.phone"
                    class="tracking-widest font-iranyekan-bold text-black"
                    v-html="obfuscateNumber(getSiteFirstPhone?.phone)"
                  ></a>
                  <span v-if="getSiteFirstPhone?.name" class="text-sm">{{
                      getSiteFirstPhone?.name
                    }}</span>
                </div>
              </div>
            </li>
            <li v-if="getSiteMail">
              <div class="font-iranyekan-bold text-sm text-center">
                <div class="flex items-center gap-3">
                  <div class="p-2 rounded-full bg-black">
                    <EnvelopeIcon class="size-6 text-teal-400"/>
                  </div>
                  <a
                    :href="'mailto:' + getSiteMail.link"
                    class="text-black"
                    v-html="obfuscateEmail(getSiteMail.link)"
                  ></a>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {computed, inject} from "vue";
import {WrenchScrewdriverIcon} from "@heroicons/vue/24/outline/index.js"
import {DevicePhoneMobileIcon, EnvelopeIcon} from "@heroicons/vue/24/solid/index.js"
import {findItemByKey, obfuscateEmail, obfuscateNumber} from "@/composables/helper.js";
import {SOCIAL_NETWORKS} from "@/composables/constants.js";

const homeSettingStore = inject('homeSettingStore')

const getSiteFirstPhone = computed(() => {
  let phones = homeSettingStore.getPhones

  if (!Array.isArray(phones) || !phones.length) return null

  let first = phones[0]
  let splatted = first.split(' ')

  if (splatted?.length !== 2) {
    return {
      phone: first
    }
  }

  return {
    phone: splatted[0],
    name: splatted[1],
  }
})

const getSiteMail = computed(() => {
  let socials = homeSettingStore.getSocials
  if (!socials?.length) return null

  return findItemByKey(socials, 'type', SOCIAL_NETWORKS.EMAIL.value, true) || null
})
</script>

<style scoped>
.gradient-background {
  background: linear-gradient(54deg, #05056d, #7e4bdb, #1c2653, #126069, #553d8e);
  background-size: 300% 300%;
  animation: gradient-animation 20s ease infinite;
}

@keyframes gradient-animation {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}
</style>
