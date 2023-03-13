<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>
        <v-select
          :items="['Todos los Empleados', 'Personal de Planta', 'Consultores', 'Sin contratos']"
          v-model="employeeType"
          class="title font-weight-medium"
        ></v-select>
      </v-toolbar-title>
      <v-tooltip color="white" role="button" bottom>
        <v-icon slot="activator" class="ml-4">info</v-icon>
        <div>
          <v-alert :value="true" type="info">SIN CONTRATOS</v-alert>
          <v-alert :value="true" type="warning" class="black--text">SIN DATOS PERSONALES</v-alert>
          <v-alert :value="true" type="error">SIN CUENTA BANCARIA O AFP</v-alert>
        </div>
      </v-tooltip>
      <v-spacer></v-spacer>
      <v-btn  @click="getEmployees(false)" :class="!this.active ? 'primary white--text' : 'normal'" class="mr-0">
        <div class="font-weight-regular subheading pa-2">ACTIVOS</div>
      </v-btn>
      <v-btn  @click="getEmployees(true)" :class="this.active ? 'primary white--text' : 'normal'" class="ml-0">
        <div class="font-weight-regular subheading pa-2">INACTIVOS</div>
      </v-btn>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
      <v-flex xs2>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          single-line
          hide-details
          full-width
          clearable
        ></v-text-field>
      </v-flex>
      <EmployeeEdit :employee="{}" :bus="bus"/>
      <RemoveItem :bus="bus"/>
      <EmployeeCertificate :employee="{}" :bus="bus"/>
    </v-toolbar>
    <div v-if="loading">
      <Loading/>
    </div>
    <v-data-table
      v-else
      :headers="headers"
      :items="filteredEmployees"
      :search="search"
      :rows-per-page-items="[10,20,30,{text:'TODO', value:-1}]"
      disable-initial-sort
      expand
    >
      <template slot="items" slot-scope="props">
        <tr :class="rowColor(props.item)">
          <td :class="(rowColor(props.item) != '' ? 'bordered' : '') + withoutBorders" @click="props.expanded = !props.expanded" class="text-md-center">{{ `${props.item.identity_card} ${props.item.city_identity_card.shortened}` }}</td>
          <td :class="(rowColor(props.item) != '' ? 'bordered' : '') + withoutBorders" @click="props.expanded = !props.expanded">{{ `${props.item.last_name} ${props.item.mothers_last_name} ${props.item.first_name} ${(props.item.second_name) ? props.item.second_name : ''} ` }}</td>
          <td :class="(rowColor(props.item) != '' ? 'bordered' : '') + withoutBorders" @click="props.expanded = !props.expanded" class="text-md-center">{{ (props.item.consultant) ? 'CONSULTOR' : ((props.item.consultant == null) ? 'SIN CONTRATOS' : 'PERSONAL DE PLANTA') }} </td>
          <td :class="(rowColor(props.item) != '' ? 'bordered' : '') + withoutBorders" @click="props.expanded = !props.expanded" class="text-md-center pl-2">{{ (props.item.birth_date == null) ? '' : $moment(props.item.birth_date).format('DD/MM/YYYY') }} </td>
          <td :class="(rowColor(props.item) != '' ? 'bordered' : '') + withoutBorders" @click="props.expanded = !props.expanded">{{ props.item.account_number || '' }} </td>
          <td :class="(rowColor(props.item) != '' ? 'bordered' : '') + withoutBorders" @click="props.expanded = !props.expanded">{{ (props.item.management_entity_id) ? props.item.management_entity.name : '' }} </td>
          <td :class="(rowColor(props.item) != '' ? 'bordered' : '') + withoutBorders" @click="props.expanded = !props.expanded">{{ props.item.nua_cua || '' }} </td>
          <td :class="(rowColor(props.item) != '' ? 'bordered' : '') + withoutBorders" class="text-md-center">
            <v-switch
              v-if="$store.getters.permissions.includes('update-employee')"
              v-show="props.item.consultant == null"
              v-model="props.item.active"
              @change="switchActive(props.item)"
            ></v-switch>
          </td>
          <td :class="(rowColor(props.item) != '' ? 'bordered' : '') + withoutBorders" class="justify-center layout">
            <v-tooltip top v-if="(!active && $store.getters.permissions.includes('update-employee')) || (active && $store.getters.permissions.includes('update-employee-inactive'))">
              <v-btn :class="withoutBorders" medium slot="activator" flat icon :color="props.item.consultant == null ? 'white' : 'info'" @click="editItem(props.item)">
                <v-icon>edit</v-icon>
              </v-btn>
              <span>Editar</span>
            </v-tooltip>
            <v-tooltip top v-if="props.item.total_contracts == 0 && $store.getters.permissions.includes('delete-employee')">
              <v-btn :class="withoutBorders" medium slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                <v-icon>delete</v-icon>
              </v-btn>
              <span>Eliminar</span>
            </v-tooltip>
            <div v-else>
              <v-tooltip top v-if="['admin', 'rrhh'].includes($store.getters.role)">
                <v-btn :class="withoutBorders" medium slot="activator" flat icon :color="props.item.consultant == null ? 'white' : 'info'" @click="certificateItem(props.item)">
                  <v-icon>timeline</v-icon>
                </v-btn>
                <span>Certificado de trabajo</span>
              </v-tooltip>
            </div>
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
                  <v-list-tile-content>{{ item.street }} {{ item.address_number }}</v-list-tile-content>
                </td>
              </tr>
              <tr>
                <td>
                  <v-list-tile-content class="font-weight-bold">Celular:</v-list-tile-content>
                </td>
                <td>
                  <v-list-tile-content>{{ item.phone_number }}</v-list-tile-content>
                </td>
              </tr>
              <tr>
                <td>
                  <v-list-tile-content class="font-weight-bold">Teléfono:</v-list-tile-content>
                </td>
                <td>
                  <v-list-tile-content>{{ item.landline_number }}</v-list-tile-content>
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
import Loading from "../Loading";
import EmployeeCertificate from "./EmployeeCertificate";

