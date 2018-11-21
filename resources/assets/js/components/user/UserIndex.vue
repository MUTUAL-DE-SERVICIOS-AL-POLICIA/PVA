<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Usuarios</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-dialog
        persistent
        v-model="dialog"
        width="500"
        @keydown.esc="$router.go({name:'userIndex', params: $store.getters.options})"
        :fullscreen="loading"
      >
        <v-btn
          slot="activator"
          color="error"
          class="mr-4"
          @click.native="updateLdap()"
          dark
        >
          Sincronizar LDAP
        </v-btn>
        <v-card :class="loading ? 'transparent' : ''">
          <v-container fluid fill-height v-if="loading" style="margin-top: 15%;">
            <v-layout row align-center justify-center>
              <v-progress-circular
                :size="100"
                :width="8"
                color="primary"
                indeterminate
              ></v-progress-circular>
            </v-layout>
          </v-container>
          <div v-else>
            <v-card-text class="primary title white--text">
              LDAP ACTUALIZADO
            </v-card-text>
            <v-card-text>
              <table>
                <tr>
                  <td>
                    <v-list-tile-content class="font-weight-bold">Total empleados: </v-list-tile-content>
                  </td>
                  <td>
                    <v-list-tile-content>{{ message.employees.total }}</v-list-tile-content>
                  </td>
                </tr>
                <tr v-if="message.employees.new">
                  <td>
                    <v-list-tile-content class="font-weight-bold">NO sincronizados: </v-list-tile-content>
                  </td>
                  <td class="error white--text">
                    <v-list-tile-content>
                      <ul v-for="employee in message.employees.new" :key="employee.id">
                        <li class="pr-4 pl-2" v-if="employee.first_name">{{ employee.first_name }} {{ employee.second_name }} {{ employee.last_name }} {{ employee.mothers_last_name }}</li>
                      </ul>
                    </v-list-tile-content>
                  </td>
                </tr>
                <tr>
                  <td>
                    <v-list-tile-content class="font-weight-bold">Total LDAP: </v-list-tile-content>
                  </td>
                  <td>
                    <v-list-tile-content>{{ message.entries.total }}</v-list-tile-content>
                  </td>
                </tr>
                <tr v-if="message.entries.old.length > 0">
                  <td>
                    <v-list-tile-content class="font-weight-bold">Eliminados: </v-list-tile-content>
                  </td>
                  <td>
                    <v-list-tile-content>
                      <ul v-for="user in message.entries.old" :key="user.id">
                        <li>{{ user }}</li>
                      </ul>
                    </v-list-tile-content>
                  </td>
                </tr>
                <tr>
                  <td>
                    <v-list-tile-content class="font-weight-bold">Cambios: </v-list-tile-content>
                  </td>
                  <td>
                    <v-list-tile-content>
                      <ul style="list-style: none; padding-left: 0;">
                        <li>+ {{ message.diff.added }}</li>
                        <li>- {{ message.diff.removed }}</li>
                      </ul>
                    </v-list-tile-content>
                  </td>
                </tr>
              </table>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="success" small @click.native="$router.go({name:'userIndex', params: $store.getters.options})"><v-icon small>check</v-icon> Cerrar</v-btn>
            </v-card-actions>
          </div>
        </v-card>
      </v-dialog>
      <v-flex xs2>
        <v-select
          :items="sources"
          xs3 md3
          label="Origen"
          v-model="sourceSelected"
        ></v-select>
      </v-flex>
    </v-toolbar>
    <UserRegistered v-if="sourceSelected == 'Registrados'" ref="UserRegistered"/>
    <UserLdap v-if="sourceSelected == 'LDAP'"/>
  </v-container>
</template>

<script>
import UserRegistered from "./UserRegistered";
import UserLdap from "./UserLdap";

export default {
  name: "userIndex",
  components: {
    UserRegistered,
    UserLdap
  },
  data() {
    return {
      sources: ["Registrados", "LDAP"],
      sourceSelected: "",
      message: {
        employees: {
          total: null,
          new: []
        },
        entries: {
          total: null,
          old: []
        },
        diff: {
          added: null,
          removed: null
        }
      },
      dialog: false,
      loading: false
    };
  },
  mounted() {
    this.sourceSelected = this.sources[0];
  },
  methods: {
    async updateLdap() {
      try {
        this.loading = true;
        let res = await axios.post(`/ldap`);
        this.message = res.data;
        this.loading = false;
        this.$refs.UserRegistered.open = true
      } catch (e) {
        console.log(e);
      }
    }
  }
};
</script>
