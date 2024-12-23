<template>
  <v-card class="elevation-12">
    <v-card-title class="bg-grey text-black py-0 mb-4">
      {{ title }}
    </v-card-title>
    <v-card-text>
      <v-container fluid>
        <div v-if="hasMedia">
          <v-row no-gutters>
            <v-col :cols="widths[0]">
              <component :is="itemForm" />
            </v-col>
            <v-col :cols="widths[1]">
              <v-container fill-height fluid>
                <v-row>
                  <v-col>
                    <MediaSquare v-bind="{ source: 'media', itemIndex: 0, }" />
                  </v-col>
                </v-row>
              </v-container>

            </v-col>
          </v-row>
        </div>
        <div v-else>
          <v-row dense>
            <component :is="itemForm" />
          </v-row>
        </div>
      </v-container>
    </v-card-text>
  </v-card>
</template>

<script lang="ts" setup>
import { type Component, computed, defineAsyncComponent } from 'vue'
import { storeToRefs } from 'pinia'
import { useDisplay } from 'vuetify'
import { useItemStore } from '../../../scripts/stores/item'
import { useCollectionMediaStore } from '../../../scripts/stores/collections/collectionMedia'
import MediaSquare from '../../media/MediaSquare.vue'

const AreaForm = defineAsyncComponent(() => import('../../modules/Area/AreaForm.vue'))
const SeasonForm = defineAsyncComponent(() => import('../../modules/Season/SeasonForm.vue'))
const SurveyForm = defineAsyncComponent(() => import('../../modules/Survey/SurveyForm.vue'))
const LocusForm = defineAsyncComponent(() => import('../../modules/Locus/LocusForm.vue'))
const CeramicForm = defineAsyncComponent(() => import('../../modules/Ceramic/CeramicForm.vue'))
const FaunaForm = defineAsyncComponent(() => import('../../modules/Fauna/FaunaForm.vue'))
const GlassForm = defineAsyncComponent(() => import('../../modules/Glass/GlassForm.vue'))
const LithicForm = defineAsyncComponent(() => import('../../modules/Lithic/LithicForm.vue'))
const MetalForm = defineAsyncComponent(() => import('../../modules/Metal/MetalForm.vue'))
const StoneForm = defineAsyncComponent(() => import('../../modules/Stone/StoneForm.vue'))

const { smAndDown } = useDisplay()
let { array } = storeToRefs(useCollectionMediaStore())
let { derived } = storeToRefs(useItemStore())

const itemForm = computed<Component | null>(() => {
  switch (derived.value.module) {
    case 'Season':
      return SeasonForm
    case 'Area':
      return AreaForm
    case 'Survey':
      return SurveyForm
    case 'Locus':
      return LocusForm
    case 'Ceramic':
      return CeramicForm
    case 'Fauna':
      return FaunaForm
    case 'Glass':
      return GlassForm
    case 'Lithic':
      return LithicForm
    case 'Metal':
      return MetalForm
    case 'Stone':
      return StoneForm
    default:
      return null
  }
})

const title = computed(() => {
  return `${derived.value.moduleAndTag} - Details`
})

const hasMedia = computed(() => {
  return array.value.length > 0
})

const widths = computed(() => {
  return smAndDown.value ? [12, 12] : [9, 3]
})
</script>
