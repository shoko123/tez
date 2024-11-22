<template>
  <v-container class="d-flex flex-column fill-height justify-center align-center"
    style="background-color: rgba(92, 19, 19, 0.3) !important;">
    <component :is="overlay" :item-index="itemIndex" :record="props.record" />
  </v-container>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { TCName, TPage } from '../../types/collectionTypes'
import { useRoutesMainStore } from '../../scripts/stores/routes/routesMain'
import OverlayRelated from './OverlayRelated.vue'
import OverlayCMedia from './OverlayCMedia.vue'
import OverlayMediaEdit from './OverlayMediaEdit.vue'
import OverlayCMain from './OverlayCMain.vue'

const props = defineProps<{
  source: TCName
  itemIndex: number
  record: TPage<'main', 'Gallery'> &
  TPage<'media', 'Gallery'> &
  TPage<'related', 'Gallery'>
}>()

const { current } = storeToRefs(useRoutesMainStore())

onMounted(() => {
  //console.log(`MediaSquare.onMounted props: ${JSON.stringify(props, null, 2)}`)
})

const overlay = computed(() => {
  switch (props.source) {
    case 'main':
      return OverlayCMain
    case 'media':
      return current.value.name === 'media' ? OverlayMediaEdit : OverlayCMedia
    case 'related':
    default:
      return OverlayRelated
  }
})
</script>
