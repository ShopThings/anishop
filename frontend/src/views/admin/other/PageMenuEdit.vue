<template>
    <partial-card>
        <template #header>
            ویرایش منو
        </template>
        <template #body>
            <div class="p-3">
                <base-loading-panel
                    :loading="loading"
                    type="form"
                >
                    <template #content>
                        <form @submit.prevent="onSubmit">
                            <div class="flex flex-wrap items-end mb-3">
                                <div class="p-2 w-full sm:w-1/2 lg:w-4/12">
                                    <base-input
                                        label-title="نام منو"
                                        placeholder="وارد نمایید"
                                        name="title"
                                        :value="menu?.title"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="p-2 w-full sm:w-1/2 lg:w-6/12">
                                    <base-input
                                        label-title="لینک"
                                        placeholder="وارد نمایید"
                                        name="link"
                                        :value="menu?.link"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="p-2 w-full sm:w-1/2 lg:w-2/12">
                                    <base-input
                                        label-title="اولویت"
                                        placeholder="وارد نمایید"
                                        type="number"
                                        :min="0"
                                        name="priority"
                                        :value="menu?.priority"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="p-2 w-full sm:w-auto">
                                    <base-switch
                                        label="نمایش منو"
                                        name="is_published"
                                        :enabled="menu?.is_published"
                                        sr-text="نمایش/عدم نمایش منو"
                                        @change="(status) => {menu.is_published=status}"
                                    />
                                </div>
                            </div>

                            <div class="p-2">
                                <nested-menus v-if="menu && menu.menus" :menus="menu.menus"/>

                                <div
                                    v-if="menu?.can_have_children"
                                    class="mt-3 mb-1"
                                >
                                    <base-button
                                        class="!text-orange-600 border-orange-400 w-full sm:w-auto flex items-center hover:bg-orange-50 mr-auto"
                                        @click="handleNewMenuClick"
                                    >
                                        <span class="mr-auto text-sm">ساخت زیر منو</span>
                                        <PlusIcon class="h-6 w-6 mr-auto sm:mr-2"/>
                                    </base-button>
                                </div>
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

                                    <span class="ml-auto">ویرایش منو</span>
                                </base-animated-button>
                            </div>
                        </form>
                    </template>
                </base-loading-panel>
            </div>
        </template>
    </partial-card>

    <pre dir="ltr">{{ menu }}</pre>
</template>

<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {ArrowLeftCircleIcon, CheckIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import NestedMenus from "./infra/NestedMenus.vue";
import BaseButton from "../../../components/base/BaseButton.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import uniqueId from "lodash.uniqueid";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const loading = ref(false)
const canSubmit = ref(true)

const menu = reactive(null)

function handleNewMenuClick() {
    if (!menu.menus) menu.menus = []

    menu.menus.push({
        id: parseInt(uniqueId()),
        parent_id: null,
        title: '',
        link: '',
        priority: menu.menus.length + 1 + '',
        can_have_children: true,
        is_published: true,
        menus: [],
    })
}

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

onMounted(() => {
    // useRequest(apiReplaceParams(apiRoutes.admin.menus.show, {menu: idParam.value}), null, {
    //     success: (response) => {
    //         menu.value = response.data
    //
    //         loading.value = false
    //     },
    // })
})
</script>

<style scoped>

</style>
