import type { TModuleInfo } from '@/types/moduleTypes'

type TLithic<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'lithics'
  fields: {
    id: string
    locus_id: string
    code: string
    basket_no: number
    artifact_no: number
    date_retrieved: Date | string | null
    field_description: string
    field_notes: string
    artifact_count: string
    square: string
    level_top: string
    level_bottom: string
    //
    description: string
    width: number
    length: number
    thickness: number
    weight: number
    burnt: boolean
    rolled: boolean
    hinge: boolean

    lithic_primary_classification_id: number
  }
  apiTabularPageFields: Pick<TLithic<T>['fields'], 'id' | 'description'>
}

export { TLithic }
