<template>
  <v-container v-if="item" fluid class="pa-0 ma-0">
    <v-row class="ga-1">
      <v-text-field v-model="item.date_retrieved" label="Date Retrieved" readonly filled />
      <v-text-field v-model="item.field_description" label="Field Description" readonly filled />
      <v-text-field v-model="item.registration_notes" label="Field Notes" readonly filled />
      <v-text-field v-model="item.weight" label="Weight" readonly filled />
    </v-row>

    <!-- <v-row class="ga-1">    
    </v-row> -->

    <v-row class="ga-1" wrap>
      <v-textarea v-model="onpsText" label="Type(s)" rows="1" auto-grow readonly filled />
      <v-textarea v-model="item.specialist_notes" label="Specialist Notes" rows="1" auto-grow readonly filled />
      <!-- <v-text-field v-for="(np, index) in onps" :key="index" v-model="np.value" :label="np.label" readonly filled>
      </v-text-field> -->

    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { TFields } from '@/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'

let { fields, onps } = storeToRefs(useItemStore())

const item = computed(() => {
  return fields.value as TFields<'Lithic'>
})

const onpsText = computed(() => {
  return onps.value.map(x => {
    return `${x.label}(${x.value})`
  }).join(',\n')
})
</script>
