<template>
  <div>
    <v-btn @click.native="openDialog" color="danger">SINCRONIZAR</v-btn>
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
                <div class="headline font-weight-light">
                  <v-icon large color="success">check</v-icon> Registros sincronizados correctamente
                </div>
                <v-alert v-for="(error, index) in data.errors" :key="index" :value="true" type="error" dismissible>{{ error }}</v-alert>
                <v-data-table
                  :headers="headers"
                  :items="data.added"
                  :loading="loading"
                  class="elevation-1"
                >
                  <template v-slot:items="props">
                    <td>{{ props.item.user }}</td>
                    <td class="text-center">{{ props.item.checks }}</td>
                    <td class="text-center">{{ props.item.device }}</td>
                  </template>
                </v-data-table>
              </template>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>

        <v-card-actions v-if="!loading">
          <v-spacer></v-spacer>
          <v-btn color="success" @click.native="close"><v-icon>close</v-icon> Cerrar</v-btn>
          <v-btn v-if="step < 2" color="error" @click.native="syncDevice" ><v-icon>check</v-icon> Aceptar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import Loading from '../Loading'

export default {
  name: 'AttendanceSync',
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
        added: []
      },
      title: 'SINCRONIZAR REGISTROS DE MARCADO',
      headers: [
        {
          text: 'Empleado',
          align: 'center',
          sortable: false,
          value: 'user'
        }, {
          text: '# de marcados',
          align: 'center',
          sortable: false,
          value: 'checks'
        }, {
          text: 'Biométrico',
          align: 'center',
          sortable: true,
          value: 'device'
        }
      ]
    }
  },
  methods: {
    openDialog() {
      this.step = 1
      this.show = true
      this.loading = false
    },
    close() {
      this.step = 1
      this.show = false
      this.loading = false
    },
    async syncDevice() {
      try {
        this.step = 2
        this.loading = true
        // if (process.env.NODE_ENV == 'production') {
        if (process.env.NODE_ENV != 'production') {
          let res = await axios.post(`attendance`)
          this.data = res.data
        } else {
          this.data = {
            errors: [],
            added: []
          }
        }
        this.loading = false
      } catch (e) {
        console.log(e)
        this.loading = false
      }
    }
  }
}
</script>
