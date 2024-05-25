import {ref} from "vue";
import isFunction from "lodash.isfunction";

export const useSelectSearching = (
  {
    limit = 15,
    currentPage = 1,
    lastPage = 1,
    searchFn = function (query) {
    },
  } = {}
) => {
  const loading = ref(false)

  const currentPageLocal = ref(currentPage)
  const lastPageLocal = ref(lastPage)
  const limitLocal = ref(limit)

  function search(query) {
    loading.value = true
    if (isFunction(searchFn)) {
      searchFn(query)
    }
  }

  function searchNextPage(query) {
    if (currentPageLocal.value < lastPageLocal.value) {
      currentPageLocal.value++
      search(query)
    }
  }

  function searchPrevPage(query) {
    if (currentPageLocal.value > 1) {
      currentPageLocal.value--
      search(query)
    }
  }

  return {
    limit: limitLocal,
    currentPage: currentPageLocal,
    lastPage: lastPageLocal,
    offset() {
      return (currentPageLocal.value - 1) * limitLocal.value;
    },
    isLoading: loading,
    search,
    searchNextPage,
    searchPrevPage,
  }
}
