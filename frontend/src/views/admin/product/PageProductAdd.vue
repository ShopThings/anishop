<template>
  <base-stepy
    v-model:current-step="currentStep"
    :steps="steps"
    :allow-change-steps-by-click="false"
    :loading="loading"
    :manual="true"
    :simple="true"
  >
    <template #information="options">
      <form-product-add-info :options="options"/>
    </template>

    <template #products="options">
      <form-product-add-products :options="options"/>
    </template>

    <template #images="options">
      <form-product-add-image-gallery :options="options"/>
    </template>

    <template #relatedProducts="options">
      <form-product-add-related-product :options="options"/>
    </template>

    <template #description="options">
      <form-product-add-description :options="options"/>
    </template>

    <template #properties="options">
      <form-product-add-properties :options="options"/>
    </template>
  </base-stepy>
</template>

<script setup>
import {reactive, ref} from "vue";
import BaseStepy from "@/components/base/BaseStepy.vue";
import FormProductAddInfo from "./forms/FormProductAddInfo.vue";
import FormProductAddProducts from "./forms/FormProductAddProducts.vue";
import FormProductAddImageGallery from "./forms/FormProductAddImageGallery.vue";
import FormProductAddRelatedProduct from "./forms/FormProductAddRelatedProduct.vue";
import FormProductAddDescription from "./forms/FormProductAddDescription.vue";
import FormProductAddProperties from "./forms/FormProductAddProperties.vue";

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
