import type { TModuleInfo } from '@/types/moduleTypes'

type TFauna<T extends TModuleInfo = TModuleInfo> = {
  url_name: 'fauna'
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
    notes: string
    has_butchery_evidence: boolean
    has_burning_evidence: boolean
    has_other_bsm_evidence: boolean
    is_fused: boolean
    is_left: boolean
    d_and_r: string
    weathering: boolean
    age: string
    breakage: string
    //
    GL: string
    Glpe: string
    GLl: string
    GLP: string
    Bd: string
    BT: string
    Dd: string
    BFd: string
    Bp: string
    Dp: string
    SD: string
    HTC: string
    Dl: string
    DEM: string
    DVM: string
    WCM: string
    DEL: string
    DVL: string
    WCL: string
    LD: string
    DLS: string
    LG: string
    BG: string
    DID: string
    BFcr: string
    GD: string
    GB: string
    BF: string
    LF: string
    GLm: string
    GH: string

    //
    fauna_element_id: number
    fauna_taxon_id: number
  }
  apiTabularPageFields: Pick<TFauna<T>['fields'], 'id' | 'description'>
}

export { TFauna }
