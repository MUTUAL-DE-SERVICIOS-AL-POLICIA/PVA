<template>
  <v-app>
    <v-navigation-drawer
      stateless
      value="true"
      persistent
      :mini-variant="miniVariant"
      :clipped="true"
      v-model="drawer"
      enable-resize-watcher
      fixed
      app
      v-if="this.$store.getters.currentUser"
      class="normal"
    >
      <v-list>
        <div v-for="item in menuLeft" :key="item.title">
          <v-list-tile :to="{name: item.href, params: item.params}" v-if="!item.group" class="mb-0">
            <v-tooltip right>
              <v-list-tile-action slot="activator">
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-tile-action>
              <span>{{ item.title }} {{ item.params }}</span>
            </v-tooltip>
            <v-list-tile-content>
              <v-list-tile-title>
                {{ item.title }}
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-list-group
            v-else
            :value="false"
            :class="`${item.color} lighten-3`"
          >
            <v-list-tile slot="activator">
              <v-tooltip right>
                <v-list-tile-action slot="activator">
                  <v-icon>{{ item.icon }}</v-icon>
                </v-list-tile-action>
                <span>{{ item.title }}</span>
              </v-tooltip>
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ item.title }}
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>

            <v-list :class="`${item.color} lighten-5`">
              <v-list-tile v-for="group in item.group" :key="group.href" :to="{name: group.href, params: group.params}">
                <v-tooltip right>
                  <v-list-tile-action slot="activator">
                    <v-icon>{{ group.icon }}</v-icon>
                  </v-list-tile-action>
                  <span>{{ group.title }}</span>
                </v-tooltip>
                <v-list-tile-content>
                  <v-list-tile-title>
                    {{ group.title }}
                  </v-list-tile-title>
                </v-list-tile-content>
              </v-list-tile>
            </v-list>
          </v-list-group>
        </div>
      </v-list>
    </v-navigation-drawer>

    <v-toolbar
      class="primary white--text"
      app
      :clipped-left="true"
      v-if="this.$store.getters.currentUser"
    >
      <v-btn icon @click.stop="miniVariant = !miniVariant">
        <v-icon v-html="miniVariant ? 'more_vert' : 'menu'" class="white--text"></v-icon>
      </v-btn>
      <v-toolbar-title v-text="`SISTEMA DE RECURSOS HUMANOS`"></v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-items>
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
export default {
  data() {
    return {
      drawer: true,
      menuLeft: null,
      miniVariant: true
    };
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
    }
  },
  created: function() {
    this.getDate();
    this.menuLeft = this.$store.getters.menuLeft;
  }
};
</script>

<style>
.copyleft {
  display:inline-block;
  transform: rotate(180deg);
}
</style>
