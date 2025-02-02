<template>
  <v-container v-if="nf" fluid>
    <template v-if="props.isCreate">
      <v-row class="ga-1">
        <v-col cols=2>
          <id-selector>
            <template #id-selector-activator>
              <v-btn v-model="dataNew.fields.id" label="tag" class="bg-grey text-black my-1"
                @click="openIdSelectorModal = true">{{ idSelectorTag }}</v-btn>
            </template>

            <template #id-selector-form>
              <LocusIdSelector />
            </template>

          </id-selector>
        </v-col>
      </v-row>
    </template>
    <v-row class="ga-1">
      <v-text-field v-model="nf.square" label="Square" :error-messages="fieldsErrorMessages.square"
        filled></v-text-field>
      <v-text-field v-model="nf.locus_above" label="Locus Above" :error-messages="fieldsErrorMessages.locus_above"
        filled></v-text-field>
      <v-text-field v-model="nf.locus_below" label="Locus Below" :error-messages="fieldsErrorMessages.locus_below"
        filled></v-text-field>
      <v-text-field v-model="nf.locus_co_existing" label="Co-Existing"
        :error-messages="fieldsErrorMessages.locus_co_existing" filled></v-text-field>
    </v-row>

    <v-row class="ga-1">
      <date-picker v-model="nf.date_opened" label="Date Opened" color="primary" clearable max-width="368">
      </date-picker>
      <date-picker v-model="nf.date_closed" label="Date Closed" color="primary" clearable max-width="368">
      </date-picker>
      <v-text-field v-model="nf.level_opened" label="Level Opened" :error-messages="fieldsErrorMessages.level_opened"
        filled></v-text-field>
      <v-text-field v-model="nf.level_closed" label="Level Closed" :error-messages="fieldsErrorMessages.level_closed"
        filled></v-text-field>
      <v-text-field v-model="nf.clean" label="Clean" :error-messages="fieldsErrorMessages.clean" filled></v-text-field>
    </v-row>

    <v-row class="ga-1">
      <v-textarea v-model="nf.description" rows="1" auto-grow label="Description"
        :error-messages="fieldsErrorMessages.description" filled></v-textarea>
      <v-textarea v-model="nf.deposit" rows="1" auto-grow label="Deposit" :error-messages="fieldsErrorMessages.deposit"
        filled></v-textarea>
      <v-textarea v-model="nf.registration_notes" rows="1" auto-grow label="Registration Notes"
        :error-messages="fieldsErrorMessages.registration_notes" filled></v-textarea>
    </v-row>

    <slot :id="nf.id" name="newItem" :v="v$" :new-fields="nf" />

  </v-container>
</template>

<script lang="ts" setup>
import { TFields, TFieldsErrors, TFieldsDefaultsAndRules } from '@/types/moduleTypes'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVuelidate } from '@vuelidate/core'
import { required, between, maxLength } from '@vuelidate/validators'

import { useModuleStore } from '../../../scripts/stores/module'
import { useItemStore } from '../../../scripts/stores/item'
import { useItemNewStore } from '../../../scripts/stores/itemNew'
import IdSelector from '../../form-elements/IdSelector.vue'
import LocusIdSelector from './LocusIdSelector.vue'
import DatePicker from '../../form-elements/DatePicker.vue'

const { tagAndSlugFromId, prepareNewFields } = useModuleStore()
const { fields } = storeToRefs(useItemStore())
const { dataNew, openIdSelectorModal } = storeToRefs(useItemNewStore())

const props = defineProps<{
  isCreate: boolean
}>()

const defaultsAndRules: TFieldsDefaultsAndRules<'Locus'> = {
  id: { d: null, r: { required, maxLength: maxLength(11) } },
  area_id: { d: null, r: { required, maxLength: maxLength(1) } },
  season_id: { d: null, r: { required, maxLength: maxLength(1) } },
  locus_no: { d: undefined, r: { required, between: between(0, 999) } },
  square: { d: null, r: { maxLength: maxLength(20) } },
  date_opened: { d: null, r: {} },
  date_closed: { d: null, r: {} },
  level_opened: { d: null, r: { maxLength: maxLength(20) } },
  level_closed: { d: null, r: { maxLength: maxLength(20) } },
  locus_above: { d: null, r: { maxLength: maxLength(50) } },
  locus_below: { d: null, r: { maxLength: maxLength(50) } },
  locus_co_existing: { d: null, r: { maxLength: maxLength(50) } },
  description: { d: null, r: { maxLength: maxLength(1000) } },
  deposit: { d: null, r: { maxLength: maxLength(500) } },
  registration_notes: { d: null, r: { maxLength: maxLength(500) } },
  clean: { d: true, r: {} },
}

const defaultsObj = computed(() => {
  return Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.d])) as Partial<TFields<'Locus'>>
})

const rulesObj = computed(() => {
  const fieldsRules = Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.r]))

  return {
    fields: fieldsRules,
  }
})

const nf = computed(() => {
  return dataNew.value.fields as TFields<'Locus'>
})

// setup
if (props.isCreate) {
  dataNew.value.fields = { ...defaultsObj.value }
  openIdSelectorModal.value = true
} else {
  dataNew.value.fields = prepareNewFields(fields.value)
}
console.log(`Locus(${props.isCreate ? 'Create' : 'Update'}) dataNew: ${JSON.stringify(dataNew.value, null, 2)}`)
// setup - end

// ID selector related
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


const v$ = useVuelidate(rulesObj.value, dataNew.value, { $autoDirty: true })

const fieldsErrorMessages = computed(() => {
  let errorMessagesObj: Partial<TFieldsErrors<'Locus'>> = {}
  for (const key in dataNew.value.fields) {
    const message = v$.value.fields[key].$errors.length > 0 ? v$.value.fields[key].$errors[0].$message : undefined
    errorMessagesObj[key as keyof TFieldsErrors<'Locus'>] = message
  }
  return errorMessagesObj
})

// Module specific manipulations before upload
function beforeStore() {
  return dataNew.value.fields
}

defineExpose({
  beforeStore
})
</script>
