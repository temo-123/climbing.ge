import Vue from "vue";
import Vuex from "vuex";
import axios from "axios"

Vue.use(Vuex);

import * as auth_user from "./moduls/auth_user"

export default new Vuex.Store({
  modules:{
    auth_user
  }
});