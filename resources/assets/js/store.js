import moment from 'moment'

export default {
  state: {
    id: localStorage.getItem('id') || null,
    user: localStorage.getItem('user') || null,
    role: localStorage.getItem('role') || null,
    permissions: localStorage.getItem('permissions') || null,
    consultant: localStorage.getItem('consultant') || null,
    ldapAuth: JSON.parse(process.env.MIX_LDAP_AUTHENTICATION),
    dateNow: moment().format('Y-MM-DD'),
    token: {
      type: localStorage.getItem('token_type') || null,
      value: localStorage.getItem('token') || null
    },
    departure: {}
  },
  getters: {
    ldapAuth(state) {
      return state.ldapAuth
    },
    id(state) {
      return JSON.parse(state.id)
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
    consultant(state) {
      return JSON.parse(state.consultant)
    },
    dateNow(state) {
      return state.dateNow
    },
    token(state) {
      return state.token
    },
    tokenExpired(state) {
      let token = localStorage.getItem('token')
      if (token) {
        let base64 = token.split('.')[1]
        token = decodeURIComponent(atob(base64).split('').map(c => {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
        }).join(''))
        return moment().isAfter(moment.unix(JSON.parse(token).exp))
      }
    },
    departure(state) {
      return state.departure
    }
  },
  mutations: {
    'logout': function (state) {
      localStorage.removeItem('user')
      localStorage.removeItem('token')
      localStorage.removeItem('token_type')
      localStorage.removeItem('role')
      localStorage.removeItem('id')
      localStorage.removeItem('permissions')
      state.id = null
      state.user = null
      state.role = null
      state.permissions = null
      state.consultant = null
    },
    'login': function (state, data) {
      localStorage.setItem("token", data.token)
      localStorage.setItem("token_type", data.token_type)
      localStorage.setItem("id", JSON.stringify(data.id))
      localStorage.setItem("user", JSON.stringify(data.user))
      localStorage.setItem("role", JSON.stringify(data.role))
      localStorage.setItem("consultant", JSON.stringify(data.consultant))
      localStorage.setItem("permissions", JSON.stringify(data.permissions))
      state.user = localStorage.getItem('id')
      state.user = localStorage.getItem('user')
      state.role = localStorage.getItem('role')
      state.permissions = localStorage.getItem('permissions')
      state.consultant = localStorage.getItem('consultant')
      state.token = {
        type: localStorage.getItem('token_type'),
        value: localStorage.getItem('token')
      }
      axios.defaults.headers.common['Authorization'] = `${state.token.type} ${state.token.value}`
    },
    'setDate': function(state, newValue) {
      state.dateNow = newValue
    },
    'setDeparture': function(state, data) {
      state.departure = data
    }
  },
  actions: {
    logout(context) {
      context.commit('logout')
    }
  }
}