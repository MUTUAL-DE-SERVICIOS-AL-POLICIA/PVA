<template>
  <v-dialog persistent v-model="show" max-width="900px" @keydown.esc="close" scrollable>
    <v-btn slot="activator" color="info">
      <v-icon>print</v-icon>
      <div class="pl-2">Reporte</div>
    </v-btn>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Generar reporte del {{ $moment(from).format('L') }} a {{ $moment(to).format('L') }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon dark @click.native="close" v-if="!loading">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <Loading v-if="loading"/>
      <div v-else>
        <v-card-text>
          <v-container grid-list-md text-xs-center fluid>
            <v-layout row wrap>
              <v-flex xs6>
                <div class="subheading font-weight-light">Desde:</div>
                <v-date-picker
                  v-model="from"
                  no-title
                  :show-current="false"
                  :landscape="false"
                  locale="es-BO"
                  first-day-of-week="1"
                  :max="to"
                  color="green darken-2"
                ></v-date-picker>
              </v-flex>
              <v-flex xs6>
                <div class="subheading font-weight-light">Hasta:</div>
                <v-date-picker
                  v-model="to"
                  no-title
                  :show-current="false"
                  :landscape="false"
                  locale="es-BO"
                  first-day-of-week="1"
                  :min="from"
                  color="orange accent-4"
                ></v-date-picker>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="success" @click.native="close"><v-icon>close</v-icon> Cerrar</v-btn>
          <v-btn color="error" @click.native="print"><v-icon>check</v-icon> Imprimir</v-btn>
        </v-card-actions>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
import Loading from '../Loading'

export default {
  name: 'ReportPrint',
  components: {
    Loading
  },
  data() {
    return {
      loading: false,
      show: false,
      from: null,
      to: null
    }
  },
  beforeMount() {
    this.from = this.$moment(this.$store.getters.dateNow).startOf('month').format('YYYY-MM-DD')
    this.to = this.$moment(this.$store.getters.dateNow).endOf('month').format('YYYY-MM-DD')
  },
  methods: {
    close() {
      if (!this.loading) this.show = false
    },
    async print() {
      try {
        this.loading = true
        let res = await axios({
          method: "GET",
          url: `departure/report/print`,
          responseType: "arraybuffer",
          params: {
            from_date: this.from,
            to_date: this.to
          }
        });
        let blob = new Blob([res.data], {
          type: "application/pdf"
        });
        printJS(window.URL.createObjectURL(blob));
      } catch(e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>