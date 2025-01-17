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
      <!-- <date-picker v-model="nf.date_retrieved" label="Date Retrieved" color="primary" clearable max-width="368">
      </date-picker> -->
    </v-row>

    <v-row class="ga-1">
      <!-- <v-text-field v-model="nf.field_description" label="Field Description" :error-messages="errors.field_description"
        filled />
      <v-text-field v-model="nf.field_notes" label="Field Notes" :error-messages="errors.field_notes" filled /> -->
    </v-row>

    <v-row class="ga-1">
      <v-select v-model="nf.primary_taxon_id" label="Select" item-title="text" item-value="extra"
        :items="taxonInfo.options"></v-select>

      <!-- <v-select v-model="nf.fauna_element_id" label="Select" item-title="text" item-value="extra"
        :items="elementInfo.options"></v-select> -->
    </v-row>
    <v-row class="ga-1">
      <!-- <v-textarea v-model="nf.field_description" label="Specialist Description"
        :error-messages="errors.field_description" filled /> -->
    </v-row>

    <slot :id="nf.id" name="newItem" :v="v$" :new-fields="nf" />

  </v-container>
</template>

<script lang="ts" setup>
import { TFields, TFieldsErrors, TFieldInfo, TFieldsDefaultsAndRules } from '@/types/moduleTypes'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVuelidate } from '@vuelidate/core'
import { required, between, minLength, maxLength } from '@vuelidate/validators'
import { useModuleStore } from '../../../scripts/stores/module'
import { useItemStore } from '../../../scripts/stores/item'
import { useItemNewStore } from '../../../scripts/stores/itemNew'
import IdSelector from '../../form-elements/IdSelector.vue'
import FaunaIdSelector from './FaunaIdSelector.vue'
// import DatePicker from '../../form-elements/DatePicker.vue'

const { tagAndSlugFromId, prepareNewFields } = useModuleStore()

const props = defineProps<{
  isCreate: boolean
}>()

/*
 $table->string('taxa', 400)->nullable();
            $table->string('bone', 400)->nullable();
            $table->string('side', 10)->nullable();
            $table->string('d_and_r', 30)->nullable();
            $table->string('age', 50)->nullable();
            $table->string('breakage', 50)->nullable();
            $table->string('butchery', 100)->nullable();
            $table->string('burning', 400)->nullable();
            $table->string('weathering', 50)->nullable();
            $table->string('other_bsm', 200)->nullable();
            $table->string('notes', 200)->nullable();
            $table->string('measured', 200)->nullable();

*/


