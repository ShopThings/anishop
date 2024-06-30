<template>
  <partial-card class="border-0">
    <template #body>
      <div class="p-3">
        <base-tab-panel :tabs="tabs" tab-panel-extra-class="!border-0">
          <template #info>
            <form-user-update-info/>
          </template>

          <template #password>
            <form-user-update-password/>
          </template>
        </base-tab-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import BaseTabPanel from "@/components/base/BaseTabPanel.vue";
import FormUserUpdateInfo from "./forms/FormUserUpdateInfo.vue";
import FormUserUpdatePassword from "./forms/FormUserUpdatePassword.vue";
import PartialCard from "@/components/partials/PartialCard.vue";

const tabs = {
  info: {
    text: 'اطلاعات حساب'
  },
  password: {
    text: 'تغییر کلمه عبور',
  },
}
</script>
