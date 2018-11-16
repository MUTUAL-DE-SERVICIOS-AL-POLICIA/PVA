<template>
  <v-container fluid>
    <v-toolbar>
        <v-toolbar-title>Contratos Eventuales</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn  @click="getContracts(true)" :class="active ? 'primary white--text' : 'normal'" class="mr-0">
          <div class="font-weight-regular subheading pa-2">ACTIVOS</div>
        </v-btn>
        <v-btn  @click="getContracts(false)" :class="!active ? 'primary white--text' : 'normal'" class="ml-0">
          <div class="font-weight-regular subheading pa-2">INACTIVOS</div>
        </v-btn>
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
        <ContractForm :contract="{}" :bus="bus"/>
        <RemoveItem :bus="bus"/>
    </v-toolbar>
    <div v-if="loading">
      <Loading/>
    </div>
    <v-data-table
        v-else
        :headers="headers"
        :items="contracts"
        :search="search"
        :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
        disable-initial-sort
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr :class="checkEnd(props.item)">
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.employee.identity_card }} {{ props.item.employee.city_identity_card.shortened }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ fullName(props.item.employee) }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.position.name }}</td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.start_date | moment("DD/MM/YYYY") }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded" v-if="props.item.retirement_date == null">
              {{ (props.item.end_date == null) ? 'Indefinido' : $moment(props.item.end_date).format('DD/MM/YYYY') }}
            </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded" v-else>
              <v-tooltip top>
                <span slot="activator">
                  {{ $moment(props.item.retirement_date).format('DD/MM/YYYY') }}
                </span>
                <span>
                  Fecha de conclusión: {{ $moment(props.item.end_date).format('DD/MM/YYYY') }}
                </span>
              </v-tooltip>
            </td>
            <td class="justify-center layout" v-if="$store.getters.options.length > 0">
              <v-menu offset-y>
                <v-btn slot="activator" flat icon color="info">
                  <v-icon>print</v-icon><v-icon small>arrow_drop_down</v-icon>
                </v-btn>
                <v-list>
                  <v-list-tile @click="print(props.item, 'printEventual')" v-if="$store.getters.options.includes('printContract')"> Contrato</v-list-tile>
                  <v-list-tile @click="print(props.item, 'printUp')" v-if="$store.getters.options.includes('printInsurance')"> Alta del seguro</v-list-tile>
                  <v-list-tile @click="print(props.item, 'printLow')" v-if="$store.getters.options.includes('printInsurance')"> Baja del seguro</v-list-tile>
                </v-list>
              </v-menu>
              <v-tooltip top v-if="$store.getters.options.includes('renew') && checkEnd(props.item) != ''">
                <v-btn slot="activator" flat icon color="info" @click="editItem(props.item, 'recontract')">
                  <v-icon>autorenew</v-icon>
                </v-btn>
                <span>Recontratar</span>
              </v-tooltip>
              <v-tooltip top v-if="$store.getters.options.includes('edit')">
                <v-btn slot="activator" flat icon color="accent" @click="editItem(props.item, 'edit')">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip top v-if="$store.getters.options.includes('delete')">
                <v-btn slot="activator" flat icon color="red darken-3" @click="removeItem(props.item)">
                  <v-icon>delete</v-icon>
                </v-btn>
                <span>Eliminar</span>
              </v-tooltip>
            </td>
          </tr>
        </template>
        <template slot="expand" slot-scope="props">
          <v-card flat>
            <v-card-text>
              <v-list>
                <v-list-tile-content><p><strong>Cargo: </strong>{{ props.item.position.charge.name }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Lugar: </strong>{{ props.item.position.position_group.name }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Tipo de contrato: </strong>{{ props.item.contract_type.name }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Modalidad de contratación: </strong>{{ props.item.contract_mode.name }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Número de contrato: </strong>{{ props.item.contract_number }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Referencia de contratación: </strong>{{ props.item.hiring_reference_number }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Cite de Recursos Humanos: </strong>{{ props.item.rrhh_cite }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Fecha de cite de recursos Humanos: </strong>{{ props.item.rrhh_cite_date }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Cite de evaluación: </strong>{{ props.item.performance_cite }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Número de asegurado: </strong>{{ props.item.insurance_number }}</p></v-list-tile-content>
                <v-list-tile-content v-if="props.item.retirement_reason"><p><strong>Motivo de retiro: </strong> {{ props.item.retirement_reason.name }} </p></v-list-tile-content>
                <v-list-tile-content v-if="props.item.retirement_reason"><p><strong>Fecha de retiro: </strong> {{ props.item.retirement_date }} </p></v-list-tile-content>
                <v-list-tile-content><p><strong>Descripción: </strong> {{ props.item.description }} </p></v-list-tile-content>
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
import ContractForm from "./ContractForm";
import RemoveItem from "../RemoveItem";
import Loading from "../Loading";
import { admin, rrhh, juridica } from "../../menu.js";
export default {
  name: "ContractIndex",
  components: {
    ContractForm,
    RemoveItem,
    Loading
  },
  data: () => ({
    loading: true,
    active: true,
    bus: new Vue(),
    headers: [
      {
        text: "C.I.",
        value: "employee.identity_card",
        align: "center"
      },
      {
        text: "Nombres",
        value: "employee.last_name",
        align: "center"
      },
      {
        text: "Puesto",
        value: "position.name",
        align: "center"
      },
      {
        text: "Fecha de Inicio",
        value: "employee.mothers_last_name",
        align: "center",
        sortable: false
      },
      {
        text: "Fecha de Conclusión",
        value: "end_date",
        align: "center",
        sortable: true
      },
      {
        text: "Acciones",
        value: "employee.first_name",
        align: "center",
        sortable: false
      }
    ],
    contracts: [],
    contractsActive: [],
    contractsInactive: [],
    search: "",
    switch1: true,
    contracState: "vigentes",
  }),
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
    this.getContracts(this.active);
    this.bus.$on("closeDialog", () => {
      this.getContracts(this.active);
    });
  },
  methods: {
    async getContracts(active = this.active) {
      try {
        let res = await axios.get(`/contract`);
        this.contractsActive = res.data.filter(obj => {
          return obj.active === true;
        });
        this.active = active;
        this.contractsInactive = res.data.filter(obj => {
          return obj.active === false;
        });
        if (active) {
          this.contracts = this.contractsActive;
        } else {
          this.contracts = this.contractsInactive.reverse();
        }
        this.loading = false
      } catch (e) {
        console.log(e);
      }
    },
    editItem(item, mode) {
      this.bus.$emit("openDialog", $.extend({}, item, { mode: mode }));
    },
    async removeItem(item) {
      let payroll = await axios.get(
        "/payroll/getpayrollcontract/" + item.id
      );
      if (payroll.data) {
        alert(
          "No se puede eliminar. Porque este contrato ya se encuentra en PLANILLAS"
        );
      } else {
        this.bus.$emit("openDialogRemove", `/contract/${item.id}`);
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
    checkEnd(contract) {
      if (
        contract.retirement_date != null &&
        this.endDate.isSame(this.$moment(contract.retirement_date), 'year') &&
        this.endDate.isSame(this.$moment(contract.retirement_date), 'month') &&
        !this.active
      ) {
        return "danger";
      } else if (
        contract.end_date != null &&
        this.endDate.isSame(this.$moment(contract.end_date), 'year') &&
        this.endDate.isSame(this.$moment(contract.end_date), 'month') &&
        !this.active
      ) {
        return "warning";
      } else if (
        this.endDate.isSameOrAfter(this.$moment(contract.end_date)) &&
        this.active) {
        return "error";
      } else {
        return "";
      }
    },
    async print(item, type) {
      try {
        let res = await axios({
          method: "GET",
          url: `/contract/print/${item.id}/${type}`,
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
