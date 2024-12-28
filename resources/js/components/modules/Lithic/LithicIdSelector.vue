<template>
  <v-card class="mx-auto" width="600">
    <v-card-text>
      <v-row class="ga-1">
        <v-select v-model="locusRelated.season" label="Season" :items="trioOptions['Season']"
          @update:model-value="seasonChanged()">
        </v-select>

        <v-select v-model="locusRelated.area" label="Area" :items="trioOptions['Area']"
          @update:model-value="areaChanged()">
        </v-select>

        <v-select v-model="locusRelated.locus_no" label="Locus No." :items="existingLocusNos"
          @update:model-value="locusNoChanged()">
        </v-select>

        <v-text-field v-if="locusIsSelected" v-model="locusTag" label="Selected Locus"></v-text-field>
      </v-row>

      <v-row v-if="locusIsSelected" class="ga-1">
        <v-select v-model="nf.code" label="Code" :items="trioOptions['Registration Code']"
          @update:model-value="codeChanged()">
        </v-select>

        <v-select v-model="nf.basket_no" label="Basket No." :items="availableBasketNos"
          @update:model-value="basketNoChanged()">
        </v-select>

        <v-select v-model="nf.artifact_no" label="Artifact No." :items="availableArtifactNos"
          @update:model-value="artifactNoChanged()">
        </v-select>
      </v-row>

      <v-row v-if="idIsSelected" class="ga-1">
        <v-btn @click="accept()">Accept new item: "{{ tag }}"</v-btn>
      </v-row>

    </v-card-text>
  </v-card>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import type { TFields, TFieldValue } from '@/types/moduleTypes'

import { useModuleStore } from '../../../scripts/stores/module'
import { useItemNewStore } from '../../../scripts/stores/itemNew'
import { useTrioStore } from '../../../scripts/stores/trio/trio'
import { useXhrStore } from '../../../scripts/stores/xhr'
import { useRoutesMainStore } from '../../../scripts/stores/routes/routesMain'

const { tagAndSlugFromId } = useModuleStore()
const { pushHome } = useRoutesMainStore()
const { openIdSelectorModal, dataNew } = storeToRefs(useItemNewStore())
const { groupLabelToGroupKeyObj, trio } = storeToRefs(useTrioStore())
const { send } = useXhrStore()

const nf = computed(() => {
  return dataNew.value.fields as Partial<TFields<'Lithic'>>
})

const locusRelated = ref<{
  season: string | null,
  area: string | null,
  locus_no: number | null
}>({
  season: null,
  area: null,
  locus_no: 0
})

const existingLocusNos = ref<number[]>([])
const itemsForLocus = ref<{
  code: string,
  basketNo: number,
  artifactNo: number
}[]>([])

// setup - start
parseLocusId()
await getExistingLocusNos()
await getExistingItemsForLocus()
// setup - finish


const trioOptions = computed(() => {
  const groups = ['Season', 'Area', 'Registration Code']
  let res = {} as Record<string, { title: string, value: TFieldValue }[]>
  groups.forEach(x => {
    const group = trio.value.groupsObj[groupLabelToGroupKeyObj.value[x]!]
    res[x] = group ? group.optionKeys.map(k => {
      const option = trio.value.optionsObj[k]!
      return { title: option.text, value: option.extra }
    }) : []
  })
  return res
})

function parseLocusId() {
  if (nf.value.locus_id) {
    locusRelated.value.season = nf.value.locus_id.substring(0, 1)
    locusRelated.value.area = nf.value.locus_id.substring(1, 2)
    locusRelated.value.locus_no = Number(nf.value.locus_id.substring(2, 5))
  }
}

async function seasonChanged() {
  await getExistingLocusNos()
  resetLocus()
}

async function areaChanged() {
  await getExistingLocusNos()
  resetLocus()
}

async function resetLocus() {
  locusRelated.value.locus_no = null
  nf.value.locus_id = undefined
  nf.value.basket_no = undefined
  nf.value.artifact_no = undefined
}

async function locusNoChanged() {
  nf.value.locus_id = `${locusRelated.value.season}${locusRelated.value.area}${String(locusRelated.value.locus_no).padStart(3, '0')}`
  nf.value.basket_no = undefined
  nf.value.artifact_no = undefined
  await getExistingItemsForLocus()
}

