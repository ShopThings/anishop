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

  const numRegex = new RegExp('(?=(?<!\.\d*)(?!^)\d{3}(?:\b|(?:\d{3})+)\b)', 'g')

  if (!separator) separator = ','
  value = value + ''

  return value
    .replace(/^0+$/g, '0')
    .replace(/(\.+|,+|\D+)/g, '')
    .replace(numRegex, separator)
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

  const rawInput = parseFloat(input.replace(/[^\d.-]/g, ''))
  return isNaN(rawInput) ? input : rawInput
}

export const getTextColor = (backgroundColor) => {
  function calculateBrightness(color) {
    // Function to calculate brightness from RGB values
    const rgb = this.hexToRgb(color);
    return (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
  }

  function hexToRgb(hex) {
    // Convert hex color to RGB
    const bigint = parseInt(hex.slice(1), 16);
    return {
      r: (bigint >> 16) & 255,
      g: (bigint >> 8) & 255,
      b: bigint & 255,
    };
  }

  // Convert the background color to a brightness value
  const brightness = this.calculateBrightness(backgroundColor);

  // Use a threshold value to determine whether to use white or black text
  return brightness > 128 ? 'black' : 'white';
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
