import type { TModuleInfo } from '@/types/moduleTypes'
type TLocus<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'loci'
  fields: {
    id: string
    area_id: string
    season_id: string
    locus_no: number
    square: string
    date_opened: string | null
    date_closed: string | null
    level_opened: string
    level_closed: string
    locus_above: string
    locus_below: string
    locus_co_existing: string
    description: string
    deposit: string
    registration_notes: string
    clean: boolean
  }
  apiTabularPageFields: Pick<
    TLocus<T>['fields'],
    'id' | 'square' | 'date_opened' | 'description' | 'registration_notes'
  >
}

export { TLocus }
