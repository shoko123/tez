<template>
  <v-container v-if="item" fluid class="pa-0 ma-0">
    <v-row>
      <v-text-field v-model="tag" label="Name" class="mr-1" readonly filled />
      <v-text-field v-model="item.surveyed_date" label="Surveyed Date" class="mr-1" readonly filled />
      <v-text-field v-model="item.elevation" label="Elevation (meters below sea-level)" readonly filled />
    </v-row>

    <v-row>
      <v-text-field v-model="item.next_to" label="Next To" class="mr-1" readonly filled />
    </v-row>

    <v-row>
      <v-textarea v-model="item.description" label="Description" class="mr-1" readonly filled />
      <v-textarea v-model="item.notes" label="Notes" readonly filled />
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { TFields } from '@/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'

let { tag, fields } = storeToRefs(useItemStore())

// const item = computed(() => {
//   return fields.value as TFields<'Survey'>
// })

const item = computed(() => {
  return <TFields<'Survey'>>fields.value
})
</script>
