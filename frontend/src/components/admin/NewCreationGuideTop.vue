<template>
    <div
        class="text-center mb-3 border-2 border-dashed border-violet-400 rounded-lg p-4 bg-white bg-opacity-60 sm:text-right sm:flex sm:items-center">
        <div class="text-sm text-secondary grow mb-2 sm:mb-0">
            <slot name="text"></slot>
        </div>
        <router-link :to="{name: routeName}"
                     class="sm:shrink-0 rounded mr-2 bg-emerald-500 text-white p-2 px-3 mx-auto flex items-center justify-center hover:bg-opacity-90 transition group text-sm">
            <slot name="buttonText"></slot>
        </router-link>
    </div>
</template>

<script setup>
defineProps({
    routeName: String
})
</script>

<style scoped>

</style>
