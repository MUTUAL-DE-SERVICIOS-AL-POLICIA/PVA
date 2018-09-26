<template>
  <v-container>
    <v-toolbar>
      <v-toolbar-title>Empleados</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn-toggle v-model="toggle_one">
        <v-btn  @click="getEmployees(false)" :class="!this.active ? 'primary white--text' : 'normal'">
          <div class="font-weight-regular subheading pa-2">ACTIVOS</div>
        </v-btn>
        <v-btn  @click="getEmployees(true)" :class="this.active ? 'primary white--text' : 'normal'">
          <div class="font-weight-regular subheading pa-2">INACTIVOS</div>
        </v-btn>
      </v-btn-toggle>
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
        ></v-text-field>
      </v-flex>
      <EmployeeEdit :employee="{}" :bus="bus"/>
      <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="employees"
      :search="search"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
      disable-initial-sort
      expand
    >
      <template slot="items" slot-scope="props">
        <tr :class="rowColor(props.item)">
          <td @click="props.expanded = !props.expanded" class="text-md-center">{{ `${props.item.identity_card} ${props.item.city_identity_card.shortened}` }}</td>
          <td @click="props.expanded = !props.expanded">{{ `${props.item.last_name} ${props.item.mothers_last_name} ${props.item.first_name} ` }}</td>
          <td @click="props.expanded = !props.expanded" class="text-md-center">{{ (props.item.birth_date == null) ? '' : $moment(props.item.birth_date).format('DD/MM/YYYY') }} </td>
          <td @click="props.expanded = !props.expanded">{{ props.item.account_number || '' }} </td>
          <td @click="props.expanded = !props.expanded">{{ (props.item.management_entity_id) ? props.item.management_entity.name : '' }} </td>
          <td @click="props.expanded = !props.expanded">{{ props.item.nua_cua || '' }} </td>
          <td class="text-md-center" v-if="options.length > 0">
            <v-switch
              v-model="props.item.active"
              @click.native="switchActive(props.item)"
              v-if="options.includes('edit')"
            ></v-switch>
          </td>
          <td class="justify-center layout" v-if="options.includes('edit')">
            <v-tooltip top>
              <v-btn medium slot="activator" flat icon color="info" @click="editItem(props.item)">
                <v-icon>edit</v-icon>
              </v-btn>
              <span>Editar</span>
            </v-tooltip>
            <v-tooltip top>
              <v-btn medium slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                <v-icon>delete</v-icon>
              </v-btn>
              <span>Eliminar</span>
            </v-tooltip>
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
      active: false,
      toggle_one: 0,
      employeesActive: [],
      employeesInactive: [],
      employees: [],
      search: "",
      headers: [
        { align: "center", text: "C.I.", value: "identity_card" },
        { text: "Funcionario", value: "last_name" },
        { align: "center", text: "Fecha de Nacimiento", value: "birth_date" },
        { text: "# Cuenta", value: "mothers_last_name" },
        { text: "AFP", value: "first_name" },
        { text: "CUA/NUA", value: "nua_cua" },
        {
          align: "center",
          text: "Activo",
          value: "account_number",
          sortable: false
        },
        {
          align: "center",
          text: "Acciones",
          sortable: false
        }
      ],
      subHeaders: [
        { align: "center", value: "name", text: "Name", sortable: false }
      ]
    };
  },
  async mounted() {
    this.getEmployees(this.active);
    this.bus.$on("closeDialog", () => {
      this.getEmployees(this.active);
    });
  },
  created() {
    for (var i = 0; i < this.$store.getters.menuLeft.length; i++) {
      if (this.$store.getters.menuLeft[i].href == "employeeIndex") {
        this.options = this.$store.getters.menuLeft[i].options;
      }
    }
    if (!this.options.includes("edit")) {
      this.headers = this.headers
        .filter(el => {
          return el.text != "Activo";
        })
        .filter(el => {
          return el.text != "Acciones";
        });
    }
  },
  methods: {
    async getEmployees(active = this.active) {
      try {
        let res = await axios.get(`/api/v1/employee`);
        this.employeesActive = res.data.filter(obj => {
          return obj.active === true;
        });
        this.active = active;
        this.toggle_one = this.active ? 1 : 0;
        this.employeesInactive = res.data.filter(obj => {
          return obj.active === false;
        });
        if (active) {
          this.employees = this.employeesInactive;
        } else {
          this.employees = this.employeesActive;
        }
      } catch (e) {
        console.log(e);
      }
    },
    async switchActive(employee) {
      try {
        let res = await axios.patch(`/api/v1/employee/${employee.id}`, {
          active: employee.active
        });
        this.getEmployees(employee.active);
      } catch (e) {
        console.log(e);
      }
    },
    editItem(employee) {
      this.bus.$emit("openDialog", employee);
    },
    removeItem(employee) {
      this.bus.$emit("openDialogRemove", `/api/v1/employee/${employee.id}`);
    },
    rowColor(payroll) {
      if (
        payroll.birth_date == null ||
        payroll.nua_cua == null ||
        payroll.account_number == null
      ) {
        return "error";
      } else if (
        payroll.location == null ||
        payroll.zone == null ||
        payroll.street == null ||
        payroll.address_number == null
      ) {
        return "warning";
      } else {
        return "";
      }
    }
  }
};
</script>