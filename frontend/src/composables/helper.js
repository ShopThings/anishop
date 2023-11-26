import {computed} from "vue";
import {useRoute} from "vue-router";

/**
 * TODO: Test it! because not sure if it has side effects or not :(
 */
export function getRouteParamByKey(key, defaultValue) {
    const route = useRoute()

    return computed(() => {
        if (!parseInt(route.params[key])) return defaultValue ?? null

        const id = parseInt(route.params[key], 10)
        if (isNaN(id)) return route.params[key]
        return id
    })
}

export function formatPriceLikeNumber(value, separator) {
    const numRegex = /(?=(?<!\.\d*)(?!^)\d{3}(?:\b|(?:\d{3})+)\b)/g

    if (!separator) separator = ','
    value = value + ''

    return value
        .replace(/^0+$/g, '0')
        .replace(/(\.+|,+|\D+)/g, '')
        .replace(numRegex, separator)
}
