<template>
  <base-dialog :open="open" @open="() => emit('open')" @close="() => emit('close')">
    <template #button="{open}">
      <button @click="open"
              type="button"
              class="w-[40px] h-[40px] border-0 py-2 px-2 rounded-lg bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all">
        <MagnifyingGlassIcon class="h-6 w-6 text-slate-400"/>
      </button>
    </template>

    <template #title>
      <div class="mb-2">
        جستجو در بلاگ
      </div>
    </template>

    <template #body>
      <form @submit.prevent>
        <base-input
            placeholder="متن جستجو را وارد نمایید..."
            name="search"
            @mount="focusOnInput"
        >
          <template #icon>
            <MagnifyingGlassCircleIcon class="h-6 w-6 text-gray-300"/>
          </template>
        </base-input>

        <div class="mt-4">
          <button
              type="submit"
              class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 transition"
          >
            <MagnifyingGlassIcon class="w-5 h-5 ml-2"/>
            انجام جستجو
          </button>
        </div>
      </form>
    </template>
  </base-dialog>
</template>

<script setup>
import {MagnifyingGlassIcon, MagnifyingGlassCircleIcon} from '@heroicons/vue/24/outline';
import BaseDialog from "./base/BaseDialog.vue";
import BaseInput from "./base/BaseInput.vue";

defineProps({
  open: {
    type: Boolean,
    default: false,
  }
})
const emit = defineEmits(['open', 'close'])

function focusOnInput(input) {
  input.focus()
}
</script>

<style scoped>

</style>
