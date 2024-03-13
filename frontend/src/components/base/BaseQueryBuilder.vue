<template>
  <div class="border-2 border-slate-300 rounded-lg bg-white p-3">
    <partial-q-b-buttons
        :buttons-text="{...labels.buttons, ...labels.conditions}"
        :show-and-or-button="false"
        :show-remove-button="false"
        @add="addRuleHandler"
        @group="addGroupHandler"
    />

    <div v-for="(q, idx) in query" :key="idx">
      <partial-q-b-group
          v-if="q.children"
          :columns="columns"
          :depth="2"
          :dragging="dragging"
          :labels="labels"
          :max-depth="maxDepth"
          :query="q.children"
          @remove-group="removeHandler(idx)"
          @change-condition="changeConditionHandler(idx, $event)"
      />
      <partial-q-b-rule
          v-else
          v-model="q.rule"
          :buttons-text="{...labels.buttons, ...labels.conditions}"
          :columns="columns"
          :operators="labels.operators"
          :operators-text="{...labels.operators}"
          @remove="removeHandler(idx)"
      />
    </div>
  </div>
</template>

<script setup>
import PartialQBButtons from "@/components/partials/querybuilder/PartialQBButtons.vue";
import PartialQBRule from "@/components/partials/querybuilder/PartialQBRule.vue";
import PartialQBGroup from "@/components/partials/querybuilder/PartialQBGroup.vue";

const props = defineProps({
  query: {
    type: Array,
    default: () => [
      {
        rule: {
          column: null,
          operator: null,
          condition: 'and',
        },
      },
    ],
    required: true,
  },
  columns: {
    type: Array,
    required: true,
  },
  labels: {
    type: Object,
    default: () => {
      return {
        conditions: {
          and: 'و',
          or: 'یا',
        },
        operators: {
          equal: 'برابر با',
          notEqual: 'مخالف',
          in: 'شامل مجموعه شود',
          notIn: 'شامل مجموعه نشود',
          less: 'کوچکتر از',
          lessOrEqual: 'کوچکتر یا مساوی با',
          greater: 'بزرگتر از',
          greaterOrEqual: 'بزرگتر یا مساوی با',
          between: 'بین',
          notBetween: 'بین نباشد',
          beginsWith: 'شروع شود با',
          notBeginsWith: 'شروع نشود با',
          contains: 'شامل شود',
          notContains: 'شامل نشود',
          endsWith: 'خاتمه یابد با',
          notEndsWith: 'خاتمه نیابد با',
          isEmpty: 'خالی باشد',
          isNotEmpty: 'خالی نباشد',
          isNull: 'پوچ (null) باشد',
          isNotNull: 'پوچ (null) نباشد',
        },
        buttons: {
          addRule: 'افزودن قاعده',
          removeRule: 'حذف قاعده',
          addGroup: 'افزودن گروه',
          removeGroup: 'حذف گروه',
        },
      }
    },
  },
  dragging: {
    type: Boolean,
    default: true,
  },
  maxDepth: {
    type: Number,
    default: 3,
  },
})

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

function removeHandler(idx) {
  if (props.query[idx])
    props.query.splice(idx, 1)
}

function changeConditionHandler(idx, cond) {
  if (props.query[idx])
    props.query[idx].condition = cond
}
</script>
