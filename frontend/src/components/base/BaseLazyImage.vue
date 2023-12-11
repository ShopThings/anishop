<template>
    <img
        v-if="!actualSrc"
        :src="loadingSrc"
        :alt="alt"
        class="app-image"
    >
    <img
        v-else
        v-lazy="{ src: actualSrc, loading: loadingSrc }"
        :alt="alt"
        class="app-image"
    >
</template>

<script setup>
import {onMounted, ref} from "vue";
import {FilemanagerAPI} from "../../service/APIFilemanager.js";
import {FileSizes} from "../../composables/file-list.js";

const props = defineProps({
    lazySrc: String,
    alt: {
        type: String,
        default: '',
    },
    isLocal: {
        type: Boolean,
        default: true, // make is "false" in test mode and fix things accordingly
    },
    size: {
        type: String,
        default: () => {
            return FileSizes.ORIGINAL
        },
        validator: (value) => {
            return Object.values(FileSizes).indexOf(value) !== -1
        },
    },
    loadingSrc: {
        type: String,
        default: '/image-placeholder.jpg',
    },
})

const actualSrc = ref(null)
if (props.isLocal) {
    actualSrc.value = props.lazySrc
}

onMounted(() => {
    if (!props.isLocal && props.lazySrc) {
        FilemanagerAPI.showFile(props.lazySrc, props.size, {
            success(response) {
                actualSrc.value = response
            },
        })
    }
})
</script>

<style scoped>
.app-image {
    max-width: 100%;
    max-height: 100%;
    vertical-align: middle;
    object-fit: contain;
}

/*.app-image[lazy=loading] {}*/
</style>
