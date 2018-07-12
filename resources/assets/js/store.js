export default {
  state: {
    currentUser: localStorage.getItem('user') || null,
    loading: false,
    error: null,
  },
  getters: {
    welcome(state) {
      return 'Bienvenido'
    },
    isLoading(state) {
      return state.loading
    },
    currentUser(state) {
      return JSON.parse(state.currentUser)
    },
  },
  mutations: {
    login(state) {
      state.loading = true
    },
    loginSuccess(state, payload) {
      state.loading = false
      state.currentUser = payload.data.user
      localStorage.setItem('user', JSON.stringify(state.currentUser))
      localStorage.setItem('token', payload.data.access_token)
    },
    loginFailed(state, payload) {
      state.loading = false
      error = payload
    },
    logout(state) {
      localStorage.removeItem('user')
      localStorage.removeItem('token')
      localStorage.removeItem('token_type')
      state.currentUser = null
    }
  },
  actions: {
    login(context) {
      context.commit('login')
    },
    logout(context) {
      context.commit('logout')
    },
  }
}