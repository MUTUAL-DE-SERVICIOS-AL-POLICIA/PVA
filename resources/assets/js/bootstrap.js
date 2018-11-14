window._ = require('lodash')
window.$ = window.jQuery = require('jquery')
window.axios = require('axios').create({
  baseURL: process.env.MIX_APP_URL
})

let ctrlKeyDown = false
$(document).ready(() => {
  $(document).on("keydown", keydown)
  $(document).on("keyup", keyup)
})
let keydown = e => {
  if ((e.which || e.keyCode) == 116 || ((e.which || e.keyCode) == 82 && ctrlKeyDown)) {
    e.preventDefault()
  } else if ((e.which || e.keyCode) == 17) {
    ctrlKeyDown = true
  }
}
let keyup = e => {
  if ((e.which || e.keyCode) == 17) ctrlKeyDown = false
}