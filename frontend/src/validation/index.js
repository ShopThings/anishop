import * as yup from 'yup';

function persian(message) {
    return this.test("persian", message, function (value) {
        const {path, createError} = this

        if (!value.match(/^[‌پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u)) {
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
                message: message ?? 'The mobile is incorrect'
            })
        }

        return true
    });
}

function persianNationalCode(message) {
    return this.test("persianNationalCode", message, function (value) {
        const {path, createError} = this

        const errMsg = 'The national code is incorrect'
        const notValidCodes = [
            '1111111111', '2222222222', '3333333333', '4444444444', '5555555555',
            '6666666666', '7777777777', '8888888888', '9999999999', '0000000000',
        ];

        const val = value + ''
        if (notValidCodes.indexOf(val) !== -1) {
            return createError({
                path,
                message: message ?? errMsg
            })
        }

        if (val.length !== 10 || /(\d)(\1){9}/.test(val))
            return createError({
                path,
                message: message ?? errMsg
            })

        let sum = 0,
            chars = val.split(''),
            lastDigit,
            remainder

        for (let i = 0; i < 9; i++) sum += +chars[i] * (10 - i)

        remainder = sum % 11
        lastDigit = remainder < 2 ? remainder : 11 - remainder

        if (+chars[9] !== lastDigit)
            return createError({
                path,
                message: message ?? errMsg
            })

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
yup.addMethod(yup.string, "persianNationalCode", persianNationalCode)

export default yup

export function transformNumbersToEnglish(value) {
    if (typeof value !== 'string') {
        // If value is not a string, return it unchanged
        return value;
    }

    // Replace Arabic numbers with their western equivalents
    const arabicNumbers = '٠١٢٣٤٥٦٧٨٩';
    const persianNumbers = '۰۱۲۳۴۵۶۷۸۹';
    const westernNumbers = '0123456789';
    let transformedValue = '';

    for (let i = 0; i < value.length; i++) {
        const char = value.charAt(i);
        const index = arabicNumbers.indexOf(char);
        const index2 = persianNumbers.indexOf(char);
        if (index !== -1) {
            transformedValue += westernNumbers.charAt(index);
        } else if (index2 !== -1) {
            transformedValue += westernNumbers.charAt(index2);
        } else {
            transformedValue += char;
        }
    }

    return transformedValue;
}
