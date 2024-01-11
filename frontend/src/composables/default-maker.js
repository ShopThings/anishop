import {isRef, toRef} from "vue";

export const useReactiveMerge = (baseValue, value) => {
  // merge arrays
  if (
    Array.isArray(baseValue)
    && Array.isArray(value)
  ) {
    for (let i = 0; i < value.length; i) {
      let val = useReactiveMerge(baseValue[i], value[i]);
      if (val === undefined)
        continue;
      if (baseValue[i])
        if (isRef(baseValue[i])) {
          toRef(baseValue, i, val)
        } else {
          baseValue.splice(i, 0, val)
        }
      else
        baseValue.push(val)
    }
  }
  // merge objects
  else if (
    value
    && baseValue
    && typeof value === 'object'
    && typeof baseValue === 'object'
  ) {
    for (let key of Object.keys(value)) {
      let val = useReactiveMerge(baseValue[key], value[key]);
      if (val === undefined)
        continue;
      if (isRef(baseValue[key])) {
        toRef(baseValue, key, val)
      } else {
        baseValue[key] = val
      }
    }
  }
  // return value
  else if (value !== undefined)
    return value

  return baseValue
}
