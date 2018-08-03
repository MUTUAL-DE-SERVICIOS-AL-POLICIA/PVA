window._ = require('lodash')
window.$ = window.jQuery = require('jquery')
window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.headers.common['Accept'] = 'application/json'
window.axios.defaults.headers.common['Content-Type'] = 'application/json'
if (localStorage.getItem('token_type') && localStorage.getItem('token')) {
  window.axios.defaults.headers.common['Authorization'] = `${localStorage.getItem('token_type')} ${localStorage.getItem('token')}`
}