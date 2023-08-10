<template>
    <form @submit.prevent>
        <div class="sm:flex text-left">
            <base-input name="folder" class="grow mb-2" placeholder="نام پوشه را وارد کنید...">
                <template #icon>
                    <FolderPlusIcon class="h-6 w-6 text-gray-400"/>
                </template>
            </base-input>
            <base-button class="sm:shrink-0 mb-2 sm:mr-2 bg-emerald-500 text-sm">
                ایجاد پوشه جدید
            </base-button>
        </div>
    </form>
</template>

<script setup>
import {FolderPlusIcon} from '@heroicons/vue/24/outline';
import BaseInput from "../BaseInput.vue";
import BaseButton from "../BaseButton.vue";

defineProps({
    path: String,
})

const emit = defineEmits(['submit'])
</script>

<style scoped>

</style>
