import {ref} from "vue";
import {useForm} from "vee-validate";
import isFunction from "lodash.isfunction";

export const useFormSubmit = (formOptions, submitAction) => {

  const canSubmit = ref(true)

  const {
    handleSubmit,
    errors,
    isSubmitting,
    values,
    validate,
    setFieldError,
    setErrors,
  } = useForm(formOptions)

  const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return

    if (isFunction(submitAction)) {
      submitAction(values, actions)
    }
  })

  return {
    canSubmit,
    onSubmit,
    errors,
    isSubmitting,
    values,
    validate,
    setFieldError,
    setErrors,
  }
}
