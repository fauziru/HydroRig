window.Vue = require('vue')
import Vuex, { createLogger } from 'vuex'
import Notification from './notification'
import LoadState from './loadstate'

Vue.use(Vuex)

const debug = process.env.APP_ENV !== 'production'

export default new Vuex.Store(
  {
    modules: {
      Notification,
      LoadState
    },
    strict: debug,
    plugins: debug ? [createLogger()] : []
  }
)
