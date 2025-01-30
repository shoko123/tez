<template>
  <v-container v-if="item" fluid class="pa-0 ma-0">
    <v-row class="ga-1">
      <v-text-field v-model="item.date_retrieved" label="Date Retrieved" readonly filled />
      <v-text-field v-model="item.weight" label="Weight (gm)" readonly filled />
      <v-text-field v-model="item.field_description" label="Field Description" readonly filled />
    </v-row>
    <v-row class="ga-1">
      <v-text-field v-model="lf.primary_taxon_id" label="Primary Taxon" readonly filled />
      <v-text-field v-model="lf.scope_id" label="Scope" readonly filled />
      <v-text-field v-model="lf.material_id" label="Material" readonly filled />
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-model="item.taxa" label="Taxa" readonly filled />
      <v-text-field v-model="item.bone" label="Element/Material" readonly filled />
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-model="item.symmetry" label="Symmetry" readonly filled />
      <v-text-field v-model="item.d_and_r" label="Dobney & Rilley 1988" readonly filled />
      <v-text-field v-model="item.age" label="Age" readonly filled />
      <v-text-field v-model="item.breakage" label="Breakage" readonly filled />
      <v-text-field v-model="item.weathering" label="Weathering (Behrensmeyer 1978)" readonly filled />
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-model="item.butchery" label="Butchery" readonly filled />
      <v-text-field v-model="item.burning" label="Burning" readonly filled />
      <v-text-field v-model="item.other_bsm" label="Other Bone Surface Modifications" readonly filled />
    </v-row>

    <v-row class="ga-1">
      <v-textarea v-model="item.specialist_notes" label="Specialist Notes" readonly filled />
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-for="(np, index) in onps" :key="index" v-model="np.value" :label="np.label" readonly filled>
      </v-text-field>
    </v-row>

  </v-container>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { TFields } from '@/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'

let { fields, lookupEnums, onps } = storeToRefs(useItemStore())

const item = computed(() => {
  return fields.value as TFields<'Fauna'>
})

const lf = computed(() => {
  return lookupEnums.value as TFields<'Fauna'>
})
</script>
