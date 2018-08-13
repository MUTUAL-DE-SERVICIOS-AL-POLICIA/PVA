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
        <v-list-tile v-for="item in menu_left" :key="item.title" @click="" :to="{name: item.href}">
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
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
      <v-toolbar-side-icon @click.stop="drawer = !drawer"><v-icon> fa fa-bars</v-icon> </v-toolbar-side-icon>
      <v-btn icon @click.stop="miniVariant = !miniVariant">
        <v-icon v-html="miniVariant ? 'fa fa-list' : 'fa fa-bars'"></v-icon>
      </v-btn>
      <v-btn icon @click.stop="clipped = !clipped">
        <v-icon>_</v-icon>
      </v-btn>
      <v-btn icon @click.stop="fixed = !fixed">
        <v-icon>o</v-icon>
      </v-btn>
      <v-toolbar-title v-text="title"></v-toolbar-title>
      <v-spacer></v-spacer>  
      <v-spacer></v-spacer>
      <v-toolbar-items class="hidden-sm-and-down">
        <v-btn flat>Notificaciones</v-btn>
        <v-btn flat>Mensajes</v-btn>
        <v-menu offset-y>
          <v-btn
            slot="activator"
            color="primary"
            dark
          >
            {{ this.$store.getters.currentUser.username }} [ {{ this.$store.getters.currentUser.roles[0].name }} ]
          </v-btn>
          <v-list>
            <v-list-tile
              v-for="(item, i) in menu_user"
              :key="item.title"
              @click=""
            >
              <v-list-tile-title @click="logout">{{ item.title }}</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </v-toolbar-items>      
     </v-toolbar>
     <v-content>
      <v-scale-transition>
      <router-view></router-view>
      </v-scale-transition>
     </v-content>
     <v-footer :fixed="fixed" app>
      <span>&copy; 2017</span>
    </v-footer>
    </v-app>
</template>

<script>
import {guest, admin} from '../menu.js'
  export default {
    data () {
      return {
        clipped: false,
        drawer: true,
        fixed: false,
        menu_left: null ,
        role: null,
        menu_user: [{
          icon: '',
          title: 'Salir'
        }],
        miniVariant: false,
        right: true,
        rightDrawer: false,
        title: 'RRHH'
      }
    },
    name: 'app-header',
    methods: {
      logout() {
        this.$store.dispatch('logout')
        this.$router.push('login')
      }
    },
    created: function () {
      console.log(this.$store.getters.currentUser.roles[0].name)
      var aux = this.$store.getters.currentUser.roles[0].name
      this.menu_left = admin
    }
  }
</script>

<style>
  .btn {
    background-color: transparent;
  }
</style>