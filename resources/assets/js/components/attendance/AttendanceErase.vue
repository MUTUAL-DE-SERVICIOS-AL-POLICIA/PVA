<template>
  <div>
    <v-btn @click.native="openDialog" color="error">BORRAR DISPOSITIVOS</v-btn>
    <v-dialog persistent v-model="show" max-width="900px" @keydown.esc="!loading ? close() : ''">
      <v-card>
        <v-toolbar dark color="secondary">
          <v-toolbar-title class="white--text">{{ title }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon dark @click.native="close()" v-if="!loading">
            <v-icon>close</v-icon>
          </v-btn>
        </v-toolbar>
        <v-stepper v-model="step">
          <v-stepper-items>
            <v-stepper-content step="1">
              <v-card-text class="headline font-weight-light">
                <v-icon large color="danger">warning</v-icon> ¿Seguro que desea {{ title.toLowerCase() }}?
              </v-card-text>
            </v-stepper-content>
            <v-stepper-content step="2">
              <Loading v-if="loading"/>
              <template v-else>
                <v-card-text>
                  <div class="headline font-weight-light"><v-icon large color="danger">warning</v-icon> ¿Seguro que desea {{ title.toLowerCase() }}?</div>
                </v-card-text>
                <v-alert v-for="(error, index) in data.errors" :key="index" :value="true" type="error" dismissible>{{ error }}</v-alert>
                <v-alert :value="true" type="error" v-if="data.devices">
                  <div color="red--text" class="title font-weight-light mb-3">Aún quedan los siguientes registros en los dispositivos:</div>
                  <v-data-table
                    :headers="headers"
                    :items="data.devices"
                    :loading="loading"
                    class="elevation-1"
                  >
                    <template v-slot:items="props">
                      <td class="text-center">{{ props.item.MachineAlias }}</td>
                      <td class="text-center">{{ props.item.users.reduce((x, y) => x + (y.checks || 0), 0) }}</td>
                      <td class="text-center">{{ props.item.MachineNumber }}</td>
                    </template>
                  </v-data-table>
                </v-alert>
              </template>
            </v-stepper-content>
            <v-stepper-content step="3">
              <Loading v-if="loading"/>
              <template v-else>
                <v-card-text>
                  <div class="headline font-weight-light">
                    <v-icon large color="success">check</v-icon> Registros eliminados correctamente
                  </div>
                </v-card-text>
              </template>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>

        <v-card-actions v-if="!loading">
          <v-spacer></v-spacer>
          <v-btn color="success" @click.native="close"><v-icon>close</v-icon> Cerrar</v-btn>
          <v-btn v-if="step == 1" color="error" @click.native="getDevices()"><v-icon>check</v-icon> Aceptar</v-btn>
          <v-btn v-if="step == 2" color="error" @click.native="eraseDevices()"><v-icon>check</v-icon> Borrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import Loading from '../Loading'

export default {
  name: 'AttendanceErase',
  components: {
    Loading
  },
  data() {
    return {
      loading: false,
      show: false,
      step: 1,
      data: {
        errors: [],
        devices: []
      },
      title: 'BORRAR REGISTROS DE BIOMÉTRICOS',
      headers: [
        {
          text: 'Ubicación',
          align: 'center',
          sortable: false,
          value: 'MachineAlias'
        }, {
          text: '# de marcados',
          align: 'center',
          sortable: false,
          value: 'users.length'
        }, {
          text: 'Biométrico',
          align: 'center',
          sortable: true,
          value: 'MachineNumber'
        }
      ],
      valid: false
    }
  },
  methods: {
    openDialog() {
      this.step = 1
      this.show = true
      this.loading = false
      this.valid = false
    },
    close() {
      this.step = 1
      this.show = false
      this.loading = false
      this.valid = false
    },
    async getDevices() {
      try {
        this.step = 2
        this.loading = true
        let res = await axios.get(`attendance`)
        this.data = res.data
        this.loading = false
      } catch (e) {
        console.log(e)
        this.loading = false
        this.step = this.step - 1
      }
    },
    async eraseDevices() {
      try {
        this.step = 3
        this.loading = true
        if (process.env.NODE_ENV == 'production') {
          let res = await axios.delete(`attendance/all`)
          this.data = res.data
        } else {
          this.data = {
            errors: [],
            devices: []
          }
        }
        this.loading = false
      } catch (e) {
        console.log(e)
        this.loading = false
        this.step = this.step - 1
      }
    }
  }
}
</script>
