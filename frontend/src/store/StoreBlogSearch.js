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

  function setCategories(categories) {
    categories.value = categories
  }

  function setBlogs(blogs) {
    blogs.value = blogs
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
