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
              <LocusIdSelector :defaults="defaultsForIdSelector"></LocusIdSelector>
            </template>

          </id-selector>
        </v-col>
      </v-row>
    </template>
    <v-row class="ga-1">
      <v-text-field v-model="nf.square" label="Square" :error-messages="fieldsErrorMessages.square"
        filled></v-text-field>
      <v-text-field v-model="nf.locus_above" readonly label="Locus Above"
        :error-messages="fieldsErrorMessages.locus_above" filled></v-text-field>
      <v-text-field v-model="nf.locus_below" readonly label="Locus Below"
        :error-messages="fieldsErrorMessages.locus_below" filled></v-text-field>
      <v-text-field v-model="nf.locus_co_existing" readonly label="Co-Existing"
        :error-messages="fieldsErrorMessages.locus_co_existing" filled></v-text-field>
    </v-row>

    <v-row class="ga-1">
      <v-text-field v-model="nf.date_opened" readonly label="Date Opened"
        :error-messages="fieldsErrorMessages.date_opened" filled></v-text-field>
      <v-text-field v-model="nf.date_closed" readonly label="Date Closed"
        :error-messages="fieldsErrorMessages.date_closed" filled></v-text-field>
      <v-text-field v-model="nf.level_opened" readonly label="Level Opened"
        :error-messages="fieldsErrorMessages.level_opened" filled></v-text-field>
      <v-text-field v-model="nf.level_closed" readonly label="Level Closed"
        :error-messages="fieldsErrorMessages.level_closed" filled></v-text-field>
      <v-text-field v-model="nf.clean" readonly label="Clean" :error-messages="fieldsErrorMessages.clean"
        filled></v-text-field>
    </v-row>

    <v-row class="ga-1">
      <v-textarea v-model="nf.description" rows="1" auto-grow readonly label="Description"
        :error-messages="fieldsErrorMessages.description" filled></v-textarea>
      <v-textarea v-model="nf.deposit" rows="1" auto-grow readonly label="Deposit"
        :error-messages="fieldsErrorMessages.deposit" filled></v-textarea>
      <v-textarea v-model="nf.registration_notes" rows="1" auto-grow readonly label="Registration Notes"
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
import { required, helpers, between, maxLength } from '@vuelidate/validators'

import { useModuleStore } from '../../../scripts/stores/module'
import { useItemStore } from '../../../scripts/stores/item'
import { useItemNewStore } from '../../../scripts/stores/itemNew'
import IdSelector from '../../form-elements/IdSelector.vue'
import LocusIdSelector from './LocusIdSelector.vue'

const { tagAndSlugFromId, prepareNewFields } = useModuleStore()
const { fields } = storeToRefs(useItemStore())
const { dataNew, openIdSelectorModal } = storeToRefs(useItemNewStore())

const props = defineProps<{
  isCreate: boolean
}>()

const defaultsAndRules: TFieldsDefaultsAndRules<'Locus'> = {
  id: { d: null, r: { required, maxLength: maxLength(11) } },
  area_id: { d: 'S', r: { required, maxLength: maxLength(1) } },
  season_id: { d: '5', r: { required, maxLength: maxLength(1) } },
  locus_no: { d: 0, r: { required, maxLength: maxLength(1) } },
  square: { d: 'S', r: { required, maxLength: maxLength(1) } },
  date_opened: { d: 'S', r: { required, maxLength: maxLength(1) } },
  date_closed: { d: 'S', r: { required, maxLength: maxLength(1) } },
  level_opened: { d: 'S', r: { required, maxLength: maxLength(1) } },
  level_closed: { d: 'S', r: { required, maxLength: maxLength(1) } },
  locus_above: { d: 'S', r: { required, maxLength: maxLength(1) } },
  locus_below: { d: 'S', r: { required, maxLength: maxLength(1) } },
  locus_co_existing: { d: 'S', r: { required, maxLength: maxLength(1) } },
  description: { d: 'S', r: { required, maxLength: maxLength(1) } },
  deposit: { d: 'S', r: { required, maxLength: maxLength(1) } },
  registration_notes: { d: 'S', r: { required, maxLength: maxLength(1) } },
  clean: { d: true, r: {} },
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
  return dataNew.value.fields as TFields<'Locus'>
})

// setup
console.log(
  `Locus(${props.isCreate ? 'Create' : 'Update'}) fields: ${JSON.stringify(fields.value, null, 2)}`,
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
  const ds = nf.value.id ? nf.value : fields.value as TFields<'Locus'>
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
