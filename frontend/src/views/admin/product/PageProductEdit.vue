<template>
  <base-stepy
      v-model:current-step="currentStep"
      :steps="steps"
      :loading="loading"
      :manual="true"
      :simple="true"
  >
    <template #information="options">
      <form-product-edit-info :options="options"/>
    </template>

    <template #products="options">
      <form-product-edit-products :options="options"/>
    </template>

    <template #images="options">
      <form-product-edit-image-gallery :options="options"/>
    </template>

    <template #relatedProducts="options">
      <form-product-edit-related-product :options="options"/>
    </template>

    <template #description="options">
      <form-product-edit-description :options="options"/>
    </template>

    <template #properties="options">
      <form-product-edit-properties :options="options"/>
    </template>
  </base-stepy>
</template>

<script setup>
import {reactive, ref} from "vue";
import BaseStepy from "../../../components/base/BaseStepy.vue";
import FormProductEditInfo from "./forms/FormProductEditInfo.vue";
import FormProductEditProducts from "./forms/FormProductEditProducts.vue";
import FormProductEditImageGallery from "./forms/FormProductEditImageGallery.vue";
import FormProductEditRelatedProduct from "./forms/FormProductEditRelatedProduct.vue";
import FormProductEditDescription from "./forms/FormProductEditDescription.vue";
import FormProductEditProperties from "./forms/FormProductEditProperties.vue";

const currentStep = ref('information')
const loading = ref(false)
const steps = reactive({
  information: {
    icon: 'ArchiveBoxIcon',
    text: 'مشخصات اولیه',
  },
  products: {
    icon: 'ListBulletIcon',
    text: 'محصولات',
  },
  images: {
    icon: 'PhotoIcon',
    text: 'گالری تصاویر',
  },
  relatedProducts: {
    icon: 'SwatchIcon',
    text: 'محصولات مرتبط',
  },
  description: {
    icon: 'Bars4Icon',
    text: 'توضیحات',
  },
  properties: {
    icon: 'TableCellsIcon',
    text: 'ویژگی‌ها',
  },
})
</script>

<style scoped>

</style>
