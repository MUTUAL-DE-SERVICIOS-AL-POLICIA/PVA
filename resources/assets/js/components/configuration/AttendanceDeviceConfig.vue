<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Biométricos</v-toolbar-title>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="devices"
      :loading="loading"
      :rows-per-page-items="[10,20,30,{text:'TODO', value:-1}]"
      disable-initial-sort
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-md-center">{{ props.item.MachineAlias }}</td>
          <td class="text-md-center">{{ props.item.IP }}</td>
          <td class="text-md-center">{{ props.item.Port }}</td>
          <td class="text-md-center">{{ props.item.MachineNumber }}</td>
          <td class="text-md-center">{{ props.item.sn }}</td>
          <td class="text-md-center">
            <v-icon v-if="Boolean(Number(props.item.Enabled))" color="success">check_circle</v-icon>
            <v-icon v-else color="error">error</v-icon>
          </td>
          <td class="text-md-center">
            <v-tooltip top>
              <v-btn small slot="activator" flat icon color="info" @click.stop="openDialog(props.item.ID)">
                <v-icon>edit</v-icon>
              </v-btn>
              <span>Editar</span>
            </v-tooltip>
          </td>
        </tr>
      </template>
      <template slot="no-data">
        <v-container fluid fill-height>
          <v-layout align-center justify-center>
            <v-progress-circular
              :width="1"
              :size="50"
              color="primary"
              indeterminate
              class="pa-5 ma-5"
            ></v-progress-circular>
          </v-layout>
        </v-container>
      </template>
    </v-data-table>
    <v-dialog
      v-model="dialog"
      width="500"
      @keydown.esc="closeDialog"
    >
      <v-card>
        <v-toolbar dark color="secondary">
          <v-toolbar-title class="white--text">Datos del Empleado</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <Loading v-if="loading"/>
          <v-time-picker
            v-else
            v-model="device.time"
            full-width
            landscape
            type="month"
            class="ma-3"
            format="24hr"
          ></v-time-picker>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" @click.native="closeDialog"><v-icon>close</v-icon> Cancelar</v-btn>
          <v-btn color="success" @click.native="updateDevice"><v-icon>check</v-icon> Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import Loading from '../Loading'

export default {
  name: 'attendanceDeviceConfig',
  components: {
    Loading
  },
  data: () => ({
    loading: true,
    devices: [],
    device: {
      time: null
    },
    dialog: false,
    headers: [
      { align: "center", text: "Nombre", class: ["ma-0", "pa-0"], value: "MachineAlias" },
      { align: "center", text: "IP", class: ["ma-0", "pa-0"], value: "IP" },
      { align: "center", text: "Puerto", class: ["ma-0", "pa-0"], value: "Port" },
      { align: "center", text: "Número", class: ["ma-0", "pa-0"], value: "MachineNumber" },
      { align: "center", text: "Identificador", class: ["ma-0", "pa-0"], value: "sn" },
      { align: "center", text: "Habilitado", class: ["ma-0", "pa-0"], value: "Enabled" },
      { align: "center", text: "Acciones", class: ["ma-0", "pa-0"], sortable: false }
    ]
  }),
  mounted() {
    this.getDevices()
  },
  methods: {
    async updateDevice() {
      try {
        this.loading = true
        let res = await axios.patch(`attendance_device/${this.device.ID}`, {
          time: this.$moment(this.device.time, 'HH:mm').format()
        })
        if (res.data.hasOwnProperty('time')) {
          this.toastr.success(`Hora establecida: ${this.$moment(res.data.time).format('HH:mm')}`)
        } else {
          this.toastr.error('Error al establecer la hora')
        }
        this.closeDialog()
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    closeDialog() {
      this.device = {
        time: null
      }
      this.dialog = false
    },
    openDialog(id) {
      this.dialog = true
      this.device = this.getDevice(id)
    },
    async getDevice(id) {
      try {
        this.loading = true
        let res = await axios.get(`/attendance_device/${id}`)
        this.device.ID = res.data.ID
        if (res.data.hasOwnProperty('time')) {
          this.device.time = this.$moment(res.data.time).format('HH:mm')
        } else {
          this.toastr.error('No se pudo obtener la hora del dispositivo')
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getDevices() {
      try {
        let res = await axios.get(`/attendance_device`)
        this.devices = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>