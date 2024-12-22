<template>
  <v-parallax :src="backgroundUrl" height="100vh">
    <v-container class="d-flex flex-column fill-height justify-center align-center">
      <div>
        <v-row class="text-h2 font-weight-black text-white">
          <v-col :cols="widths[0]" :offset="widths[1]">
            {{ header }}
          </v-col>
        </v-row>
        <v-row class="text-h5 font-weight-medium mb-2 text-white">
          <v-col :cols="widths[0]" :offset="widths[1]">
            {{ text }}
          </v-col>
        </v-row>
      </div>
    </v-container>
  </v-parallax>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useDisplay } from 'vuetify'
import { useMainStore } from '../../scripts/stores/main'

const { smAndDown } = useDisplay()
const { bucketUrl } = storeToRefs(useMainStore())

const header = computed(() => {
  return `Welcome to the Jezreel-Expedition database website!`
})

const text = computed(() => {
  return `This is the database of the Jezreel Expedition (2012-2018), a survey and excavation project in Israel sponsored by the University of Haifa and the University of Evansville and co-directed by Norma Franklin and Jennie Ebeling.`
})

const backgroundUrl = computed(() => {
  return `${bucketUrl.value}app/background/Jez_Background.jpg`
})

const widths = computed(() => {
  return smAndDown.value ? [6, 3] : [12, 0]
})
</script>

<style scoped>
.opac {
  background-color: rgba(92, 19, 19, 0.4) !important;
}
</style>
