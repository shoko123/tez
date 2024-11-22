import { TMediaOfItem, TMediaUrls } from '@/types/mediaTypes'
import { TModule, TApiTabular } from '@/types/moduleTypes'

type TCollectionView = 'Gallery' | 'Chips' | 'Tabular'

type TDefs = {
  main: {
    array: string
    apiPages: {
      Gallery: {
        id: string
        short: string
        urls: TMediaUrls
      }
      Tabular: TApiTabular
      Chips: { id: string }
    }
  }
  media: {
    array: TMediaCommon
    apiPages: {
      Gallery: TMediaCommon
    }
  }
  related: {
    array: TRelatedCommon
    apiPages: {
      Gallery: TRelatedCommon
      Tabular: TRelatedCommon
      Chips: Omit<TRelatedCommon, 'short' | 'urls'>
    }
  }
}

type TMediaCommon = {
  id: number
  order_column: number
  urls: TMediaUrls
}

type TRelatedCommon = {
  relation_name: string
  module: TModule
  id: string
  short: string
  urls: TMediaUrls
}

type TCName = keyof TDefs
type TArray<C extends TCName = TCName> = TDefs[C]['array']
type TView<S extends TCName = TCName> = keyof TDefs[S]['apiPages'] //TNewApiArrayObj<S>

type TApiPage<
  S extends TCName = TCName,
  V extends TView<S> = TView<S>,
  M extends TModule = TModule,
> = S extends 'main'
  ? V extends 'Tabular'
    ? TApiTabular<M>
    : TDefs[S]['apiPages'][V]
  : TDefs[S]['apiPages'][V]

type TPage<
  S extends TCName = TCName,
  V extends TView<S> = TView<S>,
  M extends TModule = TModule,
> = V extends 'Gallery'
  ? SwapUrlWithMedia<TApiPage<S, V, M>> & { tag: string; slug: string }
  : TApiPage<S, V, M> & { tag: string; slug: string }

// //convert media property type from the api's TMediaUrls to the frontend's TMediaOfItem
type SwapUrlWithMedia<T extends TApiPage<TCName, 'Gallery'>> = Omit<T, 'urls'> & {
  media: TMediaOfItem
}

type TArrayEqualFunc = (a: TArray, b: TArray) => boolean
type TPageEqualFunc = (a: TArray, p: TPage) => boolean

export { TArray, TCName, TCollectionView, TApiPage, TPage, TArrayEqualFunc, TPageEqualFunc, TView }
