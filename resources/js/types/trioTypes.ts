import type { TFieldValue } from '@/types/moduleTypes'

type TrioSelectorSource = 'Filter' | 'Tagger'
type TrioSourceName = TrioSelectorSource | 'Item'

// All groups have code & label keys.
type TDefs = {
  LV: {
    API: {
      table_name: string
      field_name: string
      dependency: string[]
      options: { id: number; label: string }[]
    }
    TRIO: { field_name: string; dependency: string[] }
  }
  FO: {
    API: {
      options: { id: number; label: string }[]
    }
    TRIO: object
  }
  CT: {
    API: {
      options: { index: number; label: string }[]
    }
    TRIO: object
  }
  TM: {
    API: {
      dependency: string[]
      multiple: boolean
      tag_group_id: number
      options: { tag_id: number; label: string }[]
    }
    TRIO: { multiple: boolean; dependency: string[] }
  }
  TG: {
    API: {
      dependency: string[]
      multiple: boolean
      tag_group_id: number
      options: { tag_id: number; label: string }[]
    }
    TRIO: { group_id: number; multiple: boolean; dependency: string[] }
  }
  ON: {
    API: {
      onp_group_id: number
      options: { onp_id: number; label: string }[]
    }
    TRIO: { group_id: number }
  }
  SF: {
    API: { field_name: string; options: string[] }
    TRIO: { field_name: string }
  }
  MD: {
    API: { options: string[] }
    TRIO: object
  }
  OB: {
    API: { options: string[] }
    TRIO: object
  }
}

type TCode = keyof TDefs
type TSpace = 'API' | 'TRIO'

type TGroupSpaceAndCode<L extends TSpace = TSpace, C extends TCode = TCode> = TDefs[C][L]

type TApiGroup<C extends TCode = TCode> = TGroupSpaceAndCode<'API', C> & {
  code: C
  label: string
  field_name?: string
  multiple?: boolean
  group_id?: number
  dependency?: string[]
}

type TApiTrio = { name: string; groups: TApiGroup[] }[]

//////////// TRIO types /////////////////

type TOptionWithoutGroupKey = {
  text: string
  extra: TFieldValue
}

type TOption = TOptionWithoutGroupKey & {
  groupKey: string
}

type TOptionObj = { [key: string]: TOption }

// These are the trio (front) groups, if ever one wants to narrow to a specific type.
// I was too lazy, and instead made all possible properties optional and used the non-null assertion operator (!) when needed.
type TGroupSpecific<C extends TCode> = Omit<TDefs[C]['TRIO'], 'options'> & {
  code: C
  label: string
  optionKeys: string[]
}

type TGroup<C extends TCode = TCode> = Omit<TDefs[C]['TRIO'], 'options'> & {
  code: C
  label: string
  selectorCategoryIndex: number
  field_name?: string
  optionKeys: string[]
  multiple?: boolean
  group_id?: number
  dependency?: string[]
}

type TGroupObj = {
  [key: string]: TGroup
}

/////////// extra types /////////////////////////

type TCategorizerFunc = (val: TFieldValue) => number
type TCategoriesArray = { name: string; groupKeys: string[] }[]
type TGroupOrFieldToKeyObj = { [key: string]: string }

type TTrio = { categories: TCategoriesArray; groupsObj: TGroupObj; optionsObj: TOptionObj }

export {
  TrioSourceName,
  TrioSelectorSource,
  TApiTrio,
  TTrio,
  TCategoriesArray,
  TOptionObj,
  TGroupObj,
  TGroupOrFieldToKeyObj,
  TOptionWithoutGroupKey,
  TOption,
  TCategorizerFunc,
  TApiGroup,
  TGroupSpaceAndCode,
  TGroupSpecific,
  TGroup,
  TCode,
}