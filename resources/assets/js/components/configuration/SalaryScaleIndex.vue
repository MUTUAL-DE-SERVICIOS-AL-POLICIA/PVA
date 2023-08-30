<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Escala Salarial</v-toolbar-title>
      <v-tooltip color="white" role="button" bottom></v-tooltip>
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
      <SalaryScaleForm :bus="bus" :charges="charges"/>
      <!-- <RemoveItem :bus="bus"/> -->
    </v-toolbar>
    <div v-if="loading">
      <Loading/>
    </div>
    <v-data-table
      v-else
      :headers="headers"
      :items="active_charges"
      :search="search"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
      disable-initial-sort
      class="elevation-1">
      <template slot="items" slot-scope="props">
        <tr>
          <td> {{ props.item.name }} </td>
          <td> {{ props.item.base_wage }} </td>
          <td v-if="props.item.active"> ACTIVO </td>
          <td class="justify-center layout" v-if="$store.getters.role == 'admin'">
            <v-tooltip top>
              <v-btn :class="withoutBorders" slot="activator" flat icon @click="editItem(props.item)" color="info">
                <v-icon>edit</v-icon>
              </v-btn>
              <span>Editar</span>
            </v-tooltip>
            <!-- <v-tooltip top>
              <v-btn :class="withoutBorders" slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                <v-icon>delete</v-icon>
              </v-btn>
              <span>Eliminar</span>
            </v-tooltip> -->
          </td>
        </tr>
      </template>
      <v-alert slot="no-results" :value="true" color="error">
        La búsqueda de "{{ search }}" no encontró resultados.
      </v-alert>
    </v-data-table>
  </v-container>
</template>
<script>
import Vue from "vue";
import RemoveItem from '../RemoveItem';
import SalaryScaleForm from './SalaryScaleForm';
import Loading from '../Loading';

export default {
  name: "SalaryScaleIndex",
  components: {
    RemoveItem,
    SalaryScaleForm,
    Loading,
  },
  data() {
    return {
      withoutBorders: ' ml-0 mr-0 pl-0 pr-0',
      charges: [],
      loading: true,
      bus: new Vue(),
      headers: [
          {
            text: "Cargo",
            value: "name",
            align: "center",
            class: ["ml-0", "mr-0", "pl-0", "pr-0"]
          }, {
            text: "Sueldo base",
            value: "base_wage",
            align: "center",
            class: ["ml-0", "mr-0", "pl-0", "pr-0"]
          }, {
            text: "Estado",
            value: "active",
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
      search: "",
      active_charges: [],
      no_active_charges: []
    }
  },
  mounted() {
    this.getChargeList()
    this.bus.$on("rechargeList", () => {
      this.getChargeList()
    })
  },
  methods: {
    async getChargeList() {
      try {
        let res = await axios.get(`charge`)
        this.active_charges = res.data.filter(e => {
          return e.active
        })
        this.no_active_charges = res.data.filter(e => {
          return !e.active
        })
        const newSet = new Set(this.no_active_charges)
        this.charges = Array.from(newSet)
        this.order(this.active_charges)
        this.order(this.charges)
        this.loading = false
      } catch (e) {
        console.log(e)
      }
    },
    async removeItem(item) {
      this.bus.$emit("openDialogRemove", `/charge/${item.id}`)
    },
    editItem(item) {
      this.bus.$emit("openDialog", item)
    },
    order(array) {
      array.sort((a, b) => {
        const name_A = a.name.toUpperCase();
        const name_B = b.name.toUpperCase();

        if (name_A < name_B) {
          return -1;
        }
        if (name_A > name_B) {
          return 1;
        }
        return 0;
      });
    }
  }
}
</script>