import {ref} from "vue";
import {useRoute} from "vue-router";
import {defineTransitionProps, TransitionPresets} from "vue3-page-transition";
import isObject from "lodash.isobject";

export const usePageTransition = () => {
  const route = useRoute()
  const appearance = ref(route?.meta?.appearance || {})

  let appearanceConfig = {
    mode: 'out-in',
    name: TransitionPresets.fadeInUp,
    appear: true,
    overlay: true,
    overlayBgClassName: '!bg-violet-500',
    overlayZIndex: 999,
    transformDistance: '2rem',
    transitionDuration: 300,
  }

  if (isObject(appearance.value) && Object.keys(appearance.value).length) {
    appearanceConfig = Object.assign(appearanceConfig, appearance.value)
  }

  return defineTransitionProps(appearanceConfig)
}
