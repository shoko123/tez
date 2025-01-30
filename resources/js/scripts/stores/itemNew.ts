import { ref, computed } from 'vue'
import { defineStore, storeToRefs } from 'pinia'
import type { TApiFields, TFields, TFieldInfo } from '@/types/moduleTypes'
import type { TApiItemShow } from '@/types/itemTypes'
import type { TGroup } from '@/types/trioTypes'
import { useCollectionsStore } from './collections/collections'
import { useRoutesMainStore } from './routes/routesMain'
import { useXhrStore } from './xhr'
import { useModuleStore } from './module'
import { useItemStore } from './item'
import { useTrioStore } from './trio/trio'

export const useItemNewStore = defineStore('itemNew', () => {
  const { current } = storeToRefs(useRoutesMainStore())
  const { changeCount } = useModuleStore()
  const { module } = storeToRefs(useModuleStore())
  const { tagAndSlugFromId } = useModuleStore()
  const { send } = useXhrStore()
  const { getCollectionStore } = useCollectionsStore()
  const { itemFieldsOptions } = useTrioStore()
  const { trio, groupLabelToGroupKeyObj } = storeToRefs(useTrioStore())
  const { onps } = storeToRefs(useItemStore())
  const currentIds = ref<string[]>([])
  const slug = ref<string | undefined>(undefined)
  const tag = ref<string | undefined>(undefined)
  const ready = ref<boolean>(false)
  const openIdSelectorModal = ref<boolean>(false)
  const store = getCollectionStore('main')

  const dataNew = ref<{
    fields: Partial<TFields>
    allOnps: { label: string; id: number; value: number | null }[]
  }>({ fields: {}, allOnps: [] })

  const mainArray = computed(() => {
    return store.array as string[]
  })
  const isCreate = computed(() => {
    return current.value.name === 'create'
  })

  const isUpdate = computed(() => {
    return current.value.name === 'update'
  })

  const id = computed(() => {
    return dataNew.value.fields.id
  })

  const fieldsWithOptions = computed(() => {
    if (Object.keys(dataNew.value.fields).length === 0) {
      return {}
    }

    const fo = itemFieldsOptions(dataNew.value.fields as TFields)
    const tmp: Partial<Record<keyof TFields, TFieldInfo>> = {}
    fo.forEach((x) => (tmp[x.fieldName as keyof TFields] = x))
    return tmp as Partial<Record<keyof TFields, TFieldInfo>>
  })

  function prepareOnps() {
    // Iterate over all the groups and extract options for onp groups
    // console.log(`***** itemNew.prepareOnps() ******`)

    dataNew.value.allOnps = []
    for (const property in groupLabelToGroupKeyObj.value) {
      // console.log(`${property}: ${groupLabelToGroupKeyObj.value[property]}`)
      const group = trio.value.groupsObj[groupLabelToGroupKeyObj.value[property]!]!

      if (group.code === 'ON') {
        addOnpGroup(group as TGroup<'ON'>)
      }
    }
  }

  function addOnpGroup(group: TGroup<'ON'>) {
    // console.log(`addOneGroup: ${JSON.stringify(group, null, 2)} `)
    // Copy onps from trio
    dataNew.value.allOnps = dataNew.value.allOnps.concat(
      group.optionKeys.map((x) => {
        const paramInfo = trio.value.optionsObj[x]!
        return { label: paramInfo.text, id: paramInfo.extra as number, value: null }
      }),
    )

    // console.log(` dataNew.value.onps: ${JSON.stringify(dataNew.value.allOnps, null, 2)} `)

    // If update, copy existing onps values
    if (!isCreate.value) {
      onps.value.forEach((e) => {
        const index = dataNew.value.allOnps.findIndex((n) => n.label === e.label)
        dataNew.value.allOnps[index]!.value = e.value
      })
    }
  }

  async function upload(
    isCreate: boolean,
    newFields: Partial<TFields>,
  ): Promise<{ success: true; slug: string } | { success: false; message: string }> {
    console.log(
      `item.upload isCreate: ${isCreate}, module: ${module.value}, fields: ${JSON.stringify(newFields, null, 2)}`,
    )

    const res = await send<TApiItemShow<TApiFields>>('module/store', isCreate ? 'post' : 'put', {
      module: module.value,
      data: {
        fields: newFields,
        onps: dataNew.value.allOnps
          .filter((x) => {
            return x.value
          })
          .map((x) => {
            return { id: x.id, value: x.value === null ? null : +x.value }
          }),
      },
    })

    if (!res.success) {
      return res
    }

    const tagAndSlug = tagAndSlugFromId(res.data.fields.id)

    if (isCreate) {
      changeCount('items', 1)
      //push newly created id into the 'main' collection
      mainArray.value.push(res.data.fields.id)
    }

    return { success: true, slug: tagAndSlug.slug }
  }

  function itemNewClear() {
    slug.value = undefined
    tag.value = undefined
  }

  return {
    currentIds,
    dataNew,
    slug,
    tag,
    ready,
    id,
    isCreate,
    isUpdate,
    openIdSelectorModal,
    fieldsWithOptions,
    itemNewClear,
    upload,
    prepareOnps,
  }
})
