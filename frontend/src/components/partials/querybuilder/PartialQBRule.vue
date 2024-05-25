<template>
  <div class="border-2 border-slate-300 border-dashed bg-white rounded-lg p-2 my-2">
    <div class="border border-blue-300 rounded bg-blue-100 pt-5 p-1 relative">
      <div class="!absolute top-0 left-0 -translate-y-1/4 -translate-x-2 z-[1] flex items-center">
        <div class="flex ml-2">
          <base-button
              :class="rule.condition === 'and' ? 'bg-indigo-700' : ''"
              class="!rounded-r-md !rounded-l-none bg-indigo-500 text-sm !py-0"
              @click="changeCondition('and')"
          >
            {{ buttonsText.and }}
          </base-button>
          <base-button
              :class="rule.condition === 'or' ? 'bg-indigo-700' : ''"
              class="!rounded-l-md !rounded-r-none bg-indigo-500 text-sm !py-0"
              @click="changeCondition('or')"
          >
            {{ buttonsText.or }}
          </base-button>
        </div>

        <base-button
            class="bg-rose-500 rounded !py-0 !px-2 flex items-center text-sm"
            @click="emit('remove')"
        >
          {{ buttonsText.removeRule }}
        </base-button>
      </div>

      <div class="flex flex-wrap">
        <div class="p-2 w-full sm:w-1/3 lg:w-4/12">
          <base-select-searchable
              :options="columns"
              options-key="value"
              options-text="name"
              @change="handleColumnChange"
          />
        </div>
        <div
            v-if="selectedType"
            class="p-2 w-full sm:w-1/3 lg:w-4/12"
        >
          <base-select-searchable
            ref="operatorsRef"
              :options="getOperators"
              options-key="value"
              options-text="name"
              @change="handleOperatorChange"
          />
        </div>

        <template
            v-if="getInput && selectedOperator && noNeededInputOperators.indexOf(selectedOperator.value) === -1"
        >
          <div class="w-full sm:w-1/3 lg:w-4/12 sm:flex sm:items-center">
            <div class="p-2 grow">
              <base-input
                  v-if="INPUTS.TEXT === getInput?.type"
                  :name="selectedColumn.value"
                  :placeholder="getInput?.placeholder"
                  :value="rule.value"
                  @input="(v) => {rule.value = v}"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
              <base-input
                  v-else-if="INPUTS.NUMBER === getInput?.type"
                  :max="hasTwoValues.indexOf(selectedOperator.value) !== -1 ? (rule.value2 || getInput?.max ? parseInt(rule.value2 || getInput?.max) : null) : (getInput?.max ? parseInt(getInput?.max) : null)"
                  :min="getInput?.min"
                  :name="selectedColumn.value"
                  :placeholder="getInput?.placeholder"
                  :value="rule.value"
                  klass="no-spin-arrow"
                  type="number"
                  @input="(v) => {rule.value = v}"
              >
                <template #icon>
                  <HashtagIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
              <base-range-slider
                  v-else-if="INPUTS.RANGE === getInput?.type"
                  v-model="rule.value"
                  :max="getInput?.max"
                  :min="getInput?.min"
                  show-tooltip="always"
                  tooltip-position="bottom"
              />
              <base-textarea
                  v-else-if="INPUTS.TEXTAREA === getInput?.type"
                  :name="selectedColumn.value"
                  :placeholder="getInput?.placeholder"
                  :value="rule.value"
                  @input="(v) => {rule.value = v}"
              >
                <template #icon>
                  <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                </template>
              </base-textarea>
              <color-picker
                  v-else-if="INPUTS.COLOR === getInput?.type"
                  v-model:pureColor="rule.value"
                  :disable-alpha="true"
                  format="hex6"
                  lang="En"
              />
              <base-switch
                  v-else-if="INPUTS.SWITCH === getInput?.type"
                  :enabled="getInput?.initialValue || true"
                  :label="getInput?.text"
                  :name="selectedColumn.value"
                  :sr-text="getInput?.text"
                  @change="(status) => {rule.value=status}"
              />
              <date-picker
                  v-else-if="INPUTS.DATETIME === getInput?.type || INPUTS.TIME === getInput?.type || INPUTS.DATE === getInput?.type"
                  v-model="rule.value"
                  :multiple="!!selectedOperator.multiple"
                  :placeholder="getInput?.placeholder"
                  :type="INPUTS.TIME === getInput?.type ? 'time' : (INPUTS.DATE === getInput?.type ? 'date' : 'datetime')"
              />
              <base-select-searchable
                  v-else-if="INPUTS.SELECT === getInput?.type || INPUTS.MULTISELECT === getInput?.type"
                  :current-page="inputSelectConfig.currentPage.value"
                  :multiple="INPUTS.MULTISELECT === getInput?.type"
                  :options="getInput?.options"
                  :options-key="getInput?.key"
                  :options-text="getInput?.textKey"
                  :placeholder="getInput?.placeholder"
                  :has-pagination="!!getInput?.remoteUrl"
                  :is-loading="searchInputLoading"
                  :is-local-search="!!getInput?.remoteUrl"
                  :last-page="inputSelectConfig.lastPage.value"
                  :selected="rule.tmpValue"
                  placeholder="متن جستجو را وارد نمایید"
                  @change="(selected) => {
                    rule.tmpValue=selected;
                    rule.value=getSelectedItemValue(selected);
                  }"
                  @query="searchInput"
                  @click-next-page="searchInputNextPage"
                  @click-prev-page="searchInputPrevPage"
              />
            </div>

            <template
                v-if="hasTwoValues.indexOf(selectedOperator.value) !== -1"
            >
              <span class="text-center block sm:inline-block mx-2">و</span>

              <div class="p-2 grow">
                <base-input
                    v-if="INPUTS.TEXT === getInput?.type"
                    :name="selectedColumn.value + '_2'"
                    :placeholder="getInput?.placeholder"
                    :value="rule.value2"
                    @input="(v) => {rule.value2 = v}"
                >
                  <template #icon>
                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                  </template>
                </base-input>
                <base-input
                    v-else-if="INPUTS.NUMBER === getInput?.type"
                    :max="getInput?.max"
                    :min="rule.value || getInput?.min ? parseInt(rule.value || getInput?.min) : null"
                    :name="selectedColumn.value + '_2'"
                    :placeholder="getInput?.placeholder"
                    :value="rule.value2"
                    klass="no-spin-arrow"
                    type="number"
                    @input="(v) => {rule.value2 = v}"
                >
                  <template #icon>
                    <HashtagIcon class="h-6 w-6 text-gray-400"/>
                  </template>
                </base-input>
                <base-range-slider
                    v-else-if="INPUTS.RANGE === getInput?.type"
                    v-model="rule.value2"
                    :max="getInput?.max"
                    :min="getInput?.min"
                    show-tooltip="always"
                    tooltip-position="bottom"
                />
                <base-textarea
                    v-else-if="INPUTS.TEXTAREA === getInput?.type"
                    :name="selectedColumn.value + '_2'"
                    :placeholder="getInput?.placeholder"
                    :value="rule.value2"
                    @input="(v) => {rule.value2 = v}"
                >
                  <template #icon>
                    <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                  </template>
                </base-textarea>
                <color-picker
                    v-else-if="INPUTS.COLOR === getInput?.type"
                    v-model:pureColor="rule.value2"
                    :disable-alpha="true"
                    format="hex6"
                    lang="En"
                />
                <base-switch
                    v-else-if="INPUTS.SWITCH === getInput?.type"
                    :enabled="getInput?.initialValue || true"
                    :label="getInput?.text"
                    :name="selectedColumn.value"
                    :sr-text="getInput?.text"
                    @change="(status) => {rule.value2=status}"
                />
                <date-picker
                    v-else-if="INPUTS.DATETIME === getInput?.type || INPUTS.TIME === getInput?.type || INPUTS.DATE === getInput?.type"
                    v-model="rule.value2"
                    :multiple="!!selectedOperator.multiple"
                    :placeholder="getInput?.placeholder"
                    :type="INPUTS.TIME === getInput?.type ? 'time' : (INPUTS.DATE === getInput?.type ? 'date' : 'datetime')"
                />
                <base-select-searchable
                    v-else-if="INPUTS.SELECT === getInput?.type || INPUTS.MULTISELECT === getInput?.type"
                    :current-page="inputSelectConfig.currentPage.value"
                    :multiple="INPUTS.MULTISELECT === getInput?.type"
                    :options="getInput?.options"
                    :options-key="getInput?.key"
                    :options-text="getInput?.textKey"
                    :placeholder="getInput?.placeholder"
                    :has-pagination="!!getInput?.remoteUrl"
                    :is-loading="searchInputLoading"
                    :is-local-search="!!getInput?.remoteUrl"
                    :last-page="inputSelectConfig.lastPage.value"
                    :selected="rule.tmpValue2"
                    placeholder="متن جستجو را وارد نمایید"
                    @change="(selected) => {
                      rule.tmpValue2=selected;
                      rule.value2=getSelectedItemValue(selected);
                    }"
                    @query="searchInput"
                    @click-next-page="searchInputNextPage"
                    @click-prev-page="searchInputPrevPage"
                />
              </div>
            </template>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import {computed, ref} from "vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import {
  hasMultipleValues,
  hasTwoValues,
  INPUTS,
  noNeededInputOperators,
  operatorsMap,
} from "@/composables/query-builder.js";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import {ArrowLeftCircleIcon, HashtagIcon, InformationCircleIcon,} from "@heroicons/vue/24/outline/index.js";
import BaseRangeSlider from "@/components/base/BaseRangeSlider.vue";
import {useRequest} from "@/composables/api-request.js";
import {useSelectSearching} from "@/composables/select-searching.js";

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  buttonsText: {
    type: Object,
    required: true,
  },
  operatorsText: {
    type: Object,
    required: true,
  },
  columns: {
    type: Array,
    required: true,
  },
  operators: {
    type: Object,
    required: true,
  },
})
const emit = defineEmits(['update:modelValue', 'remove'])

