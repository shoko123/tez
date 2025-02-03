import type { TModuleInfo } from '@/types/moduleTypes'

type TCeramic<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'ceramics'
  fields: {
    id: string
    locus_id: string
    code: string
    basket_no: number
    artifact_no: number
    //
    date_retrieved: Date | string | null
    field_description: string
    field_notes: string
    //
    square: string
    level_top: string
    level_bottom: string
    //
    primary_classification_id: number
    specialist: string
    periods: string
    specialist_description: string
  }
  apiTabularPageFields: Pick<TCeramic<T>['fields'], 'id' | 'periods' | 'specialist_description'>
}

export { TCeramic }
