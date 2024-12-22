// notifications.ts
//handles spinner and snackbar
import { defineStore } from 'pinia'
import { computed } from 'vue'

export const useLandingStore = defineStore('landing', () => {
  const aboutExcavation = computed(() => {
    return {
      title: `About the Jezrel-Expedition`,
      paragraphs: [
        {
          title: `Tel Ein Jezreel`,
          text: `The 2013-2018 Excavations focused on the newly-identified site of Tel Ein Jezreel. Located immediately south of the spring called Ein Jezreel ("Spring of Jezreel"), this site was inhabited from at least as early as the Late Neolithic period and appears to have reached its peak of settlement in the Early Bronze Age. Tel Ein Jezreel is located approximately one kilometer north of Tel Jezreel, a foothill of the Gilboa mountain range that was excavated in the 1990s and believed to be the location of biblical Jezreel described in 1 and 2 Kings.`,
        },
        {
          title: `Project Goals`,
          text: `The main goals of the Jezreel Expedition team were to reconstruct the changing nature of settlement and the human impact on the environment of "greater Jezreel." We began by commissioning a LiDAR (light detection and ranging) scan of 7.5 square km of greater Jezreel and conducting a traditional foot survey of the core area, some 3 square km. The results shed light on the different settlements at Jezreel from late prehistory through the 20th century and led us to choose several areas for excavation starting in 2013.`,
        },
        {
          title: `Project Results`,
          text: `Excavations in Area S - our main excavation area in Tel Ein Jezreel - yielded evidence for human settlement from as early as the Late Neolithic Period. The site was inhabited during all phases of the Early Bronze Age and sporadically in later periods (Iron, Persian, Roman, Medieval). Discoveries in other excavation areas include an Iron Age winery complex, Middle Bronze shaft tombs, and a series of paths connecting Tel Jezreel and Tel Ein Jezreel to the spring below.`,
        },
        {
          title: `Ongoing Research`,
          text: `In addition to analyzing the results of six seasons of excavation in preparation for publication, members of the Jezreel Expedition team, an international team of researchers, and archaeology students and alumni from the University of Evansville continue to research the long and fascinating history of the site.`,
        },
        {
          title: `Sponsers`,
          text: `The Jezreel Expedition was sponsored by eight consortium institutions: Campbell University, Chapman University, Moravian Theological Seminary, San Francisco Theological Seminary/Graduate Theological Union, University of Arizona, Vanderbilt University, Villanova University, and Wesley Theological Seminary. In addition to being an ASOR-Affiliated field and research project, the Jezreel Expedition's field school was certified by the Register of Professional Archaeologists (RPA). Support was also provided by the Foundation for Biblical Archaeology (Sheila Bishop), other generous donors, and members of Kibbutz Yizra'el. We are grateful to them all.`,
        },
      ],
    }
  })

  const aboutWebsite = computed(() => {
    return {
      title: `About this website`,
      paragraphs: [
        {
          title: `What is the purpose of this website?`,
          text: `This website serves as a central repository of information about the material remains excavated by the Jezreel Expedition. The bulk of the website consists of detailed, media-rich records of the small finds and their immediate contexts (loci).`,
        },
        {
          title: `Motivation`,
          text: `The Jezreel Expedition yielded a surprisingly large number of small finds, including the largest ground stone assemblage excavated at an archaeological site in Israel. This was the motivation for creating this database. During the field seasons, recording was initially done on paper and then entered into excel spreadsheets. In addition, many thousands of field and object photos were taken. This database provides a unified system for easy data retrieval.`,
        },
        {
          title: `The Registration System`,
          text: `Every find was registered according to locus, pottery basket, and registration type [pottery (PT), ground stone (GS), lithics (FL), lab (LB) and special finds (AR)]. Locus numbers include year/area/locus (for example, 14/S/123). Small finds were either assigned an individual number or were part of a basket. For example, 14/S/123PT1 refers to a pottery basket in locus 14/S/123 and 14/S/123PT1.5 refers to a specific item in this pottery basket.`,
        },
        // {
        //   title: `Login Information`,
        //   text: `Feel free to Register and use the website.`,
        // },
        {
          title: `Software Stack`,
          text: `Backend: MySql, Nginx, Laravel 11, Spatie (Permissions, Medialibrary). Frontend: Vue3, Pinia, Vuetify, Vuelidate, Vue-Router. Proudly hosted on a $6 Digital Ocean Ubuntu droplet.`,
        },
      ],
    }
  })

  const introductionText = computed(() => {
    return `The Jezreel Expedition was a survey (2012) and excavation (2013-2018) project focused on reconstructing the settlement history of "greater Jezreel" in Israel's Jezreel Valley. The project was sponsored by the Zinman Institute of Archaeology at the University of Haifa, Israel, and the University of Evansville, Indiana, USA. Norma Franklin (University of Haifa) and Jennie Ebeling (University of Evansville) co-directed the project.`
  })

  const locationText = computed(() => {
    return `Jezreel is located at the midway point of the Jezreel Valley, the largest east-west valley in Israel, on the edge of the Gilboa mountain range. The international highway the Via Maris ("Way of the Sea") ran through the valley floor and linked the site to Megiddo (Armageddon) to the west and Bet Shean (Scythopolis) to the east. The Jezreel Valley takes its name from the site, which means "God sows" in Hebrew. Its rich agricultural land and copious springs made the valley an attractive place for settlement for the last 8,000 years.`
  })

  return { introductionText, aboutExcavation, aboutWebsite, locationText }
})
