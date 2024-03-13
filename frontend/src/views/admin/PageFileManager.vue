<template>
  <base-file-manager
      :allow-copy="true"
      :allow-delete="true"
      :allow-download="true"
      :allow-move="true"
      :allow-multi-operation="true"
      :allow-rename="true"
      :has-create-folder="true"
      :has-search="true"
      :has-uploader="true"
  >
  </base-file-manager>
</template>

<script setup>
import BaseFileManager from "@/components/base/BaseFileManager.vue";
</script>
