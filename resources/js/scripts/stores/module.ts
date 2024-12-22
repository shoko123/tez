import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type {
  TApiModuleInit,
  TUrlModule,
  TModule,
  TItemsPerPageByView,
  TViewsForCollection,
  TModuleToUrlName,
  TUrlModuleNameToModule,
  TModuleBtnsInfo,
} from '../../types/moduleTypes'
import type { TCName } from '@/types/collectionTypes'
type TItemViews = { options: string[]; index: number }

import { moduleDefinitions } from './moduleDefinitions'

export const useModuleStore = defineStore('module', () => {
  const module = ref<TModule>('Locus')
  const counts = ref({ items: 0, media: 0 })
  const welcomeText = ref<string>('')
  const firstSlug = ref<string>('')
  const dateFields = ref<string[]>([])
  const itemsPerPage = ref<TItemsPerPageByView>({ Gallery: 0, Tabular: 0, Chips: 0 })
  const collectionViews = ref<TViewsForCollection>({ main: [], media: [], related: [] })
  const itemViews = ref<TItemViews>({ options: [], index: -1 })

  // Next 2 structures define the modules and their required properties.
  const moduleToUrlModuleName = ref<TModuleToUrlName>({
    Area: 'areas',
    Season: 'seasons',
    Survey: 'survey',
    Locus: 'loci',
    Ceramic: 'ceramics',
    Stone: 'stones',
    Lithic: 'lithics',
    Fauna: 'fauna',
    Metal: 'metals',
    Glass: 'glass',
  })

  function setModuleInfo(initData: TApiModuleInit) {
    module.value = initData.module
    counts.value = initData.counts
    welcomeText.value = initData.welcome_text
    const ts = tagAndSlugFromId(initData.first_id, initData.module)
    firstSlug.value = ts.slug
    dateFields.value = initData.dateFields
    itemsPerPage.value = initData.display_options.items_per_page
    collectionViews.value = initData.display_options.collection_views
    itemViews.value.index = 0
    itemViews.value.options = initData.display_options.item_views
  }

  // Construct necessary module related structures (often accessed by module rather than by url_module).
  const urlModuleNameToModule = ref(inverse(moduleToUrlModuleName.value))

  function inverse(moduleToUrl: TModuleToUrlName) {
    return Object.fromEntries(Object.entries(moduleToUrl).map(([key, value]) => [value, key]))
  }

  // Collection views related functionality
  function getItemsPerPage(collectionName: TCName, collectionViewIndex: number): number {
    return itemsPerPage.value[collectionViews.value[collectionName]![collectionViewIndex]!]!
  }

  function getCollectionViewName(collectionName: TCName, collectionViewIndex: number) {
    return collectionViews.value[collectionName]![collectionViewIndex]!
  }

  function getCollectionViews(collectionName: TCName) {
    return collectionViews.value[collectionName]!
  }

  const moduleBtnsInfo = computed<TModuleBtnsInfo[]>(() => {
    const arr: TModuleBtnsInfo[] = []
    Object.entries(moduleToUrlModuleName.value).forEach(([k, v]) => {
      arr.push({
        title: k,
        url_module: v,
        module: <TModule>k,
      })
    })
    return arr
  })

  const tabularHeaders = computed(() => {
    return moduleDefinitions[module.value].tabHeaders
  })

  // item views related
  function setNextItemView() {
    itemViews.value.index = (itemViews.value.index + 1) % itemViews.value.options.length
  }

  function resetItemView() {
    itemViews.value.index = 0
  }

  const itemView = computed(() => {
    return itemViews.value.options[itemViews.value.index]
  })

  function getCategorizerFunc() {
    return moduleDefinitions[module.value].categorizerFunc ?? null
  }

  // id, tag and slug conversions
  function parseSlug(
    module: TModule,
    slug: string,
  ): { success: true; id: string } | { success: false; message: string } {
    const res = moduleDefinitions[module].slugRegExp.exec(slug)
    if (res === null) {
      return { success: false, message: `Unsupported ${module} slug: ${slug}` }
    } else {
      return {
        success: true,
        id: moduleDefinitions[module].idFormatter(res.groups!),
      }
    }
  }

  function tagAndSlugFromId(id: string, m?: TModule) {
    // If the module is not provided, use current.
    const mod = m === undefined ? module.value : m

    const res = moduleDefinitions[mod].idRegExp.exec(id)
    if (res === null) {
      console.log(`*** Error in Formatting id (${mod}) "${id}"`)
      return { tag: '', slug: '' }
    } else {
      return moduleDefinitions[mod].idDerived(res.groups!)
    }
  }

  function parseModule(urlModule: string) {
    if (urlModule in urlModuleNameToModule.value) {
      return {
        success: true,
        module: urlModuleNameToModule.value[urlModule as keyof TUrlModuleNameToModule],
        url_module: urlModule as TUrlModule,
      }
    } else {
      return {
        success: false,
        data: null,
        message: `Error: unknown url module "${urlModule}"`,
      }
    }
  }

  function prepareNewFields(fields: object) {
    const newFields = Object.fromEntries(
      Object.entries(fields).map(([key, value]) => {
        if (dateFields.value.includes(key) && value !== null) {
          console.log(`converting field ${key}(${value}) to Date`)
          return [key, new Date(value)]
        } else {
          return [key, value]
        }
      }),
    )
    return newFields
  }

  function changeCount(countOf: 'items' | 'media', change: number) {
    counts.value[countOf] += change
  }

  return {
    setModuleInfo,
    module,
    counts,
    welcomeText,
    firstSlug,
    itemsPerPage,
    collectionViews,
    urlModuleNameToModule,
    moduleToUrlModuleName,
    moduleBtnsInfo,
    parseModule,
    parseSlug,
    tagAndSlugFromId,
    getCollectionViews,
    getCollectionViewName,
    getItemsPerPage,
    getCategorizerFunc,
    itemViews, // itemView will only be refreshed if itemViews are exposed
    itemView,
    setNextItemView,
    resetItemView,
    tabularHeaders,
    dateFields,
    prepareNewFields,
    changeCount,
  }
})
