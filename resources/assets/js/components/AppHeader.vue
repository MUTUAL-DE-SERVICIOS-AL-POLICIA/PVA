<template>
  <nav class="navbar navbar-expand-md navbar-light navbar-laravel" v-if="this.$store.getters.user">
    <div class="container">
      <router-link class="navbar-brand" to="home">PVA</router-link>
      <ul class="nav">
        <li class="dropdown">
          <button class="btn dropdown-toggle btn-transparent btn-default" type="button" id="dropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-user pull-left"></span> {{ this.$store.getters.user }} <span class="caret"></span>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownProfile">
            <router-link @click.native="logout" class="dropdown-item" tag="a" to="/login">
              <span class="fa fa-power-off pull-left"></span> Salir
            </router-link>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  name: "AppHeader",
  methods: {
    async logout() {
      try {
        await axios.get("/api/auth/logout");
        this.$store.dispatch("logout");
        this.$router.push("login");
      } catch (e) {
        console.log(e);
      }
    }
  }
};
</script>

<style>
.btn {
  background-color: transparent;
}
</style>