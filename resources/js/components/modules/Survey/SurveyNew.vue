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
              <SurveyIdSelector :defaults="defaultsForIdSelector"></SurveyIdSelector>
            </template>

          </id-selector>
        </v-col>
      </v-row>
    </template>

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

const { tagAndSlugFromId, prepareNewFields } = useModuleStore()
const { fields } = storeToRefs(useItemStore())
const { dataNew, openIdSelectorModal } = storeToRefs(useItemNewStore())

const props = defineProps<{
  isCreate: boolean
}>()

const defaultsAndRules: TFieldsDefaultsAndRules<'Survey'> = {
  id: { d: null, r: { required, maxLength: maxLength(20) } },
  area_id: { d: 'S', r: { required, maxLength: maxLength(1) } },
  feature_no: { d: 0, r: { required, maxLength: maxLength(1) } },
  surveyed_date: { d: null, r: { required, maxLength: maxLength(1) } },
  elevation: { d: 0, r: { required, maxLength: maxLength(1) } },
  next_to: { d: '', r: { required, maxLength: maxLength(1) } },
  description: { d: null, r: { maxLength: maxLength(100) } },
  notes: { d: null, r: { maxLength: maxLength(100) } },
}

const defaultsObj = computed(() => {
  return Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.d]))
})

const rulesObj = computed(() => {
  const fieldsRules = Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.r]))

  return {
    fields: fieldsRules,
    onps: {
      $each: helpers.forEach({
        value: {
          betweenValue: between(1, 999),
        },
      })
    }
  }
})

const nf = computed(() => {
  return dataNew.value.fields as TFields<'Survey'>
})

// setup
console.log(
  `Survey(${props.isCreate ? 'Create' : 'Update'}) fields: ${JSON.stringify(fields.value, null, 2)}`,
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
  const ds = nf.value.id ? nf.value : fields.value as TFields<'Survey'>
  return {
    season: ds.id.substring(0, 1),
    area: ds.id.substring(1, 2),
    locusNo: Number(ds.id.substring(2, 5)),
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
