<template>
  <div
      ref="panel"
      v-click-outside="closePanel"
      :class="containerClass"
      @click="containerClick"
  >
    <slot></slot>
  </div>
</template>

<script>
import {ref} from 'vue';
import isFunction from "lodash.isfunction";

const clickOutsideDirective = {
  beforeMount(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!el.contains(event.target) && el !== event.target) {
        binding.value(event);
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  beforeUnmount(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  },
};

export default {
  props: {
    containerClass: [String, Array],
    closeCallback: Function,
  },
  emits: ['click'],
  setup(props, {emit}) {
    const panel = ref(null);

    const closePanel = (event) => {
      if (isFunction(props.closeCallback)) {
        props.closeCallback(event);
      }
    };

    const containerClick = (event) => {
      emit('click', event)
    };

    return {
      panel,
      closePanel,
      containerClick,
    };
  },
  directives: {
    'click-outside': clickOutsideDirective,
  },
};
</script>
