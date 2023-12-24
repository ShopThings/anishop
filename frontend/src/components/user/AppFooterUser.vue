<template>
  <div class="relative border-t-2">
    <footer class="p-2 pb-6 text-center z-[1]">
      <router-link :to="{name: 'home'}"
                   class="mb-3 inline-block max-w-[150px]">
        <img class="mt-[10px] max-w-full"
             src="/logo-with-type.png"
             alt="لوگو">
      </router-link>

      <div class="md:container md:mx-auto">
        <p class="text-zinc-800 text-sm">
          &copy;
          کلیه حقوق این فروشگاه متعلق به شرکت هیوا می‌باشد و هرگونه کپی برداری از آن پیگرد قانونی دارد.
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup>

</script>

<style scoped>

</style>
