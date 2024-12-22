<template>
  <!-- <v-card class="mx-auto" color="transparent" flat> -->
  <v-row class="text-body-1 text-white">
    {{ text }}
  </v-row>
  <v-row>
    <v-btn class="ml-2 bg-grey-lighten-1" @click="goToItem()"> {{ tag }} </v-btn>
    <v-btn v-if="props.record.media.hasMedia" class="ml-2 bg-grey-lighten-1" @click="openModalCarousel()">
      Lightbox
    </v-btn>
  </v-row>
  <!-- </v-card> -->
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { TCarousel } from '../../types/mediaTypes'
import { useCarouselStore } from '../../scripts/stores/modals/carousel'
import { useRoutesMainStore } from '../../scripts/stores/routes/routesMain'
import { useNotificationsStore } from '../../scripts/stores/notifications'

const props = defineProps<{
  itemIndex: number
  record: TCarousel<'main'>
}>()

let { routerPush } = useRoutesMainStore()
const { open } = useCarouselStore()
const { showSpinner } = useNotificationsStore()
const { pushHome } = useRoutesMainStore()

const text = computed(() => {
  return props.record.short?.substring(0, 120)
})
const tag = computed(() => {
  return props.record.tag
})
async function openModalCarousel() {
  showSpinner(`Loading carousel item...`)
  const ok = await open('main', props.itemIndex)
  showSpinner(false)
  if (!ok) {
    await pushHome(`Problem encountered! Redirected to home page.`)
  }
}

async function goToItem() {
  await routerPush('show', props.record.slug)
}
</script>
