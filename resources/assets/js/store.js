import menu from "./menu.js";
export default {
  state: {
    currentUser: localStorage.getItem('user') || null,
    menuLeft: null
  },
  getters: {
    currentUser(state) {
      return JSON.parse(state.currentUser)
    },
    menuLeft(state) {
      if (state.currentUser) {
        return menu[JSON.parse(state.currentUser).roles[0].name]  
      } 
      return null
    } 
  },
  mutations: {
    logout(state) {
      localStorage.removeItem('user')
      localStorage.removeItem('token')
      localStorage.removeItem('token_type')
      state.currentUser = null
      state.menuLeft = null
    }
  },
  actions: {
    logout(context) {
      context.commit('logout')
    },
  }
}