<template>
  <VTransitionSlideFadeLeftX v-show="isOpen">
    <ul class="mr-3 block">
      <VTransitionSlideFadeLeftXGroup>
        <li
            v-for="(item, idx) in items" :key="idx"
            :ref="() => {
                        if(!itemsRef[idx]) itemsRef[idx] = {open: false}
                    }"
            @click="clickHandler(idx, items)"
        >
          <div class="flex items-center mb-2">
            <FolderOpenIcon v-if="itemsRef.value[idx]?.open" class="h-6 w-6 ml-2"/>
            <FolderIcon v-else class="h-6 w-6 ml-2"/>

            {{ item.name }}

            <template v-if="itemsRef.value[idx]?.chilren.length">
              <ChevronDownIcon v-if="itemsRef.value[idx]?.open" class="h-5 w-5 mr-2"/>
              <ChevronLeftIcon v-else class="h-5 w-5 mr-2"/>
            </template>
          </div>

          <partial-tree-directory
              v-if="itemsRef.value[idx]?.chilren.length"
              :open="itemsRef.value[idx]?.open"
              :items="itemsRef.value[idx]?.chilren"
              :disk="disk"
              @selection-change="emit('selection-change', item)"
          ></partial-tree-directory>
          <div v-else class="mr-3 text-sm text-gray-400">
            هیچ پوشه‌ای وجود ندارد.
          </div>
        </li>
      </VTransitionSlideFadeLeftXGroup>
    </ul>
  </VTransitionSlideFadeLeftX>
</template>

<script setup>
import {FolderIcon, FolderOpenIcon, ChevronDownIcon, ChevronLeftIcon} from '@heroicons/vue/24/outline';
import {ref} from "vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";
import VTransitionSlideFadeLeftXGroup from "../../../transitions/VTransitionSlideFadeLeftXGroup.vue";
import VTransitionSlideFadeLeftX from "../../../transitions/VTransitionSlideFadeLeftX.vue";

const props = defineProps({
  open: {
    type: Boolean,
    default: true,
  },
  items: Object,
  disk: String,
})

const emit = defineEmits(['selection-change'])

const isOpen = ref(props.open)
const itemsRef = ref({})

function clickHandler(idx, item) {
  emit('selection-change', item)

  if (itemsRef.value[idx].children) {
    itemsRef.value[idx].open = !itemsRef.value[idx].open
  } else {
    useRequest(apiRoutes.admin.files.tree, {
      params: {
        path: item.full_path,
        disk: props.disk,
      },
    }, {
      success: (response) => {
        itemsRef.value[idx].children = response.data
        itemsRef.value[idx].open = true
      },
    })
  }
}
</script>

<style scoped>

</style>
