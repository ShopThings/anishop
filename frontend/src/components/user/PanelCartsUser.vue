<template>
  <ul>
    <li class="border rounded p-3 mb-3 flex items-center border-emerald-500 cursor-pointer hover:bg-gray-50 transition">
      <ShoppingBagIcon class="w-6 h-6 text-emerald-400 ml-2"/>
      <span class="text-black">سبد خرید فعال</span>
    </li>
    <li class="border rounded p-3 mb-3 flex items-center cursor-pointer hover:bg-gray-50 transition">
      <ShoppingBagIcon class="w-6 h-6 text-gray-400 ml-2"/>
      <span class="text-gray-600">سبد خرید پشتیبان</span>
    </li>
  </ul>
</template>

<script setup>
import {ShoppingBagIcon} from "@heroicons/vue/24/outline";
</script>
