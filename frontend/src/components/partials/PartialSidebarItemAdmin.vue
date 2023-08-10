<template>
    <MenuItem v-slot="{ active }">
        <li class="border-r border-r-white border-opacity-40 pr-2">
            <slot></slot>
        </li>
    </MenuItem>
</template>

<script setup>
import {MenuItem} from "@headlessui/vue"
</script>

<style scoped>

</style>
