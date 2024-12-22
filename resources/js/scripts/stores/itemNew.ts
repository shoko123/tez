import { ref, computed } from 'vue'
import { defineStore, storeToRefs } from 'pinia'
import type { TApiFields, TFields, TFieldInfo } from '@/types/moduleTypes'
import type { TApiItemShow } from '@/types/itemTypes'
import { useCollectionsStore } from './collections/collections'
import { useRoutesMainStore } from './routes/routesMain'
import { useXhrStore } from './xhr'
import { useModuleStore } from './module'
import { useTrioStore } from './trio/trio'

export const useItemNewStore = defineStore('itemNew', () => {
  const { current } = storeToRefs(useRoutesMainStore())
  const { changeCount } = useModuleStore()
  const { module } = storeToRefs(useModuleStore())
  const { tagAndSlugFromId } = useModuleStore()
  const { send } = useXhrStore()
  const { getCollectionStore } = useCollectionsStore()
  const { itemFieldsOptions } = useTrioStore()

  const currentIds = ref<string[]>([])
  const slug = ref<string | undefined>(undefined)
  const tag = ref<string | undefined>(undefined)
  const ready = ref<boolean>(false)
  const openIdSelectorModal = ref<boolean>(false)
  const store = getCollectionStore('main')

  const dataNew = ref<{
    fields: Partial<TFields>
    onps: { label: string; id: number; value: number | null }[]
  }>({ fields: {}, onps: [] })

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
        onps: dataNew.value.onps
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
  }
})
