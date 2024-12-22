//showTypes.ts
//types returned from 'show' api route
import { TArray } from '@/types/collectionTypes'
import { TApiFields } from '@/types/moduleTypes'
type TOnp = {
  // optional numeric property
  group_label: string
  label: string
  value: number
}
type TApiTag = { group_label: string; tag_text: string }
type TApiItemShow<F extends TApiFields> = {
  fields: F
  module_tags: TApiTag[]
  global_tags: TApiTag[]
  onps: TOnp[]
  media: TArray<'media'>[]
  related: TArray<'related'>[]
  slug: string
  short: string
}

type TApiItemUpdate = {
  fields: TApiFields
  slug: string
}

export { TApiItemShow, TOnp, TApiTag, TApiItemUpdate }
