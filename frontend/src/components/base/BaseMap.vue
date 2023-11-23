<template>
    <div :class="containerClass">
        <div class="w-full h-full" id="mapContainer"></div>

        <div ref="popupDialogElement" v-show="false">
            <slot name="markerPopup"></slot>
        </div>
    </div>
</template>

<script setup>
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import {computed, onBeforeUnmount, onMounted, ref} from "vue";

const props = defineProps({
    containerClass: {
        type: String,
        default: 'w-full h-96',
    },
    center: {
        type: Array,
        required: true,
    },
    zoom: {
        type: Number,
        default: 14,
    },
})
const emit = defineEmits(['update:center', 'update:zoom'])

const popupDialogElement = ref(null)

const map = ref(null)
const center = computed({
    get() {
        return props.center
    },
    set(value) {
        emit('update:center', value)
    }
})
const zoom = computed({
    get() {
        return props.zoom
    },
    set(value) {
        emit('update:zoom', value)
    },
})

onMounted(() => {
    map.value = L.map("mapContainer").setView(center.value, zoom.value)
    L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map.value)
    const marker = L.marker(center.value).addTo(map.value)

    if (popupDialogElement.value) {
        const content = popupDialogElement.value.innerHTML
        marker.bindPopup(content).openPopup();
    }
})

onBeforeUnmount(() => {
    if (map.value)
        map.value.remove()
})
</script>

<style>
.leaflet-pane {
    z-index: 8;
}

.leaflet-top, .leaflet-bottom {
    z-index: 9;
}
</style>
