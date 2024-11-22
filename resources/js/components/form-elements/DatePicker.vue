<template>
    <v-date-input v-model="modelValue" :label="label" :color="color" clearable max-width="368" prepend-icon=""
        prepend-inner-icon="$calendar" @click:clear="modelValue = null">
    </v-date-input>
</template>

<script lang="ts" setup>
import { defineProps, defineModel, PropType } from "vue";
import { VDateInput } from 'vuetify/labs/VDateInput'

const modelValue = defineModel({
    type: [Date, String, null] as PropType<Date | string | null>,
    default: ''
})

defineProps<{
    label?: string
    color?: string
}>()
</script>