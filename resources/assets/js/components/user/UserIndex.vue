<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Usuarios</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-dialog
        persistent
        v-model="dialog"
        width="600"
        @keydown.esc="$router.go({name:'userIndex'})"
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
              <v-container grid-list-md>
                <v-layout row wrap>
                  <v-flex xs12 sm6>
                    <v-card>
                      <v-card-title class="subheading font-weight-bold">Sincronización</v-card-title>
                        <v-divider></v-divider>
                        <v-list dense>
                          <v-list-tile>
                            <v-list-tile-content>Base de datos:</v-list-tile-content>
                            <v-list-tile-content class="align-end">{{ message.employees.total }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile class="white--text" :class="message.employees.new.length > 0 ? 'error' : 'primary'">
                          <v-list-tile-content>NO sincronizados:</v-list-tile-content>
                          <v-list-tile-content class="align-end" v-if="message.employees.new">
                            <ul v-for="employee in message.employees.new" :key="employee.id">
                              <li class="pr-4 pl-2" v-if="employee.first_name">{{ employee.first_name }} {{ employee.second_name }} {{ employee.last_name }} {{ employee.mothers_last_name }}</li>
                            </ul>
                          </v-list-tile-content>
                          <v-list-tile-content class="align-end">
                            <v-list-tile-content class="align-end">0</v-list-tile-content>
                          </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                          <v-list-tile-content>LDAP:</v-list-tile-content>
                          <v-list-tile-content class="align-end">{{ message.entries.total }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile v-if="message.entries.old.length > 0">
                          <v-list-tile-content>Eliminados:</v-list-tile-content>
                          <v-list-tile-content class="align-end">
                            <ul v-for="user in message.entries.old" :key="user.id">
                              <li>{{ user }}</li>
                            </ul>
                          </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                          <v-list-tile-content>Cambios:</v-list-tile-content>
                          <v-list-tile-content class="align-end">
                            <ul style="list-style: none; padding-left: 0;">
                              <li>+ {{ message.diff.added }}</li>
                              <li>- {{ message.diff.removed }}</li>
                            </ul>
                          </v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                    </v-card>
                  </v-flex>
                  <v-flex xs12 sm6 v-if="zammadSync">
                    <v-card>
                      <v-card-title class="subheading font-weight-bold">Zammad</v-card-title>
                      <v-divider></v-divider>
                      <v-list dense v-if="message.zammad.hasOwnProperty('error')">
                        <v-list-tile class="error white--text">
                          <v-list-tile-content>Error:</v-list-tile-content>
                          <v-list-tile-content class="align-end">{{ message.zammad.error }}</v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                      <v-list dense v-else>
                        <v-list-tile>
                          <v-list-tile-content>Omitidos:</v-list-tile-content>
                          <v-list-tile-content class="align-end">{{ message.zammad.skipped }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                          <v-list-tile-content>Creados:</v-list-tile-content>
                          <v-list-tile-content class="align-end">{{ message.zammad.created }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                          <v-list-tile-content>Actualizados:</v-list-tile-content>
                          <v-list-tile-content class="align-end">{{ message.zammad.updated }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                          <v-list-tile-content>Sin cambios:</v-list-tile-content>
                          <v-list-tile-content class="align-end">{{ message.zammad.unchanged }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile class="white--text" :class="message.zammad.failed > 0 ? 'error' : 'primary'">
                          <v-list-tile-content>Falla:</v-list-tile-content>
                          <v-list-tile-content class="align-end">{{ message.zammad.failed }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                          <v-list-tile-content>Desactivados:</v-list-tile-content>
                          <v-list-tile-content class="align-end">{{ message.zammad.deactivated }}</v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile>
                          <v-list-tile-content>Total:</v-list-tile-content>
                          <v-list-tile-content class="align-end">{{ message.zammad.total }}</v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                    </v-card>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="success" small @click.native="$router.go({name:'userIndex'})"><v-icon small>check</v-icon> Cerrar</v-btn>
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
    <UserRole v-if="sourceSelected == 'Roles'" ref="UserRole"/>
    <UserPermission v-if="sourceSelected == 'Permisos'" ref="UserPermission"/>
    <UserLdap v-if="sourceSelected == 'LDAP'"/>
  </v-container>
</template>

<script>
import UserRole from "./UserRole"
import UserPermission from "./UserPermission"
import UserLdap from "./UserLdap"
import { log } from 'util'

export default {
  name: "userIndex",
  components: {
    UserRole,
    UserPermission,
    UserLdap
  },
  computed: {
    zammadSync() {
      return JSON.parse(process.env.MIX_ZAMMAD_SYNC)
    }
  },
  data() {
    return {
      sources: ["Roles", "Permisos", "LDAP"],
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
        },
        zammad: {
          skipped: null,
          created: null,
          updated: null,
          unchanged: null,
          failed: null,
          deactivated: null,
          sum: null,
          total: null
        }
      },
      dialog: false,
      loading: false
    }
  },
  mounted() {
    this.sourceSelected = this.sources[0]
  },
  methods: {
    async updateLdap() {
      try {
        this.loading = true
        let res = await axios.post(`/ldap`)
        this.message = res.data
        this.loading = false
        this.$refs.UserRole.open = true
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>
