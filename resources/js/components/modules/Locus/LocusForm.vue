<template>
  <v-container v-if="item" fluid>

    <v-row wrap no-gutters class="ga-1">
      <v-text-field v-model="item.square" readonly label="Square" filled></v-text-field>
      <v-text-field v-model="item.locus_above" readonly label="Locus Above" filled></v-text-field>
      <v-text-field v-model="item.locus_below" readonly label="Locus Below" filled></v-text-field>
      <v-text-field v-model="item.locus_co_existing" readonly label="Co-Existing" filled></v-text-field>
    </v-row>

    <v-row wrap no-gutters class="ga-1">
      <v-text-field v-model="item.date_opened" readonly label="Date Opened" filled></v-text-field>
      <v-text-field v-model="item.date_closed" readonly label="Date Closed" filled></v-text-field>
      <v-text-field v-model="item.level_opened" readonly label="Level Opened" filled></v-text-field>
      <v-text-field v-model="item.level_closed" readonly label="Level Closed" filled></v-text-field>
      <v-text-field v-model="item.clean" readonly label="Clean" filled></v-text-field>
    </v-row>

    <v-row wrap no-gutters class="ga-1">
      <v-textarea v-model="item.description" rows="1" auto-grow readonly label="Description" filled></v-textarea>
      <v-textarea v-model="item.deposit" rows="1" auto-grow readonly label="Deposit" filled></v-textarea>
      <v-textarea v-model="item.registration_notes" rows="1" auto-grow readonly label="Registration Notes"
        filled></v-textarea>
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
  return <TFields<'Locus'>>fields.value
})
</script>