const locusIsSelected = computed(() => {
  return !(locusRelated.value.locus_no == null)
})

const locusTag = computed(() => {
  return locusIsSelected.value ? `${tagAndSlugFromId(nf.value.locus_id!, 'Locus').tag}` : ``
})

async function getExistingLocusNos() {
  console.log(`getExistingLocusNos() season: ${locusRelated.value.season} , area; ${locusRelated.value.area}`)
  locusRelated.value.locus_no = null
  nf.value.artifact_no = undefined
  const res = await send<string[]>('module/index', 'post', {
    module: 'Locus',
    query: {
      "discrete_field_values": [
        {
          "label": "Season",
          "field_name": "season_id",
          "vals": [locusRelated.value.season]
        },
        {
          "label": "Area",
          "field_name": "area_id",
          "vals": [locusRelated.value.area]
        }
      ],
    }
  })

  if (res.success) {
    existingLocusNos.value = res.data.map(x => {
      return Number(x.substring(2))
    })
    console.log(`existingLocusNos(): [${existingLocusNos.value}]`)
  } else {
    pushHome(`DB access problem!  Redirected to home page.`)
  }
}

async function getExistingItemsForLocus() {
  console.log(`getExistingItemsForLocus() season: ${locusRelated.value.season}, area; ${locusRelated.value.area}, locusNo: ${locusRelated.value.locus_no}`)

  const res = await send<string[]>('module/index', 'post', {
    module: 'Lithic',
    query: {
      "discrete_field_values": [
        {
          "label": "Locus Id",
          "field_name": "locus_id",
          "vals": [`${nf.value.locus_id}`]
        },
      ],
    }
  })

  if (res.success) {
    itemsForLocus.value = res.data.map((v) => {
      return {
        code: v.substring(5, 7),
        basketNo: Number(v.substring(7, 9)),
        artifactNo: Number(v.substring(9, 11)),
      }
    })
  } else {
    pushHome(`DB access problem!  Redirected to home page.`)
  }
}

const existingArtifactNos = computed(() => {
  if (nf.value.basket_no == null) { return [] }
  return itemsForLocus.value.filter(x => x.code === nf.value.code && x.basketNo === nf.value.basket_no).map(x => x.artifactNo)
})

const availableBasketNos = computed(() => {
  if (!locusIsSelected.value) { return [] }
  switch (nf.value.code) {
    case 'AR':
      return [0]
    case 'FL':
      return Array.from(Array(100).keys()).map((v, i) => i + 1)
    default:
      return []
  }
})

const availableArtifactNos = computed(() => {
  if (nf.value.basket_no == null) { return [] }
  let all: number[] = []
  switch (nf.value.code) {
    case 'AR':
      all = Array.from(Array(99).keys()).map((v, i) => i + 1)
      break
    case 'FL':
      all = Array.from(Array(100).keys()).map((v, i) => i)
      break
    default:
    //
  }
  return all.filter((v) => { return !existingArtifactNos.value.includes(v) })
})

const idIsSelected = computed(() => {
  switch (nf.value.code) {
    case 'AR':
      return nf.value.basket_no === 0 && nf.value.artifact_no && nf.value.artifact_no > 0
    case 'FL':
      return nf.value.basket_no && nf.value.basket_no > 0 && !(nf.value.artifact_no == null)
    default:
      return false
  }
})

const tag = computed(() => {
  return nf.value.id == null ? null : tagAndSlugFromId(nf.value.id!, 'Lithic').tag
})

function codeChanged() {
  nf.value.basket_no = undefined
  nf.value.artifact_no = undefined
}

function basketNoChanged() {
  nf.value.artifact_no = undefined
}

function artifactNoChanged() {
  nf.value.id = `${nf.value.locus_id}${nf.value.code}${String(nf.value.basket_no).padStart(2, '0')}${String(nf.value.artifact_no).padStart(2, '0')}`
  console.log(`artifactNoChanged. id: ${nf.value.id}`)
}

function accept() {
  console.log(`accept() nf:  ${JSON.stringify(nf.value, null, 2)}`)
  openIdSelectorModal.value = false
}

</script>
