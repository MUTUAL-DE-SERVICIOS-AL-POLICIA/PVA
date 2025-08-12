window._ = require('lodash')
window.$ = window.jQuery = require('jquery')
window.axios = require('axios').create({
  baseURL: process.env.MIX_APP_URL
})
window.axios2 = require('axios').create({
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  baseURL: process.env.MIX_APP_URL_PGA
})
console.log('Base URL PGA:', process.env.MIX_APP_URL_PGA)