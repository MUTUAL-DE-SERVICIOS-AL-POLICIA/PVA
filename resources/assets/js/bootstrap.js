window._ = require('lodash')
window.$ = window.jQuery = require('jquery')
window.axios = require('axios').create({
  baseURL: process.env.MIX_APP_URL
})