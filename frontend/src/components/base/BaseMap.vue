<template>
  <div :class="containerClass" class="relative">
    <div class="w-full h-full" id="mapContainer"></div>

    <div ref="popupDialogElement" v-show="false">
      <slot name="markerPopup"></slot>
    </div>

    <div
        v-if="allowFindMyLocation"
        class="absolute right-3 top-3 z-10"
    >
      <button
          v-tooltip.bottom-start="'پیدا کردن/رفتن به موقعیت من'"
          type="button"
          class="rounded-full p-2 flex items-center justify-center shadow-md bg-white group"
          @click="handleGeolocation"
      >
        <MapPinIcon class="w-6 h-6 group-hover:text-rose-600 transition"/>
      </button>
    </div>
  </div>
</template>

<script setup>
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import {computed, onBeforeUnmount, onMounted, ref, useSlots} from "vue";
import {MapPinIcon} from "@heroicons/vue/24/outline/index.js"
import {useGeolocation} from "@vueuse/core";
import {useToast} from "vue-toastification";

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
  allowEditMarker: {
    type: Boolean,
    default: false,
  },
  allowFindMyLocation: {
    type: Boolean,
    default: false,
  },
})
const slot = useSlots()
const emit = defineEmits(['update:center', 'update:zoom', 'loaded'])

const toast = useToast()

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

let marker;
const {coords, error, resume, pause} = useGeolocation()

function handleGeolocation() {
  if (!coords.value) resume()

  if (error.value || !coords.value) {
    toast.warning('موقعیت قابل دستیابی نمی‌باشد! دوباره تلاش نمایید.')
    console.error(error.value)
    return
  }

  const notAllowed = [Number.POSITIVE_INFINITY, Number.NEGATIVE_INFINITY]
  if (
      notAllowed.indexOf(coords.value.latitude) === -1 &&
      notAllowed.indexOf(coords.value.longitude) === -1
  ) {
    center.value = [coords.value.latitude, coords.value.longitude]
  } else {
    toast.info('عدم امکان تشخیص مکان شما!')
  }

  if (map.value && marker) {
    let newLatLng = new L.LatLng(center.value[0], center.value[1])
    marker.setLatLng(newLatLng)
    map.value.panTo(newLatLng)
    pause()
  }
}

onMounted(() => {
  map.value = L.map("mapContainer").setView(center.value, zoom.value)
  L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map.value)

  const opts = {}
  if (props.allowEditMarker) {
    opts.draggable = true
    opts.autoPan = true
  }

  marker = L.marker(center.value, opts).addTo(map.value)

  if (slot['markerPopup'] && popupDialogElement.value) {
    const content = popupDialogElement.value.innerHTML
    marker.bindPopup(content).openPopup();
  }

  if (props.allowEditMarker) {
    marker.on('dragend', function (event) {
      let mk = event.target
      let latlng = mk.getLatLng()
      center.value = [latlng.lat, latlng.lng]
    })
  }

  setTimeout(() => {
    if (map.value) emit('loaded', map.value)
  }, 1000)
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
