<template>
  <v-card class="mx-auto" color="transparent" flat>
    <v-card-text class="text-body-1 text-white">
      {{ text }}
    </v-card-text>
    <v-card-actions>
      <v-btn class="ml-2 bg-grey-lighten-1" @click="goToItem()"> {{ tag }} </v-btn>
      <v-btn v-if="props.record.media.hasMedia" class="ml-2 bg-grey-lighten-1" @click="openModalCarousel()">
        Lightbox
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { TPage } from '../../types/collectionTypes'
import { useRoutesMainStore } from '../../scripts/stores/routes/routesMain'
import { useCarouselStore } from '../../scripts/stores/modals/carousel'
import { useNotificationsStore } from '../../scripts/stores/notifications'

const props = defineProps<{
  itemIndex: number
  record: TPage<'related', 'Gallery'>
}>()

let { moveToRelatedItem } = useRoutesMainStore()
const { showSpinner } = useNotificationsStore()
const { pushHome } = useRoutesMainStore()
const { open } = useCarouselStore()

const text = computed(() => {
  return props.record.short?.substring(0, 120)
})
const tag = computed(() => {
  return 'visit'
  // return props.record.module + '::' + props.record.tag
})

async function openModalCarousel() {
  showSpinner(`Loading carousel item...`)
  const ok = await open('related', props.itemIndex)
  showSpinner(false)
  if (!ok) {
    await pushHome(`Error encountered! Redirected to home page.`)
  }
}

async function goToItem() {
  await moveToRelatedItem(props.record.module, props.record.id)
}
</script>
