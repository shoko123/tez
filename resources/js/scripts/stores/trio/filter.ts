import { defineStore, storeToRefs } from 'pinia'
import type { TArray } from '@/types/collectionTypes'
import { useXhrStore } from '../xhr'
import { useModuleStore } from '../module'
import { useTrioStore } from './trio'
import { assert } from '../../utils/utils'
import type { TApiFilters } from '@/types/routesTypes'
export const useFilterStore = defineStore('filter', () => {
  const trioStore = useTrioStore()
  const { send } = useXhrStore()
  const { module } = storeToRefs(useModuleStore())

  function filtersToQueryObject() {
    const q: {
      [key: string]: string
    } = {}

    trioStore.filterAllOptionKeys.sort((a, b) => {
      return a > b ? 1 : -1
    })

    trioStore.filterAllOptionKeys.forEach((k) => {
      const optionUlined = trioStore.trio.optionsObj[k]!.text.replace(/ /g, '_')
      const groupUlined = trioStore.trio.groupsObj[
        trioStore.trio.optionsObj[k]!.groupKey
      ]!.label.replace(/ /g, '_')
      if (Object.prototype.hasOwnProperty.call(q, groupUlined)) {
        q[groupUlined] += ',' + optionUlined
      } else {
        q[groupUlined] = optionUlined
      }
    })
    console.log(`filtersToQueryObject().q: ${JSON.stringify(q, null, 2)}`)
    return q
  }

  function getQueryParamsFromSelectedOptions() {
    const all: TApiFilters = {
      module_tag_ids: [],
      global_tag_ids: [],
      onp_ids: [],
      categorized: [],
      discrete_field_values: [],
      field_search: [],
      media: [],
      order_by: [],
    }

    //push options into their correct arrays, according to group type
    trioStore.filterAllOptionKeys.forEach((key) => {
      const option = trioStore.trio.optionsObj[key]!
      const group = trioStore.trio.groupsObj[trioStore.trio.optionsObj[key]!.groupKey]!

      switch (group.code) {
        case 'LV':
        case 'RV':
        case 'EM':
          {
            const i = all.discrete_field_values.findIndex((x) => {
              return x.field_name === group.field_name
            })

            if (i === -1) {
              //if new group, push the option's group into the groups array with itself as the first option
              all.discrete_field_values.push({
                label: group.label,
                field_name: group.field_name!,
                vals: [option.extra],
              })
            } else {
              //if the group is already selected, add option's text to the group's options array
              //all.discrete_field_values[i].vals.push(option.text)
              all.discrete_field_values[i]!.vals.push(option.extra)
            }
          }
          break

        case 'SF':
          {
            const i = all.field_search.findIndex((x) => {
              return x.field_name === group.field_name
            })
            if (i === -1) {
              //if new group, push the option's group into the groups array with itself as the first option
              all.field_search.push({
                field_name: group.field_name!,
                vals: [option.text],
              })
            } else {
              //if the group is already selected, add option's text to the group's options array
              all.field_search[i]!.vals.push(option.text)
            }
          }
          break

        case 'TM':
          all.module_tag_ids.push(<number>option.extra)
          break

        case 'TG':
          all.global_tag_ids.push(<number>option.extra)
          break

        case 'ON':
          all.onp_ids.push(<number>option.extra)
          break

        case 'CT':
          {
            const i = all.categorized.findIndex((x) => {
              return x.group_name === group.label
            })

            if (i === -1) {
              //if new group, push the option's group into the groups array with itself as the first option
              all.categorized.push({
                group_name: group.label,
                selected: [{ name: option.text, index: option.extra as number }],
              })
            } else {
              //if the group is already selected, add option's text to the group's options array
              all.categorized[i]!.selected.push({
                name: option.text,
                index: option.extra as number,
              })
            }
          }
          break

        case 'MD':
          all.media.push(option.text)
          break

        case 'OB':
          {
            const ordeByItem = trioStore.orderByOptions.find((x) => x === option.text.slice(0, -2))
            assert(ordeByItem !== undefined, `Selected OrderBy option "${option.text} not found`)

            all.order_by.push({
              label: <string>ordeByItem,
              asc: option.text.slice(-1) === 'A',
            })
          }
          break
      }
    })
    return all
  }

  async function getCount() {
    const res = await send<TArray[]>('module/index', 'post', {
      module: module.value,
      query: getQueryParamsFromSelectedOptions(),
    })
    return res.success ? res.data.length : -1
  }

  return {
    filtersToQueryObject,
    getQueryParamsFromSelectedOptions,
    getCount,
  }
})
