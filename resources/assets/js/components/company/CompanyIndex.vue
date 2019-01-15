<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Compañia</v-toolbar-title>
        <v-spacer></v-spacer>        
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
        <CompanyForm :contract="{}" :bus="bus"/>
        <!-- <RemoveItem :bus="bus"/> -->
    </v-toolbar>
    <v-data-table
        :headers="headers"
        :items="company"
        :search="search"
        :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
        disable-initial-sort
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.name }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.shortened }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.tax_number }}</td>
            <td class="justify-center layout">              
              <v-tooltip top v-if="$store.getters.role == 'admin'">
                <v-btn slot="activator" flat icon color="accent" @click="editItem(props.item, props.item.document)">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
            </td>
          </tr>
        </template>
        <template slot="expand" slot-scope="props" v-if="props.item.document_id">
          <v-card flat>
            <v-card-text>
              <v-list>
                <v-list-tile-content><p><strong>Tipo de documento: </strong>{{ props.item.document.document_type.name }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Documento: </strong>{{ props.item.document.name }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Descripcion de documento: </strong>{{ props.item.document.description }}</p></v-list-tile-content>
              </v-list>
            </v-card-text>
          </v-card>
        </template>
        <v-alert slot="no-results" :value="true" color="error" icon="fa fa-times">
          Tu Busqueda de "{{ search }}" no encontró resultados.
        </v-alert>
        <template slot="no-data">
          <v-btn color="primary" @click="initialize">Recargar</v-btn>
        </template>
    </v-data-table>
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue";
import CompanyForm from "./CompanyForm";
// import RemoveItem from "../RemoveItem";
// import { admin, rrhh, juridica } from "../../menu.js";
export default {
  name: "ContractIndex",
  components: {
    CompanyForm,
    // RemoveItem
  },
  data: () => ({
    toggle_one: 0,
    bus: new Vue(),
    headers: [
      {
        text: "Nombre",
        value: "name",
        align: "center"
      },
      {
        text: "Sigla",
        value: "shortened",
        align: "center"
      },
      {
        text: "NIT",
        value: "tax_number",
        align: "center"
      },
      
      {
        text: "Opciones",
        align: "center",
        sortable: false
      }
    ],
    company: [],
    search: "",
    options: ""
  }),
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? "Nuevo " : "Editar ";
    }
  },
  created() {
    this.initialize();
    this.bus.$on("closeDialog", () => {
      this.initialize();
    });
  },
  methods: {
    async initialize() {
      try {
        let company = await axios.get("/company");
        this.company = company.data;
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item, document) {
      this.bus.$emit("openDialog", $.extend({}, item, { document: document }));
    },
  }
};
</script>
