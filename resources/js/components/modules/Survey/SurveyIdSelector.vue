<template>
  <v-card class="mx-auto" width="600">
    <v-card-text>

      <v-row class="ga-1">
        <v-select v-model="nf.area_id" label="Area" :items="areas" @update:model-value="areaChanged()"> </v-select>
        <v-select v-model="nf.feature_no" label="Feature No." :items="availableFeatureNos"> </v-select>
      </v-row>

      <v-row v-if="id" class="ga-1">
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
  return dataNew.value.fields as Partial<TFields<'Survey'>>
})

// setup - start
const existingFeatureNos = ref<number[]>([])
await getExistingFeatureNos()
// setup - end

const areas = computed(() => {
  const group = trio.value.groupsObj[groupLabelToGroupKeyObj.value['Area']!]
  return group?.optionKeys.map(k => {
    const option = trio.value.optionsObj[k]!
    return { title: option.text, value: option.extra }
  })
})

async function areaChanged() {
  await getExistingFeatureNos()
}

async function getExistingFeatureNos() {
  if (!nf.value.area_id) {
    return []
  }
  // console.log(`getExistingFeatureNos() area_id; ${JSON.stringify(nf.value.area_id, undefined, 2)}`)

  nf.value.feature_no = undefined
  const res = await send<string[]>('module/index', 'post', {
    module: 'Survey',
    query: {
      "discrete_field_values": [
        {
          "group_name": "Area",
          "vals": [nf.value.area_id]
        }
      ],
    }
  })

  if (res.success) {
    existingFeatureNos.value = res.data.map(x => { return Number(x.substring(1)) })
    // console.log(`existingFeatureNos(): ${existingFeatureNos.value}`)
  } else {
    pushHome(`DB access problem!  Redirected to home page.`)
  }
}

const availableFeatureNos = computed(() => {
  return Array.from(Array(200).keys()).map((v, i) => i + 1).filter(x => { return !existingFeatureNos.value.includes(x) })
})

const id = computed(() => {
  return nf.value.feature_no ? `${nf.value.area_id}${nf.value.feature_no}` : undefined
})

const tag = computed(() => {
  return id.value ? `${tagAndSlugFromId(id.value).tag}` : undefined
})

function accept() {
  console.log(`accept() id: ${id.value}, dataNew:  ${JSON.stringify(nf.value, null, 2)}`)
  nf.value.id = id.value!
  openIdSelectorModal.value = false
}

</script>