const rule = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  },
})
const selectedColumn = ref(null)
const selectedOperator = ref(null)
const selectedType = computed(() => {
  return selectedColumn.value?.type
})

const operatorsRef = ref(null)

const getOperators = computed(() => {
  let ops = []
  for (let i in props.operatorsText) {
    if (props.operatorsText.hasOwnProperty(i)) {
      if (operatorsMap[i] && selectedType.value && operatorsMap[i].applyTo.indexOf(selectedType.value) !== -1) {
        ops.push({
          value: i,
          name: props.operatorsText[i],
          multiple: operatorsMap[i].multiple,
          statement: operatorsMap[i].statement,
          replacement: operatorsMap[i]?.replacement,
        })

        if (
            [INPUTS.DATETIME, INPUTS.TIME, INPUTS.DATE, INPUTS.MULTISELECT].indexOf(getInput.value.type) === -1 &&
            hasMultipleValues.indexOf(i) !== -1
        ) {
          ops.splice(ops.length - 1, 1)
        }
      }
    }
  }
  return ops
})
const getInput = computed(() => {
  if (!selectedColumn.value) return null

  if (!selectedColumn.value.input) {
    selectedColumn.value.input = {}

    if (!selectedColumn.value.input.type)
      selectedColumn.value.input.type = INPUTS.TEXT

    if (!selectedColumn.value.input.value)
      selectedColumn.value.input.value = null
    if (!selectedColumn.value.input.value2)
      selectedColumn.value.input.value2 = null
  }

  // set initial values
  if (selectedColumn.value.input.initialValue)
    rule.value.value = selectedColumn.value.input.initialValue
  if (selectedColumn.value.input.initialValue2)
    rule.value.value2 = selectedColumn.value.input.initialValue2

  return selectedColumn.value.input
})

