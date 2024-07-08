<template>
  <vue3-tags-input
    ref="tagsInputRef"
    :add-tag-on-keys="addTagOnKeys"
    :placeholder="placeholder"
    :read-only="readOnly"
    :tags="tags"
    @on-tags-changed="tagChangeHandler"
  />
</template>

<script setup>
import Vue3TagsInput from "vue3-tags-input";
import {computed, ref} from "vue";
import {watchImmediate} from "@vueuse/core";

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
    default: [13, 190],
  },
})
const emit = defineEmits(['update:tags', 'on-tags-changed'])

const tagsInputRef = ref(null)

const tags = computed({
  get() {
    return props.tags
  },
  set(value) {
    emit('update:tags', value)
  },
})

function tagChangeHandler(t) {
  emit('on-tags-changed', t)
}

watchImmediate(tagsInputRef, () => {
  if (tagsInputRef.value) {
    let inp = tagsInputRef.value.$el.querySelector('input')
    inp.enterKeyHint = "done"
  }
})
</script>
