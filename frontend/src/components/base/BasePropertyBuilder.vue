<template>
  <div class="p-2">
    <TransitionGroup name="fade-group">
      <div
          v-for="(property, idx) in internalProperties"
          :key="idx"
          class="p-2 border-2 border-dashed rounded-lg border-indigo-400 mb-3 relative"
      >
        <partial-builder-remove-btn
            v-if="internalProperties.length > 1"
            @click="handleRemoveProperty(idx)"
        />

        <div class="p-2 w-full sm:w-1/2 xl:w-1/3">
          <base-input
              label-title="عنوان ویژگی"
              placeholder="وارد نمایید"
              :name="'title[' + idx + ']'"
              :value="property?.title"
              @input="(v) => {property.title = v}"
          />
        </div>

        <partial-baby-property-builder
            v-model:properties="property.children"
            property-title-text="عنوان زیر ویژگی"
            tags-text="ویژگی‌ها"
            new-button-text="زیر ویژگی جدید"
        />
      </div>
    </TransitionGroup>

    <div class="mt-3 mb-1">
      <base-button
          class="!text-green-600 border-green-500 w-full sm:w-auto flex items-center hover:bg-green-50 mr-auto"
          @click="handlePropertyClick"
      >
        <span class="mr-auto text-sm">ویژگی جدید</span>
        <PlusIcon class="h-6 w-6 mr-auto sm:mr-2"/>
      </base-button>
    </div>
  </div>
</template>

<script setup>
import {computed} from "vue";
import BaseInput from "./BaseInput.vue";
import BaseButton from "./BaseButton.vue";
import {PlusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialBabyPropertyBuilder from "../partials/PartialBabyPropertyBuilder.vue";
import PartialBuilderRemoveBtn from "../partials/PartialBuilderRemoveBtn.vue";

const props = defineProps({
  properties: Array,
})
const emit = defineEmits(['update:properties', 'remove', 'add'])

const internalProperties = computed({
  get() {
    return props.properties
  },
  set(value) {
    emit('update:properties', value)
  },
})

if (!props.properties || !props.properties.length) {
  internalProperties.value = [{
    title: '',
    children: [
      {
        title: '',
        tags: [],
      },
    ],
  }]
}

function handlePropertyClick() {
  internalProperties.value.push({
    title: '',
    children: [
      {
        title: '',
        tags: [],
      },
    ],
  })

  emit('add', internalProperties.value)
}

function handleRemoveProperty(idx) {
  internalProperties.value.splice(idx, 1)

  emit('remove', internalProperties.value)
}
</script>

<style scoped>
.fade-group-enter-active,
.fade-group-leave-active {
  transition: opacity 0.3s ease;
  transition-delay: .05s;
}

.fade-group-enter-from,
.fade-group-leave-to {
  opacity: 0;
}
</style>
