import type { TFields, TModuleDefinitionImplementation } from '../../types/moduleTypes'

const smallFindsCommon = {
  idRegExp:
    /^(?<season>[2-8])(?<area>[K-S])(?<locus_no>\d{3})(?<code>[A-Z]{2})(?<basket_no>\d{2})(?<artifact_no>\d{2})$/,

  idDerived: (g: Record<string, string>) => {
    return {
      slug: `1${g.season}.${g.area}.${Number(g.locus_no)}.${g.code}.${Number(g.basket_no)}.${Number(g.artifact_no)}`,
      tag: `1${g.season}/${g.area}/${Number(g.locus_no)}.${g.code}.${Number(g.basket_no)}.${Number(g.artifact_no)}`,
    }
  },

  slugRegExp:
    /^1(?<season>[2-8]).(?<area>[K-S]).(?<locus_no>\d{1,3}).(?<code>[A-Z]{2}).(?<basket_no>\d{1,2}).(?<artifact_no>\d{1,2})$/,

  idFormatter: (g: Record<string, string>) => {
    return `${g.season}${g.area}${g.locus_no!.padStart(3, '0')}${g.code}${g.basket_no!.padStart(2, '0')}${g.artifact_no!.padStart(2, '0')}`
  },
}

const moduleDefinitions: TModuleDefinitionImplementation = {
  Season: {
    idRegExp: /^(?<season>[2-8])$/,
    idDerived: (g: Record<string, string>) => {
      return { slug: `${Number(g.season) + 2010}`, tag: `${Number(g.season) + 2010}` }
    },
    slugRegExp: /^201(?<season>[2-8])$/,
    idFormatter: (g: Record<string, string>) => {
      return `${g.season}`
    },
    //[title, align, key] per vuetify v-data-table-virtual
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Description', 'start', 'description'],
      ['Staff', 'start', 'staff'],
    ],
  },
  Area: {
    idRegExp: /^(?<area>[K-S])$/,
    idDerived: (g: Record<string, string>) => {
      return { slug: `${g.area}`, tag: `${g.area}` }
    },
    slugRegExp: /^(?<area>[K-S])$/,
    idFormatter: (g: Record<string, string>) => {
      return `${g.area}`
    },
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Description', 'start', 'description'],
      ['Notes', 'start', 'notes'],
    ],
  },
  Survey: {
    idRegExp: /^(?<area>[K-Z])(?<feature_no>\d{1,3})$/,
    idDerived: (g: Record<string, string>) => {
      return {
        slug: `${g.area}.${g.feature_no}`,
        tag: `${g.area}/${g.feature_no}`,
      }
    },
    slugRegExp: /^(?<area>[K-Z]).(?<feature_no>\d{1,3})$/,
    idFormatter: (g: Record<string, string>) => {
      return `${g.area}${g.feature_no}`
    },
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Description', 'start', 'description'],
      ['Elevation', 'start', 'elevation'],
      ['Date Surveyed', 'start', 'surveyed_date'],
    ],
  },
  Locus: {
    idRegExp: /^(?<season>[2-8])(?<area>[K-S])(?<locus_no>\d{3})$/,
    idDerived: (g: Record<string, string>) => {
      return {
        slug: `1${g.season}.${g.area}.${Number(g.locus_no)}`,
        tag: `1${g.season}/${g.area}/${Number(g.locus_no)}`,
      }
    },
    slugRegExp: /^1(?<season>[2-8]).(?<area>[K-S]).(?<locus_no>\d{1,3})$/,
    idFormatter: (g: Record<string, string>) => {
      return `${g.season}${g.area}${g.locus_no!.padStart(3, '0')}`
    },
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Date Opened', 'start', 'date_opened'],
      ['Description', 'start', 'description'],
      ['Deposit', 'start', 'deposit'],
      ['Registration Notes', 'start', 'registration_notes'],
    ],
  },
  Ceramic: {
    idRegExp: smallFindsCommon.idRegExp,
    idDerived: smallFindsCommon.idDerived,
    slugRegExp: smallFindsCommon.slugRegExp,
    idFormatter: smallFindsCommon.idFormatter,
    categorizerFunc: (fields) => {
      const d = fields as TFields<'Ceramic'>
      return {
        'Registration Scope': d.artifact_no === 0 ? 0 : 1,
        'Includes Date': d.date_retrieved === null ? 1 : 0,
      }
    },
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Date Collected', 'start', 'date_retrieved'],
      ['Field Reading', 'start', 'field_description'],
      ['Field Notes', 'start', 'field_notes'],
      ['Periods', 'start', 'periods'],
      ['Description', 'start', 'description'],
    ],
  },
  Stone: {
    idRegExp: smallFindsCommon.idRegExp,
    idDerived: smallFindsCommon.idDerived,
    slugRegExp: smallFindsCommon.slugRegExp,
    idFormatter: smallFindsCommon.idFormatter,
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Date Collected', 'start', 'date_retrieved'],
      ['Primary Classification', 'start', 'stone_primary_classification_id'],
      ['Material', 'start', 'material_id'],
      ['Field Reading', 'start', 'field_description'],
      ['Description', 'start', 'description'],
    ],
  },
  Lithic: {
    idRegExp: smallFindsCommon.idRegExp,
    idDerived: smallFindsCommon.idDerived,
    slugRegExp: smallFindsCommon.slugRegExp,
    idFormatter: smallFindsCommon.idFormatter,
    categorizerFunc: (fields) => {
      const d = fields as TFields<'Lithic'>
      return {
        'Registration Scope': d.artifact_no === 0 ? 0 : 1,
      }
    },
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Date Collected', 'start', 'date_retrieved'],
      ['Weight', 'start', 'weight'],
      ['Type(s)', 'start', 'onps'],
      ['Field Reading', 'start', 'field_description'],
    ],
  },
  Fauna: {
    idRegExp: smallFindsCommon.idRegExp,
    idDerived: smallFindsCommon.idDerived,
    slugRegExp: smallFindsCommon.slugRegExp,
    idFormatter: smallFindsCommon.idFormatter,
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Taxa', 'start', 'primary_taxon_id'],
      ['Bone', 'start', 'bone'],
    ],
  },
  Metal: {
    idRegExp: smallFindsCommon.idRegExp,
    idDerived: smallFindsCommon.idDerived,
    slugRegExp: smallFindsCommon.slugRegExp,
    idFormatter: smallFindsCommon.idFormatter,
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Date Collected', 'start', 'date_retrieved'],
      ['Primary Classification', 'start', 'metal_primary_classification_id'],
      ['Material', 'start', 'material_id'],
      ['Field Reading', 'start', 'field_description'],
      ['Field Notes', 'start', 'field_notes'],
      ['Description', 'start', 'description'],
    ],
  },
  Glass: {
    idRegExp: smallFindsCommon.idRegExp,
    idDerived: smallFindsCommon.idDerived,
    slugRegExp: smallFindsCommon.slugRegExp,
    idFormatter: smallFindsCommon.idFormatter,
    tabHeaders: [
      ['Name', 'start', 'tag'],
      ['Date Collected', 'start', 'date_retrieved'],
      ['Primary Classification', 'start', 'glass_primary_classification_id'],
      ['Field Reading', 'start', 'field_description'],
      ['Field Notes', 'start', 'field_notes'],
      ['Description', 'start', 'description'],
    ],
  },
}

export { moduleDefinitions }
