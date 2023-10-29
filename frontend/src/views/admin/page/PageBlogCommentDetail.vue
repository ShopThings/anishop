<template>
    <partial-card>
        <template #header>
            جزئیات دیدگاه برای بلاگ -
            <span
                v-if="blog?.id"
                class="text-teal-600"
            >{{ blog?.title }}</span>
        </template>
        <template #body>
            <div class="p-3">
                <base-loading-panel
                    :loading="loading"
                    type="content"
                >
                    <template #content>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <li class="sm:flex sm:items-center">
                                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">ارسال شده توسط:</span>
                                <span class="block mt-1 sm:mt-0 sm:inline-block">{{ comment?.user ?? '-' }}</span>
                            </li>
                            <li class="sm:flex sm:items-center">
                                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">ارسال شده در تاریخ:</span>
                                <span class="block mt-1 sm:mt-0 sm:inline-block">{{ comment?.created_at ?? '-' }}</span>
                            </li>
                        </ul>

                        <div
                            v-if="comment?.prev_comment"
                            class="mt-3"
                        >
                            <partial-input-label title="دیدگاه پیشین"/>
                            <div class="rounded bg-gray-100 p-3 border leading-loose">
                                {{ comment?.prev_comment }}
                            </div>
                        </div>

                        <div class="mt-3">
                            <partial-input-label title="دیدگاه کاربر"/>
                            <div class="rounded bg-blue-100 p-3 border leading-loose">
                                {{ comment?.description ?? '-' }}
                            </div>
                        </div>

                        <form @submit.prevent="onSubmit">
                            <div class="p-2">
                                <base-textarea
                                    label-title="پاسخ خود را وارد نمایید"
                                    name="answer"
                                >
                                    <template #icon>
                                        <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                                    </template>
                                </base-textarea>
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
                                        <ArrowUturnRightIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                                    </template>

                                    <span class="ml-auto">ثبت پاسخ</span>
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
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRequest} from "../../../composables/api-request.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import {useRoute, useRouter} from "vue-router";
import BaseTextarea from "../../../components/base/BaseTextarea.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {ArrowUturnRightIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";

const router = useRouter()
const route = useRoute()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})
const commentId = computed(() => {
    const detail = parseInt(route.params.detail, 10)
    if (isNaN(detail)) return route.params.detail
    return detail
})

const loading = ref(false)
const canSubmit = ref(true)

const comment = ref(null)
const blog = ref(null)

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

// onMounted(() => {
//     useRequest(
//     apiReplaceParams(apiRoutes.admin.blogComments.show, {
//     blog: idParam.value,
//     comment: commentId.value
//     blog: idParam.value
//     }
//     ), null, {
//         success: (response) => {
//             comment.value = response.data
//
//             loading.value = false
//         }
//     })
//
//     useRequest(apiReplaceParams(apiRoutes.admin.blogs.show, {blog: idParam.value}), null, {
//         success: (response) => {
//             blog.value = response.data
//         }
//     })
// })
</script>

<style scoped>

</style>
