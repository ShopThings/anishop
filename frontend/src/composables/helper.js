import {computed} from "vue";
import {useRoute} from "vue-router";
import isObject from "lodash.isobject";

export function isValidInternalRedirectLink(link) {
  return link && /^\/[a-zA-Z0-9-_]+(?:\/[a-zA-Z0-9-_]+)*/g.test(link)
}

export function trimChar(str, char) {
  if (char === "]") char = "\\]";
  if (char === "\\") char = "\\\\";
  return str.replace(new RegExp(
    "^[" + char + "]+|[" + char + "]+$", "g"
  ), "");
}

export function obfuscateEmail(email) {
  return email.replace(/./g, '&#46;');
}

export function obfuscateNumber(phone) {
  return phone.replace(/\d/g, (match) => `&#${match.charCodeAt(0)};`);
}

export function concatPath(...paths) {
  const r = new RegExp('[\\\\]*')
  let path = []
  for (const p of paths) {
    path.push(trimChar(p.replace(r, '/'), '/'))
  }

  path = path.filter((item) => {
    return ['', '.', '..'].indexOf(item.trim()) === -1
  })

  return path.join('/')
}

export function getRouteParamByKey(key, defaultValue, isNumeric) {
  const route = useRoute()
  isNumeric = typeof isNumeric === 'undefined' ? true : !!isNumeric

  return computed(() => {
    if (isNumeric) {
      if (!parseInt(route.params[key])) return defaultValue ?? null

      const id = parseInt(route.params[key], 10)
      if (isNaN(id)) return route.params[key]
      return id
    }
    return route.params[key] ? decodeURIComponent(route.params[key]) : (defaultValue ?? null)
  })
}

export function formatPriceLikeNumber(value, separator) {
  if (!value) return '0';

  if (!separator) separator = ','
  value = (value + '')
    .replace(/^0+$/g, '0')
    .replace(/(\.+|,+|\D+)/g, '')

  const numericValue = Number(value);
  if (isNaN(numericValue)) {
    console.error('Invalid numeric value:', value);
    return '0';
  }

  return numericValue.toLocaleString(undefined, {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
    useGrouping: true,
    groupingSeparator: separator,
  })
}

export const getPercentageOfPortion = (portion, total) => {
  // Explicit type conversion to handle string inputs
  portion = +portion;
  total = +total;

  if (isNaN(portion) || isNaN(total) || portion < 0 || total < 0) return 0
  if (portion > total) return 100

  return Math.round((total - portion) * 100 / total)
}

export function escapeMoneyCharacter(input, only) {
  if ([false, true, null].includes(input)) return input

  if (!Array.isArray(only)) {
    only = true
  }

  if (Array.isArray(input)) {
    for (let i = 0; i < input.length; i++) {
      if (only === true || only.includes(input[i]) || isObject(input[i])) {
        input[i] = escapeMoneyCharacter(input[i], only);
      }
    }
    return input
  } else if (isObject(input)) {
    for (let i in input) {
      if (input.hasOwnProperty(i) && (only === true || only.includes(i) || Array.isArray(input[i]))) {
        input[i] = escapeMoneyCharacter(input[i], only)
      }
    }
    return input
  }

  // '-' character in regex is not a hyphen, it's for negative numbers
  const rawInput = parseFloat(input.replace(/[^\d.-]/g, ''))
  return isNaN(rawInput) ? input : rawInput
}

export const getTextColor = (backgroundColor) => {
  function hexToRgb(hex) {
    // Convert hex color to RGB
    const bigint = parseInt(hex.slice(1), 16);
    return {
      r: (bigint >> 16) & 255,
      g: (bigint >> 8) & 255,
      b: bigint & 255,
    };
  }

  function calculateBrightness(color) {
    // Function to calculate brightness from RGB values
    const rgb = hexToRgb(color);
    return (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
  }

  // Convert the background color to a brightness value
  const brightness = calculateBrightness(backgroundColor);

  // Use a threshold value to determine whether to use white or black text
  return brightness > 155 ? 'black' : 'white';
}

export const nestedArray = {
  get(obj, key) {
    return key.split('.').reduce((acc, currentKey) => acc ? acc[currentKey] : undefined, obj)
  },

  set(obj, key, value) {
    const keys = key.split('.');
    const lastKey = keys.pop();

    const nestedObj = keys.reduce((acc, currentKey) => {
      if (!acc[currentKey] || typeof acc[currentKey] !== 'object') {
        acc[currentKey] = {};
      }
      return acc[currentKey];
    }, obj);

    nestedObj[lastKey] = value;
  },

  remove(obj, key) {
    const keys = key.split('.');
    const lastKey = keys.pop();

    const nestedObj = keys.reduce((acc, currentKey) => {
      if (!acc[currentKey] || typeof acc[currentKey] !== 'object') {
        // If the nested structure doesn't exist, no need to proceed further
        return undefined;
      }
      return acc[currentKey];
    }, obj);

    if (nestedObj && nestedObj.hasOwnProperty(lastKey)) {
      delete nestedObj[lastKey];
    }
  }
}

export const findItemByKey = (arr, key, value, strict = true) => {
  for (let item of arr) {
    if (strict ? item[key] === value : item[key] == value) {
      return item
    }
  }

  return {}
}

export const addQueryParam = (key, values) => {
  const route = useRoute()

  if (typeof values !== undefined && values !== null) {
    values = Array.isArray(values) ? values : [values]
  }

  const currentValues = Array.isArray(route.query[key]) ? [...route.query[key]] : [];
  const newValues = [...currentValues, ...values];

  return {
    [key]: newValues,
  }
}
