import type { TModuleInfo } from '@/types/moduleTypes'

type TMetal<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'metals'
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
    measurements: string
    notes: string
    material_id: number
    metal_primary_classification_id: number
  }
  apiTabularPageFields: Pick<TMetal<T>['fields'], 'id' | 'description'>
}

export { TMetal }
