import type { TModuleInfo } from '@/types/moduleTypes'

type TArea<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'areas'
  fields: {
    id: string
    description: string
    notes: string
  }
  apiTabularPageFields: Pick<TArea<T>['fields'], 'id' | 'description' | 'notes'>
}

export { TArea }
