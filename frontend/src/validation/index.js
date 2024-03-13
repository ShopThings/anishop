import * as yup from 'yup';

function persian(message) {
  return this.test("persian", message, function (value) {
    const {path, createError} = this

    if (value && !isValidPersianName(value)) {
      return createError({
        path,
        message: message ?? 'The name may contain only persian letters'
      })
    }

    return true
  })
}

function persianMobile(message) {
  return this.test("persianMobile", message, function (value) {
    const {path, createError} = this

    if (value && !isValidPersianMobile(value)) {
      return createError({
        path,
        message: message ?? 'The mobile is incorrect'
      })
    }

    return true
  })
}

function persianNationalCode(message) {
  return this.test("persianNationalCode", message, function (value) {
    const {path, createError} = this

    const errMsg = 'The national code is incorrect'

    if (isValidPersianNationalCode(value)) {
      return createError({
        path,
        message: message ?? errMsg
      })
    }

    return true
  })
}

function percentage(message) {
  const msg = message ?? 'Percentage should be between 0 and 100'
  return this
    .min(0, msg)
    .max(100, msg)
}

function folderName(message) {
  return this.test("folderName", message, function (value) {
    const {path, createError} = this

    if (value && !isValidFolderName(value)) {
      return createError({
        path,
        message: message ?? 'Folder name is invalid'
      })
    }

    return true
  })
}

function colorHex(message) {
  return this.test("colorHex", message, function (value) {
    const {path, createError} = this

    if (value && !isValidColorHex(value)) {
      return createError({
        path,
        message: message ?? 'Color is invalid'
      })
    }

    return true
  })
}

function positiveNumber(message, {gt = 0, optional = false} = {}) {
  return this.test('positiveNumber', message, function (value) {
    const {path, createError, originalValue} = this;

    // If optional and value is undefined, consider it as valid
    if (optional && (originalValue === undefined || parseFloat(originalValue) === 0)) {
      return true;
    }

    if (!isNaN(parseFloat(gt))) gt = parseFloat(gt)

    if (value) {
      value = parseFloat(value)

      if (isNaN(value) || value < gt) {
        return createError({
          path,
          message: message ?? 'Price must be a positive number',
        });
      }
    }

    return true;
  });
}

function lessThanNumber(number, message, {equal = false} = {}) {
  return this.test('lessThanNumber', message, function (value) {
    const {path, createError, originalValue} = this;

    let tmpNumber = number

    if (originalValue === undefined || tmpNumber === undefined) {
      return true;
    }

    if (typeof tmpNumber === 'string') {
      const tmpRefNumber = this.resolve(yup.ref(tmpNumber));
      if (tmpRefNumber) {
        tmpNumber = tmpRefNumber
      }
    } else if (typeof tmpNumber !== 'number') {
      throw new Error('Invalid numberRef type');
    }

    // Parse numbers, handle parsing failures
    const parsedNumber = parseFloat(tmpNumber)
    const parsedValue = parseFloat(value)

    // Check if parsing was successful
    if (isNaN(parsedNumber) || isNaN(parsedValue)) {
      return createError({
        path,
        message: 'Invalid number format',
      });
    }

    const cond = equal ? parsedValue <= parsedNumber : parsedValue < parsedNumber;

    if (!cond) {
      return createError({
        path,
        message: message || `${path} should be less than ${equal ? 'or equal to' : ''} ${parsedNumber}`,
      });
    }

    return true;
  });
}

function greaterThanNumber(number, message, {equal = false} = {}) {
  return this.test('greaterThanNumber', message, function (value) {
    const {path, createError, originalValue} = this;

    let tmpNumber = number

    if (originalValue === undefined || tmpNumber === undefined) {
      return true;
    }

    if (typeof tmpNumber === 'string') {
      const tmpRefNumber = this.resolve(yup.ref(tmpNumber));
      if (tmpRefNumber) {
        tmpNumber = tmpRefNumber
      }
    } else if (typeof tmpNumber !== 'number') {
      throw new Error('Invalid numberRef type');
    }

    // Parse numbers, handle parsing failures
    const parsedNumber = parseFloat(tmpNumber);
    const parsedValue = parseFloat(value);

    // Check if parsing was successful
    if (isNaN(parsedNumber) || isNaN(parsedValue)) {
      return createError({
        path,
        message: 'Invalid number format',
      });
    }

    const cond = equal ? parsedValue >= parsedNumber : parsedValue > parsedNumber;
    if (!cond) {
      return createError({
        path,
        message: message || `${path} should be greater than ${equal ? 'or equal to' : ''} ${parsedNumber}`,
      });
    }

    return true;
  });
}

yup.addMethod(yup.string, "persian", persian)
yup.addMethod(yup.string, "persianMobile", persianMobile)
yup.addMethod(yup.string, "percentage", percentage)
yup.addMethod(yup.string, "persianNationalCode", persianNationalCode)
yup.addMethod(yup.string, "folderName", folderName)
yup.addMethod(yup.string, "colorHex", colorHex)
yup.addMethod(yup.string, "positiveNumber", positiveNumber)
yup.addMethod(yup.string, "lessThanNumber", lessThanNumber)
yup.addMethod(yup.string, "greaterThanNumber", greaterThanNumber)

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

export function isValidPersianName(value) {
  if (!value) return false
  return value.match(/^[‌پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأآءًٌٍَُِّ\s]+$/u)
}

export function isValidPersianMobile(value) {
  if (!value) return false
  return value.match(/^(098|\+98|0)?9\d{9}$/)
}

export function isValidPersianNationalCode(value) {
  if (!value) return false

  const val = value + ''
  const notValidCodes = [
    '1111111111', '2222222222', '3333333333', '4444444444', '5555555555',
    '6666666666', '7777777777', '8888888888', '9999999999', '0000000000',
  ];

  if (notValidCodes.indexOf(val) !== -1) return false
  if (val.length !== 10 || /(\d)(\1){9}/.test(val)) return false

  let sum = 0,
    chars = val.split(''),
    lastDigit,
    remainder

  for (let i = 0; i < 9; i++) sum += +chars[i] * (10 - i)

  remainder = sum % 11
  lastDigit = remainder < 2 ? remainder : 11 - remainder

  return +chars[9] !== lastDigit
}

export function isValidPercentage(value) {
  if (!value) return false

  value = +value

  return 0 <= value && value <= 100
}

export function isValidFolderName(value) {
  if (!value) return false
  return value.match(/^(?![.]{1,2}$)(?!.*[<>:"\/\\?*]).+$/)
}

export function isValidColorHex(value) {
  if (!value) return false
  return value.match(/^(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d.]+%?\))$/i)
}
