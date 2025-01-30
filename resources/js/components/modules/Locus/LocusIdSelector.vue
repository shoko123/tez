<template>
  <v-card class="mx-auto" width="600">
    <v-card-text>

      <v-row class="ga-1">
        <v-select v-model="nf.season_id" label="Season" :items="seasons" @update:model-value="seasonChanged()">
        </v-select>
        <v-select v-model="nf.area_id" label="Area" :items="areas" @update:model-value="areaChanged()"> </v-select>
        <v-select v-model="nf.locus_no" label="Locus Number" :items="availableLocusNos"> </v-select>
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
import { useItemStore } from '../../../scripts/stores/item'

import { useItemNewStore } from '../../../scripts/stores/itemNew'
import { useTrioStore } from '../../../scripts/stores/trio/trio'
import { useXhrStore } from '../../../scripts/stores/xhr'
import { useRoutesMainStore } from '../../../scripts/stores/routes/routesMain'

const { pushHome } = useRoutesMainStore()
const { tagAndSlugFromId } = useModuleStore()
const { fields } = storeToRefs(useItemStore())
const { openIdSelectorModal, dataNew } = storeToRefs(useItemNewStore())
const { groupLabelToGroupKeyObj, trio } = storeToRefs(useTrioStore())
const { send } = useXhrStore()

const nf = computed(() => {
  return dataNew.value.fields as Partial<TFields<'Locus'>>
})

const currentItemFields = computed(() => {
  return fields.value as Partial<TFields<'Locus'>>
})

// setup - start
const existingLocusNos = ref<number[]>([])
nf.value.season_id = currentItemFields.value.season_id
nf.value.area_id = currentItemFields.value.area_id
await getExistingLociNos()
// setup - end


const seasons = computed(() => {
  const group = trio.value.groupsObj[groupLabelToGroupKeyObj.value['Season']!]
  return group?.optionKeys.map(k => {
    const option = trio.value.optionsObj[k]!
    return { title: option.text, value: option.extra }
  })
})

const areas = computed(() => {
  const group = trio.value.groupsObj[groupLabelToGroupKeyObj.value['Area']!]
  return group?.optionKeys.map(k => {
    const option = trio.value.optionsObj[k]!
    return { title: option.text, value: option.extra }
  })
})

async function seasonChanged() {
  await getExistingLociNos()
}

async function areaChanged() {
  await getExistingLociNos()
}

async function getExistingLociNos() {
  if (!nf.value.area_id) {
    return []
  }
  console.log(`getExistingLociNos() area_id; ${JSON.stringify(nf.value.area_id, undefined, 2)}`)

  nf.value.locus_no = undefined
  const res = await send<string[]>('module/index', 'post', {
    module: 'Locus',
    query: {
      "discrete_field_values": [
        {
          "group_name": "Area",
          "vals": [nf.value.area_id]
        },
        {
          "group_name": "Season",
          "vals": [nf.value.season_id]
        }
      ],
    }
  })

  if (res.success) {
    existingLocusNos.value = res.data.map(x => { return Number(x.substring(2)) })
    console.log(`existingLocusNos(): ${existingLocusNos.value}`)
  } else {
    pushHome(`DB access problem!  Redirected to home page.`)
  }
}

const availableLocusNos = computed(() => {
  return Array.from(Array(200).keys()).map((v, i) => i + 1).filter(x => { return !existingLocusNos.value.includes(x) })
})

const id = computed(() => {
  return nf.value.locus_no ? `${nf.value.season_id}${nf.value.area_id}${String(nf.value.locus_no).padStart(3, '0')}` : undefined
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
