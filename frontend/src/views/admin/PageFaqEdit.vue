<template>
    <partial-card>
        <template #header>
            ویرایش سؤال
        </template>
        <template #body>
            <div class="p-3">
                <form @submit.prevent="onSubmit">
                    <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                        <base-switch
                            label="عدم نمایش سؤال"
                            on-label="نمایش سؤال"
                            name="is_published"
                            :enabled="faq?.is_published"
                            sr-text="نمایش/عدم نمایش سؤال"
                            @change="(status) => {publishStatus=status}"
                        />
                    </div>

                    <div class="p-2">
                        <base-input
                            label-title="سؤال"
                            placeholder="وارد نمایید"
                            name="question"
                            :value="faq?.question"
                        >
                            <template #icon>
                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                            </template>
                        </base-input>
                    </div>

                    <div class="p-2">
                        <partial-input-label title="کلمات کلیدی"/>
                        <vue3-tags-input
                            :tags="tags"
                            placeholder="کلمات کلیدی خود را وارد نمایید"
                            @on-tags-changed="(t) => {tags = t}"
                        />
                    </div>

                    <div class="p-2">
                        <partial-input-label title="پاسخ"/>
                        <base-editor
                            name="answer"
                            :value="faq?.description"
                        />
                    </div>

                    <div class="px-2 py-3">
                        <base-animated-button
                            type="submit"
                            class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                            :disabled="isSubmitting"
                        >
                            <VTransitionFade>
                                <loader-circle
                                    v-if="isSubmitting"
                                    main-container-klass="absolute w-full h-full"
                                    big-circle-color="border-transparent"
                                />
                            </VTransitionFade>

                            <template #icon="{klass}">
                                <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                            </template>

                            <span class="ml-auto">ویرایش سؤال</span>
                        </base-animated-button>
                    </div>
                </form>
            </div>
        </template>
    </partial-card>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import PartialCard from "../../components/partials/PartialCard.vue";
import LoaderCircle from "../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../components/base/BaseAnimatedButton.vue";
import BaseSwitch from "../../components/base/BaseSwitch.vue";
import BaseInput from "../../components/base/BaseInput.vue";
import Vue3TagsInput from "vue3-tags-input";
import PartialInputLabel from "../../components/partials/PartialInputLabel.vue";
import BaseEditor from "../../components/base/BaseEditor.vue";
import {useRequest} from "../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../router/api-routes.js";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const canSubmit = ref(true)

const faq = ref(null)

const publishStatus = ref(true)
const tags = ref([])

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

// onMounted(() => {
//     useRequest(apiReplaceParams(apiRoutes.admin.faqs.show, {faq: idParam}), null, {
//         success: (response) => {
//             faq.value = response.data
//             tags.value = response.data.keywords
//
//             loading.value = false
//         },
//     })
// })
</script>

<style scoped>

</style>
