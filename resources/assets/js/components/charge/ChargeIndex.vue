<template>
  <v-container >
    <v-toolbar>
        <v-toolbar-title>Cargos</v-toolbar-title>
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
        <ChargeForm :contract="{}" :bus="bus"/>
        <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
        :headers="headers"
        :items="charges"
        :search="search"
        :rows-per-page-items="[10,20]"
        disable-initial-sort
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-left"> {{ props.item.name }} </td>
            <td class="text-xs-left"> {{ props.item.base_wage }} </td>
            <td class="text-xs-left">
              <v-icon color="primary" v-html="props.item.active==true ? 'check' : 'close'" class="white--text"></v-icon>
            </td>
            <td class="justify-center layout">              
              <v-tooltip top v-if="options.includes('edit')">
                <v-btn slot="activator" flat icon color="accent" @click="editItem(props.item)">
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
import ChargeForm from "./ChargeForm";
import RemoveItem from "../RemoveItem";
export default {
  name: "ChargeIndex",
  components: {
    ChargeForm,
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
        text: "Salario Base",
        value: "base_wage",
        align: "center"
      },
      {
        text: "Activo",
        value: "active",
        align: "center"
      },
      
      {
        text: "Opciones",
        align: "center",
        sortable: false
      }
    ],
    charges: [],
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
      if (this.$store.getters.menuLeft[i].href == "chargeIndex") {
        this.options = this.$store.getters.menuLeft[i].options;
      }
    }
  },
  methods: {
    async initialize() {
      try {
        let charges = await axios.get("/api/v1/charge");
        this.charges = charges.data;
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item, document) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `/api/v1/charge/${item.id}`);
    },
  }
};
</script>
