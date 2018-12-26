<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Agenda Telefonica</v-toolbar-title>
      <v-tooltip color="white" role="button" bottom>      
    </v-tooltip>
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
      <PhonebookForm :bus="bus"/>
      <RemoveItem :bus="bus"/>
    </v-toolbar>
    <div v-if="loading">
      <Loading/>
    </div>
    <v-data-table
      v-else
      :headers="headers"
      :items="phones"
      :search="search"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
      disable-initial-sort
      class="elevation-1">
      <template slot="items" slot-scope="props">
        <tr>
          <td> {{ props.item.position_group.name }} </td>
          <td> {{ props.item.name }} </td>
          <td> {{ props.item.phone_number }} </td>
          <td class="justify-center layout" v-if="$store.getters.options.length > 0">            
            <v-tooltip top v-if="$store.getters.options.includes('edit')">
              <v-btn :class="withoutBorders" slot="activator" flat icon @click="editItem(props.item)" color="info">
                <v-icon>edit</v-icon>
              </v-btn>
              <span>Editar</span>
            </v-tooltip>
            <v-tooltip top v-if="$store.getters.options.includes('delete')">
              <v-btn :class="withoutBorders" slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                <v-icon>delete</v-icon>
              </v-btn>
              <span>Eliminar</span>
            </v-tooltip>
          </td>
        </tr>
      </template>
      <v-alert slot="no-results" :value="true" color="error">
        La búsqueda de "{{ search }}" no encontró resultados.
      </v-alert>
    </v-data-table>
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue";
import PhonebookForm from "./PhonebookForm";
import RemoveItem from "../RemoveItem";
import Loading from "../Loading";
import { admin, rrhh, juridica } from "../../menu.js";
export default {
  name: "PhonebookIndex",
  components: {
    PhonebookForm,
    RemoveItem,
    Loading
  },
  data() {
    return {
      withoutBorders: ' ml-0 mr-0 pl-0 pr-0',
      loading: true,
      active: true,
      bus: new Vue(),
      headers: [
        {
          text: "Dirección",
          value: "position_group.name",
          align: "center",
          class: ["ml-0", "mr-0", "pl-0", "pr-0"],
        }, {
          text: "Ubicación",
          value: "name",
          align: "center",
          class: ["ml-0", "mr-0", "pl-0", "pr-0"],
        }, {
          text: "Numero telefonico",
          value: "phone_number",
          align: "center",
          class: ["ml-0", "mr-0", "pl-0", "pr-0"],
        }, {
          text: "Acciones",
          value: "",
          align: "center",
          class: ["ml-0", "mr-0", "pl-0", "pr-0"],
          sortable: false
        }
      ],
      phones: [],
      search: ""
    }
  },
  computed: {
    endDate() {
      return this.$moment(this.$store.getters.dateNow).endOf('month')
    },
    dateNow() {
      return this.$moment(this.$store.getters.dateNow)
    },
    formTitle() {
      return this.selectedIndex === -1 ? "Nuevo contrato" : "Editar contrato";
    }
  },
  mounted() {
    if (!this.$store.getters.options.includes("edit")) {
      this.headers = this.headers
        .filter(el => {
          return el.text != "Acciones";
        });
    }
    this.getPhones();
    this.bus.$on("closeDialog", () => {
      this.getPhones();
    });
  },
  methods: {
    async getPhones() {
      try {        
        let res = await axios.get(`/location`);
        this.phones = res.data        
        this.loading = false
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item) {
      this.bus.$emit("openDialog", item);
    },
    async removeItem(item) {      
      this.bus.$emit("openDialogRemove", `/location/${item.id}`);      
    },
            
    async print() {
      try {
        let res = await axios({
          method: "GET",
          url: `/phone/print`,
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