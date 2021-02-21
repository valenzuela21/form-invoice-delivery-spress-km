import Vue from 'vue'
import App from './App.vue'
import store from "frontend/store";
import Buefy from 'buefy'

Vue.use(Buefy)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#vue-frontend-app',
  store,
  render: h => h(App)
})
