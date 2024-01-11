<template>
  <div class="relative h-full">
    <nav
      class="sticky top-0 hidden lg:block z-[1] transition-all delay-[200ms] duration-[450ms]"
      :class="[
                 bg,
                 !mini ? 'w-72' : 'w-20'
             ]">
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
