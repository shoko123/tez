<template>
  <v-card class="mx-auto" min-width="800" max-width="900">
    <v-card-text>
      <v-row>
        <v-select v-model="year" label="Year" item-title="text" item-value="extra" :items="yearInfo.options"
          class="mx-2" @update:model-value="yearSelected"></v-select>

      </v-row>
      <v-row>
        <v-btn class="ma-5" :disabled="!ready" @click="accept">{{ label }}</v-btn>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import type { TFields, TFieldInfo } from '@/types/moduleTypes'
import { useItemNewStore } from '../../../scripts/stores/itemNew'



const { openIdSelectorModal, dataNew, fieldsWithOptions } = storeToRefs(useItemNewStore())

// We define local values for year & objectNo (rather than using values in newFields) to handle
// the cases when there are no objectNo options for a specific year.
const year = ref<number>(20)



const ceramicFieldsWithOptions = computed(() => {
  return fieldsWithOptions.value as Partial<Record<keyof TFields<'Survey'>, TFieldInfo>>
})

const yearInfo = computed(() => {
  return ceramicFieldsWithOptions.value['area_id']!
})

function yearSelected(year: number) {
  return year
}

const ready = computed(() => {
  return true
})

const label = computed(() => {
  return ready.value ? `20${year.value}` : `ID not set`
})

function accept() {
  openIdSelectorModal.value = false
  console.log(`id accepted: ${dataNew.value.fields.id}`)
}
</script>
