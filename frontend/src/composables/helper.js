import {computed} from "vue";
import {useRoute} from "vue-router";
import isObject from "lodash.isobject";

/**
 * This is mostly for integrated title operations
 */
export const titleOperations = {
  join(arr, {withRouteTitle, withTitleFromRoute} = {
    withRouteTitle: false,
    withTitleFromRoute: null
  }, separator = ' - ') {
    let joinedTitle = ''

    if (Array.isArray(arr)) {
      arr = arr.filter((item) => {
        return item.toString().trim() !== ''
      })

      joinedTitle = arr.join(separator)
    } else {
      try {
        joinedTitle = arr.toString()
      } catch (e) {
        console.error(e)
      }
    }

    if (withTitleFromRoute && withTitleFromRoute?.meta?.title) {
      joinedTitle = titleOperations.join([withTitleFromRoute.meta.title, joinedTitle])
    } else if (!!withRouteTitle) {
      const route = useRoute()
      if (route?.meta?.title) {
        joinedTitle = titleOperations.join([route.meta.title, joinedTitle])
      }
    }

    return joinedTitle
  },
}

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
  return email.replace(/./g, (match) => `&#${match.charCodeAt(0)};`);
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

export function numberFormat(value, separator) {
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

  if (isNaN(portion) || isNaN(total) || portion < 0 || total <= 0) return 0
  if (portion > total) return 100

  return parseFloat((total - portion) * 100 / total).toFixed(2)
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
  try {
    const rawInput = parseFloat(input.replace(/[^\d.-]/g, ''))
    return isNaN(rawInput) ? input : rawInput
  } catch (e) {
    return input
  }
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

export const estimateReadTime = (content) => {
  let readTimeMinutes

  // Count the number of words in the content
  const words = content.split(/\s+/);

  // Count the number of words in the content
  const wordCount = words.length;

  // Set the average reading speed in words per minute (wpm)
  const readingSpeed = 200; // Adjust this value as needed

  if (wordCount <= 15000) {
    // Calculate the total weight based on word length
    const totalWeight = words.reduce((total, word) => {
      // Increase weight for longer words
      const wordWeight = Math.max(1, Math.ceil(word.length / 5)); // Adjust the divisor as needed
      return total + wordWeight;
    }, 0);

    // Calculate the estimated read time in minutes
    readTimeMinutes = totalWeight / readingSpeed;
  } else {
    // Calculate the estimated read time in minutes
    readTimeMinutes = wordCount / readingSpeed
  }

  // Round the read time to the nearest whole number
  return Math.ceil(readTimeMinutes);
}

/*********************************************************************
 * @function  : persianToCalendars(year, month, day, [options])
 *
 * @purpose   : Converts Persian/Iranian Date (Jalali Date) to the corresponding Gregorian Date.
 *              Handles Persian dates from -272,442 AP to +275,139 AP.
 *              Uses the 'JS Calendar Conversion by Target Approximation' Method.
 *              No external libraries or complex mathematical/astronautical formulas.
 *
 * @version   : 1.00
 * @author    : Mohsen Alyafei
 * @date      : 17 Feb 2022
 * @licence   : MIT
 * @param     : year  : (numeric) Persian year  (-272442 to 275139)
 * @param     : month : (numeric) Persian month (1 to 12) note: months is standard 1 based
 * @param     : day   : (numeric) Persian day   (1 to 31)
 * @param     : options: Object with the following optional parameters:
 *
 *              'toCal' : Specifies the the type of output Calendar to convert to with 18 Calendars:
 *                        - "gregory" : (default)
 *                        - "buddhist", "chinese", "coptic", "dangi", "ethioaa", "ethiopic",
 *                          "hebrew", "indian", "islamic", "islamic-umalqura", "islamic-tbla",
 *                          "islamic-civil", "islamic-rgsa", "iso8601", "japanese", "persian", "roc".
 *
 *               'dateStyle' Same as used in the Intl.DateTimeFormat() constructor.
 *                           If not stated, default output is in Gregorian ISO Format: YYYY:MM:DDTHH:mm:ss.sssZ
 *
 *               'locale' The BCP 47 language tag for formatting (default is 'en'). If the 'locale'
 *                        is given then no date conversion happens and the Persian date is formatted
 *                        based on the specified 'dateStyle' and 'locale'.
 *
 *               Other options: As used in the Intl.DateTimeFormat() constructor.
 *
 * @returns   : Return the date in the calendar and format of the specified 'options'
 **********************************************************************/
export function persianToCalendars(year, month, day, op = {}) {
  const formatOut = gD => "toCal" in op ? (op.calendar = op.toCal, new Intl.DateTimeFormat(op.locale ?? "en", op).format(gD)) : gD,
    dFormat = new Intl.DateTimeFormat('en-u-ca-persian', {dateStyle: 'short', timeZone: "UTC"});
  let gD = new Date(Date.UTC(2000, month, day));
  gD = new Date(gD.setUTCDate(gD.getUTCDate() + 226867));
  const gY = gD.getUTCFullYear() - 2000 + year;
  gD = new Date(((gY < 0) ? "-" : "+") + ("00000" + Math.abs(gY)).slice(-6) + "-" + ("0" + (gD.getUTCMonth() + 1)).slice(-2) + "-" + ("0" + (gD.getUTCDate())).slice(-2));
  let [pM, pD, pY] = [...dFormat.format(gD).split("/")], i = 0;
  gD = new Date(gD.setUTCDate(gD.getUTCDate() +
    ~~(year * 365.25 + month * 30.44 + day - (pY.split(" ")[0] * 365.25 + pM * 30.44 + pD * 1)) - 2));
  while (i < 4) {
    [pM, pD, pY] = [...dFormat.format(gD).split("/")];
    if (pD == day && pM == month && pY.split(" ")[0] == year) return formatOut(gD);
    gD = new Date(gD.setUTCDate(gD.getUTCDate() + 1));
    i++;
  }
  throw new Error('Invalid Persian Date!');
}
