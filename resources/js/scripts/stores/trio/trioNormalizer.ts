import { storeToRefs } from 'pinia'
import { computed, ref } from 'vue'
import { useMainStore } from '../main'
import type {
  TApiTrio,
  TOptionObj,
  TGroupObj,
  TCategoriesArray,
  TGroupOrFieldToKeyObj,
  TApiGroup,
  TOptionWithoutGroupKey,
} from '@/types/trioTypes'

let categories: TCategoriesArray = []
let groupsObj: TGroupObj = {}
let optionsObj: TOptionObj = {}
let groupLabelToGroupKeyObj: TGroupOrFieldToKeyObj = {}
let itemFieldsToGroupKeyObj: TGroupOrFieldToKeyObj = {}
let orderByOptions: string[] = []
let catCnt = 0
const grpCnt = ref(0)
let prmCnt = 0

function clear() {
  categories = []
  groupsObj = {}
  optionsObj = {}
  groupLabelToGroupKeyObj = {}
  itemFieldsToGroupKeyObj = {}
  catCnt = 0
  grpCnt.value = 0
  prmCnt = 0
}

export async function normalizeTrio(apiTrio: TApiTrio) {
  clear()

  console.log(`normalizeTrio()`)
  apiTrio.forEach((cat) => {
    categories.push({ name: cat.name, groupKeys: [] })
    cat.groups.forEach((grp) => {
      categories[catCnt]!.groupKeys.push(grpKey.value)
      switch (grp.code) {
        case 'EM':
          handleEM(grp as TApiGroup<'EM'>)
          break

        case 'LV':
          handleLV(grp as TApiGroup<'LV'>)
          break

        case 'FO':
          handleOF(grp as TApiGroup<'FO'>)
          break

        case 'SF':
          handleSF(grp as TApiGroup<'SF'>)
          break

        case 'TM':
          handleTag(grp as TApiGroup<'TM'>)
          break

        case 'TG':
          handleTag(grp as TApiGroup<'TG'>)
          break

        case 'ON':
          handleOnp(grp as TApiGroup<'ON'>)
          break

        case 'CT':
          handleCT(grp as TApiGroup<'CT'>)
          break

        case 'MD':
          handleMD(grp as TApiGroup<'MD'>)
          break

        case 'OB':
          handleOB(grp as TApiGroup<'OB'>)
          break

        default:
          console.log(`*** trioNormalize() failed - unrecognized code on group: "${grp}"`)
      }
      grpCnt.value++
    })
    catCnt++
  })

  return {
    trio: { categories, groupsObj, optionsObj },
    groupLabelToGroupKeyObj,
    itemFieldsToGroupKeyObj,
    orderByOptions,
  }
}

function addToGroupAndOptionObjects(grp: TApiGroup, apiOptions: TOptionWithoutGroupKey[]) {
  // console.log(
  //   `addGroupAndOptions group: ${JSON.stringify(grp, null, 2)} options: ${JSON.stringify(apiOptions, null, 2)}`,
  // )
  // eslint-disable-next-line
  const { options, ...groupWithoutOption } = grp

  const grpToSave = {
    ...groupWithoutOption,
    optionKeys: <string[]>[],
    selectorCategoryIndex: catCnt,
  }

  apiOptions.forEach((p) => {
    const prmKey = pad(prmCnt, 3)
    grpToSave.optionKeys.push(prmKey)
    optionsObj[prmKey] = { ...p, groupKey: pad(grpCnt.value, 3) }
    prmCnt++
  })

  groupsObj[grpKey.value] = grpToSave
  groupLabelToGroupKeyObj[grpToSave.label] = grpKey.value

  if ('LV' === grpToSave.code) {
    itemFieldsToGroupKeyObj[grpToSave.field_name!] = grpKey.value
  }
}

const grpKey = computed(() => {
  return pad(grpCnt.value, 3)
})

function pad(num: number, size: number): string {
  let s = num + ''
  while (s.length < size) s = '0' + s
  return s
}

function processDependency(dependency: string[][]) {
  return dependency.map((x) => {
    return processDependencyOrArray(x)
  })
}

function processDependencyOrArray(dependencyOrArray: string[]) {
  return dependencyOrArray.map((x) => {
    const pieces = x.split(':')
    const group = groupsObj[groupLabelToGroupKeyObj[pieces[0]!]!]!
    //console.log(`groupLabel: ${pieces[0]}. key: ${groupLabelToGroupKeyObj[pieces[0]]}  `);
    const res = group.optionKeys.find((x) => optionsObj[x]!.text === pieces[1])
    if (res === undefined) {
      console.log(
        `TRIO normalizer can't find dependency: ${x}. key: ${groupLabelToGroupKeyObj[pieces[0]!]}  `,
      )
    }
    return res!
  })
}

function handleEM<C extends 'EM'>(grp: TApiGroup<C>) {
  const options = grp.options.map((x) => {
    return { text: x.label, extra: x.index }
  })
  grp.dependency = processDependency(grp.dependency)
  addToGroupAndOptionObjects(grp, options)
}

function handleLV<C extends 'LV'>(grp: TApiGroup<C>) {
  const options = grp.options.map((x) => {
    return { text: x.label, extra: x.id }
  })
  grp.dependency = processDependency(grp.dependency)
  addToGroupAndOptionObjects(grp, options)
}

function handleOF<C extends 'FO'>(grp: TApiGroup<C>) {
  const options = grp.options.map((x) => {
    return { text: x.label, extra: x.id }
  })
  addToGroupAndOptionObjects(grp, options)
}

function handleCT<C extends 'CT'>(grp: TApiGroup<C>) {
  const options = grp.options.map((x) => {
    return { text: x.label, extra: x.index }
  })
  addToGroupAndOptionObjects(grp, options)
}

function handleSF<C extends 'SF'>(grp: TApiGroup<C>) {
  const options = Array(6).fill({ text: '', extra: null })
  addToGroupAndOptionObjects(grp, options)
}

function handleTag<C extends 'TM' | 'TG'>(grp: TApiGroup<C>) {
  const options = [...grp.options].map((x) => {
    return { text: x.label, extra: x.tag_id }
  })
  grp.dependency = processDependency(grp.dependency)
  addToGroupAndOptionObjects(grp, options)
}

// Optional Numeric Properties
function handleOnp<C extends 'ON'>(grp: TApiGroup<C>) {
  const options = [...grp.options].map((x) => {
    return { text: x.label, extra: x.onp_id }
  })
  addToGroupAndOptionObjects(grp, options)
}

function handleMD<C extends 'MD'>(grp: TApiGroup<C>) {
  const { mediaCollectionNames } = storeToRefs(useMainStore())
  const options = mediaCollectionNames.value.map((x) => {
    return { text: x, extra: '' }
  })
  addToGroupAndOptionObjects(grp, options)
}

function handleOB<C extends 'OB'>(grp: TApiGroup<C>) {
  orderByOptions = grp.options
  const options = Array<TOptionWithoutGroupKey>(grp.options.length).fill({ text: '', extra: null })
  addToGroupAndOptionObjects(grp, options)
}
