import type { TModuleInfo } from '@/types/moduleTypes'
type TStone<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'stones'
  fields: {
    id: string
    locus_id: string
    code: string
    basket_no: number
    artifact_no: number
    date_retrieved: string | Date | null
    field_description: string
    field_notes: string
    square: string
    level_top: string
    level_bottom: string
    artifact_count: string
    ///
    description: string
    notes: string
    weight: string
    length: string
    width: string
    height: string
    depth: string
    thickness_min: string
    thickness_max: string
    perforation_diameter_max: string
    perforation_diameter_min: string
    perforation_depth: string
    diameter: string
    rim_diameter: string
    rim_thickness: string
    base_diameter: string
    base_thickness: string
    stone_primary_classification_id: number
    material_id: number
  }
  apiTabularPageFields: Pick<TStone<T>['fields'], 'id' | 'field_description'>
}

export { TStone }
