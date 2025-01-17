<template>
  <v-container v-if="item" fluid class="pa-0 ma-0">
    <v-row class="ga-1">
      <v-text-field v-model="item.taxa" label="Taxa" readonly filled />
      <v-text-field v-model="item.bone" label="Bone" readonly filled />
      <!-- <v-text-field v-model="item.level_bottom" label="Level Bottom" readonly filled />
      <v-text-field v-model="item.artifact_count" label="Artifact Count" readonly filled /> -->
    </v-row>

    <v-row class="ga-1">
      <!-- <v-text-field v-model="item.field_description" label="Field Description" readonly filled />
      <v-text-field v-model="item.field_notes" label="Field Notes" readonly filled /> -->
    </v-row>
    <v-row class="ga-1">
      <!-- <v-textarea v-model="item.description" label="Specialist Description" readonly filled /> -->
      <v-textarea v-model="item.notes" label="Specialist Notes" readonly filled />
    </v-row>
    <v-row class="ga-1">
      <v-text-field v-model="lf.primary_taxon_id" label="Taxon" readonly filled />
      <!-- <v-text-field v-model="item.has_butchery_evidence" label="Bitchery" readonly filled />
      <v-text-field v-model="item.has_burning_evidence" label="Burning" readonly filled />
      <v-text-field v-model="item.has_other_bsm_evidence" label="BSM" readonly filled /> -->
    </v-row>

    <!-- <v-row class="ga-1">
      <v-text-field v-if="item.GL" v-model="item.GL" label="GL" readonly filled />
      <v-text-field v-if="item.Glpe" v-model="item.Glpe" label="Glpe" readonly filled />
      <v-text-field v-if="item.GLl" v-model="item.GLl" label="GLi" readonly filled />
      <v-text-field v-if="item.GLP" v-model="item.GLP" label="GLP" readonly filled />
      <v-text-field v-if="item.Bd" v-model="item.Bd" label="Bd" readonly filled />
      <v-text-field v-if="item.BT" v-model="item.BT" label="BT" readonly filled />
      <v-text-field v-if="item.Dd" v-model="item.Dd" label="Dd" readonly filled />
      <v-text-field v-if="item.BFd" v-model="item.BFd" label="BFd" readonly filled />
      <v-text-field v-if="item.Bp" v-model="item.Bp" label="Bp" readonly filled />
      <v-text-field v-if="item.Dp" v-model="item.Dp" label="Dp" readonly filled />
      <v-text-field v-if="item.SD" v-model="item.SD" label="SD" readonly filled />
      <v-text-field v-if="item.HTC" v-model="item.HTC" label="HTC" readonly filled />
      <v-text-field v-if="item.Dl" v-model="item.Dl" label="Dl" readonly filled />
      <v-text-field v-if="item.DEM" v-model="item.DEM" label="DEM" readonly filled />
      <v-text-field v-if="item.DVM" v-model="item.DVM" label="DVM" readonly filled />
      <v-text-field v-if="item.WCM" v-model="item.WCM" label="WCM" readonly filled />
    </v-row>
    <v-row class="ga-1">
      <v-text-field v-if="item.DEL" v-model="item.DEL" label="DEL" readonly filled />
      <v-text-field v-if="item.DVL" v-model="item.DVL" label="DVL" readonly filled />
      <v-text-field v-if="item.WCL" v-model="item.WCL" label="WCL" readonly filled />
      <v-text-field v-if="item.LD" v-model="item.LD" label="LD" readonly filled />
      <v-text-field v-if="item.DLS" v-model="item.DLS" label="DLS" readonly filled />
      <v-text-field v-if="item.LG" v-model="item.LG" label="LG" readonly filled />
      <v-text-field v-if="item.BG" v-model="item.BG" label="BG" readonly filled />
      <v-text-field v-if="item.DID" v-model="item.DID" label="DID" readonly filled />
      <v-text-field v-if="item.BFcr" v-model="item.BFcr" label="BFcr" readonly filled />
      <v-text-field v-if="item.GD" v-model="item.GD" label="GD" readonly filled />
      <v-text-field v-if="item.GB" v-model="item.GB" label="GB" readonly filled />
      <v-text-field v-if="item.BF" v-model="item.BF" label="BF" readonly filled />
      <v-text-field v-if="item.LF" v-model="item.LF" label="LF" readonly filled />
      <v-text-field v-if="item.GLm" v-model="item.GLm" label="GLm" readonly filled />
      <v-text-field v-if="item.GH" v-model="item.GH" label="GH" readonly filled />
    </v-row> -->
  </v-container>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { TFields } from '@/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'

let { fields, lookupFields } = storeToRefs(useItemStore())

const item = computed(() => {
  return fields.value as TFields<'Fauna'>
})

const lf = computed(() => {
  return lookupFields.value as TFields<'Fauna'>
})
</script>
