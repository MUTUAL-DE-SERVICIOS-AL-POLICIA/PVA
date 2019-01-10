import menu from "./menu.js";
import moment from 'moment';

export default {
  state: {
    currentUser: localStorage.getItem('user') || null,    
    ldapAuth: JSON.parse(process.env.MIX_LDAP_AUTHENTICATION),
    dateNow: moment().format('Y-MM-DD'),
    token: {
      type: localStorage.getItem('token_type') || null,
      value: localStorage.getItem('token') || null
    },
    options: localStorage.getItem('options'),
  },
  getters: {
    ldapAuth(state) {
      return state.ldapAuth
    },
    currentUser(state) {
      return JSON.parse(state.currentUser)
    },
    menuLeft(state) {
      if (state.currentUser) {
        if (JSON.parse(state.currentUser).roles[0]) {
          return menu[JSON.parse(state.currentUser).roles[0].name];
        } else {
          return menu['empleado'];
        }
        return
      }
      return null
    },
    dateNow(state) {
      return state.dateNow
    },
    token(state) {
      return state.token
    },
    options(state) {
      return state.options
    },
    role(state) {
      return JSON.parse(state.currentUser).roles[0].name
    }
  },
  mutations: {
    'logout': function (state) {
      localStorage.removeItem('user')
      localStorage.removeItem('token')
      localStorage.removeItem('token_type')
      state.currentUser = null
      state.menuLeft = null
      state.options = null
    },
    'login': function (state, data) {
      localStorage.setItem("token", data.token);
      localStorage.setItem("token_type", data.token_type);
      localStorage.setItem("user", JSON.stringify(data.user));
      state.currentUser = localStorage.getItem('user');
      state.token = {
        type: localStorage.getItem('token_type'),
        value: localStorage.getItem('token')
      }
      axios.defaults.headers.common['Authorization'] = `${state.token.type} ${state.token.value}`
    },
    'setDate': function(state, newValue) {
      state.dateNow = newValue;
    },
    'setOptions': function(state, data) {
      localStorage.setItem("options", JSON.stringify(data));
      state.options = localStorage.getItem('options');
    }
  },
  actions: {
    logout(context) {
      context.commit('logout')
    },
  }
}