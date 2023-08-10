import * as yup from 'yup';

function persian(message) {
    return this.test("persian", message, function (value) {
        const {path, createError} = this

        if (!value.match(/^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u)) {
            return createError({
                path,
                message: message ?? 'The name may contain only persian letters'
            })
        }

        return true
    });
}

function persianMobile(message) {
    return this.test("persianMobile", message, function (value) {
        const {path, createError} = this

        if (!value.match(/^(098|\+98|0)?9\d{9}$/)) {
            return createError({
                path,
                message: message ?? 'The mobile is not correct'
            })
        }

        return true
    });
}

function percentage(message) {
    const msg = message ?? 'Percentage should be between 0 and 100'
    return this
        .min(0, msg)
        .max(100, msg)
}

yup.addMethod(yup.string, "persian", persian)
yup.addMethod(yup.string, "persianMobile", persianMobile)
yup.addMethod(yup.string, "percentage", percentage)

export default yup
