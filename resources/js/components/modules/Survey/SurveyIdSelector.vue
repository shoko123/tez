<template>
  <v-card class="mx-auto" width="600">
    <v-card-text>

      <v-row class="ga-1">
        <v-select v-model="area_id" label="Area" :items="areas" @update:model-value="areaChanged()"> </v-select>
        <v-select v-model="featureNo" label="Feature No." :items="availableFeatureNos"> </v-select>
        <v-text-field v-if="feature" v-model="feature" label="Selected Feature"></v-text-field>
      </v-row>

      <v-row v-if="selectionIsOk" class="ga-1">
        <v-btn @click="accept()">Accept new id: "{{ tag }}"</v-btn>
      </v-row>

    </v-card-text>
  </v-card>

</template>


<script lang="ts" setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import type { TFields } from '@/types/moduleTypes'

import { useModuleStore } from '../../../scripts/stores/module'
import { useItemNewStore } from '../../../scripts/stores/itemNew'
import { useTrioStore } from '../../../scripts/stores/trio/trio'
import { useXhrStore } from '../../../scripts/stores/xhr'
import { useRoutesMainStore } from '../../../scripts/stores/routes/routesMain'
const { pushHome } = useRoutesMainStore()
const { tagAndSlugFromId } = useModuleStore()
const { openIdSelectorModal, dataNew } = storeToRefs(useItemNewStore())
const { groupLabelToGroupKeyObj, trio } = storeToRefs(useTrioStore())
const { send } = useXhrStore()

const nf = computed(() => {
  return dataNew.value.fields as TFields<'Survey'>
})

// setup - start
const area_id = ref(nf.value.area_id)
const existingFeatureNos = ref<number[]>([])
const featureNo = ref<number | null>(null)
await getExistingFeatureNos()
// setup - end

async function areaChanged() {
  await getExistingFeatureNos()
}

const areas = computed(() => {
  const group = trio.value.groupsObj[groupLabelToGroupKeyObj.value['Area']!]
  return group?.optionKeys.map(k => {
    const option = trio.value.optionsObj[k]!
    return { title: option.text, value: option.extra }
  })
})

const feature = computed(() => {
  return featureNo.value ? `${area_id.value}/${featureNo.value}` : null
})

const availableFeatureNos = computed(() => {
  return Array.from(Array(200).keys()).map((v, i) => i + 1).filter(x => { return !existingFeatureNos.value.includes(x) })
})

async function getExistingFeatureNos() {
  if (area_id.value === null) {
    return []
  }
  // console.log(`getExistingFeatureNos() area_id; ${JSON.stringify(area_id.value, null, 2)}`)

  featureNo.value = null
  const res = await send<string[]>('module/index', 'post', {
    module: 'Survey',
    query: {
      "discrete_field_values": [
        {
          "label": "Area",
          "field_name": "area_id",
          "vals": [area_id.value]
        }
      ],
    }
  })

  if (res.success) {
    existingFeatureNos.value = res.data.map(x => {
      return Number(x.substring(1))
    })
    // console.log(`existingFeatureNos(): ${existingFeatureNos.value}`)
  } else {
    pushHome(`DB access problem!  Redirected to home page.`)
  }
}

const selectionIsOk = computed(() => {
  return !(featureNo.value === null)

})

const id = computed(() => {
  return featureNo.value ? `${area_id.value}${featureNo.value}` : null
})

const tag = computed(() => {
  return id.value ? `${tagAndSlugFromId(id.value).tag}` : null
})

function accept() {
  console.log(`accept() id: ${id.value}, featureId: ${feature.value}`)

  nf.value.id = id.value!
  nf.value.area_id = area_id.value!
  nf.value.feature_no = featureNo.value!
  openIdSelectorModal.value = false
}

</script>
