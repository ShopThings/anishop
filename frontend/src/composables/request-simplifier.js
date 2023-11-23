import {useRequest} from "./api-request.js";
import isFunction from "lodash.isfunction";

export function useRequestWrapper(url, config, callbacks, userCallbacks) {
    const onBeforeRequest = callbacks?.beforeRequest
    const onSuccess = callbacks?.success
    const onError = callbacks?.error
    const onFinally = callbacks?.finally

    const onUserSuccess = userCallbacks?.success
    const onUserError = userCallbacks?.error
    const onUserFinally = userCallbacks?.finally

    useRequest(url, config, {
        beforeRequest: onBeforeRequest,
        success: (response, total) => {

            if (isFunction(onSuccess))
                onSuccess.apply(null, [response, total])

            let answer = true;
            if (isFunction(onUserSuccess))
                answer = onUserSuccess.apply(null, [response, total])

            return answer !== false
        },
        error: (err) => {
            if (isFunction(onError))
                onError.apply(null, [err])

            let answer = true;
            if (isFunction(onUserError))
                answer = onUserError.apply(null, [err])

            return answer !== false
        },
        finally: () => {
            if (isFunction(onFinally))
                onFinally.apply(null)

            if (isFunction(onUserFinally))
                onUserFinally.apply(null)
        },
    })
}
