<template>
  <template v-if="moduleHasFilters">
    <v-row wrap dense class="mt-1">
      <v-col :cols="widths[0]">
        <FilterSelected source="Filter" />
      </v-col>
      <v-col :cols="widths[1]">
        <FilterSelector />
      </v-col>
    </v-row>
  </template>
  <template v-else>
    <v-card class="mt-1">
      <v-card-text>No filters were defined for this module</v-card-text>
    </v-card>
  </template>
</template>

<script lang="ts" setup>
import { computed, defineAsyncComponent } from 'vue'
import { useDisplay } from 'vuetify'
import { storeToRefs } from 'pinia'
import { useTrioStore } from '../../scripts/stores/trio/trio'

const FilterSelector = defineAsyncComponent(() => import('../filter/FilterSelector.vue'))
const FilterSelected = defineAsyncComponent(() => import('../trio/TrioSelectedForm.vue'))

const { trio } = storeToRefs(useTrioStore())

const moduleHasFilters = computed(() => {
  return trio.value.categories.length > 0
})

const widths = computed(() => {
  const { smAndDown } = useDisplay()
  return smAndDown.value ? [12, 12] : [3, 9]
})
</script>
