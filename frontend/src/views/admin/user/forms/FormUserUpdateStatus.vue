<template>
    <form @submit.prevent="onSubmit">
        <base-switch
            v-if="userStore.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN])"
            class="mb-3"
            label="غیر قابل حذف نمودن کاربر توسط سایر اعضاء"
            name="is_deletable"
            :enabled="!user?.is_deletable"
            enabled-color="bg-pink-600"
            disabled-color="bg-pink-300"
            sr-text="غیر قابل حذف نمودن کاربر توسط سایر اعضاء"
            @change="(status) => {deletableStatus=status}"
        />

        <base-switch
            label="عدم اجازه فعالیت کاربر"
            on-label="اجازه فعالیت کاربر"
            name="is_banned"
            :enabled="!user?.is_banned"
            sr-text="اجازه یا جلوگیری از فعالیت کاربر"
            @change="(status) => {banStatus=status}"
        />

        <VTransitionSlideFadeDownY>
            <div
                v-if="!banStatus"
                class="mt-3"
            >
                <base-textarea
                    name="ban_desc"
                    placeholder="توضیحات خود را وارد کنید..."
                    :value="user?.ban_desc"
                    label-title="علت عدم اجازه فعالیت به کاربر"
                    :has-edit-mode="user?.ban_desc ? false : true"
                >
                    <template #icon>
                        <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                    </template>
                </base-textarea>
            </div>
        </VTransitionSlideFadeDownY>

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

                <span class="ml-auto">ویرایش وضعیت‌ها</span>
            </base-animated-button>
        </div>
    </form>
</template>

<script setup>
import LoaderCircle from "../../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../../transitions/VTransitionFade.vue";
import {CheckIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseSwitch from "../../../../components/base/BaseSwitch.vue";
import BaseAnimatedButton from "../../../../components/base/BaseAnimatedButton.vue";
import VTransitionSlideFadeDownY from "../../../../transitions/VTransitionSlideFadeDownY.vue";
import BaseTextarea from "../../../../components/base/BaseTextarea.vue";
import {computed, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../../validation/index.js";
import {useRequest} from "../../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../../router/api-routes.js";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";
import {useAdminStore, ROLES} from "../../../../store/StoreUserAuth.js";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
})
const emit = defineEmits(['update:user'])

const user = computed({
    get() {
        return props.user
    },
    set(value) {
        emit('update:user', value)
    },
})

const userStore = useAdminStore()

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    return route.params.id
})

const canSubmit = ref(true)
const banStatus = ref(!user.value?.is_banned ?? false)
const deletableStatus = ref(!user.value?.is_deletable ?? false)

const {handleSubmit, isSubmitting} = useForm({
    validationSchema: yup.object().shape({
        is_banned: yup.boolean(),
        ban_desc: yup.string().when('is_banned', {
            is: false,
            then: (schema) => {
                return schema.required('وارد نمودن توضیحات عدم اجازه فعالیت ضروری می‌باشد.')
            },
            otherwise: (schema) => schema.optional().nullable(),
        }),
        is_deletable: yup.boolean(),
    }),
    keepValuesOnUnmount: true,
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return

    canSubmit.value = false

    if (banStatus.value)
        delete values['ban_desc']

    if (!userStore.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN]))
        delete values['is_deletable']

    // is MUST inverse values for backend
    values.is_banned = !values.is_banned
    values.is_deletable = !values.is_deletable

    useRequest(apiReplaceParams(apiRoutes.admin.users.update, {
        user: idParam.value,
    }), {
        method: 'PUT',
        data: values,
    }, {
        success: (response) => {
            toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
            user.value = response.data
            return false
        },
        error: (error) => {
            if (error.errors && Object.keys(error.errors).length >= 1)
                actions.setErrors(error.errors)

            return false
        },
        finally: function () {
            canSubmit.value = true
        },
    })
})
</script>

<style scoped>

</style>
