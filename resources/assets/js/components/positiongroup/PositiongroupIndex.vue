<template>
  <v-container >
    <v-toolbar>
        <v-toolbar-title>Direcciones/Unidades</v-toolbar-title>
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
        <PositiongroupForm :contract="{}" :bus="bus"/>
        <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
        :headers="headers"
        :items="positiongroups"
        :search="search"
        :rows-per-page-items="[10,20]"
        disable-initial-sort
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.name }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.shortened }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.company_address.address }}</td>
            <td class="justify-center layout">              
              <v-tooltip top v-if="options.includes('edit')">
                <v-btn slot="activator" flat icon color="accent" @click="editItem(props.item, props.item.document)">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip top v-if="options.includes('delete')">
                <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                  <v-icon>delete</v-icon>
                </v-btn>
                <span>Eliminar</span>
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
import PositiongroupForm from "./PositiongroupForm";
import RemoveItem from "../RemoveItem";
// import { admin, rrhh, juridica } from "../../menu.js";
export default {
  name: "PositiongroupIndex",
  components: {
    PositiongroupForm,
    RemoveItem
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
        text: "Ciudad",
        value: "company_address.address",
        align: "center"
      },
      
      {
        text: "Opciones",
        align: "center",
        sortable: false
      }
    ],
    positiongroups: [],
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
    for (var i = 0; i < this.$store.getters.menuLeft.length; i++) {
      if (this.$store.getters.menuLeft[i].href == "positiongroupIndex") {
        this.options = this.$store.getters.menuLeft[i].options;
      }
    }
  },
  methods: {
    async initialize() {
      try {
        let positiongroups = await axios.get("/api/v1/position_group");
        this.positiongroups = positiongroups.data;
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item, document) {
      this.bus.$emit("openDialog", $.extend({}, item, { document: document }));
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `/api/v1/position_group/${item.id}`);
    },
  }
};
</script>