export default {
  name: "EmployeeIndex",
  components: {
    EmployeeEdit,
    RemoveItem,
    EmployeeCertificate,
    Loading
  },
  data() {
    return {
      withoutBorders: ' ml-0 mr-0 pl-0 pr-0',
      loading: true,
      bus: new Vue(),
      startIndex: 0,
      dialog: false,
      active: false,
      employeesActive: [],
      employeesInactive: [],
      employees: [],
      search: "",
      headers: [
        { align: "center", text: "C.I.", class: ["ml-0", "mr-0", "pl-0", "pr-0"], value: "identity_card" },
        { text: "Funcionario", class: ["ml-0", "mr-0", "pl-0", "pr-0"], value: "last_name" },
        { align: "center", text: "Contrato", class: ["ml-0", "mr-0", "pl-0", "pr-0"], value: "mothers_last_name", sortable: false },
        { align: "center", text: "Nacimiento", class: ["ml-0", "mr-0", "pl-0", "pr-0"], value: "phone_number" },
        { text: "# Cuenta", class: ["ml-0", "mr-0", "pl-0", "pr-0"], value: "account_number" },
        { text: "AFP", class: ["ml-0", "mr-0", "pl-0", "pr-0"], value: "management_entity.name" },
        { text: "CUA/NUA", class: ["ml-0", "mr-0", "pl-0", "pr-0"], value: "nua_cua" },
        {
          align: "left",
          text: "Activo",
          class: ["ml-0", "mr-0", "pl-0", "pr-0"],
          value: "first_name",
          sortable: false
        },
        {
          align: "center",
          text: "Acciones",
          class: ["ml-0", "mr-0", "pl-0", "pr-0"],
          value: "second_name",
          sortable: false
        }
      ],
      employeeType: 'Todos los Empleados'
    };
  },
  computed: {
    filteredEmployees() {
      if (this.employeeType == 'Todos los Empleados') {
        return this.employees
      } else if (this.employeeType == 'Personal de Planta') {
        return this.employees.filter(o => {
          return o.consultant == false
        })
      } else if (this.employeeType == 'Consultores') {
        return this.employees.filter(o => {
          return o.consultant == true
        })
      } else if (this.employeeType == 'Sin contratos') {
        return this.employees.filter(o => {
          return o.consultant == null
        })
      }
    }
  },
  mounted() {
    if (!this.$store.getters.permissions.includes("update-employee")) {
      this.headers = this.headers
        .filter(el => {
          return el.text != "Activo";
        })
        .filter(el => {
          return el.text != "Acciones";
        });
    }
    this.getEmployees(this.active);
    this.bus.$on("closeDialog", () => {
      this.getEmployees(this.active);
    });
  },
  methods: {
    async getEmployees(active = this.active) {
      try {
        let res = await axios.get(`/employee`);
        this.employeesActive = res.data.filter(obj => {
          return obj.active === true;
        });
        this.active = active;
        this.employeesInactive = res.data.filter(obj => {
          return obj.active === false;
        });
        if (active) {
          this.employees = this.employeesInactive;
        } else {
          this.employees = this.employeesActive;
        }
        this.loading = false
      } catch (e) {
        console.log(e);
      }
    },
    async switchActive(employee) {
      try {
        let res = await axios.patch(`/employee/${employee.id}`, {
          active: employee.active
        })
        this.employees = this.employees.filter(o => o.id != employee.id)
      } catch (e) {
        console.log(e)
      }
    },
    editItem(employee) {
      this.bus.$emit("openDialog", employee);
    },
    removeItem(employee) {
      this.bus.$emit("openDialogRemove", `/employee/${employee.id}`);
    },
    certificateItem(item) {
      this.bus.$emit("openDialogCertificate", item);
    },
    rowColor(employee) {
      if (
        employee.birth_date == null ||
        employee.nua_cua == null ||
        employee.account_number == null
      ) {
        return "error white--text";
      } else if (
        employee.location == null ||
        employee.zone == null ||
        employee.street == null ||
        employee.address_number == null
      ) {
        return "warning";
      } else if (
        employee.consultant == null
      ) {
        return "info white--text";
      } else {
        return "";
      }
    }
  }
};
</script>

<style>
.bordered {
  border-bottom: 0.2pt solid #212121;
}
</style>