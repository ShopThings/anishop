import {ref} from "vue";

export const useLightbox = (items) => {
  const visibleRef = ref(false)
  const itemsRef = ref(items)

  const onLightboxShow = () => (visibleRef.value = true)
  const onLightboxHide = () => (visibleRef.value = false)

  return {
    visibleRef,
    itemsRef,
    onLightboxShow,
    onLightboxHide,
  }
}
