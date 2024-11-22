<template>
  <v-container v-if="item">
    <v-row wrap no-gutters>
      <v-text-field v-model="tag" label="Name" class="mr-1" readonly filled />
      <v-text-field v-model="item.surveyed_date" label="Surveyed Date" class="mr-1" readonly filled />
      <v-text-field v-model="item.elevation" label="Elevation (meters below sea-level)" readonly filled />
    </v-row>

    <v-row wrap no-gutters>
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