//---------------------------------------------------------------
// Input select remote search operation
//---------------------------------------------------------------
const inputSelectConfig = useSelectSearching({
  searchFn(query) {
    let inp = getInput.value

    if (!inp || !inp.remoteUrl) {
      inputSelectConfig.isLoading.value = false
      return
    }

    useRequest(inp.remoteUrl, {
      params: {
        limit: inputSelectConfig.limit.value,
        offset: inputSelectConfig.offset(),
        text: query,
      },
    }, {
      success: (response) => {
        inp.options = response.data
        if (response.meta) {
          inputSelectConfig.lastPage.value = response.meta?.last_page
        }
      },
      finally: () => {
        inputSelectConfig.isLoading.value = false
      }
    })
  },
})
const searchInput = inputSelectConfig.search
const searchInputLoading = inputSelectConfig.isLoading
const searchInputNextPage = inputSelectConfig.searchNextPage
const searchInputPrevPage = inputSelectConfig.searchPrevPage

//---------------------------------------------------------------
function getSelectedItemValue(selected) {
  let selectedItems = null

  if (INPUTS.MULTISELECT === getInput.value?.type) {
    if (selected && selected?.length) {
      selectedItems = []
      selected.forEach(item => {
        if (item[getInput.value?.key]) {
          selectedItems.push(item[getInput.value?.key])
        }
      })
    }
  } else {
    selectedItems = selected[getInput.value?.key] || null
  }

  return selectedItems
}

function handleColumnChange(selected) {
  selectedColumn.value = selected
  rule.value.column = selected

  rule.value.operator = null
  if (operatorsRef.value?.removeSelectedItems) {
    operatorsRef.value.removeSelectedItems()
  }

  delete rule.value.value
  delete rule.value.value2
}

function handleOperatorChange(selected) {
  selectedOperator.value = selected
  rule.value.operator = selected
}

function changeCondition(condition) {
  rule.value.condition = condition
}
</script>
