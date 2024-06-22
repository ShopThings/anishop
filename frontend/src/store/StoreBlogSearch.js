import {computed, ref} from "vue"
import {defineStore} from "pinia"

export const useBlogSearchStore = defineStore('blog_search_result', () => {
  let categories = ref(null)
  let blogs = ref(null)

  const getCategories = computed(
    () => categories.value
  )
  const getBlogs = computed(
    () => blogs.value
  )

  function setCategories(items) {
    categories.value = items
  }

  function setBlogs(items) {
    blogs.value = items
  }

  function $reset() {
    categories.value = null
    blogs.value = null
  }

  return {
    categories, getCategories, setCategories,
    blogs, getBlogs, setBlogs,
    //
    $reset,
  }
})
