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
      v-if="$store.getters.user"
      class="normal"
    >
      <v-list>
        <div v-for="item in menuLeft" :key="item.title" class="mb-0 mt-0">
          <template v-if="checkPermission(item)">
            <v-list-tile
              v-if="!item.group"
              :to="{name: item.href}"
              active-class="tertiary"
              @click.stop="miniVariant = true"
              @mouseover="miniVariant = false"
              @mouseout="miniVariant = true"
            >
              <v-list-tile-action>
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-tile-action>
              <v-list-tile-title>{{ item.title }}</v-list-tile-title>
            </v-list-tile>

            <v-list-group
              v-else
              :value="false"
              :prepend-icon="item.icon"
              @mouseover="miniVariant = false"
              @mouseout="miniVariant = true"
            >
              <v-list-tile slot="activator">
                <v-list-tile-title :class="miniVariant ? 'pl-3' : ''">{{ item.title }}</v-list-tile-title>
              </v-list-tile>

              <v-list class="pt-0 pb-0">
                <v-list-tile
                  v-for="group in item.group"
                  :key="group.href"
                  :to="{name: group.href}"
                  active-class="tertiary"
                  @click.stop="miniVariant = true"
                  @mouseover="miniVariant = false"
                  @mouseout="miniVariant = true"
                >
                  <template v-if="checkPermission(item)">
                    <v-list-tile-action>
                      <v-icon :class="!miniVariant ? 'pl-4' : 'ml-2'">{{ group.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title>{{ group.title }}</v-list-tile-title>
                  </template>
                </v-list-tile>
              </v-list>
            </v-list-group>
          </template>
        </div>
      </v-list>
    </v-navigation-drawer>

    <v-toolbar
      app
      :class="bar.color"
      :clipped-left="clipped"
      v-if="$store.getters.user"
    >
      <v-btn icon @click.stop="drawer = !drawer; miniVariant = false">
        <v-icon v-html="miniVariant ? 'more_vert' : 'menu'" class="white--text"></v-icon>
      </v-btn>
      <v-toolbar-title v-text="bar.text"></v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-menu offset-y>
          <v-btn
            slot="activator"
            color="primary"
            dark
          >
            <template v-if="$store.getters.user">
              <v-icon>person </v-icon>
              <div>{{ $store.getters.user }}</div>
            </template>
          </v-btn>
          <v-list>
            <v-list-tile :to="{name: 'profile'}">
              <v-icon>person</v-icon> Perfil
            </v-list-tile>
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
    <v-footer :fixed="true" app>
      <v-spacer></v-spacer>
      <span class="font-weight-thin caption mr-2">MUSERPOL <span class="copyleft">&copy;</span> - 2018</span>
    </v-footer>
  </v-app>
</template>

<script>
import menuLeft from '../menu.json'

export default {
  data() {
    return {
      clipped: true,
      drawer: true,
      miniVariant: true,
      menuLeft: menuLeft
    };
  },
  computed: {
    bar() {
      if (process.env.NODE_ENV == 'production') {
        return {
          color: `primary white--text`,
          text: `SISTEMA DE RECURSOS HUMANOS`
        }
      } else {
        return {
          color: `error white--text`,
          text: `SISTEMA DE RECURSOS HUMANOS (VERSIÓN DE PRUEBA)`
        }
      }
    }
  },
  name: "app-header",
  methods: {
    logout() {
      this.$store.dispatch("logout");
      this.$router.go("login");
    },
    async getDate() {
      try {
        let res = await axios.get(`/date`);
        this.$store.commit("setDate", res.data.now);
      } catch (e) {
        console.log(e);
        this.$store.commit("setDate", this.$moment().format("YYYY-MM-DD"));
      }
    },
    checkPermission(item) {
      return item.permission == null || this.$store.getters.permissions.includes(item.permission) || (this.$store.getters.role == 'admin' && item.permission != '!admin') || (this.$store.getters.role != 'admin' && item.permission == '!admin') || (this.$store.getters.role == 'admin' && this.$store.getters.id)
    }
  },
  created: function() {
    this.getDate();
  }
};
</script>

<style>
.copyleft {
  display:inline-block;
  transform: rotate(180deg);
}
</style>
