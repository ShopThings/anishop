<template>
  <div class="p-3">
    <product-detail :slider-top-spacing="110"/>
  </div>

  <app-newsletter/>
</template>

<script setup>
import ProductDetail from "@/components/product/ProductDetail.vue";
import AppNewsletter from "@/components/AppNewsletter.vue";
</script>
