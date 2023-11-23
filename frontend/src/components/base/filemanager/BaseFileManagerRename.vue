<template>
    <partial-dialog
        v-model:open="isOpen"
        @close="$emit('close')"
    >
        <template #title>
            <div class="flex items-center">
                <PencilSquareIcon class="h-6 w-6 ml-2"/>
                تغییر نام
            </div>
        </template>

        <template #body>
            <form @submit.prevent="onSubmit">
                <base-input class="grow mb-3" name="newName"
                            :value="name"
                            placeholder="نام جدید را وارد کنید...">
                    <template #icon>
                        <PencilIcon class="w-6 h-6 text-gray-400"/>
                    </template>
                </base-input>
                <div class="text-left">
                    <base-button type="submit"
                                 class="bg-emerald-500 border-emerald-600 grow rounded-lg text-sm px-5">
                        تغییر نام
                    </base-button>
                </div>
            </form>
        </template>
    </partial-dialog>
</template>

<script setup>
import {ref} from "vue";
import {useForm} from "vee-validate";
import {PencilSquareIcon, PencilIcon} from "@heroicons/vue/24/outline/index.js";
import yup from "../../../validation/index.js";
import PartialDialog from "../../partials/PartialDialog.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";
import BaseInput from "../BaseInput.vue";
import BaseButton from "../BaseButton.vue";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    open: Boolean,
    path: {
        type: String,
        required: true,
    },
    disk: String,
})

const emit = defineEmits(['success'])

const isOpen = ref(props.open)

const {handleSubmit} = useForm({
    validationSchema: yup.object().shape({
        newName: yup.string().required('نام جدید فایل/پوشه را وارد نمایید.')
    })
})

const onSubmit = handleSubmit((values, actions) => {
    if(values.newName === props.name) {
        actions.setFieldError('newName', 'نام جدید با نام پیشین یکسان می‌باشد!')
        return
    }

    useRequest(apiRoutes.admin.files.rename, {
        method: 'POST',
        data: {
            old_name: props.name,
            new_name: values.newName,
            path: props.path,
            disk: props.disk,
        },
    }, {
        success: () => {
            emit('success', values.newName, props.name)

            actions.resetForm()
            isOpen.value = false
        },
    })
})
</script>

<style scoped>

</style>
