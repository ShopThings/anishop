export function formatPriceLikeNumber(value, separator) {
    const numRegex = /(?=(?<!\.\d*)(?!^)\d{3}(?:\b|(?:\d{3})+)\b)/g

    if (!separator) separator = ','
    value = value + ''

    return value
        .replace(/^0+$/g, '0')
        .replace(/(\.+|,+|\D+)/g, '')
        .replace(numRegex, separator)
}

export function getLengthOfProxyObject(obj) {
    const z = JSON.parse(JSON.stringify(obj))
    let counter = 0
    for (let o in z) {
        if (z.hasOwnProperty(o)) {
            counter++
        }
    }
    return counter
}
