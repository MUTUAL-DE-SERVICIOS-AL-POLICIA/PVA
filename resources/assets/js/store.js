export default {
  state: {
    currentUser: localStorage.getItem('user') || null,
  },
  getters: {
    currentUser(state) {
      return JSON.parse(state.currentUser)
    },
  },
  mutations: {
    logout(state) {
      localStorage.removeItem('user')
      localStorage.removeItem('token')
      localStorage.removeItem('token_type')
      state.currentUser = null
    }
  },
  actions: {
    logout(context) {
      context.commit('logout')
    },
  }
}