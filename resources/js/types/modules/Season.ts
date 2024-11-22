import type { TModuleInfo } from '@/types/moduleTypes'

type TSeason<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'seasons'
  fields: {
    id: string
    description: string
    staff: string
  }
  apiTabularPageFields: Pick<TSeason<T>['fields'], 'id' | 'description' | 'staff'>
}

export { TSeason }
