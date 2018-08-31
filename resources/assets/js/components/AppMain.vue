<template>
  <v-app>
  <v-navigation-drawer
      persistent
      :mini-variant="miniVariant"
      :clipped="clipped"
      v-model="drawer"
      enable-resize-watcher
      fixed
      app
      v-if="this.$store.getters.currentUser"
    >
      <v-list dense>
        <v-list-tile v-for="item in menu_left" :key="item.title" :to="{name: item.href, params: item.params}">
          <v-list-tile-action>
            <v-tooltip right>
              <v-icon slot="activator">{{ item.icon }}</v-icon>
              <span>{{ item.title }}</span>
            </v-tooltip>
            
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>
              {{ item.title }}
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>        
      </v-list>
    </v-navigation-drawer>
    <v-toolbar app :clipped-left="clipped" v-if="this.$store.getters.currentUser">
      <v-btn icon @click.stop="miniVariant = !miniVariant">
        <v-icon v-html="miniVariant ? 'more_vert' : 'menu'"></v-icon>
      </v-btn>
      <v-toolbar-title v-text="titles"></v-toolbar-title>
      <v-spacer></v-spacer>  
      <v-spacer></v-spacer>
      <v-toolbar-items class="">
        <v-menu offset-y>
          <v-btn
            slot="activator"
            color="primary"
            dark
          >
          <template v-if="this.$store.getters.currentUser.username">
            <v-icon>person </v-icon>
            <div>{{ this.$store.getters.currentUser.username }}</div>
          </template>
          </v-btn>
          <v-list>
            <v-list-tile @click="logout">
              <v-icon>lock</v-icon> Cerrar Sesión
            </v-list-tile>
          </v-list>
        </v-menu>
      </v-toolbar-items>      
     </v-toolbar>
     <v-content>
      <v-slide-x-transition>
      <router-view></router-view>
      </v-slide-x-transition>
     </v-content>
     <v-footer :fixed="fixed" app v-if="this.$store.getters.currentUser">
      <span>MUSERPOL &copy; 2018</span>
    </v-footer>
    </v-app>
</template>

<script>
import { guest, admin } from "../menu.js";
export default {
  data() {
    return {
      clipped: true,
      drawer: true,
      fixed: true,
      menu_left: null,
      role: null,
      
      miniVariant: true,
      right: true,
      rightDrawer: false,
      titles: "SISTEMA DE RECURSOS HUMANOS"
    };
  },
  name: "app-header",
  methods: {
    logout() {
      this.$store.dispatch("logout");
      this.$router.push("login");
    }
  },
  created: function() {
    if (this.$store.getters.currentUser) {
      var aux = this.$store.getters.currentUser.roles[0].name;
      this.menu_left = admin;
    }
  }
};
</script>