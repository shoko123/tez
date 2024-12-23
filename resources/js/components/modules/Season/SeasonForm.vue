<template>
  <v-container v-if="item" fluid class="pa-0 ma-0">
    <v-row>
      <v-textarea v-model="item.description" label="Description" class="mr-1" rows="1" auto-grow readonly
        filled></v-textarea>
      <v-textarea v-model="item.staff" label="Staff" class="mr-1" rows="1" auto-grow readonly filled></v-textarea>
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { TFields } from '@/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'

let { fields } = storeToRefs(useItemStore())

const item = computed(() => {
  return <TFields<'Season'>>fields.value
})
</script>
