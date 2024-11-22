import type { TModuleInfo } from '@/types/moduleTypes'

type TGlass<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'glass'
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

    rim_diameter: number
    base_diameter: number
    bangle_diameter: number
    bead_diameter: number
    pontil_diameter: number

    glass_primary_classification_id: number
  }
  apiTabularPageFields: Pick<TGlass<T>['fields'], 'id' | 'description'>
}

export { TGlass }
