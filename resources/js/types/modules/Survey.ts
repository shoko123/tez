import type { TModuleInfo } from '@/types/moduleTypes'
type TSurvey<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'survey'
  fields: {
    id: string
    id_old: number
    area_id: string
    feature_no: number
    surveyed_date: string | null
    elevation: number
    urinext_to: string
    description: string
    notes: string
  }
  apiTabularPageFields: Pick<
    TSurvey<T>['fields'],
    'id' | 'surveyed_date' | 'elevation' | 'description' | 'notes'
  >
}

export { TSurvey }
