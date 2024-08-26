<template>
  <v-dialog persistent v-model="show" max-width="900px" @keydown.esc="close" scrollable>
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo registro</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Adicionar registro</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon dark @click.native="close">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-container grid-list-md text-xs-center fluid>
          <v-layout row wrap>
            <v-flex xs6>
              <v-date-picker
                v-model="check.date"
                :max="limits.end"
                :min="limits.start"
                locale="es-bo"
                first-day-of-week="1"
              ></v-date-picker>
            </v-flex>
            <v-flex xs6>
              <v-time-picker
                v-model="check.time"
                format="24hr"
              ></v-time-picker>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="success" @click.native="close"><v-icon>close</v-icon> Cerrar</v-btn>
        <v-btn color="error" @click.native="addCheck" ><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'AttendanceAdd',
  props: ['id', 'limits', 'bus'],
  data() {
    return {
      show: false,
      check: {
        date: this.$store.getters.dateNow,
        time: null
      }
    }
  },
  watch: {
    'check.time': function(newVal, oldVal) {
      if (newVal != oldVal && newVal != null) {
        if (newVal.split(':').length < 3) {
          this.check.time = `${newVal}:${(Math.random() * 59 | 1).toString().padStart(2, '0')}`
        }
      }
    }
  },
  methods: {
    close() {
      this.show = false,
      this.check = {
        date: this.$store.getters.dateNow,
        time: null
      }
    },
    async addCheck() {
      try {
        let res = await axios.patch(`attendance/${this.id}`, this.check)
        this.bus.$emit('newCheck', res.data)
        this.toastr.success('Registro adicionado correctamente')
        this.close()
      } catch (e) {
        console.log(e)
        this.toastr.error('Error al guardar el registro')
      }
    }
  }
}
</script>
