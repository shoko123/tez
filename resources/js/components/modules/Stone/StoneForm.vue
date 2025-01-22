<template>
  <v-container v-if="item" fluid class="pa-0 ma-0">
    <v-row class="ga-1">
      <v-text-field v-model="item.date_retrieved" label="Date Retrieved" readonly filled />
      <v-text-field v-model="item.square" label="Square" readonly filled />
      <v-text-field v-model="item.level_top" label="Level Top" readonly filled />
      <v-text-field v-model="item.level_bottom" label="Level Bottom" readonly filled />
      <!-- <v-text-field v-model="item.artifact_count" label="Artifact Count" readonly filled /> -->
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-model="item.field_description" label="Field Description" readonly filled />
      <v-text-field v-model="item.field_notes" label="Field Notes" readonly filled />
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-if="lf.stone_primary_classification_id" v-model="lf.stone_primary_classification_id"
        label="Primary Classification" readonly filled></v-text-field>
      <v-text-field v-if="lf.material_id" v-model="lf.material_id" label="Material" readonly filled></v-text-field>
      <v-text-field v-if="item.weight" v-model="item.weight" label="Weight" readonly filled></v-text-field>
      <v-text-field v-if="item.length" v-model="item.length" label="Length" readonly filled></v-text-field>
      <v-text-field v-if="item.width" v-model="item.width" label="Width" readonly filled></v-text-field>
      <v-text-field v-if="item.height" v-model="item.height" label="Height" readonly filled></v-text-field>
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-if="item.depth" v-model="item.depth" label="Depth" readonly filled></v-text-field>
      <v-text-field v-if="item.thickness_min" v-model="item.thickness_min" label="Thickness (min)" readonly
        filled></v-text-field>
      <v-text-field v-if="item.thickness_max" v-model="item.thickness_max" label="Thickness (max)" readonly
        filled></v-text-field>
      <v-text-field v-if="item.diameter" v-model="item.diameter" label="Diameter" readonly filled></v-text-field>
      <v-text-field v-if="item.perforation_diameter_min" v-model="item.perforation_diameter_min" label="Perf Diam (min)"
        readonly filled></v-text-field>
      <v-text-field v-if="item.perforation_diameter_max" v-model="item.perforation_diameter_max" label="Perf Diam (max)"
        readonly filled></v-text-field>
      <v-text-field v-if="item.perforation_depth" v-model="item.perforation_depth" label="Perf Depth" readonly
        filled></v-text-field>
      <v-text-field v-if="item.rim_diameter" v-model="item.rim_diameter" label="Rim Diam" readonly
        filled></v-text-field>
      <v-text-field v-if="item.rim_thickness" v-model="item.rim_thickness" label="Rim Thickness" readonly
        filled></v-text-field>
      <v-text-field v-if="item.base_diameter" v-model="item.base_diameter" label="Base Diam" readonly
        filled></v-text-field>
      <v-text-field v-if="item.base_thickness" v-model="item.base_thickness" label="Base Thickness" readonly
        filled></v-text-field>
    </v-row>

    <v-row class="ga-1">
      <v-textarea v-model="item.description" label="Description" readonly filled />
      <v-textarea v-model="item.notes" label="Notes" readonly filled />
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { TLookupFields, TFields } from '@/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'

let { fields, lookupEnums } = storeToRefs(useItemStore())

const item = computed(() => {
  return <TFields<'Stone'>>fields.value
})

const lf = computed(() => {
  return <TLookupFields<'Stone'>>lookupEnums.value
})
</script>