<template>
  <template v-if="user?.first_name || user?.last_name">
    {{
      (user?.first_name + ' ' + user?.last_name).trim()
    }}
  </template>
  <template v-else>
    {{ user?.username || '-' }}
  </template>
</template>

<script setup>
defineProps({
  user: Object
})
</script>
