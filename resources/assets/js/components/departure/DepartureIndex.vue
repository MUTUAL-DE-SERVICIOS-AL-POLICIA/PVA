<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Salidas y Licencias</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-chip color="secondary white--text"> Hrs/mes: {{ hrsxMes }} </v-chip>
        <v-chip color="secondary white--text"> dias/año: {{ dayxYear }} </v-chip>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-toolbar-title>
          <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              clearable
              single-line
              hide-details
              width="20px"
            ></v-text-field>
        </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <DepartureForm :bus="bus"/>
        <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
        :headers="headers"
        :items="departures"
        :search="search"
        :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
        disable-initial-sort
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr :class="checkEnd(props.item)">
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.certificate.correlative + '/' + props.item.certificate.year }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.departure_reason.departure_type.name }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.departure_reason.name }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ $moment(props.item.created_at).format('DD/MM/YYYY') }} </td>
            <td class="text-xs-center"> {{ (props.item.approved == true) ? 'APROBADO' : (props.item.approved == false) ? 'RECHAZADO' : 'PENDIENTE' }} </td>
            <td class="justify-center layout">
              <v-tooltip top v-if="props.item.approved == null">
                <v-btn slot="activator" flat icon color="accent" @click="print(props.item)">
                  <v-icon>print</v-icon>
                </v-btn>
                <span>Imprimir</span>
              </v-tooltip>
              <v-tooltip top v-if="props.item.approved == null">
                <v-btn slot="activator" flat icon color="accent" @click="editItem(props.item, 'edit')">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip top v-if="props.item.approved == null">
                <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                  <v-icon>delete</v-icon>
                </v-btn>
                <span>Eliminar</span>
              </v-tooltip>
            </td>
          </tr>
        </template>
        <template slot="expand" slot-scope="props">
          <v-card flat>
            <v-card-text>
              <v-list>
                <v-list-tile-content><p><strong>Fecha de salida: </strong>{{ $moment(props.item.departure_date).format('DD/MM/YYYY') }} {{ $moment(props.item.departure_time, "HH:mm:ss").format("HH:mm") }} </p></v-list-tile-content>
                <v-list-tile-content><p><strong>Fecha de retorno: </strong>{{ $moment(props.item.return_date).format('DD/MM/YYYY') }} {{ $moment(props.item.return_time, "HH:mm:ss").format("HH:mm") }} </p></v-list-tile-content>
                <v-list-tile-content><p><strong>Destino: </strong>{{ props.item.destiny }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Descripción: </strong>{{ props.item.description }}</p></v-list-tile-content>
              </v-list>
            </v-card-text>
          </v-card>
        </template>
        <v-alert slot="no-results" :value="true" color="error">
          La búsqueda de "{{ search }}" no encontró resultados.
        </v-alert>
    </v-data-table>
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue";
import DepartureForm from "./DepartureForm";
import RemoveItem from "../RemoveItem";

export default {
  name: "ContractIndex",
  components: {
    DepartureForm,
    RemoveItem
  },
  data: () => ({
    bus: new Vue(),
    headers: [
      {
        text: "No. Solicitud",
        value: "certificate.correlative",
        align: "center"
      },
      {
        text: "Tipo",
        value: "departure_reason.departure_type.name",
        align: "center"
      },
      {
        text: "Razón",
        value: "departure_reason.name",
        align: "center"
      },
      {
        text: "Fecha de Solicitud",
        value: "created_at",
        align: "center"
      },
      {
        text: "Estado",
        value: "approved",
        align: "center"
      },
      {
        text: "Acciones",
        value: "",
        align: "center",
        sortable: false
      }
    ],
    departures: [],
    departureComision: [],
    departureLicence: [],
    search: "",
    switch1: true,
    contracState: "vigentes",
    hrsxMes: null,
    dayxYear: null
  }),
  async mounted() {
    this.getDepartures()
    this.bus.$on("closeDialog", departures => {
      this.getDepartures()
    })
    this.bus.$on("printDeparture", item => {
      this.print(item)
    })
  },
  methods: {
    async getDepartures() {
      try {
        let contract = await axios.get('/contract/last_contract/' + this.$store.getters.id);
        let res = await axios.get(`/departure/get_departures/${contract.data.id}`);
        this.departures = res.data
        let departure_used = await axios.get('/departure/get_departures_used/' + this.$store.getters.id);
        this.hrsxMes = Math.trunc((departure_used.data.total_minutes_month_rest / 60)) + " hr. y " + (departure_used.data.total_minutes_month_rest % 60) + " min.";
        this.dayxYear = Math.trunc((departure_used.data.total_minutes_year_rest / 480)) + " dia. y " + (departure_used.data.total_minutes_year_rest % 480 / 60) + " hr.";
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item, mode) {
      this.bus.$emit("openDialog", $.extend({}, item, { mode: mode }));
    },
    async removeItem(item) {
      let departure = await axios.get("/departure/" + item.id
      );
      if (departure.data.approved == true) {
        alert(
          "No se puede eliminar. Porque esta solicitud ya se encuentra aprobada"
        );
      } else {
        this.bus.$emit("openDialogRemove", `/departure/${item.id}`);
      }
    },
    fullName(employee) {
      let names = `${employee.last_name || ""} ${employee.mothers_last_name ||
        ""} ${employee.surname_husband || ""} ${employee.first_name ||
        ""} ${employee.second_name || ""} `;
      names = names
        .replace(/\s+/gi, " ")
        .trim()
        .toUpperCase();
      return names;
    },
    checkEnd(departure) {
      if (departure.approved == true) {
        return "";
      } else if (departure.approved == false){
        return "danger";
      } else {
        return "warning";
      }
    },
    async print(item) {
      try {
        if ('id' in item) {
          let res = await axios({
            method: "GET",
            url: `/departure/print/${item.id}`,
            responseType: "arraybuffer"
          });
          let blob = new Blob([res.data], {
            type: "application/pdf"
          });
          printJS(window.URL.createObjectURL(blob));
        }
      } catch (e) {
        console.log(e);
      }
    }
  }
};
</script>