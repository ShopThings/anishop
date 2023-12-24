<template>
  <div class="border-2 border-slate-300 border-dashed bg-white rounded-lg p-2 my-2">
    <div class="border border-blue-300 rounded bg-blue-100 pt-5 p-1 relative">
      <div class="!absolute top-0 left-0 -translate-y-1/4 -translate-x-2 z-[1] flex items-center">
        <div class="flex ml-2">
          <base-button
              class="!rounded-r-md !rounded-l-none bg-indigo-500 text-sm !py-0"
              :class="rule.condition === 'and' ? 'bg-indigo-700' : ''"
              @click="changeCondition('and')"
          >
            {{ buttonsText.and }}
          </base-button>
          <base-button
              class="!rounded-l-md !rounded-r-none bg-indigo-500 text-sm !py-0"
              :class="rule.condition === 'or' ? 'bg-indigo-700' : ''"
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
              options-text="name"
              options-key="value"
              :options="columns"
              @change="handleColumnChange"
          />
        </div>
        <div
            v-if="selectedType"
            class="p-2 w-full sm:w-1/3 lg:w-4/12"
        >
          <base-select-searchable
              options-text="name"
              options-key="value"
              :options="getOperators"
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
                  :name="selectedColumn.value"
                  type="number"
                  :min="getInput?.min"
                  :max="hasTwoValues.indexOf(selectedOperator.value) !== -1 ? (rule.value2 || getInput?.max ? parseInt(rule.value2 || getInput?.max) : null) : (getInput?.max ? parseInt(getInput?.max) : null)"
                  :placeholder="getInput?.placeholder"
                  :value="rule.value"
                  @input="(v) => {rule.value = v}"
              >
                <template #icon>
                  <HashtagIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
              <base-range-slider
                  v-else-if="INPUTS.RANGE === getInput?.type"
                  :min="getInput?.min"
                  :max="getInput?.max"
                  show-tooltip="always"
                  tooltip-position="bottom"
                  v-model="rule.value"
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
                  :disable-alpha="true"
                  format="hex6"
                  lang="En"
                  v-model:pureColor="rule.value"
              />
              <base-switch
                  v-else-if="INPUTS.SWITCH === getInput?.type"
                  :label="getInput?.text"
                  :name="selectedColumn.value"
                  :enabled="getInput?.initialValue || true"
                  :sr-text="getInput?.text"
                  @change="(status) => {rule.value=status}"
              />
              <date-picker
                  v-else-if="INPUTS.DATETIME === getInput?.type || INPUTS.TIME === getInput?.type || INPUTS.DATE === getInput?.type"
                  :placeholder="getInput?.placeholder"
                  v-model="rule.value"
                  :multiple="!!selectedOperator.multiple"
                  :type="INPUTS.TIME === getInput?.type ? 'time' : (INPUTS.DATE === getInput?.type ? 'date' : 'datetime')"
              />
              <base-select-searchable
                  v-else-if="INPUTS.SELECT === getInput?.type || INPUTS.MULTISELECT === getInput?.type"
                  :placeholder="getInput?.placeholder"
                  :options-text="getInput?.textKey"
                  :options-key="getInput?.key"
                  :multiple="INPUTS.MULTISELECT === getInput?.type"
                  :options="getInput?.options"
                  :selected="rule.value"
                  @change="(selected) => {rule.value=selected}"
                  :is-loading="!!getInput?.loading"
                  :is-local-search="!!getInput?.remoteUrl"
                  @query="searchIn"
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
                    :name="selectedColumn.value + '_2'"
                    type="number"
                    :min="rule.value || getInput?.min ? parseInt(rule.value || getInput?.min) : null"
                    :max="getInput?.max"
                    :placeholder="getInput?.placeholder"
                    :value="rule.value2"
                    @input="(v) => {rule.value2 = v}"
                >
                  <template #icon>
                    <HashtagIcon class="h-6 w-6 text-gray-400"/>
                  </template>
                </base-input>
                <base-range-slider
                    v-else-if="INPUTS.RANGE === getInput?.type"
                    :min="getInput?.min"
                    :max="getInput?.max"
                    show-tooltip="always"
                    tooltip-position="bottom"
                    v-model="rule.value2"
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
                    :disable-alpha="true"
                    format="hex6"
                    lang="En"
                    v-model:pureColor="rule.value2"
                />
                <base-switch
                    v-else-if="INPUTS.SWITCH === getInput?.type"
                    :label="getInput?.text"
                    :name="selectedColumn.value"
                    :enabled="getInput?.initialValue || true"
                    :sr-text="getInput?.text"
                    @change="(status) => {rule.value2=status}"
                />
                <date-picker
                    v-else-if="INPUTS.DATETIME === getInput?.type || INPUTS.TIME === getInput?.type || INPUTS.DATE === getInput?.type"
                    :placeholder="getInput?.placeholder"
                    :multiple="!!selectedOperator.multiple"
                    v-model="rule.value2"
                    :type="INPUTS.TIME === getInput?.type ? 'time' : (INPUTS.DATE === getInput?.type ? 'date' : 'datetime')"
                />
                <base-select-searchable
                    v-else-if="INPUTS.SELECT === getInput?.type || INPUTS.MULTISELECT === getInput?.type"
                    :placeholder="getInput?.placeholder"
                    :options-text="getInput?.textKey"
                    :options-key="getInput?.key"
                    :multiple="INPUTS.MULTISELECT === getInput?.type"
                    :options="getInput?.options"
                    :selected="rule.value2"
                    @change="(selected) => {rule.value2=selected}"
                    :is-loading="!!getInput?.loading"
                    :is-local-search="!!getInput?.remoteUrl"
                    @query="searchIn"
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
import BaseButton from "../../base/BaseButton.vue";
import BaseSelectSearchable from "../../base/BaseSelectSearchable.vue";
import {
  INPUTS,
  hasTwoValues,
  hasMultipleValues,
  noNeededInputOperators,
  operatorsMap,
} from "../../../composables/query-builder.js";
import BaseInput from "../../base/BaseInput.vue";
import BaseTextarea from "../../base/BaseTextarea.vue";
import BaseSwitch from "../../base/BaseSwitch.vue";
import {
  InformationCircleIcon,
  ArrowLeftCircleIcon,
  HashtagIcon,
} from "@heroicons/vue/24/outline/index.js";
import BaseRangeSlider from "../../base/BaseRangeSlider.vue";
import {useRequest} from "../../../composables/api-request.js";

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

function searchIn(query) {
  let inp = getInput.value

  if (!inp || !inp.remoteUrl) return

  inp.loading = true
  useRequest(inp.remoteUrl, {
    data: {
      query,
    },
  }, {
    success: (response) => {
      inp.options = response.data
    },
    finally: () => {
      inp.loading = false
    }
  })
}

function handleColumnChange(selected) {
  selectedColumn.value = selected
  rule.value.column = selected
}

function handleOperatorChange(selected) {
  selectedOperator.value = selected
  rule.value.operator = selected
}

function changeCondition(condition) {
  rule.value.condition = condition
}
</script>

<style scoped>

</style>
