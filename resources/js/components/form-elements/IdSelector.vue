<template>
    <slot name='id-selector-activator'>
    </slot>
    <v-dialog v-model="openIdSelectorModal" fullscreen>
        <v-container fluid>
            <v-card class="mx-auto" width="800">
                <v-card-title class="bg-grey text-black py-0 mb-4"> Id Selector Form for a new {{ current.module
                    }}</v-card-title>
                <v-card-text>
                    <v-row class="my-4 mr-2">
                        <Suspense>
                            <slot name='id-selector-form'>
                            </slot>
                        </Suspense>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn variant="outlined" class="ml-1" @click="cancel"> Cancel </v-btn>
                </v-card-actions>
            </v-card>

        </v-container>
    </v-dialog>
</template>

<script lang="ts" setup>
import { storeToRefs } from 'pinia'
import { useItemNewStore } from '../../scripts/stores/itemNew'
import { useRoutesMainStore } from '../../scripts/stores/routes/routesMain'

let { routerPush } = useRoutesMainStore()
const { current } = storeToRefs(useRoutesMainStore())
const { openIdSelectorModal } = storeToRefs(useItemNewStore())

const cancel = async () => {
    await routerPush('back1')
}
</script>