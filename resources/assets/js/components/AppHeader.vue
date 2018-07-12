<template>
  <nav class="navbar navbar-expand-md navbar-light navbar-laravel" v-if="this.$store.getters.currentUser">
    <div class="container">
      <router-link class="navbar-brand" to="/">RR.HH.</router-link>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ this.$store.getters.currentUser.username }}<span class="fa fa-bug"></span><span class="glyphicon glyphicon-user pull-right"></span></a>
          <ul class="dropdown-menu">
            <li><a v-on:click.stop="logout" href="/login">Salir</a></li>
          </ul>
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
        this.$router.push({
          path: '/login'
        })
      }
    },
  }
</script>