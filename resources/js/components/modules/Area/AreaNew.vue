<template>
  <v-container fluid>
    <v-row class="ga-1">
      <v-textarea v-model="nf.description" label="Square" :error-messages="errors.description" filled />
      <v-textarea v-model="nf.notes" label="Square" :error-messages="errors.notes" filled />
    </v-row>

    <slot :id="nf.id" name="newItem" :v="v$" :new-fields="nf" />

  </v-container>
</template>

<script lang="ts" setup>
import { TFields, TFieldsErrors, TFieldsDefaultsAndRules } from '@/types/moduleTypes'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength } from '@vuelidate/validators'
import { useModuleStore } from '../../../scripts/stores/module'
import { useItemStore } from '../../../scripts/stores/item'
import { useItemNewStore } from '../../../scripts/stores/itemNew'

const { prepareNewFields } = useModuleStore()

const defaultsAndRules: TFieldsDefaultsAndRules<'Area'> = {
  id: { d: null, r: { required, maxLength: maxLength(1) } },
  description: { d: null, r: { maxLength: maxLength(2000) } },
  notes: { d: null, r: { maxLength: maxLength(2000) } },
}

const rulesObj = computed(() => {
  return Object.fromEntries(Object.entries(defaultsAndRules).map(([k, v]) => [k, v.r]))
})

const { fields } = storeToRefs(useItemStore())
const { dataNew } = storeToRefs(useItemNewStore())

// setup
dataNew.value.fields = prepareNewFields(fields.value)
console.log(`Area(Update) dataNew: ${JSON.stringify(dataNew.value, null, 2)}`)
// setup - end

// Standard fields validations and errors
const nf = computed(() => {
  return dataNew.value.fields as TFields<'Area'>
})

const v$ = useVuelidate(rulesObj.value, dataNew.value.fields, { $autoDirty: true })

const errors = computed(() => {
  let errorObj: Partial<TFieldsErrors<'Area'>> = {}
  for (const key in dataNew.value.fields) {
    const message = v$.value[key].$errors.length > 0 ? v$.value[key].$errors[0].$message : undefined
    errorObj[key as keyof TFieldsErrors<'Area'>] = message
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
