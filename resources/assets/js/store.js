import moment from 'moment';

export default {
  state: {
    user: localStorage.getItem('user') || null,
    role: localStorage.getItem('role') || null,
    permissions: localStorage.getItem('permissions') || null,
    ldapAuth: JSON.parse(process.env.MIX_LDAP_AUTHENTICATION),
    dateNow: moment().format('Y-MM-DD'),
    token: {
      type: localStorage.getItem('token_type') || null,
      value: localStorage.getItem('token') || null
    }
  },
  getters: {
    ldapAuth(state) {
      return state.ldapAuth
    },
    user(state) {
      return JSON.parse(state.user)
    },
    role(state) {
      return JSON.parse(state.role)
    },
    permissions(state) {
      return JSON.parse(state.permissions)
    },
    dateNow(state) {
      return state.dateNow
    },
    token(state) {
      return state.token
    }
  },
  mutations: {
    'logout': function (state) {
      localStorage.removeItem('user')
      localStorage.removeItem('token')
      localStorage.removeItem('token_type')
      state.user = null
      state.role = null
      state.permissions = null
    },
    'login': function (state, data) {
      localStorage.setItem("token", data.token);
      localStorage.setItem("token_type", data.token_type);
      localStorage.setItem("user", JSON.stringify(data.user));
      localStorage.setItem("role", JSON.stringify(data.role));
      localStorage.setItem("permissions", JSON.stringify(data.permissions));
      state.user = localStorage.getItem('user');
      state.role = localStorage.getItem('role');
      state.permissions = localStorage.getItem('permissions');
      state.token = {
        type: localStorage.getItem('token_type'),
        value: localStorage.getItem('token')
      }
      axios.defaults.headers.common['Authorization'] = `${state.token.type} ${state.token.value}`
    },
    'setDate': function(state, newValue) {
      state.dateNow = newValue;
    }
  },
  actions: {
    logout(context) {
      context.commit('logout')
    },
  }
}