<template>
  <component :is="as">
    <partial-navbar-item-guest :as="asChild" class="relative">
      <a href="#"
         class="text-black hover:opacity-75 transition w-full inline-block peer">
        خانه
      </a>
      <span
        class="w-0 h-0.5 bg-sky-400 rounded-full absolute -bottom-2 right-0 peer-hover:w-5 transition-all"></span>
    </partial-navbar-item-guest>

    <partial-navbar-item-guest :as="asChild" class="relative">
      <a href="#"
         class="text-black hover:opacity-75 transition w-full inline-block peer">
        اخبار
      </a>
      <span
        class="w-0 h-0.5 bg-sky-400 rounded-full absolute -bottom-2 right-0 peer-hover:w-5 transition-all"></span>
    </partial-navbar-item-guest>

    <partial-navbar-item-guest :as="asChild" class="relative">
      <a href="#"
         class="text-black hover:opacity-75 transition w-full inline-block peer">
        دسته‌بندی‌ها
      </a>
      <span
        class="w-0 h-0.5 bg-sky-400 rounded-full absolute -bottom-2 right-0 peer-hover:w-5 transition-all"></span>
    </partial-navbar-item-guest>

    <partial-navbar-item-guest :as="asChild" class="relative">
      <a href="#"
         class="text-black hover:opacity-75 transition w-full inline-block peer">
        درباره ما
      </a>
      <span
        class="w-0 h-0.5 bg-sky-400 rounded-full absolute -bottom-2 right-0 peer-hover:w-5 transition-all"></span>
    </partial-navbar-item-guest>
  </component>
</template>

<script setup>
import PartialNavbarItemGuest from "./PartialNavbarItemGuest.vue";

defineProps({
  as: {
    type: String,
    default: 'ul',
  },
  asChild: {
    type: String,
    default: 'li',
  }
})
</script>
