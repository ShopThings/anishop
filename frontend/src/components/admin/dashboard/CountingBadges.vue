<template>
  <div
    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3"
  >
    <partial-counting-badge
      v-if="userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.READ)"
      :count="0"
      icon="UsersIcon"
      text="کاربران"
    />
  </div>
</template>

<script setup>
import PartialCountingBadge from "@/components/admin/dashboard/partials/PartialCountingBadge.vue";
import {PERMISSION_PLACES, PERMISSIONS, useAdminAuthStore} from "@/store/StoreUserAuth.js";

const userStore = useAdminAuthStore()
</script>
