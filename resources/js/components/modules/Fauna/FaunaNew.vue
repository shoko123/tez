<template>
  <v-container fluid>
    <v-row class="ga-1">
      <template v-if="isCreate">
        <id-selector>
          <template #id-selector-activator>
            <v-btn v-model="nf.id" label="tag" class="bg-grey text-black my-1" @click="openIdSelectorModal = true">{{
              idSelectorTag }}</v-btn>
          </template>

          <template #id-selector-form>
            <FaunaIdSelector :defaults="defaultsForIdSelector"></FaunaIdSelector>
          </template>

        </id-selector>
      </template>
    </v-row>

    <v-row class="ga-1">
      <date-picker v-model="nf.date_retrieved" label="Date Retrieved" color="primary" clearable max-width="368">
      </date-picker>
      <v-text-field v-model="nf.weight" label="Weight" :error-messages="errors.weight" filled />
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-model="nf.field_description" label="Field Description" :error-messages="errors.field_description"
        filled />
      <v-text-field v-model="nf.taxa" label="Taxa" :error-messages="errors.taxa" filled />
      <v-text-field v-model="nf.bone" label="Bone" :error-messages="errors.bone" filled />
    </v-row>

    <template v-if="isArtifact">
      <v-row class="ga-1">
        <v-text-field v-model="nf.d_and_r" label="D&R" :error-messages="errors.d_and_r" filled />
        <v-text-field v-model="nf.age" label="Age" :error-messages="errors.age" filled />
        <v-text-field v-model="nf.breakage" label="Breakage" :error-messages="errors.taxa" filled />
      </v-row>

      <v-row class="ga-1">
        <v-text-field v-model="nf.butchery" label="Butchery" :error-messages="errors.bone" filled />
        <v-text-field v-model="nf.burning" label="Burning" :error-messages="errors.d_and_r" filled />
        <v-text-field v-model="nf.other_bsm" label="Other BSM" :error-messages="errors.age" filled />
      </v-row>

    </template>

    <v-row v-if="isArtifact" class="border-md" dense>
      <v-col v-for="(item, index) in dataNew.allOnps" :key="index" :cols="2">
        <v-text-field v-model="item.value" :label="item.label" :error-messages="onpsErrorMessages[index]" filled>
        </v-text-field>
      </v-col>
    </v-row>
    <v-row class="ga-1">
      <v-text-field v-model="nf.specialist_notes" label="Specialist Notes" :error-messages="errors.specialist_notes"
        filled />
    </v-row>

    <slot :id="nf.id" name="newItem" :v="v$" :new-fields="nf" />

  </v-container>
</template>


<script lang="ts" setup>
import { TFields, TFieldsErrors, TFieldsDefaultsAndRules } from '@/types/moduleTypes'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVuelidate } from '@vuelidate/core'
import { required, between, minLength, maxLength, helpers } from '@vuelidate/validators'
import { useModuleStore } from '../../../scripts/stores/module'
import { useItemStore } from '../../../scripts/stores/item'
import { useItemNewStore } from '../../../scripts/stores/itemNew'
import IdSelector from '../../form-elements/IdSelector.vue'
import FaunaIdSelector from './FaunaIdSelector.vue'
import DatePicker from '../../form-elements/DatePicker.vue'

const { tagAndSlugFromId, prepareNewFields } = useModuleStore()
const { fields, tag } = storeToRefs(useItemStore())
const { prepareOnps } = useItemNewStore()
const { dataNew, openIdSelectorModal, isCreate } = storeToRefs(useItemNewStore())

const defaultsAndRules: TFieldsDefaultsAndRules<'Fauna'> = {
  id: { d: null, r: { required, minValue: minLength(11), maxLength: maxLength(11) } },
  locus_id: { d: '3S001', r: { required, minValue: minLength(5), maxValue: maxLength(5) } },
  code: { d: 'PT', r: { required, minValue: minLength(2), maxLength: maxLength(2) } },
  basket_no: { d: 9, r: { between: between(0, 99) } },
  artifact_no: { d: 99, r: { required, between: between(0, 99) } },
  //
  date_retrieved: { d: null, r: {} },
  weight: { d: null, r: { between: between(1, 2000) } },
  field_description: { d: null, r: { maxLength: maxLength(255) } },
  //
  primary_taxon_id: { d: 1, r: { required, between: between(0, 99) } },
  scope_id: { d: 1, r: { required, between: between(0, 99) } },
  material_id: { d: 1, r: { required, between: between(0, 99) } },
  //
  taxa: { d: null, r: { maxLength: maxLength(400) } },
  bone: { d: null, r: { maxLength: maxLength(400) } },
  symmetry: { d: 'Unassigned', r: {} },
  d_and_r: { d: null, r: { maxLength: maxLength(30) } },
  age: { d: null, r: { maxLength: maxLength(50) } },
  breakage: { d: null, r: { maxLength: maxLength(50) } },
  butchery: { d: null, r: { maxLength: maxLength(100) } },
  burning: { d: null, r: { maxLength: maxLength(100) } },
  weathering: { d: 'Unassigned', r: {} },
  other_bsm: { d: null, r: { maxLength: maxLength(200) } },
  specialist_notes: { d: null, r: { maxLength: maxLength(200) } },
  measured: { d: null, r: {} },
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
  return dataNew.value.fields as TFields<'Fauna'>
})

// setup
console.log(
  `Fauna(${isCreate.value ? 'Create' : 'Update'}) fields: ${JSON.stringify(fields.value, null, 2)}`,
)

prepareOnps()
if (isCreate.value) {
  dataNew.value.fields = { ...defaultsObj.value }
  openIdSelectorModal.value = true
} else {
  dataNew.value.fields = prepareNewFields(fields.value)
}
const v$ = useVuelidate(rulesObj.value, dataNew.value, { $autoDirty: true })
// setup - end

// ID selector related
const defaultsForIdSelector = computed(() => {
  const ds = nf.value.id ? nf.value : fields.value as TFields<'Fauna'>
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


const isArtifact = computed(() => {
  return nf.value.artifact_no !== 0
})

const errors = computed(() => {
  let errorObj: Partial<TFieldsErrors<'Fauna'>> = {}
  for (const key in dataNew.value.fields) {
    const message = v$.value.fields[key].$errors.length > 0 ? v$.value.fields[key].$errors[0].$message : undefined
    errorObj[key as keyof TFieldsErrors<'Fauna'>] = message
  }
  return errorObj
})

const onpsErrorMessages = computed(() => {
  const $msgs = v$.value.allOnps.$each.$message as unknown as string[]
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
