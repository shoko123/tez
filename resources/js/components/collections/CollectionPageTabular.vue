<template>
  <v-data-table-virtual v-if="collectionIsNotEmpty" :headers="headers" :items="page" class="elevation-1" height="80vh"
    item-value="slug" fixed-header>
    <template #[`item.tag`]="{ item }">
      <v-btn @click="btnClicked(item)">
        {{ item.tag }}
      </v-btn>
    </template>
  </v-data-table-virtual>
</template>

<script lang="ts" setup>
import type { VDataTableVirtual } from 'vuetify/lib/components/index.mjs'
import type { TModule } from '@/types/moduleTypes'
import type { TCName, TPage } from '@/types/collectionTypes'

import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useCollectionsStore } from '../../scripts/stores/collections/collections'
import { useCollectionRelatedStore } from '../../scripts/stores/collections/collectionRelated'
import { useRoutesMainStore } from '../../scripts/stores/routes/routesMain'
import { useModuleStore } from '../../scripts/stores/module'

type THeader = { title: string, align: 'start' | 'end' | 'center' | undefined, key: string }
const props = defineProps<{
  source: TCName
  pageNoB1: number
}>()

const { getCollectionStore } = useCollectionsStore()
const { relatedTableHeaders } = storeToRefs(useCollectionRelatedStore())
const { tabularHeaders } = storeToRefs(useModuleStore())
const { routerPush, moveToRelatedItem } = useRoutesMainStore()

const headers = computed(() => {
  if (props.source === 'main') {
    return tabularHeaders.value.map(x => {
      return { title: x[0], align: x[1], key: x[2] }
    })
  } else {
    return relatedTableHeaders.value as THeader[]
  }
})

const c = computed(() => {
  return getCollectionStore(props.source)
})

const page = computed(() => {
  switch (props.source) {
    case 'main':
      return c.value.page as TPage<'main', 'Tabular', TModule>[]
    case 'related':
      return c.value.page as unknown as TPage<'related', 'Tabular'>[]
    default:
      return []
  }
})

const collectionIsNotEmpty = computed(() => {
  return page.value === undefined ? 0 : page.value.length > 0
})

async function btnClicked(item: TPage<'main', 'Tabular', TModule> | TPage<'related', 'Tabular'>) {
  console.log(`pageTable.btnClicked() item: ${JSON.stringify(item, null, 2)}`)
  if (props.source === 'main') {
    await routerPush('show', item.slug)
  } else {
    const related = item as TPage<'related', 'Tabular'>
    await moveToRelatedItem(related.module, related.id)
  }
}

</script>
