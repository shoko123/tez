<template>
  <v-card outlined>
    <v-card-title class="bg-grey-lighten-3 text-black title mb-2"> Introduction </v-card-title>
    <v-card-text class="text-body-1 mb-2 text-black pt-4">
      {{ introductionText }}
    </v-card-text>
  </v-card>
</template>

<script lang="ts" setup>
import { useLandingStore } from '../../scripts/stores/landing'
const { introductionText } = useLandingStore()
</script>
