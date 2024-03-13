<template>
  <div class="relative h-full">
    <nav
        :class="[
                 bg,
                 !mini ? 'w-72' : 'w-20'
             ]"
        class="sticky top-0 hidden lg:block z-[1] transition-all delay-[200ms] duration-[450ms]">
      <slot/>
    </nav>
  </div>
</template>

<script setup>
defineProps({
  mini: {
    type: Boolean,
    default: true,
  },
  bg: {
    type: String,
    default: 'bg-indigo-600 text-white'
  },
})
</script>
