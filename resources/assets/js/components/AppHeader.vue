<template>
  <nav class="navbar navbar-expand-md navbar-light navbar-laravel" v-if="this.$store.getters.currentUser">
    <div class="container">
      <router-link class="navbar-brand" to="home">RR.HH.</router-link>
      <ul class="nav">
        <li class="dropdown">
          <button class="btn dropdown-toggle btn-transparent btn-default" type="button" id="dropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-user pull-left"></span> {{ this.$store.getters.currentUser.username }} <span class="caret"></span>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownProfile">
            <a v-on:click.prevent="logout" href="/login" class="dropdown-item"><span class="fa fa-power-off pull-left"></span> Salir</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
  export default {
    name: 'app-header',
    methods: {
      logout() {
        this.$store.dispatch('logout')
        this.$router.push('login')
      }
    },
  }
</script>

<style>
  .btn {
    background-color: transparent;
  }
</style>