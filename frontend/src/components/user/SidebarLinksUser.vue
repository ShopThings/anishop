<template>
  <ul class="flex flex-col gap-3">
    <li>
      <router-link
        :to="{name: 'user.home'}"
        class="rounded-2xl flex items-center gap-2 bg-white ring-rose-400 hover:bg-opacity-75 hover:ring-2 transition-all py-5 pl-3 pr-5 relative"
      >
        <div class="p-2 rounded-lg bg-cyan-200 shrink-0">
          <WindowIcon class="h-6 w-6 text-cyan-700"/>
        </div>
        <div class="grow">
          پیشخوان
        </div>
      </router-link>
    </li>
    <li>
      <router-link
        :to="{name: 'user.orders'}"
        class="rounded-2xl flex items-center gap-2 bg-white ring-rose-400 hover:bg-opacity-75 hover:ring-2 transition-all py-5 pl-3 pr-5 relative"
      >
        <div class="p-2 rounded-lg bg-cyan-200 shrink-0">
          <ShoppingBagIcon class="h-6 w-6 text-cyan-700"/>
        </div>
        <div class="grow">
          سفارشات
        </div>
        <div class="text-xl text-slate-400 border-r-2 border-slate-200 pr-2 min-w-[2rem] text-center">
          12
        </div>
      </router-link>
    </li>
    <li>
      <router-link
        :to="{name: 'user.return_orders'}"
        class="rounded-2xl flex items-center gap-2 bg-white ring-rose-400 hover:bg-opacity-75 hover:ring-2 transition-all py-5 pl-3 pr-5 relative"
      >
        <div class="p-2 rounded-lg bg-cyan-200 shrink-0">
          <ArchiveBoxXMarkIcon class="h-6 w-6 text-cyan-700"/>
        </div>
        <div class="grow">
          سفارشات مرجوع شده
        </div>
        <div class="text-xl text-slate-400 border-r-2 border-slate-200 pr-2 min-w-[2rem] text-center">
          0
        </div>
      </router-link>
    </li>
    <li>
      <router-link
        :to="{name: 'user.comments'}"
        class="rounded-2xl flex items-center gap-2 bg-white ring-rose-400 hover:bg-opacity-75 hover:ring-2 transition-all py-5 pl-3 pr-5 relative"
      >
        <div class="p-2 rounded-lg bg-cyan-200 shrink-0">
          <ChatBubbleLeftRightIcon class="h-6 w-6 text-cyan-700"/>
        </div>
        <div class="grow">
          دیدگاه‌ها
        </div>
        <div class="text-xl text-slate-400 border-r-2 border-slate-200 pr-2 min-w-[2rem] text-center">
          0
        </div>
      </router-link>
    </li>
    <li>
      <router-link
        :to="{name: 'user.favorite_products'}"
        class="rounded-2xl flex items-center gap-2 bg-white ring-rose-400 hover:bg-opacity-75 hover:ring-2 transition-all py-5 pl-3 pr-5 relative"
      >
        <div class="p-2 rounded-lg bg-cyan-200 shrink-0">
          <HeartIcon class="h-6 w-6 text-cyan-700"/>
        </div>
        <div class="grow">
          محصولات مورد علاقه
        </div>
        <div class="text-xl text-slate-400 border-r-2 border-slate-200 pr-2 min-w-[2rem] text-center">
          0
        </div>
      </router-link>
    </li>
    <li>
      <router-link
        :to="{name: 'user.addresses'}"
        class="rounded-2xl flex items-center gap-2 bg-white ring-rose-400 hover:bg-opacity-75 hover:ring-2 transition-all py-5 pl-3 pr-5 relative"
      >
        <div class="p-2 rounded-lg bg-cyan-200 shrink-0">
          <BookOpenIcon class="h-6 w-6 text-cyan-700"/>
        </div>
        <div class="grow">
          آدرس‌های من
        </div>
        <div class="text-xl text-slate-400 border-r-2 border-slate-200 pr-2 min-w-[2rem] text-center">
          1
        </div>
      </router-link>
    </li>
    <li>
      <router-link
        :to="{name: 'user.contacts'}"
        class="rounded-2xl flex items-center gap-2 bg-white ring-rose-400 hover:bg-opacity-75 hover:ring-2 transition-all py-5 pl-3 pr-5 relative"
      >
        <div class="p-2 rounded-lg bg-cyan-200 shrink-0">
          <ChatBubbleLeftEllipsisIcon class="h-6 w-6 text-cyan-700"/>
        </div>
        <div class="grow">
          تماس‌های من
        </div>
        <div class="text-xl text-slate-400 border-r-2 border-slate-200 pr-2 min-w-[2rem] text-center">
          0
        </div>
      </router-link>
    </li>
  </ul>
</template>

<script setup>
import {
  WindowIcon,
  ShoppingBagIcon,
  ArchiveBoxXMarkIcon,
  ChatBubbleLeftRightIcon,
  HeartIcon,
  BookOpenIcon,
  ChatBubbleLeftEllipsisIcon,
} from "@heroicons/vue/24/outline/index.js"
</script>

<style scoped>
.router-link-active,
.router-link-exact-active {
  --tw-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  --tw-shadow-colored: 0 4px 6px -1px var(--tw-shadow-color), 0 2px 4px -2px var(--tw-shadow-color);
  box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
}

.router-link-active:before,
.router-link-exact-active:before {
  content: '';
  position: absolute;
  bottom: 0;
  right: 50%;
  width: 30%;
  height: .325rem;
  border-radius: 50rem;
  background-color: #000000;
  transform: translate(50%, 0);
}
</style>
