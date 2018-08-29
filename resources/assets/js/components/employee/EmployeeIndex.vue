<template>
  <v-container>
    <v-toolbar>
      <v-toolbar-title>Empleados</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-btn-toggle mandatory v-model="toggle_one">
          <v-btn class="btn-toggle" flat :to="{name: 'employeeIndex', params: {active: true}}">Activos</v-btn>
          <v-btn class="btn-toggle" flat :to="{name: 'employeeIndex', params: {active: false}}">Inactivos</v-btn>
        </v-btn-toggle>
      </v-toolbar-items>
      <v-flex xs2>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          hide-details
          full-width
        ></v-text-field>
      </v-flex>
      <EmployeeEdit :employee="{}" :bus="bus"/>
      <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="employees"
      :search="search"
      :rows-per-page-items="[10,20]"
      disable-initial-sort
      expand
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td @click="props.expanded = !props.expanded" class="text-md-center">{{ `${props.item.identity_card} ${props.item.city_identity_card.shortened}` }}</td>
          <td @click="props.expanded = !props.expanded">{{ `${props.item.last_name} ${props.item.mothers_last_name} ${props.item.first_name} ` }}</td>
          <td @click="props.expanded = !props.expanded" class="text-md-center">{{ props.item.birth_date | moment("DD/MM/YYYY") }} </td>
          <td @click="props.expanded = !props.expanded">{{ props.item.account_number || '' }} </td>
          <td @click="props.expanded = !props.expanded">{{ (props.item.management_entity_id) ? props.item.management_entity.name : '' }} </td>
          <td @click="props.expanded = !props.expanded">{{ props.item.nua_cua || '' }} </td>
          <td class="text-md-center">
            <v-switch
              v-model="props.item.active"
              @click="switchActive(props.item)"
            ></v-switch>
          </td>
          <td class="text-md-center">
            <v-layout wrap>
              <v-flex xs6 sm6 md6>
                <v-btn icon>
                  <v-tooltip top>
                    <v-icon medium slot="activator" color="primary" @click="editItem(props.item)">edit</v-icon>
                    <span>Editar</span>
                  </v-tooltip>
                </v-btn>
              </v-flex>
              <v-flex xs6 sm6 md6>
                <v-btn icon>
                  <v-tooltip top>
                    <v-icon medium slot="activator" color="error" @click="removeItem(props.item)">close</v-icon>
                    <span>Eliminar</span>
                  </v-tooltip>
                </v-btn>
              </v-flex>
            </v-layout>
          </td>
        </tr>
      </template>
      <template slot="expand" slot-scope="{ item }">
        <v-card>
          <v-card-text>
            <table>
              <tr>
                <td>
                  <v-list-tile-content class="font-weight-bold">Ciudad:</v-list-tile-content>
                </td>
                <td>
                  <v-list-tile-content>{{ item.location }}</v-list-tile-content>
                </td>
              </tr>
              <tr>
                <td>
                  <v-list-tile-content class="font-weight-bold">Zona:</v-list-tile-content>
                </td>
                <td>
                  <v-list-tile-content>{{ item.zone }}</v-list-tile-content>
                </td>
              </tr>
              <tr>
                <td>
                  <v-list-tile-content class="font-weight-bold">Calle:</v-list-tile-content>
                </td>
                <td>
                  <v-list-tile-content>{{ item.street }}</v-list-tile-content>
                </td>
              </tr>
              <tr>
                <td>
                  <v-list-tile-content class="font-weight-bold">Número:</v-list-tile-content>
                </td>
                <td>
                  <v-list-tile-content>{{ item.address_number }}</v-list-tile-content>
                </td>
              </tr>
              <tr>
                <td>
                  <v-list-tile-content class="font-weight-bold">Teléfono:</v-list-tile-content>
                </td>
                <td>
                  <v-list-tile-content>{{ item.phone_number }}</v-list-tile-content>
                </td>
              </tr>
            </table>
          </v-card-text>
        </v-card>
      </template>
      <v-alert slot="no-results" :value="true" color="error">
        La búsqueda de "{{ search }}" no encontró resultados.
      </v-alert>
    </v-data-table>
  </v-container>
</template>

<script>
import Vue from "vue";
import EmployeeEdit from "./EmployeeEdit";
import RemoveItem from "../RemoveItem";

export default {
  name: "EmployeeIndex",
  components: {
    EmployeeEdit,
    RemoveItem
  },
  data() {
    return {
      bus: new Vue(),
      startIndex: 0,
      dialog: false,
      active: JSON.parse(this.$route.params.active),
      toggle_one: 0,
      employees: [],
      search: "",
      headers: [
        { align: "center", text: "C.I.", value: "identity_card" },
        { text: "Funcionario", value: "last_name" },
        { align: "center", text: "Fecha de Nacimiento", value: "birth_date" },
        { text: "# Cuenta", value: "account_number" },
        { text: "AFP", value: "name" },
        { text: "CUA/NUA", value: "nua_cua" },
        { align: "center", text: "Activo", value: "active", sortable: false },
        { align: "center", text: "Acciones", sortable: false }
      ],
      subHeaders: [
        { align: "center", value: "name", text: "Name", sortable: false }
      ],
      subItems: [{ name: "asdfasdf" }]
    };
  },
  watch: {
    "$route.params.active": val => {
      location.reload();
    }
  },
  async mounted() {
    this.getEmployees(this.active);
    this.bus.$on("closeDialog", () => {
      this.getEmployees(this.active);
    });
  },
  methods: {
    reset: function() {
      var vm = this;
      vm.drawComponent = false;
      Vue.nextTick(function() {
        vm.drawComponent = true;
      });
    },
    // filteredItems(items, search, filter) {
    //   return items.filter(obj => {
    //     return obj.active == this.active;
    //   });
    // },
    async getEmployees(active = true) {
      try {
        let res = await axios.get(`/api/v1/employee`);
        this.employees = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    async switchActive(employee) {
      try {
        let res = await axios.put(`/api/v1/employee/${employee.id}`, {
          active: !employee.active
        });
        this.getEmployees(this.active);
        this.$router.push({
          name: "employeeIndex",
          params: { active: this.active }
        });
      } catch (e) {
        console.log(e);
      }
    },
    editItem(employee) {
      this.bus.$emit("openDialog", employee);
    },
    removeItem(employee) {
      this.bus.$emit("openDialogRemove", `/api/v1/employee/${employee.id}`);
    }
  }
};
</script>

<style>
.btn-toggle {
  background-color: #e4e4e4;
}
</style>