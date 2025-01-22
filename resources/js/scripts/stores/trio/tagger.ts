import { defineStore, storeToRefs } from 'pinia'
import type { TFields, TFieldValue } from '@/types/moduleTypes'
import { useXhrStore } from '../xhr'
import { useItemStore } from '../item'
import { useModuleStore } from '../module'
import { useTrioStore } from './trio'

export const useTaggerStore = defineStore('tagger', () => {
  const { fields } = storeToRefs(useItemStore())
  const { send } = useXhrStore()
  const { module } = storeToRefs(useModuleStore())
  const { trio, itemAllOptionKeys, taggerAllOptionKeys } = storeToRefs(useTrioStore())

  function taggerCopyItemOptionsToTagger() {
    const tmp = [...itemAllOptionKeys.value]
    taggerAllOptionKeys.value = [
      ...tmp.sort((a, b) => {
        return a > b ? 1 : -1
      }),
    ]
  }

  // Clear module & global tags
  function taggerSetDefaultOptions() {
    taggerAllOptionKeys.value = taggerAllOptionKeys.value.filter((x) => {
      const group = trio.value.groupsObj[trio.value.optionsObj[x]!.groupKey]!
      return !['TM', 'TG'].includes(group.code)
    })
  }

  function taggerConvertSelectedToApi() {
    const optionsParams = {
      global_tag_ids: <number[]>[],
      module_tag_ids: <number[]>[],
      fields: <{ field_name: string; val: TFieldValue }[]>[],
    }

    taggerAllOptionKeys.value.forEach((optionKey) => {
      const group = trio.value.groupsObj[trio.value.optionsObj[optionKey]!.groupKey]!
      switch (group.code) {
        case 'TG':
          optionsParams.global_tag_ids.push(<number>trio.value.optionsObj[optionKey]!.extra)
          break

        case 'TM':
          optionsParams.module_tag_ids.push(<number>trio.value.optionsObj[optionKey]!.extra)
          break

        case 'LV':
        case 'EM':
          {
            if (group.useInTagger) {
              const option = trio.value.optionsObj[optionKey]!
              optionsParams.fields.push({
                field_name: group.field_name!,
                val: group.code === 'LV' ? option.extra : option.text,
              })
            }
          }
          break
      }
    })
    // console.log(`taggerConvertSelectedToApi: ${JSON.stringify(optionsParams, null, 2)}`)
    return optionsParams
  }
  async function sync() {
    const payload = {
      module: module.value,
      module_id: (<TFields>fields.value).id,
      ...taggerConvertSelectedToApi(),
    }

    //console.log(`tagger.toSend: ${JSON.stringify(payload, null, 2)}`)
    const res = await send<boolean>('tags/sync', 'post', payload)

    if (res.success) {
      return { success: true }
    }
    return { success: false, message: res.message }
  }

  return {
    sync,
    taggerCopyItemOptionsToTagger,
    taggerSetDefaultOptions,
  }
})
