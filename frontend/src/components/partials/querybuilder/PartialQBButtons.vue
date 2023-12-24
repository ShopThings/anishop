<template>
  <div class="flex flex-wrap">
    <div class="flex ml-2">
      <base-button
          v-if="showAddButton"
          class="!rounded-r-md bg-emerald-500 text-sm !py-1"
          :class="[showGroupButton ? '!rounded-l-none' : '']"
          @click="emit('add', condition)"
      >
        {{ buttonsText.addRule }}
      </base-button>
      <base-button
          v-if="showGroupButton"
          class="!rounded-l-md bg-emerald-500 text-sm !py-1"
          :class="[showAddButton ? '!rounded-r-none' : '']"
          @click="emit('group', condition)"
      >
        {{ buttonsText.addGroup }}
      </base-button>
    </div>

    <base-button
        v-if="showRemoveButton"
        class="ml-2 bg-rose-500 text-sm !py-1"
        @click="emit('remove')"
    >
      {{ buttonsText.removeGroup }}
    </base-button>

    <div
        v-if="showAndOrButton"
        class="flex"
    >
      <base-button
          class="!rounded-r-md !rounded-l-none bg-indigo-500 text-sm !py-1"
          :class="[condition === 'and' ? 'bg-indigo-700' : '']"
          @click="changeCondition('and')"
      >
        {{ buttonsText.and }}
      </base-button>
      <base-button
          class="!rounded-l-md !rounded-r-none bg-indigo-500 text-sm !py-1"
          :class="[condition === 'or' ? 'bg-indigo-700' : '']"
          @click="changeCondition('or')"
      >
        {{ buttonsText.or }}
      </base-button>
    </div>
  </div>
</template>

<script setup>
import BaseButton from "../../base/BaseButton.vue";
import {ref} from "vue";

const props = defineProps({
  showAddButton: {
    type: Boolean,
    default: true,
  },
  showGroupButton: {
    type: Boolean,
    default: true,
  },
  showRemoveButton: {
    type: Boolean,
    default: true,
  },
  showAndOrButton: {
    type: Boolean,
    default: true,
  },
  buttonsText: {
    type: Object,
    required: true,
  },
})
const emit = defineEmits(['add', 'group', 'remove', 'change-condition'])

const condition = ref('and')

function changeCondition(cond) {
  condition.value = cond
  emit('change-condition', cond)
}
</script>

<style scoped>

</style>
