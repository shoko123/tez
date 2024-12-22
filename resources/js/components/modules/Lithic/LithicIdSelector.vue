<template>
  <v-card class="mx-auto" width="600">
    <v-card-text>
      <v-row class="ga-1">
        <v-select v-model="season" label="Season" :items="seasons" @update:model-value="seasonChanged()">
        </v-select>

        <v-select v-model="area" label="Area" :items="areas" @update:model-value="areaChanged()">
        </v-select>

        <v-select v-model="locusNo" label="Locus No." :items="locusNos" @update:model-value="locusNoChanged()">
        </v-select>

        <v-text-field v-if="locus" v-model="locus" label="Selected Locus"></v-text-field>
      </v-row>

      <v-row v-if="locus" class="ga-1">
        <v-select v-model="code" label="Code" :items="codes" @update:model-value="codeChanged()">
        </v-select>

        <v-select v-model="basketNo" label="Basket No." :items="availableBasketNos"
          @update:model-value="basketNoChanged()">
        </v-select>

        <v-select v-model="artifactNo" label="Artifact No." :items="availableArtifactNos">
        </v-select>
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

import { useItemNewStore } from '../../../scripts/stores/itemNew'
import { useTrioStore } from '../../../scripts/stores/trio/trio'
import { useXhrStore } from '../../../scripts/stores/xhr'
import { useRoutesMainStore } from '../../../scripts/stores/routes/routesMain'
const { pushHome } = useRoutesMainStore()
const { openIdSelectorModal, dataNew } = storeToRefs(useItemNewStore())
const { groupLabelToGroupKeyObj, trio } = storeToRefs(useTrioStore())
const { send } = useXhrStore()

type TCode = 'AR' | 'FL' | null
const props = defineProps<{
  defaults: {
    season: string,
    area: string,
    locusNo: number,
    code: TCode,
    basketNo: number
    artifactNo: number | null
  }
}>()

const season = ref(props.defaults.season)
const area = ref(props.defaults.area)
const locusNos = ref<number[]>([])
const locusNo = ref<number | null>(null)
const code = ref<TCode>(null)
const basketNo = ref<number | null>(null)
const artifactNo = ref<number | null>(null)
const itemsForLocus = ref<string[]>([])

await getExistingLocusNos()
locusNo.value = props.defaults.locusNo
await getExistingItemsForLocus()
code.value = props.defaults.code
basketNo.value = props.defaults.basketNo
artifactNo.value = props.defaults.artifactNo  // null on initial call

async function seasonChanged() {
  await getExistingLocusNos()
  locusNo.value = locusNos.value.length ? props.defaults.locusNo : null
  await getExistingItemsForLocus()
}

async function areaChanged() {
  await getExistingLocusNos()
  locusNo.value = locusNos.value.length ? props.defaults.locusNo : null
  await getExistingItemsForLocus()
  artifactNo.value = null
}

async function locusNoChanged() {
  basketNo.value = 0
  artifactNo.value = null
  await getExistingItemsForLocus()
  artifactNo.value = null
}

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

const locus = computed(() => {
  return locusNo.value === null ? null : `${season.value}/${area.value}/${locusNo.value}`
})

async function getExistingLocusNos() {
  console.log(`getExistingLocusNos() season: ${JSON.stringify(season.value, null, 2)} , area; ${JSON.stringify(area.value, null, 2)}`)
  locusNo.value = null
  artifactNo.value = null
  const res = await send<string[]>('module/index', 'post', {
    module: 'Locus',
    query: {
      "discrete_field_values": [
        {
          "label": "Season",
          "field_name": "season_id",
          "vals": [season.value]
        },
        {
          "label": "Area",
          "field_name": "area_id",
          "vals": [area.value]
        }
      ],
    }
  })

  if (res.success) {
    locusNos.value = res.data.map(x => {
      return Number(x.substring(2))
    })
  } else {
    pushHome(`DB access problem!  Redirected to home page.`)
  }
}

const codes = computed(() => {
  const group = trio.value.groupsObj[groupLabelToGroupKeyObj.value['Registration Code']!]
  return group?.optionKeys.map(k => {
    const option = trio.value.optionsObj[k]!
    return { title: option.text, value: option.extra }
  })
})

async function getExistingItemsForLocus() {
  console.log(`getExistingItemsForLocus() season: ${season.value}, area; ${area.value}, locusNo: ${locusNo.value}`)

  const res = await send<string[]>('module/index', 'post', {
    module: 'Lithic',
    query: {
      "discrete_field_values": [
        {
          "label": "Locus Id",
          "field_name": "locus_id",
          "vals": [`${season.value}${area.value}${String(locusNo.value).padStart(3, '0')}`]
        },
      ],
    }
  })

  if (res.success) {
    itemsForLocus.value = res.data
  } else {
    pushHome(`DB access problem!  Redirected to home page.`)
  }
}

const existingArtifacts = computed(() => {
  if (locus.value === null) {
    return []
  }
  return itemsForLocus.value.map((v) => {
    return {
      code: v.substring(5, 7),
      basketNo: Number(v.substring(7, 9)),
      artifactNo: Number(v.substring(9, 11)),
    }
  }).filter(x => x.code == code.value && x.basketNo == basketNo.value).map(x => x.artifactNo)
})

const availableBasketNos = computed(() => {
  if (locusNo.value === null) {
    return []
  }
  switch (code.value) {
    case 'AR':
      return [0]
    case 'FL':
      return Array.from(Array(100).keys()).map((v, i) => i)
    default:
      return []
  }
})

const availableArtifactNos = computed(() => {
  console.log(`availableArtifactNos() itemsForLocus: ${JSON.stringify(itemsForLocus.value, null, 2)}`)
  console.log(`code: ${code.value}, basketNo: ${basketNo.value}\nexisting: ${JSON.stringify(existingArtifacts.value, null, 2)}`)
  const all = Array.from(Array(100).keys()).map((v, i) => i)
  // return all
  return all.filter((v) => { return !existingArtifacts.value.includes(v) })
})

const selectionIsOk = computed(() => {
  switch (code.value) {
    case 'AR':
      return basketNo.value === 0 && artifactNo.value && artifactNo.value > 0
    case 'FL':
      return basketNo.value && basketNo.value > 0 && artifactNo.value !== null
    default:
      return false
  }
})

const id = computed(() => {
  return artifactNo.value === null ? null : `${season.value}${area.value}${String(locusNo.value).padStart(3, '0')}${code.value}${String(basketNo.value).padStart(2, '0')}${String(artifactNo.value).padStart(2, '0')}`
})
const tag = computed(() => {
  return artifactNo.value === null ? null : `${season.value}/${area.value}/${locusNo.value}.${code.value}.${basketNo.value}.${artifactNo.value}`
})

function basketNoChanged() {
  artifactNo.value = null
}

function codeChanged() {
  artifactNo.value = null
}

const nf = computed(() => {
  return dataNew.value.fields as TFields<'Lithic'>
})

function accept() {
  console.log(`accept() id: ${id.value}, locusId: ${locus.value}, code: ${code.value}, basketNo: ${basketNo.value}, artifactNo: ${artifactNo.value} `)

  nf.value.id = id.value!//`${season.value}${area.value}${String(locusNo.value).padStart(3, '0')}${code.value}${String(basketNo.value).padStart(2, '0')}${String(artifactNo.value).padStart(2, '0')}`
  nf.value.locus_id = id.value!.substring(0, 5)
  nf.value.code = code.value!
  nf.value.basket_no = basketNo.value!
  nf.value.artifact_no = artifactNo.value!
  openIdSelectorModal.value = false

}

</script>
