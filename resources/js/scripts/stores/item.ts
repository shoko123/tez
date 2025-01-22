import type { TApiFields, TFields, TLookupFields } from '@/types/moduleTypes'
import type { TApiItemShow, TOnp } from '@/types/itemTypes'

import { ref, computed } from 'vue'
import { defineStore, storeToRefs } from 'pinia'

import { useCollectionsStore } from './collections/collections'
import { useElementAndCollectionStore } from './collections/elementAndCollection'
import { useRoutesMainStore } from './routes/routesMain'
import { useXhrStore } from './xhr'
import { useModuleStore } from './module'
import { useTrioStore } from './trio/trio'

export const useItemStore = defineStore('item', () => {
  const { current, to } = storeToRefs(useRoutesMainStore())
  const { itemFieldsOptions, itemAddTagsKeys, itemApiTagsToOptionKeys, itemSetFieldsKeys } =
    useTrioStore()
  const { tagAndSlugFromId, changeCount } = useModuleStore()
  const { module } = storeToRefs(useModuleStore())
  const { getCollectionStore, setCollectionArray } = useCollectionsStore()
  const { send } = useXhrStore()

  const fields = ref<Partial<TFields>>({})
  const onps = ref<TOnp[]>([])
  const slug = ref<string>('')
  const tag = ref<string>('')
  const short = ref<string>('')
  const ready = ref<boolean>(false)

  const id = computed(() => {
    return fields.value.id
  })

  const derived = computed(() => {
    return {
      module: current.value.module,
      slug: current.value.slug,
      tag: tag.value,
      moduleAndTag: `${current.value === undefined ? '' : current.value.module} ${tag.value}`,
    }
  })

  const lookupEnums = computed(() => {
    if (Object.keys(fields.value).length === 0 || current.value.module !== to.value.module) {
      return {}
    }

    const fo = itemFieldsOptions(fields.value as TFields)
    const tmp: Partial<TLookupFields> = {}
    fo.forEach((x) => (tmp[x.fieldName as keyof TFields] = x.optionLabel))
    return tmp
  })

  async function saveitemFieldsPlus<F extends TApiFields>(apiItem: TApiItemShow<F>) {
    // save media & related collections
    setCollectionArray('media', apiItem.media, true)
    setCollectionArray('related', apiItem.related, true)
    console.log(`saveitemFieldsPlus`)
    // saveItemFields(apiItem.fields)
    fields.value = apiItem.fields
    onps.value = apiItem.onps
    const res = tagAndSlugFromId(apiItem.fields.id)
    tag.value = res.tag
    slug.value = res.slug

    // Set fields related option keys
    itemSetFieldsKeys(apiItem.fields)

    // Add tag options keys
    const tagOptions = itemApiTagsToOptionKeys(apiItem.module_tags.concat(apiItem.global_tags))
    itemAddTagsKeys([...tagOptions])
  }

  function clearItem() {
    fields.value = {}
    onps.value = []
    slug.value = ''
    short.value = ''
    tag.value = ''
    setCollectionArray('media', [], true)
    setCollectionArray('related', [], true)
  }

  async function itemRemove(): Promise<
    { success: true; slug: string | null } | { success: false; message: string }
  > {
    const mcs = getCollectionStore('main')
    const mainArray = mcs.array as string[]
    const ecStore = useElementAndCollectionStore()
    const res = await send<{ deleted_id: string }>('module/destroy', 'post', {
      module: module.value,
      id: fields.value?.id,
    })

    if (!res.success) {
      return res
    }

    changeCount('items', -1)

    // If we splice at index 0, the index will remain 0, else move to one on left.
    if (ecStore.indices.Show.index === 0) {
      mainArray.splice(ecStore.indices.Show.index, 1)
    } else {
      ecStore.setNextIndex('Show', false)
      mainArray.splice(ecStore.indices.Show.index + 1, 1)
    }

    if (mainArray.length === 0) {
      return { success: true, slug: null }
    } else {
      const id = ecStore.getElement('Show') as string
      const tagAndSlug = tagAndSlugFromId(id, current.value.module)
      return { success: true, slug: tagAndSlug.slug }
    }
  }

  return {
    slug,
    tag,
    short,
    ready,
    fields,
    onps,
    id,
    derived,
    clearItem,
    saveitemFieldsPlus,
    itemRemove,
    lookupEnums,
  }
})
