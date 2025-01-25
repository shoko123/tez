// routesStore.js
import type { TCollectionView } from '@/types/collectionTypes'
import type { TModule, TUrlModule, TFieldValue } from '@/types/moduleTypes'

type TRoutes =
  | { name: 'home' }
  | { name: 'welcome'; params: { url_module: TUrlModule } }
  | { name: 'filter'; module: TModule; params: { url_module: TUrlModule } }
  | { name: 'index'; module: TModule; params: { url_module: TUrlModule; query: string } }
  | {
      name: 'show'
      module: TModule
      params: { url_module: TUrlModule; query: string; slug: string }
    }
  | { name: 'create'; module: TModule; params: { url_module: TUrlModule } }
  | {
      name: 'update'
      module: TModule
      params: { url_module: TUrlModule; slug: string; action: 'update' }
    }
  | {
      name: 'tag'
      module: TModule
      params: { url_module: TUrlModule; slug: string; action: 'tag' }
    }
  | {
      name: 'media'
      media: TModule
      params: { url_module: TUrlModule; slug: string; action: 'media' }
    }
  | { name: 'login' }
  | { name: 'register' }
  | { name: 'forgot-password' }
  | { name: 'reset-password' }
  | { name: 'reset-dashboard' }

type TPageName = TRoutes['name'] | 'undefined-route-name'

type TPlanAction =
  | 'load.module'
  | 'load.collection'
  | 'load.pageByIndex'
  | 'load.firstPage'
  | 'load.item'
  | 'load.itemAndCollection'
  | 'clear.module'
  | 'clear.collection'
  | 'clear.item'
  | 'setIndex.ItemInMainCollection'
  | 'resetIndices.trio'
  | 'resetIndex.itemView'

type TRouteInfo = {
  name: TPageName
  url_module: TUrlModule | undefined
  slug: string | undefined
  url_full_path: string | undefined
  module: TModule | undefined
  queryParams: object | undefined
  preLoginFullPath: string | undefined
}

type TSelectedFilterFromQuery = {
  param: string
  code: string
  extra: string
}

type TApiFilters = {
  module_tag_ids: number[]
  global_tag_ids: number[]
  onp_ids: number[] //optional numeric property
  categorized: { group_name: string; selected: { name: string; index: number }[] }[]
  discrete_field_values: {
    group_name: string
    vals: TFieldValue[]
  }[]
  field_search: { field_name: string; vals: string[] }[]
  media: string[]
  order_by: { group_name: string; asc: boolean }[]
}

type TParseQuery = {
  success: boolean
  apiFilters: TApiFilters
  selectedFilters: TSelectedFilterFromQuery[]
  message: string
}

type TPlanResponse =
  | {
      success: true
      data: TPlanAction[]
    }
  | {
      success: false
      message: string
    }

type TFuncLoadPage = (
  pageNoB1: number,
  view: TCollectionView,
  length: number,
  module: TModule,
) => Promise<{ success: true; message: string } | { success: false; message: string }>

export {
  TPageName,
  TRouteInfo,
  TParseQuery,
  TApiFilters,
  TSelectedFilterFromQuery,
  TPlanResponse,
  TPlanAction,
  TFuncLoadPage,
}
