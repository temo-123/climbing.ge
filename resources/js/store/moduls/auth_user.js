import Vue from "vue";
import Vuex from "vuex";
import axios from "axios"

Vue.use(Vuex);

export default new Vuex.Store({
  actions: {
    async authing_user(ctx){
      axios
      .get('./api/auth_user')
      .then((response)=>{ 
          const action_user = response.data
          ctx.commit('update_user_data', action_user)
      })
    }
  },

  mutations: {
    update_user_data(state, auth_user){
      state.auth_user = auth_user
    }
  },

  state: {
    auth_user: [],
    is_user_login: false,
  },

  getters: {
    get_auth_user(state){
       return state.auth_user
    }
  },
});