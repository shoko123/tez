<template>
  <v-container fluid>
    <v-row class="ga-1">
      <template v-if="props.isCreate">
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
      <template v-else>
        <v-text-field v-model="tag" label="Label" filled disabled />
      </template>
      <date-picker v-model="nf.date_retrieved" label="Date Retrieved" color="primary" clearable max-width="368">
      </date-picker>
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-model="nf.field_description" label="Field Description" :error-messages="errors.field_description"
        filled />
      <v-text-field v-model="nf.specialist_notes" label="Specialist Notes" :error-messages="errors.specialist_notes"
        filled />
    </v-row>

    <!-- <v-row v-if="props.isCreate" class="ga-1">
      <v-select v-model="nf.primary_taxon_id" label="Select" item-title="text" item-value="extra"
        :items="taxonInfo.options"></v-select>

      <v-select v-model="nf.scope_id" label="Select" item-title="text" item-value="extra"
        :items="scopeInfo.options"></v-select>

      <v-select v-model="nf.material_id" label="Select" item-title="text" item-value="extra"
        :items="materialInfo.options"></v-select>
    </v-row> -->

    <v-row v-if="isArtifact" class="border-md mb-2" dense>
      <v-col v-for="(item, index) in dataNew.allOnps" :key="index" :cols="2">
        <v-text-field v-model="item.value" :label="item.label" :error-messages="onpsErrorMessages[index]" filled>
        </v-text-field>
      </v-col>
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

const props = defineProps<{
  isCreate: boolean
}>()

const defaultsAndRules: TFieldsDefaultsAndRules<'Fauna'> = {
  id: { d: null, r: { required, maxLength: maxLength(20) } },
  locus_id: { d: '3S001', r: { required, minValue: minLength(5), maxValue: maxLength(5) } },
  code: { d: 'PT', r: { required, maxLength: maxLength(2) } },
  basket_no: { d: 9, r: { between: between(0, 99) } },
  artifact_no: { d: 99, r: { required, between: between(0, 99) } },
  date_retrieved: { d: null, r: {} },
  weight: { d: null, r: {} },
  field_description: { d: null, r: {} },
  primary_taxon_id: { d: 99, r: { required, between: between(0, 99) } },
  scope_id: { d: 99, r: { required, between: between(0, 99) } },
  material_id: { d: 99, r: { required, between: between(0, 99) } },
  taxa: { d: null, r: { maxLength: maxLength(200) } },
  bone: { d: null, r: { maxLength: maxLength(200) } },
  symmetry: { d: null, r: { maxLength: maxLength(4) } },
  d_and_r: { d: null, r: { maxLength: maxLength(400) } },
  age: { d: null, r: { maxLength: maxLength(400) } },
  breakage: { d: null, r: { maxLength: maxLength(400) } },
  butchery: { d: null, r: { maxLength: maxLength(400) } },
  burning: { d: null, r: { maxLength: maxLength(400) } },
  weathering: { d: null, r: {} },
  other_bsm: { d: null, r: { maxLength: maxLength(400) } },
  specialist_notes: { d: null, r: { maxLength: maxLength(400) } },
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

const { fields, tag } = storeToRefs(useItemStore())
const { dataNew, openIdSelectorModal } = storeToRefs(useItemNewStore())

// setup
console.log(
  `Fauna(${props.isCreate ? 'Create' : 'Update'}) fields: ${JSON.stringify(fields.value, null, 2)}`,
)
// prepareOnps()
if (props.isCreate) {
  dataNew.value.fields = { ...defaultsObj.value }
  openIdSelectorModal.value = true
} else {
  dataNew.value.fields = prepareNewFields(fields.value)
}
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

// Lookup fields
// const FaunaFieldsWithOptions = computed(() => {
//   return fieldsWithOptions.value as Partial<Record<keyof TFields<'Fauna'>, TFieldInfo>>
// })

// const taxonInfo = computed(() => {
//   return FaunaFieldsWithOptions.value['primary_taxon_id']!
// })
// const scopeInfo = computed(() => {
//   return FaunaFieldsWithOptions.value['scope_id']!
// })

// const materialInfo = computed(() => {
//   return FaunaFieldsWithOptions.value['material_id']!
// })

// Standard fields validations and errors
const nf = computed(() => {
  return dataNew.value.fields as TFields<'Fauna'>
})

const isArtifact = computed(() => {
  return nf.value.artifact_no !== 0
})

const v$ = useVuelidate(rulesObj.value, dataNew.value, { $autoDirty: true })

const errors = computed(() => {
  let errorObj: Partial<TFieldsErrors<'Fauna'>> = {}
  for (const key in dataNew.value.fields) {
    const message = v$.value.fields[key].$errors.length > 0 ? v$.value[key].$errors[0].$message : undefined
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
