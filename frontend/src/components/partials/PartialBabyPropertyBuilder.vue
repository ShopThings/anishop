<template>
    <TransitionGroup name="fade-group">
        <div
            v-for="(property, idx) in internalProperties"
            :key="idx"
            class="p-2 border-2 border-dashed rounded-lg border-indigo-200 mb-3 relative"
        >
            <partial-builder-remove-btn
                v-if="internalProperties.length > 1"
                @click="handleRemoveProperty(idx)"
            />

            <div class="flex flex-wrap grow">
                <div class="p-2 w-full sm:w-1/3 xl:w-1/3">
                    <base-input
                        :label-title="propertyTitleText"
                        placeholder="وارد نمایید"
                        :value="property?.title"
                        :name="'baby_property_title[' + idx + ']'"
                        @input="(v) => {property.title = v}"
                    />
                </div>
                <div class="p-2 w-full sm:w-2/3 xl:w-2/3">
                    <partial-input-label :title="tagsText"/>
                    <vue3-tags-input
                        :tags="property?.tags"
                        placeholder="وارد نمایید"
                        @on-tags-changed="(t) => {property.tags = t}"
                    />
                </div>
            </div>
        </div>
    </TransitionGroup>

    <div class="mt-3 mb-1">
        <base-button
            class="!text-orange-600 border-orange-400 w-full sm:w-auto flex items-center hover:bg-orange-50 mr-auto"
            @click="handlePropertyClick"
        >
            <span class="mr-auto text-sm">{{ newButtonText }}</span>
            <PlusIcon class="h-6 w-6 mr-auto sm:mr-2"/>
        </base-button>
    </div>
</template>

<script setup>
import {computed} from "vue";
import {PlusIcon} from "@heroicons/vue/24/outline/index.js";
import BaseButton from "../base/BaseButton.vue";
import BaseInput from "../base/BaseInput.vue";
import Vue3TagsInput from "vue3-tags-input";
import PartialInputLabel from "./PartialInputLabel.vue";
import PartialBuilderRemoveBtn from "./PartialBuilderRemoveBtn.vue";


const props = defineProps({
    properties: Array,
    propertyTitleText: {
        type: String,
        required: true,
    },
    tagsText: {
        type: String,
        required: true,
    },
    newButtonText: {
        type: String,
        required: true,
    },
})
const emit = defineEmits(['update:properties', 'remove', 'add'])

const internalProperties = computed({
    get() {
        return props.properties
    },
    set(value) {
        emit('update:properties', value)
    },
})

function handlePropertyClick() {
    internalProperties.value.push({
        title: '',
        tags: [],
    })

    emit('add', internalProperties.value)
}

function handleRemoveProperty(idx) {
    internalProperties.value.splice(idx, 1)

    emit('remove', internalProperties.value)
}
</script>

<style scoped>
.fade-group-enter-active,
.fade-group-leave-active {
    transition: opacity 0.3s ease;
    transition-delay: .05s;
}

.fade-group-enter-from,
.fade-group-leave-to {
    opacity: 0;
}
</style>
