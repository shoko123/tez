<template>
  <v-container fluid>
    <v-row class="ga-1">
      <template v-if="props.isCreate">
        <id-selector>
          <template #id-selector-activator>
            <v-btn v-model="dataNew.fields.id" label="tag" class="bg-grey text-black my-1"
              @click="openIdSelectorModal = true">{{ idSelectorTag }}</v-btn>
          </template>

          <template #id-selector-form>
            <LithicIdSelector :defaults="defaultsForIdSelector"></LithicIdSelector>
          </template>

        </id-selector>
      </template>
      <template v-else>
        <v-text-field v-model="tag" label="Label" filled disabled />
      </template>
      <date-picker v-model="nf.date_retrieved" label="Date Retrieved" color="primary" clearable max-width="368">
      </date-picker>
    </v-row>

    <v-row class="ga-1">
      <v-textarea v-model="nf.field_description" label="Field Description"
        :error-messages="fieldsErrorMessages.field_description" filled />
      <v-textarea v-model="nf.registration_notes" label="Registration Notes"
        :error-messages="fieldsErrorMessages.registration_notes" filled />
      <v-textarea v-model="nf.specialist_notes" label="Specialist Notes"
        :error-messages="fieldsErrorMessages.specialist_notes" filled />
    </v-row>

    <div>
      {{ isBasket ? 'Type Counts' : 'Choose Type' }}
    </div>
    <v-row v-if="isBasket" class="border-md mb-2" dense>
      <v-col v-for="(item, index) in dataNew.allOnps" :key="index" :cols="2">
        <v-text-field v-model="item.value" :label="item.label" :error-messages="onpsErrorMessages[index]" filled>
        </v-text-field>
      </v-col>
    </v-row>

    <v-row v-if="isArtifact" class="border-md mb-2" dense>
      <v-chip v-for="(item, index) in dataNew.allOnps" :key="index" class="ma-2"
        :color="index === artifactTypeIndex ? 'primary' : ''" @click="optionClicked(index)">
        {{ item.label }}
      </v-chip>
    </v-row>

    <slot :id="nf.id" name="newItem" :v="v$" :new-fields="nf" />

  </v-container>
</template>

<script lang="ts" setup>
import { TFields, TFieldsErrors, TFieldsDefaultsAndRules } from '@/types/moduleTypes'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVuelidate } from '@vuelidate/core'
import { required, helpers, between, minLength, maxLength } from '@vuelidate/validators'

import { useModuleStore } from '../../../scripts/stores/module'
import { useItemStore } from '../../../scripts/stores/item'
import { useItemNewStore } from '../../../scripts/stores/itemNew'
import IdSelector from '../../form-elements/IdSelector.vue'
import LithicIdSelector from './LithicIdSelector.vue'
import DatePicker from '../../form-elements/DatePicker.vue'

const { tagAndSlugFromId, prepareNewFields } = useModuleStore()
const { fields, tag } = storeToRefs(useItemStore())
const { dataNew, openIdSelectorModal } = storeToRefs(useItemNewStore())

const props = defineProps<{
  isCreate: boolean
}>()

const defaultsAndRules: TFieldsDefaultsAndRules<'Lithic'> = {
  id: { d: null, r: { required, maxLength: maxLength(20) } },
  locus_id: { d: '3S001', r: { required, minValue: minLength(5), maxValue: maxLength(5) } },
  code: { d: 'AR', r: { required, maxLength: maxLength(2) } },
  basket_no: { d: 9, r: { between: between(0, 99) } },
  artifact_no: { d: 99, r: { required, between: between(0, 99) } },
  date_retrieved: { d: null, r: {} },
  weight: { d: null, r: {} },
  field_description: { d: null, r: { maxLength: maxLength(100) } },
  registration_notes: { d: null, r: { maxLength: maxLength(100) } },
  specialist_notes: { d: null, r: { maxLength: maxLength(100) } },
}

const defaultsObj = computed(() => {
  return Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.d]))
})

const rulesObj = computed(() => {
  const fieldsRules = Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.r]))

  return {
    fields: fieldsRules,
    allOnps: {
      $each: helpers.forEach({
        value: {
          betweenValue: between(1, 999),
        },
      })
    }
  }
})

const nf = computed(() => {
  return dataNew.value.fields as TFields<'Lithic'>
})

const isBasket = computed(() => {
  return nf.value.artifact_no === 0
})

const isArtifact = computed(() => {
  return nf.value.artifact_no !== 0
})

// setup
console.log(
  `Lithic(${props.isCreate ? 'Create' : 'Update'}) fields: ${JSON.stringify(fields.value, null, 2)}`,
)

if (props.isCreate) {
  dataNew.value.fields = { ...defaultsObj.value }
  openIdSelectorModal.value = true
} else {
  dataNew.value.fields = prepareNewFields(fields.value)
}
// setup - end

// ID selector related
const defaultsForIdSelector = computed(() => {
  const ds = nf.value.id ? nf.value : fields.value as TFields<'Lithic'>
  return {
    season: ds.id.substring(0, 1),
    area: ds.id.substring(1, 2),
    locusNo: Number(ds.id.substring(2, 5)),
    code: ds.code,
    basketNo: ds.basket_no,
    artifactNo: nf.value.id ? ds.artifact_no : null
  }
})

const idSelectorTag = computed(() => {
  if (nf.value.id === null) {
    return `[ID Not Selected]`
  }
  const tg = tagAndSlugFromId(nf.value.id)
  return tg.tag
})
// ID selector related - end

// Lookup fields
// none

// Standard fields validations and errors

// Used only for artifacts
function optionClicked(index: number) {
  console.log(`option Clicked(${index})`)

  // Clear all onps except the newly clicked one
  dataNew.value.allOnps.forEach(e => {
    e.value = null
  })
  dataNew.value.allOnps[index]!.value = 1
}

const artifactTypeIndex = computed(() => {
  const index = dataNew.value.allOnps.findIndex((n) => n.value !== null)
  return index < 0 ? null : index
})

const v$ = useVuelidate(rulesObj.value, dataNew.value, { $autoDirty: true })

const fieldsErrorMessages = computed(() => {
  let errorMessagesObj: Partial<TFieldsErrors<'Lithic'>> = {}
  for (const key in dataNew.value.fields) {
    const message = v$.value.fields[key].$errors.length > 0 ? v$.value.fields[key].$errors[0].$message : undefined
    errorMessagesObj[key as keyof TFieldsErrors<'Lithic'>] = message
  }
  return errorMessagesObj
})

const onpsErrorMessages = computed(() => {
  const $msgs = v$.value.allOnps

    .$each.$message as unknown as string[]
  return $msgs.map(x => {
    return x.length > 0 ? x[0] : undefined
  })
})

// Module specific manipulations before upload
function beforeStore() {
  return dataNew.value.fields
}

defineExpose({
  beforeStore
})
</script>
