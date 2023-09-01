import BaseConfirm from "../components/base/BaseConfirm.vue";
import {POSITION, TYPE, useToast} from "vue-toastification";
import isFunction from "lodash.isfunction";

export const useConfirmToast = (onAccept, title, subTitle) => {
    onAccept = isFunction(onAccept) ? onAccept : () => {
    }

    const toast = useToast()

    toast({
        component: BaseConfirm,
        props: {
            title,
            subTitle,
        },
        listeners: {
            accept: onAccept
        },
    }, {
        timeout: false,
        type: TYPE.DEFAULT,
        position: POSITION.TOP_CENTER,
        closeOnClick: false,
        closeButton: false,
        toastClassName: 'confirmation-toast',
    })
}
