<template>
  <v-card outlined>
    <v-card-title class="bg-grey-lighten-3 text-black title mb-2"> Location </v-card-title>

    <v-card class="ma-2 pa-2">
      {{ locationText }}
    </v-card>

    <v-card class="ma-2 pa-2">
      <v-img :src="bgUrls.fullUrl" :lazy-src="bgUrls.tnUrl" :cover="true">
      </v-img>
    </v-card>

    <v-card class="ma-2 pa-2">
      <GoogleMap :api-key="googleMapsApiKey" style="width: 100%; height: 700px" :zoom="17" :center="center"
        map-type-id="satellite" :street-view-control="false" :map-id="mapId">
        <AdvancedMarker :options="{ position: center }" />
      </GoogleMap>
    </v-card>
  </v-card>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useLandingStore } from '../../scripts/stores/landing'
const { locationText } = useLandingStore()
import { storeToRefs } from 'pinia'
import { GoogleMap, AdvancedMarker } from 'vue3-google-map'
import { useMainStore } from '../../scripts/stores/main'
const { bucketUrl } = storeToRefs(useMainStore())
const { googleMapsApiKey } = storeToRefs(useMainStore())
const center = { lat: 32.55864, lng: 35.33668 }
const mapId = 'my_map_id'

const bgUrls = computed(() => {
  return {
    fullUrl: `${bucketUrl.value}app/about/Location.jpg`,
    tnUrl: `${bucketUrl.value}app/about/Location-tn.jpg`,
  }
})
</script>
