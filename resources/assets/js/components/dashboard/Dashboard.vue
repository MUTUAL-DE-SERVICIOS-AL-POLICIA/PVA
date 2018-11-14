<template>
  <v-container >
    <v-toolbar>
        <v-toolbar-title>Inicio</v-toolbar-title>
        <v-spacer></v-spacer>        
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-toolbar-title>
          
        </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
    </v-toolbar>
    
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue";
import { admin, rrhh, juridica } from "../../menu.js";
export default {
  name: "dashboard",
  components: {
  },
  data: () => ({
    active: true,
    bus: new Vue(),
    headers: [
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
    contractsActive: [],
    contractsInactive: [],
    search: "",
    switch1: true,
    contracState: "vigentes",
    options: [],
    hrsxMes: null,
    dayxYear: null
  }),
  computed: {    
    
  },
  async created() {
    
  },
  methods: {
    
  }
};
</script>
