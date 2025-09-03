<template>
    <v-container fluid>
      <v-toolbar>
        <v-toolbar-title>Psantia/Trabajo dirigido</v-toolbar-title>
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
        <AssistantForm :contract="{}" :bus="bus"/>
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
        class="elevation-1"
      >
        <template slot="items" slot-scope="props">
          <tr :class="checkEnd(props.item)">
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.employee.identity_card }} {{ props.item.employee.city_identity_card.shortened }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ fullName(props.item.employee) }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.assistant_position }}</td>
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
            <td class="justify-center layout" v-if="$store.getters.role == 'rrhh' || $store.getters.role == 'admin'">
              <v-tooltip top v-if="$store.getters.permissions.includes('update-consultant') && checkEnd(props.item) != ''">
                <v-btn slot="activator" flat icon color="info" @click="editItem(Object.assign(props.item, {edit: false, new: false}))">
                  <v-icon>autorenew</v-icon>
                </v-btn>
                <span>Recontratar</span>
              </v-tooltip>
              <v-tooltip top v-if="$store.getters.permissions.includes('update-consultant')">
                <v-btn slot="activator" flat icon color="accent" @click="editItem(Object.assign(props.item, {edit: true, new: false}))">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip top v-if="$store.getters.permissions.includes('delete-consultant')">
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
                <v-list-tile-content><p><strong>Cargo: </strong>{{ props.item.assistant_position }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Lugar: </strong>{{ props.item.position_group.name }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Número de registro: </strong>{{ props.item.register_number }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Universidad: </strong>{{ props.item.university }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Modalidad: </strong>{{ props.item.assistant_position }}</p></v-list-tile-content>
                <!-- <v-list-tile-content><p><strong>Certificado de equivalencia RRHH: </strong>{{ props.item.rrhh_cite }}</p></v-list-tile-content>
                <v-list-tile-content><p><strong>Fecha de cite de recursos Humanos: </strong>{{ props.item.rrhh_cite_date }}</p></v-list-tile-content> -->
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
  import AssistantForm from "./AssistantForm";
  import RemoveItem from "../RemoveItem";
  import Loading from "../Loading";
  
  export default {
    name: "ConsultantIndex",
    components: {
      AssistantForm,
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
          value: "assistant_position",
          align: "center"
        },
        {
          text: "Fecha de Inicio",
          value: "start_date",
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

          align: "center",
          sortable: false
        }
      ],
      contracts: [],
      contractsActive: [],
      contractsInactive: [],
      search: ""
    }),
    computed: {
      endDate() {
        return this.$moment(this.$store.getters.dateNow).endOf('month')
      },
      dateNow() {
        return this.$moment(this.$store.getters.dateNow)
      },
      formTitle() {
        return this.selectedIndex === -1 ? "Nuevo registro" : "Editar registro";
      }
    },
    async created() {
      if (!this.$store.getters.permissions.includes("update-consultant")) {
        this.headers = this.headers.filter(el => {
          return el.text != "Acciones"
        })
      }
      this.getContracts(this.active);
      this.bus.$on("closeDialog", () => {
        this.getContracts(this.active);
      })
    },
    methods: {
      async getContracts(active = this.active) {
        try {
          let res = await axios.get(`/assistant_contract`);
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
        this.bus.$emit("openDialog", item)
        console.log(mode)
        console.log(item)
      },
      async removeItem(item) {
        // let payrolls = await axios.get(`/consultant_payroll/contract/${item.id}`)
        // if (payrolls.data.count > 0) {
        //   this.toastr.warning('No se puede eliminar. Porque este contrato ya se encuentra en PLANILLAS')
        // } else {
          this.bus.$emit("openDialogRemove", `/assistant_contract/${item.id}`)
        //}
      },
      fullName(employee) {
        let names = `${employee.last_name || ""} ${employee.mothers_last_name ||
          ""} ${employee.first_name || ""} ${employee.second_name || ""}`;
        names = names.replace(/\s+/gi, " ").trim().toUpperCase();
        return names;
      },
      checkEnd(contract) {
        if (
          contract.retirement_date != null &&
          this.endDate.isSame(this.$moment(contract.retirement_date), 'month', 'year') &&
          !this.active
        ) {
          return "danger";
        } else if (
          contract.end_date != null &&
          this.endDate.isSame(this.$moment(contract.end_date), 'month', 'year') &&
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
    }
  };
  </script>
  