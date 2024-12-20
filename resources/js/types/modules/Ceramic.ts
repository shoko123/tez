import type { TModuleInfo } from '@/types/moduleTypes'

type TCeramic<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'ceramics'
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
    periods: string
    description: string
    ceramic_primary_classification_id: number
  }
  apiTabularPageFields: Pick<TCeramic<T>['fields'], 'id' | 'periods' | 'description'>
}

export { TCeramic }
