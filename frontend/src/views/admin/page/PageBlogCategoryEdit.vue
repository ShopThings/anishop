<template>
    <partial-card>
        <template #header>
            ویرایش دسته‌بندی بلاگ
        </template>
        <template #body>
            <div class="p-3">
                <base-loading-panel
                    :loading="loading"
                    type="form"
                >
                    <template #content>
                        <form @submit.prevent="onSubmit">
                            <div class="p-2">
                                <base-switch
                                    label="عدم نمایش بلاگ"
                                    on-label="نمایش بلاگ"
                                    name="is_published"
                                    :enabled="blogCategory?.is_published"
                                    sr-text="نمایش/عدم نمایش بلاگ"
                                    @change="(status) => {publishStatus=status}"
                                />
                            </div>

                            <div class="flex flex-wrap">
                                <div class="w-full p-2 sm:w-1/2">
                                    <base-input
                                        label-title="نام دسته‌بندی"
                                        placeholder="وارد نمایید"
                                        name="title"
                                        :value="blogCategory?.title"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2">
                                    <base-input
                                        type="number"
                                        label-title="اولویت"
                                        placeholder="وارد نمایید"
                                        name="priority"
                                        :value="blogCategory?.priority"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                            </div>

                            <div class="flex flex-wrap">
                                <div class="w-full p-2 sm:w-1/2">
                                    <base-switch
                                        label="نمایش در منوی اصلی"
                                        name="show_in_menu"
                                        :enabled="blogCategory?.show_in_menu"
                                        sr-text="نمایش/عدم نمایش در منوی اصلی"
                                        @change="(status) => {showInMenuStatus=status}"
                                    />
                                </div>
                                <div class="w-full p-2 sm:w-1/2">
                                    <base-switch
                                        label="نمایش در منوی کناری"
                                        name="show_in_side_menu"
                                        :enabled="blogCategory?.show_in_side_menu"
                                        sr-text="نمایش/عدم نمایش در منوی کناری"
                                        @change="(status) => {showInSideMenuStatus=status}"
                                    />
                                </div>
                            </div>

                            <div class="p-2">
                                <partial-input-label title="کلمات کلیدی"/>
                                <vue3-tags-input
                                    :tags="tags"
                                    placeholder="کلمات کلیدی خود را وارد نمایید"
                                    @on-tags-changed="(t) => {tags = t}"
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

                                    <span class="ml-auto">ایجاد دسته‌بندی بلاگ</span>
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
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import Vue3TagsInput from "vue3-tags-input";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import {useToast} from "vue-toastification";
import {useRoute} from "vue-router";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRequest} from "../../../composables/api-request.js";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const loading = ref(false)
const canSubmit = ref(true)

const blogCategory = ref(null)

const tags = ref([])
const publishStatus = ref(true)
const showInMenuStatus = ref(true)
const showInSideMenuStatus = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

// onMounted(() => {
//     useRequest(apiReplaceParams(apiRoutes.admin.blogCategories.show, {blog_category: idParam}), null, {
//         success: (response) => {
//             blogCategory.value = response.data
//             tags.value = response.data.keywords
//
//             loading.value = false
//         },
//     })
// })
</script>

<style scoped>

</style>
