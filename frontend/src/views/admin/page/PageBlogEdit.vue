<template>
    <partial-card>
        <template #header>
            ویرایش بلاگ
        </template>
        <template #body>
            <div class="p-3">
                <base-loading-panel
                    :loading="loading"
                    type="form"
                >
                    <template #content>
                        <form @submit.prevent="onSubmit">
                            <div class="flex flex-wrap items-end justify-between">
                                <div class="p-2">
                                    <partial-input-label
                                        title="انتخاب تصویر"
                                    />
                                    <base-media-placeholder type="image"/>
                                </div>

                                <div class="p-2">
                                    <base-switch
                                        label="عدم نمایش بلاگ"
                                        on-label="نمایش بلاگ"
                                        name="is_published"
                                        :enabled="blog?.is_published"
                                        sr-text="نمایش/عدم نمایش بلاگ"
                                        @change="(status) => {publishStatus=status}"
                                    />
                                </div>
                            </div>

                            <div class="flex flex-wrap">
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input
                                        label-title="عنوان بلاگ"
                                        placeholder="عنوان را وارد نمایید"
                                        name="title"
                                        :value="blog?.title"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <partial-input-label title="دسته‌بندی"/>
                                    <base-select
                                        :options="categories"
                                        options-key="value"
                                        options-text="name"
                                        name="type"
                                        @change="categoryChange"
                                    >
                                    </base-select>
                                    <partial-input-error-message :error-message="errors.type"/>
                                </div>
                            </div>

                            <div class="p-2">
                                <base-switch
                                    label="اجازه ارسال دیدگاه"
                                    name="is_commenting_allowed"
                                    :enabled="blog?.is_commenting_allowed"
                                    sr-text="اجازه/عدم اجازه ارسال دیدگاه"
                                    @change="(status) => {commentAllowing=status}"
                                />
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
                                <partial-input-label title="نوشته خود را وارد کنید"/>
                                <base-editor
                                    name="description"
                                    :value="blog?.description"
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
                                            main-container-klass="absolute w-full h-full top-0 left-0"
                                            big-circle-color="border-transparent"
                                        />
                                    </VTransitionFade>

                                    <template #icon="{klass}">
                                        <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                                    </template>

                                    <span class="ml-auto">ویرایش بلاگ</span>
                                </base-animated-button>
                            </div>
                        </form>
                    </template>
                </base-loading-panel>
            </div>
        </template>
    </partial-card>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import BaseMediaPlaceholder from "../../../components/base/BaseMediaPlaceholder.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import PartialInputErrorMessage from "../../../components/partials/PartialInputErrorMessage.vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseLazyImage from "../../../components/base/BaseLazyImage.vue";
import VTransitionSlideFadeDownY from "../../../transitions/VTransitionSlideFadeDownY.vue";
import BaseTextarea from "../../../components/base/BaseTextarea.vue";
import BaseSelect from "../../../components/base/BaseSelect.vue";
import {ArrowLeftCircleIcon, CheckIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import Vue3TagsInput from "vue3-tags-input";
import BaseEditor from "../../../components/base/BaseEditor.vue";
import {useToast} from "vue-toastification";
import {useRoute} from "vue-router";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const loading = ref(false)
const canSubmit = ref(true)

const blog = ref(null)
const categories = ref([])

const tags = ref([])
const publishStatus = ref(true)
const commentAllowing = ref(true)
const selectedCategory = ref(null)

function categoryChange(selected) {
    selectedCategory.value = selected
}

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

// onMounted(() => {
//     useRequest(apiReplaceParams(apiRoutes.admin.blogs.show, {blog: idParam.value}), null, {
//         success: (response) => {
//             blog.value = response.data
//             selectedCategory.value = response.data.category
//             tags.value = response.data.keywords
//
//             loading.value = false
//         },
//     })
//
//     useRequest(apiRoutes.admin.blogCategories.index, null, {
//         success: (response) => {
//             categories.value = response.data
//         },
//     })
// })
</script>

<style scoped>

</style>