const defaultsAndRules: TFieldsDefaultsAndRules<'Fauna'> = {
  id: { d: null, r: { required, maxLength: maxLength(20) } },
  locus_id: { d: '3S001', r: { required, minValue: minLength(5), maxValue: maxLength(5) } },
  code: { d: 'PT', r: { required, maxLength: maxLength(2) } },
  basket_no: { d: 9, r: { between: between(0, 99) } },
  artifact_no: { d: 99, r: { required, between: between(0, 99) } },
  // date_retrieved: { d: null, r: {} },
  taxa: { d: null, r: { maxLength: maxLength(200) } },
  bone: { d: null, r: { maxLength: maxLength(200) } },
  side: { d: null, r: { maxLength: maxLength(4) } },
  d_and_r: { d: null, r: { maxLength: maxLength(400) } },
  age: { d: null, r: { maxLength: maxLength(400) } },
  breakage: { d: null, r: { maxLength: maxLength(400) } },
  butchery: { d: null, r: { maxLength: maxLength(400) } },
  burning: { d: null, r: { maxLength: maxLength(400) } },
  weathering: { d: null, r: {} },
  other_bsm: { d: null, r: { maxLength: maxLength(400) } },
  notes: { d: null, r: { maxLength: maxLength(400) } },
  measured: { d: null, r: {} },
  //
  // GL: { d: null, r: { maxLength: maxLength(400) } },
  // Glpe: { d: null, r: { maxLength: maxLength(400) } },
  // GLl: { d: null, r: { maxLength: maxLength(400) } },
  // GLP: { d: null, r: { maxLength: maxLength(400) } },
  // Bd: { d: null, r: { maxLength: maxLength(400) } },
  // BT: { d: null, r: { maxLength: maxLength(400) } },
  // Dd: { d: null, r: { maxLength: maxLength(400) } },
  // BFd: { d: null, r: { maxLength: maxLength(400) } },
  // Bp: { d: null, r: { maxLength: maxLength(400) } },
  // Dp: { d: null, r: { maxLength: maxLength(400) } },
  // SD: { d: null, r: { maxLength: maxLength(400) } },
  // HTC: { d: null, r: { maxLength: maxLength(400) } },
  // Dl: { d: null, r: { maxLength: maxLength(400) } },
  // DEM: { d: null, r: { maxLength: maxLength(400) } },
  // DVM: { d: null, r: { maxLength: maxLength(400) } },
  // WCM: { d: null, r: { maxLength: maxLength(400) } },
  // DEL: { d: null, r: { maxLength: maxLength(400) } },
  // DVL: { d: null, r: { maxLength: maxLength(400) } },
  // WCL: { d: null, r: { maxLength: maxLength(400) } },
  // LD: { d: null, r: { maxLength: maxLength(400) } },
  // DLS: { d: null, r: { maxLength: maxLength(400) } },
  // LG: { d: null, r: { maxLength: maxLength(400) } },
  // BG: { d: null, r: { maxLength: maxLength(400) } },
  // DID: { d: null, r: { maxLength: maxLength(400) } },
  // BFcr: { d: null, r: { maxLength: maxLength(400) } },
  // GD: { d: null, r: { maxLength: maxLength(400) } },
  // GB: { d: null, r: { maxLength: maxLength(400) } },
  // BF: { d: null, r: { maxLength: maxLength(400) } },
  // LF: { d: null, r: { maxLength: maxLength(400) } },
  // GLm: { d: null, r: { maxLength: maxLength(400) } },
  // GH: { d: null, r: { maxLength: maxLength(400) } },
  // fauna_element_id: { d: 99, r: { required, between: between(0, 99) } },
  primary_taxon_id: { d: 99, r: { required, between: between(0, 99) } },
}

const defaultsObj = computed(() => {
  return Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.d]))
})

const rulesObj = computed(() => {
  return Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.r]))
})

const { fields, tag } = storeToRefs(useItemStore())
const { dataNew, openIdSelectorModal, fieldsWithOptions } = storeToRefs(useItemNewStore())

// setup
console.log(
  `Fauna(${props.isCreate ? 'Create' : 'Update'}) fields: ${JSON.stringify(fields.value, null, 2)}`,
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
const FaunaFieldsWithOptions = computed(() => {
  return fieldsWithOptions.value as Partial<Record<keyof TFields<'Fauna'>, TFieldInfo>>
})

const taxonInfo = computed(() => {
  return FaunaFieldsWithOptions.value['primary_taxon_id']!
})
// const elementInfo = computed(() => {
//   return FaunaFieldsWithOptions.value['fauna_element_id']!
// })
// Standard fields validations and errors
const nf = computed(() => {
  return dataNew.value.fields as TFields<'Fauna'>
})

const v$ = useVuelidate(rulesObj.value, dataNew.value.fields, { $autoDirty: true })

const errors = computed(() => {
  let errorObj: Partial<TFieldsErrors<'Fauna'>> = {}
  for (const key in dataNew.value.fields) {
    const message = v$.value[key].$errors.length > 0 ? v$.value[key].$errors[0].$message : undefined
    errorObj[key as keyof TFieldsErrors<'Fauna'>] = message
  }
  return errorObj
})

// Module specific manipulations before upload
function beforeStore() {
  return dataNew.value.fields
}

defineExpose({
  beforeStore
})
</script>
