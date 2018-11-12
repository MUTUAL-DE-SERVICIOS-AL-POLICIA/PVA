<template>
  <v-container >
    <v-toolbar>
        <v-toolbar-title>Salidas, Comisiones y Licencias</v-toolbar-title>
        <v-spacer></v-spacer>  
         <DepartureReport :bus="bus"/>
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
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.contract.employee.identity_card }} {{ props.item.contract.employee.city_identity_card.shortened }}</td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ fullName(props.item.contract.employee) }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.contract.position.name }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.departure_reason.departure_type.name }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.departure_reason.name }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ $moment(props.item.created_at).format('DD/MM/YYYY') }} </td>
            <td class="text-md-center">
              <v-switch
                v-model="props.item.approved"
                @click.native="switchActive(props.item)"
                v-if="options.includes('active')"
              ></v-switch>
            </td>
            <td class="justify-center layout" v-if="options.length > 0">
              <v-tooltip top v-if="options.includes('print')">
                <v-btn slot="activator" flat icon color="accent" @click="print(props.item)">
                  <v-icon>print</v-icon>
                </v-btn>
                <span>Imprimir</span>
              </v-tooltip>              
            </td>
          </tr>
        </template>
        <template slot="expand" slot-scope="props">
          <v-card flat>
            <v-card-text>
              <v-list>
                <v-list-tile-content><p><strong>Fecha de salida: </strong>{{ $moment(props.item.departure_date).format('DD/MM/YYYY') }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Fecha de retorno: </strong>{{ $moment(props.item.return_date).format('DD/MM/YYYY') }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Hora de salida: </strong>{{ $moment(props.item.departure_time, "HH:mm:ss").format("HH:mm") }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Hora de retorno: </strong>{{ $moment(props.item.return_time, "HH:mm:ss").format("HH:mm") }}</p></v-list-tile-content>
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
import DepartureReport from "./DepartureReport";
import RemoveItem from "../RemoveItem";
import { admin, rrhh, juridica } from "../../menu.js";
export default {
  name: "DepartureIndex",
  components: {
    DepartureReport,
    RemoveItem
  },
  data: () => ({
    active: true,
    bus: new Vue(),
    headers: [
      {
        text: "C.I.",
        value: "contractemployee.identity_card",
        align: "center"
      },
      {
        text: "Nombres",
        value: "contract.employee.last_name",
        align: "center"
      },
      {
        text: "Puesto",
        value: "contract.position.name",
        align: "center"
      },
      {
        text: "Tipo",
        value: "departure_reason.departure_type.name",
        align: "center"
      },
      {
        text: "Razón",
        value: "contract.position.name",
        align: "center"
      },      
      {
        text: "Fecha de Solicitud",
        value: "description",
        align: "center"
      },
      {
        text: "Apobado",
        value: "approved",
        align: "center"
      },
      {
        text: "Acciones",
        value: "employee.first_name",
        align: "center",
        sortable: false
      }
    ],
    departures: [],
    search: "",
    switch1: true,
    contracState: "vigentes",
    options: []
  }),
  computed: {    
    
  },
  async created() {
    this.getDepartures();
    this.bus.$on("closeDialog", () => {
      this.getDepartures();
    });
    for (var i = 0; i < this.$store.getters.menuLeft.length; i++) {
      if (this.$store.getters.menuLeft[i].href == "departureIndex") {
        this.options = this.$store.getters.menuLeft[i].options;
      }
    }
    if (!this.options.includes("edit")) {
      this.headers = this.headers
        .filter(el => {
          return el.text != "Acciones";
        });
    }
  },
  methods: {
    async getDepartures() {
      try {
        let res = await axios.get(`/departure`);
        this.departures = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item, mode) {
      this.bus.$emit("openDialog", $.extend({}, item, { mode: mode }));
    },
    async removeItem(item) {
      console.log(item);
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
        return "";
      } else {
        return "warning";
      }
    },
    async switchActive(departure) {
      try {
        let res = await axios.patch(`/departure/${departure.id}`, {
          approved: departure.approved
        });
        this.getDepartures();
      } catch (e) {
        console.log(e);
      }
    },
    async print(item) {
      try {
        let res = await axios({
          method: "GET",
          url: `/departure/print/${item.id}`,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {
          type: "application/pdf"
        });
        printJS(window.URL.createObjectURL(blob));
      } catch (e) {
        console.log(e);
      }
    }
  }
};
</script>
