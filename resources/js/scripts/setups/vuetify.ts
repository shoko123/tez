import { createVuetify } from 'vuetify'
import '../../../css/app.scss'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
  theme: {
    defaultTheme: 'light',
  },
  // Locale & date are defined to support iso 8061 YYYY-MM-DD date presentation
  locale: {
    locale: 'en',
  },
  date: {
    locale: {
      en: 'en-CA',
    },
  },
  components,
  directives,
})

export default vuetify
