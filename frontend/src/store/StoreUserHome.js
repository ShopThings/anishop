import {computed, reactive} from "vue";
import {defineStore} from "pinia";

export const useLoginOTPStore = defineStore('useLoginOTP', () => {
  // Made each steps to be objects. It makes any data we want to store in it
  const steps = reactive({
    mobile: {},
    code: {},
  })

  const getMobileStep = computed(() => {
    return steps.mobile
  })

  function setMobileStep(data) {
    steps.mobile = {
      mobile: data.mobile,
    }
  }

  const getCodeStep = computed(() => {
    return steps.code
  })

  const canGoToStepCode = computed(() => {
    return !!getMobileStep.value?.mobile
  })

  function setCodeStep(data) {
    steps.code = {
      code: data.code,
    }
  }

  function resetCodeStep() {
    steps.code = {}
  }

  function $reset() {
    steps.mobile = {}
    steps.code = {}
  }

  return {
    steps,
    //
    getMobileStep, setMobileStep,
    getCodeStep, canGoToStepCode, setCodeStep, resetCodeStep,
    //
    $reset,
  }
})

export const useSignupStore = defineStore('userSignup', () => {
  // Made each steps to be objects. It makes any data we want to store in it
  const steps = reactive({
    mobile: {},
    code: {},
    password: {},
  })

  const getMobileStep = computed(() => {
    return steps.mobile
  })

  function setMobileStep(data) {
    steps.mobile = {
      mobile: data.mobile,
    }
  }

  const getCodeStep = computed(() => {
    return steps.code
  })

  const canGoToStepCode = computed(() => {
    return !!getMobileStep.value?.mobile
  })

  function setCodeStep(data) {
    steps.code = {
      code: data.code,
    }
  }

  function resetCodeStep() {
    steps.code = {}
  }

  const getPasswordStep = computed(() => {
    return steps.password
  })

  const canGoToStepPassword = computed(() => {
    return canGoToStepCode.value && !!getCodeStep.value?.code
  })

  function $reset() {
    steps.mobile = {}
    steps.code = {}
    steps.password = {}
  }

  return {
    steps,
    //
    getMobileStep, setMobileStep,
    getCodeStep, canGoToStepCode, setCodeStep, resetCodeStep,
    getPasswordStep, canGoToStepPassword,
    //
    $reset,
  }
})

export const useRecoverPasswordStore = defineStore('userRecoverPassword', () => {
  // Made each steps to be objects. It makes any data we want to store in it
  const steps = reactive({
    mobile: {},
    code: {},
    password: {},
  })

  const getMobileStep = computed(() => {
    return steps.mobile
  })

  function setMobileStep(data) {
    steps.mobile = {
      mobile: data.mobile,
    }
  }

  const getCodeStep = computed(() => {
    return steps.code
  })

  const canGoToStepCode = computed(() => {
    return !!getMobileStep.value?.mobile
  })

  function setCodeStep(data) {
    steps.code = {
      code: data.code,
    }
  }

  function resetCodeStep() {
    steps.code = {}
  }

  const getPasswordStep = computed(() => {
    return steps.password
  })

  const canGoToStepPassword = computed(() => {
    return canGoToStepCode.value && !!getCodeStep.value?.code
  })

  function $reset() {
    steps.mobile = {}
    steps.code = {}
    steps.password = {}
  }

  return {
    steps,
    //
    getMobileStep, setMobileStep,
    getCodeStep, canGoToStepCode, setCodeStep, resetCodeStep,
    getPasswordStep, canGoToStepPassword,
    //
    $reset,
  }
})
