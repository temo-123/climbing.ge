import axios from "axios"

export default {
  namespaced: true,
  actions: {
    async authing_user(ctx){
      try {
        const response = await axios.get('auth_user');
        ctx.commit('update_user_data', response.data);
      } catch (error) {
        ctx.commit('update_user_data', null);
      }
    }
  },

  mutations: {
    update_user_data(state, auth_user){
      state.auth_user = auth_user
      state.is_user_login = !!auth_user
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
};

