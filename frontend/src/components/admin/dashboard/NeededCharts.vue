<template>
  <div v-if="userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.READ)">
    <hr class="mt-12 mb-6">
    <partial-chart-user/>
  </div>

  <div v-if="userStore.hasPermission(PERMISSION_PLACES.ORDER, PERMISSIONS.READ)">
    <hr class="mt-12 mb-6">
    <partial-chart-order/>
  </div>

  <div v-if="userStore.hasPermission(PERMISSION_PLACES.RETURN_ORDER_REQUEST, PERMISSIONS.READ)">
    <hr class="mt-12 mb-6">
    <partial-chart-return-order/>
  </div>

  <div v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.READ)">
    <hr class="mt-12 mb-6">
    <partial-table-most-sell-product/>
  </div>
</template>

<script setup>
import PartialChartUser from "@/components/admin/dashboard/partials/PartialChartUser.vue";
import {PERMISSION_PLACES, PERMISSIONS, useAdminAuthStore} from "@/store/StoreUserAuth.js";
import PartialChartOrder from "@/components/admin/dashboard/partials/PartialChartOrder.vue";
import PartialChartReturnOrder from "@/components/admin/dashboard/partials/PartialChartReturnOrder.vue";
import PartialTableMostSellProduct from "@/components/admin/dashboard/partials/PartialTableMostSellProduct.vue";

const userStore = useAdminAuthStore()
</script>
