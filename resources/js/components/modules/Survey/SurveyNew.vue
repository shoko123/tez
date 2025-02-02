<template>
  <v-container fluid>
    <template v-if="props.isCreate">
      <v-row class="ga-1">
        <v-col cols=2>
          <id-selector>
            <template #id-selector-activator>
              <v-btn v-model="dataNew.fields.id" label="tag" class="bg-grey text-black my-1"
                @click="openIdSelectorModal = true">{{ idSelectorTag }}</v-btn>
            </template>

            <template #id-selector-form>
              <SurveyIdSelector />
            </template>

          </id-selector>
        </v-col>
      </v-row>
    </template>
    <v-row class="ga-1">
      <date-picker v-model="nf.surveyed_date" label="Survey Date" color="primary" clearable max-width="368">
      </date-picker>
      <v-text-field v-model="nf.elevation" label="Elevation" :error-messages="fieldsErrorMessages.elevation" filled />
      <v-text-field v-model="nf.next_to" label="Next To" :error-messages="fieldsErrorMessages.next_to" filled />
    </v-row>

    <v-row class="ga-1">
      <v-textarea v-model="nf.description" label="Description" :error-messages="fieldsErrorMessages.description"
        filled />
      <v-textarea v-model="nf.notes" label="Notes" :error-messages="fieldsErrorMessages.notes" filled />
    </v-row>

    <slot :id="nf.id" name="newItem" :v="v$" :new-fields="nf" />

  </v-container>
</template>

<script lang="ts" setup>
import { TFields, TFieldsErrors, TFieldsDefaultsAndRules } from '@/types/moduleTypes'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVuelidate } from '@vuelidate/core'
import { required, helpers, between, maxLength } from '@vuelidate/validators'

import { useModuleStore } from '../../../scripts/stores/module'
import { useItemStore } from '../../../scripts/stores/item'
import { useItemNewStore } from '../../../scripts/stores/itemNew'
import IdSelector from '../../form-elements/IdSelector.vue'
import SurveyIdSelector from './SurveyIdSelector.vue'
import DatePicker from '../../form-elements/DatePicker.vue'

const { tagAndSlugFromId, prepareNewFields } = useModuleStore()
const { fields } = storeToRefs(useItemStore())
const { dataNew, openIdSelectorModal } = storeToRefs(useItemNewStore())

const props = defineProps<{
  isCreate: boolean
}>()

const defaultsAndRules: TFieldsDefaultsAndRules<'Survey'> = {
  id: { d: undefined, r: { required, maxLength: maxLength(4) } },
  area_id: { d: 'S', r: { required, maxLength: maxLength(1) } },
  feature_no: { d: undefined, r: { required, between: between(1, 200) } },
  surveyed_date: { d: null, r: {} },
  elevation: { d: null, r: { between: between(1, 200) } },
  next_to: { d: null, r: { maxLength: maxLength(50) } },
  description: { d: null, r: { maxLength: maxLength(200) } },
  notes: { d: null, r: { maxLength: maxLength(100) } },
}

const defaultsObj = computed(() => {
  return Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.d]))
})

const rulesObj = computed(() => {
  const fieldsRules = Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.r]))

  return {
    fields: fieldsRules,
  }
})

const nf = computed(() => {
  return dataNew.value.fields as TFields<'Survey'>
})

// setup
if (props.isCreate) {
  dataNew.value.fields = { ...defaultsObj.value }
  openIdSelectorModal.value = true
} else {
  dataNew.value.fields = prepareNewFields(fields.value)
}
console.log(`Survey(${props.isCreate ? 'Create' : 'Update'}) dataNew: ${JSON.stringify(dataNew.value, null, 2)}`)
// setup - end

const idSelectorTag = computed(() => {
  if (nf.value.id === undefined) {
    return `[ID Not Selected]`
  }
  const tg = tagAndSlugFromId(nf.value.id)
  return tg.tag
})

const v$ = useVuelidate(rulesObj.value, dataNew.value, { $autoDirty: true })

const fieldsErrorMessages = computed(() => {
  let errorMessagesObj: Partial<TFieldsErrors<'Survey'>> = {}
  for (const key in dataNew.value.fields) {
    const message = v$.value.fields[key].$errors.length > 0 ? v$.value.fields[key].$errors[0].$message : undefined
    errorMessagesObj[key as keyof TFieldsErrors<'Survey'>] = message
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
