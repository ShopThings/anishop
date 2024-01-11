<template>
  <div class="border-2 border-slate-300 rounded-lg bg-white p-2 my-2">
    <partial-q-b-buttons
      :buttons-text="{...labels.buttons, ...labels.conditions}"
      @add="addRuleHandler"
      @group="addGroupHandler"
      @remove="emit('remove-group')"
      @change-condition="emitChangeCondition"
      :show-group-button="depth < maxDepth"
    />

    <div v-for="(q, idx) in query" :key="idx">
      <partial-q-b-group
        v-if="q.children"
        :query="q.children"
        :columns="columns"
        :labels="labels"
        :dragging="dragging"
        :depth="depth + 1"
        :max-depth="maxDepth"
        @remove-group="removeHandler(idx)"
        @change-condition="changeConditionHandler(idx, $event)"
      />
      <partial-q-b-rule
        v-else
        v-model="q.rule"
        :operators="labels.operators"
        :columns="columns"
        :buttons-text="{...labels.buttons, ...labels.conditions}"
        :operators-text="{...labels.operators}"
        @remove="removeHandler(idx)"
      />
    </div>
  </div>
</template>

<script setup>
import PartialQBButtons from "./PartialQBButtons.vue";
import PartialQBRule from "./PartialQBRule.vue";

const props = defineProps({
  query: {
    type: Array,
    required: true,
  },
  columns: {
    type: Array,
    required: true,
  },
  labels: {
    type: Object,
    required: true,
  },
  dragging: {
    type: Boolean,
    required: true,
  },
  depth: {
    type: Number,
    required: true,
  },
  maxDepth: {
    type: Number,
    required: true,
  },
})
const emit = defineEmits(['remove-group', 'change-condition'])

if (!props.query.length) {
  props.query.push({
    rule: {
      column: null,
      operator: null,
      condition: 'and',
    },
  })
}

function addRuleHandler(condition) {
  props.query.push({
    rule: {
      column: null,
      operator: null,
      condition,
    },
  })
}

function addGroupHandler(condition) {
  if (props.depth < props.maxDepth) {
    props.query.push({
      condition: 'and',
      children: [
        {
          rule: {
            column: null,
            operator: null,
            condition,
          },
        },
      ],
    })
  }
}

function removeHandler(idx) {
  if (props.query[idx])
    props.query.splice(idx, 1)
}

function emitChangeCondition(cond) {
  emit('change-condition', cond)
}

function changeConditionHandler(idx, cond) {
  if (props.query[idx])
    props.query[idx].condition = cond
}
</script>
