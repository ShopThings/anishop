import {computed, ref} from "vue";
import {defineStore} from "pinia";

export const usePageLoaderStore = defineStore('page_loader', () => {
  const loading = ref(false)

  const isLoading = computed(() => {
    return loading.value
  })

  function setLoading(boolean) {
    loading.value = boolean
  }

  function $reset() {
    loading.value = false
  }

  return {
    loading,
    isLoading,
    setLoading,
    //
    $reset,
  }
})
