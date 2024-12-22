<template>
  <v-container fluid>
    <v-card class="elevation-12">
      <v-card-title class="bg-grey text-black py-0 mb-4">
        {{ title }}
      </v-card-title>
      <v-card-text>
        <component :is="formNew" ref="childRef" :is-create="isCreate">
          <template #newItem="{ v }">
            <v-btn variant="outlined" @click="submit(v)"> Submit </v-btn>
            <v-btn variant="outlined" class="ml-1" @click="cancel"> Cancel </v-btn>
          </template>
        </component>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script lang="ts" setup>
import { computed, type Component, defineAsyncComponent, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { type Validation } from '@vuelidate/core'

import { useRoutesMainStore } from '../../scripts/stores/routes/routesMain'
import { useItemNewStore } from '../../scripts/stores/itemNew'
import { useModuleStore } from '../../scripts/stores/module'
import { useNotificationsStore } from '../../scripts/stores/notifications'

const StoneNew = defineAsyncComponent(() => import('../modules/Stone/StoneNew.vue'))
const CeramicNew = defineAsyncComponent(() => import('../modules/Ceramic/CeramicNew.vue'))
const LithicNew = defineAsyncComponent(() => import('../modules/Lithic/LithicNew.vue'))
let { showSpinner, showSnackbar } = useNotificationsStore()
let { upload } = useItemNewStore()
let { isCreate } = storeToRefs(useItemNewStore())

const { module } = storeToRefs(useModuleStore())
let { routerPush, pushHome } = useRoutesMainStore()

const title = computed(() => {
  return isCreate.value ? 'Create' : 'Update'
})

const formNew = computed<Component>(() => {
  switch (module.value) {
    case 'Ceramic':
      return CeramicNew
    case 'Stone':
      return StoneNew
    case 'Lithic':
      return LithicNew
    default:
      console.log(`Update.vue invalid module ${module.value}`)
      pushHome(`Create/Update is currently not implemented for module "${module.value}""`)
      return CeramicNew // Make editor happy
  }
})


const childRef = ref();

async function submit(v: Validation) {
  //console.log(`CreateUpdate.submit() data: ${JSON.stringify(data, null, 2)}`)

  // vuelidate validation
  await v.$validate()

  if (v.$error || v.$silentErrors.length > 0) {
    showSnackbar('Please correct the marked errors!', 'orange')
    console.log(`validation errors: ${JSON.stringify(v.$errors, null, 2)}`)
    console.log(`validation silent errors: ${JSON.stringify(v.$silentErrors, null, 2)}`)
    return
  }

  // Give module a chance to do some manipulations
  let fieldsToSend = childRef.value.beforeStore();

  // Convert Dates to strings
  fieldsToSend = Object.fromEntries(Object.entries(fieldsToSend).map(([key, value]) => {
    if (value instanceof Date) {
      return [key, value.toISOString().substring(0, 10)]
    } else {
      return [key, value]
    }
  }))

  // Upload
  const creatOrUpdat = isCreate.value ? 'Creat' : 'Updat'

  showSpinner(`${creatOrUpdat}ing ${module.value} Item...`)
  const res = await upload(isCreate.value, fieldsToSend)
  showSpinner(false)

  if (!res.success) {
    showSnackbar(`Failed to ${creatOrUpdat}e Item. ${res.message}`, 'red')
    return
  }

  showSnackbar(
    `${module.value} Item ${creatOrUpdat}ed Successfully!`,
  )

  await routerPush('show', res.slug)
}

const cancel = async () => {
  console.log(`CreateUpdateForm.cancel()`)
  await routerPush('back1')
}
</script>
