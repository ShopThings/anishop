<template>
  <vue3-tags-input
      :add-tag-on-keys="addTagOnKeys"
      :placeholder="placeholder"
      :read-only="readOnly"
      :tags="tags"
      @on-tags-changed="(t) => {emit('on-tags-changed', t)}"
  />
</template>

<script setup>
import Vue3TagsInput from "vue3-tags-input";
import {computed} from "vue";

const props = defineProps({
  tags: Array,
  placeholder: String,
  readOnly: Boolean,
  addTagOnKeys: {
    type: Array,
    // 13: Enter
    // 190: Comma or '.'
    // 188: Comma or ','
    // 32: Space or ' '
    default: [13, 190, 32],
  },
})
const emit = defineEmits(['update:tags', 'on-tags-changed'])

const tags = computed({
  get() {
    return props.tags
  },
  set(value) {
    emit('update:tags', value)
  },
})
</script>
