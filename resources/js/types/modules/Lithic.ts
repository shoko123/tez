import type { TModuleInfo } from '@/types/moduleTypes'

type TLithic<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'lithics'
  fields: {
    id: string
    locus_id: string
    code: 'AR' | 'FL'
    basket_no: number
    artifact_no: number
    date_retrieved: Date | string | null
    weight: number
    field_description: string
    registration_notes: string
    specialist_notes: string
  }
  apiTabularPageFields: Pick<
    TLithic<T>['fields'],
    'id' | 'date_retrieved' | 'field_description' | 'weight'
  >
}

export { TLithic }
