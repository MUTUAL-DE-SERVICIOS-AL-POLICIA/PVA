<template>
  <v-dialog persistent v-model="dialog" max-width="1000px" @keydown.esc="close">
    <v-tooltip slot="activator" top v-if="$store.getters.permissions.includes('create_eventual') || $store.getters.role == 'admin'">
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo Contrato</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">{{ formTitle }} <span v-if="recontract==true||selectedIndex!=-1"> - {{ fullName(tableEmployee) }} </span></v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-container grid-list-md layout>
          <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-form ref="form">
                  <v-autocomplete
                    v-if="edit||selectedIndex==-1"
                    v-model="selectedItem.employee_id"
                    :items="employees"
                    item-text="identity_card"
                    item-value="id"
                    label="Empleado"
                    v-on:change="onSelectEmployee"
                    v-validate="'required'"
                    name="Empleado"
                    :error-messages="errors.collect('Empleado')"
                    :disabled="juridica">
                  </v-autocomplete>                
                  <v-autocomplete
                    v-if="edit||selectedIndex==-1"
                    v-model="selectedItem.position_id"
                    :items="positions"
                    item-text="name" 
                    item-value="id"
                    label="Puesto"
                    @change="onSelectPosition"
                    v-validate="'required'"
                    name="Puesto"
                    :error-messages="errors.collect('Puesto')"
                    :disabled="juridica==true">
                  </v-autocomplete>
                <v-layout row wrap>
                  <v-flex xs6>
                    <v-select
                      v-if="edit||selectedIndex==-1"
                      v-model="selectedItem.contract_type_id"
                      :items="contractTypes"
                      item-text="name" 
                      item-value="id"                    
                      label="Tipo de contratación"
                      v-validate="'required'"
                      name="Tipo de contratacion"
                      :error-messages="errors.collect('Tipo de contratacion')"
                      :disabled="juridica"
                      @change="date2=null"
                    ></v-select>
                  </v-flex>
                  <v-flex xs6>
                    <v-select
                      v-if="edit||selectedIndex==-1"
                      v-model="selectedItem.contract_mode_id"
                      :items="contractModes"
                      item-text="name" 
                      item-value="id"                    
                      label="Modalidad de contratación"
                      v-validate="'required'"
                      name="Modalidad de contratacion"
                      :error-messages="errors.collect('Modalidad de contratacion')"
                      :disabled="juridica"
                    ></v-select>
                  </v-flex>
                </v-layout>
                <v-layout row wrap>
                  <v-flex xs6>
                    <v-menu
                      :close-on-content-click="false"
                      v-model="menuDate"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                      :disabled="selectedItem.id && $store.getters.role != 'admin'"
                    >
                      <v-text-field
                        slot="activator"
                        v-model="formatDateStart"
                        label="Fecha de inicio"
                        prepend-icon="event"
                        v-validate="'required'"
                        name="Fecha de inicio"
                        :error-messages="errors.collect('Fecha de inicio')"
                        readonly
                        :disabled="selectedItem.id && $store.getters.role != 'admin'"
                        autocomplete='cc-exp-month'
                      ></v-text-field>
                      <v-date-picker v-model="date" no-title 
                      @input="menuDate = false" 
                      @change="monthSalaryCalc" 
                      locale="es-bo"></v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs6>
                    <v-menu
                      :close-on-content-click="false"
                      v-model="menuDate2"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                      :disabled="selectedItem.id && $store.getters.role != 'admin'"
                    >
                      <v-text-field
                        slot="activator"
                        v-model="formatDateEnd"
                        label="Fecha de conclusión"
                        prepend-icon="event" 
                        :disabled="selectedItem.id && $store.getters.role != 'admin'"
                        autocomplete='cc-exp-year'
                        readonly
                        clearable
                        @input="dateEndNull"
                      ></v-text-field>
                      <v-date-picker 
                      v-model="date2" no-title 
                      :min="date"
                      @input="menuDate2 = false" 
                      @change="monthSalaryCalc"
                      locale="es-bo">                        
                      </v-date-picker>
                    </v-menu>
                  </v-flex>
                </v-layout>                
                <v-text-field
                  v-model="selectedItem.contract_number"
                  label="Número de contrato"
                  :outline="juridica"
                  autocomplete='cc-number'
                ></v-text-field>
                <v-layout row wrap>
                  <v-flex xs6>
                    <v-text-field
                      v-model="selectedItem.rrhh_cite"
                      label="Cite de Recursos Humanos"
                      v-validate="'required'"
                      name="Cite de Recursos Humanos"
                      :error-messages="errors.collect('Cite de Recursos Humanos')"
                      :disabled="$store.getters.role != 'admin' && $store.getters.role != 'rrhh'"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs6>
                    <v-menu
                      :close-on-content-click="true"
                      v-model="menuDate4"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                      :disabled="$store.getters.role != 'admin' && $store.getters.role != 'rrhh'"
                    >
                      <v-text-field
                        slot="activator"
                        v-model="formatDateCite"
                        label="Fecha de cite de Recursos Humanos"
                        prepend-icon="event"
                        readonly
                        clearable
                        v-validate="'required'"
                        name="Fecha de cite de Recursos Humanos"
                        :error-messages="errors.collect('Fecha de cite de Recursos Humanos')"
                        @input="dateCiteNull"
                        :disabled="$store.getters.role != 'admin' && $store.getters.role != 'rrhh'"
                      ></v-text-field>
                      <v-date-picker v-model="date4" no-title @input="menuDate4 = false" locale="es-bo"></v-date-picker>
                    </v-menu>
                  </v-flex>
                </v-layout>
                <v-layout row wrap>
                  <v-flex xs6>
                    <v-select
                      v-model="selectedItem.insurance_company_id"
                      :items="insuranceCompanies"
                      item-text="shortened"
                      item-value="id"
                      label="Compañia de seguro"
                      v-validate="'required'"
                      name="Seguro"
                      :error-messages="errors.collect('Seguro')"
                      :disabled="juridica"
                      :hint="selectedItem.insurance_company_id ? `${insuranceCompanies.find(o => o.id == selectedItem.insurance_company_id).name}` : ``"
                    ></v-select>
                  </v-flex>
                  <v-flex xs6>
                    <v-text-field
                      v-model="selectedItem.insurance_number"
                      label="Número de asegurado"
                      :disabled="juridica"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
                <template v-if="!showMore">
                  <v-layout align-center justify-center>
                    <v-btn
                      flat
                      small
                      @click="showMore = true"
                    >
                      Mas Opciones
                      <v-icon dark right>arrow_drop_down</v-icon>
                    </v-btn>
                  </v-layout>
                </template>
                <template v-if="showMore">
                  <v-layout row wrap>
                    <v-flex xs6>
                      <v-text-field
                        v-model="selectedItem.performance_cite"
                        label="Cite de evaluación"
                        :outline="juridica"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-if="edit||selectedIndex==-1"
                        v-model="selectedItem.hiring_reference_number"
                        label="Referencia de contratación"
                        :outline="juridica"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-textarea
                    v-model="selectedItem.description"
                    label="Descripción/Observaciones"
                    rows="2"
                  ></v-textarea>
                  <v-radio-group
                    v-model="selectedSchedule.id"
                    v-validate="'required'"
                    name="Horario"
                    :error-messages="errors.collect('Horario')"
                    v-if="$store.getters.role != 'financiera'"
                  >
                    <template v-for="n in jobSchedules">
                      <v-radio
                        label="Horario  (08:00-12:00 | 14:30-18:30)"
                        :key="n.id"
                        :value="n.id"
                        color="primary"
                        v-if="n.id==1"
                      ></v-radio>
                    </template>
                    <template v-for="n in jobSchedules">
                      <v-radio
                        :label="`Horario (${n.start_hour}:${n.start_minutes}0 - ${n.end_hour}:${n.end_minutes}0)`"
                        :key="n.id"
                        :value="n.id"
                        color="primary"
                        v-if="n.id!=1 && n.id!=2"
                      ></v-radio>
                    </template>
                  </v-radio-group>
                  <template v-if="edit">
                    <v-layout align-center justify-center>
                      <v-switch
                        v-model="deactivateContract"
                        :value="deactivateContract"
                        label="Dar de baja"
                        color="red"
                        hide-details
                      ></v-switch>
                    </v-layout>
                  </template>
                  <template v-if="deactivateContract">
                    <v-layout row wrap>
                      <v-flex xs6>
                        <v-select
                          v-if="edit"
                          v-model="selectedItem.retirement_reason_id"
                          :items="retirementReasons"
                          item-text="name"
                          item-value="id"
                          label="Razón del retiro"
                          :disabled="juridica"
                          v-validate="deactivateContract ? 'required' : ''"
                          name="Razón del retiro"
                          :error-messages="errors.collect('Razón del retiro')"
                        ></v-select>
                      </v-flex>
                      <v-flex xs6>
                        <v-menu
                          v-if="edit"
                          :close-on-content-click="true"
                          v-model="menuDate3"
                          :nudge-right="40"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          max-width="290px"
                          min-width="290px"
                          :disabled="juridica"
                        >
                          <v-text-field
                            slot="activator"
                            v-model="formatDateRetirement"
                            prepend-icon="event"
                            label="Fecha de retiro" :disabled="juridica"
                            readonly
                            clearable
                            @input="dateRetirementNull"
                            v-validate="deactivateContract ? 'required' : ''"
                            name="Fecha de retiro"
                            :error-messages="errors.collect('Fecha de retiro')"
                          ></v-text-field>
                          <v-date-picker v-model="date3" no-title @input="menuDate3 = false" locale="es-bo"></v-date-picker>
                        </v-menu>
                      </v-flex>
                    </v-layout>
                  </template>
                </template>
              </v-form>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm6 md6>
              <v-card>
                <v-card-text>
                  <p v-if="selectedIndex==-1"><strong>Empleado: </strong> {{ fullName(tableEmployee) }}
                    <v-chip v-if="tableEmployeeFree==1" small color="red" text-color="white">Ocupado</v-chip>
                  </p>
                  <p><strong>Puesto: </strong> {{ tablePosition }} 
                    <v-chip v-if="tablePositionFree==1" small color="red" text-color="white">Ocupado</v-chip>
                  </p>
                  <p><strong>Haber Basico: </strong> Bs. {{ tableSalary }} </p>
                  <table class="v-datatable v-table theme--light">
                    <thead>
                      <tr>
                        <th>Mes</th>
                        <th>Dias</th>
                        <th>Salario</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="item in tableData"
                        :key="item.id"
                        :value="item.id"
                      >
                        <td> {{ (item.month).toUpperCase() }} </td>
                        <td class="column sortable text-xs-center"> {{ item.day }} <p class="red">{{ item.obs }}</p> </td>
                        <td class="column sortable text-xs-right"> Bs.{{ item.salary }} </td>
                      </tr>
                    </tbody>
                    <tfoot style="font-weight: bold;">
                        <tr>
                            <td colspan="2"><span>Total </span></td>
                            <td class="column sortable text-xs-right"> Bs.{{ tableData.reduce((total, o) => parseFloat(o.salary) + total,0).toFixed(2) }} </td>
                        </tr>
                    </tfoot>
                  </table>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="this.errors.any() || !valid || tableEmployeeFree==1 || tablePositionFree==1" @click="save()" v-if="recontract==false"><v-icon>check</v-icon> Guardar</v-btn>
        <v-btn color="success" :disabled="!valid" @click="saveRecontract()" v-if="recontract==true"><v-icon>done_all</v-icon> Recontratar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "ContractForm",
  props: ["item", "bus"],
  data() {
    return {
      employees: [],
      positions: [],
      contractTypes: [],
      contractModes: [],
      retirementReasons: [],
      insuranceCompanies: [],
      jobSchedules: [],
      valid: true,
      menu: false,
      date: null,
      date2: null,
      date3: null,
      date4: null,
      menuDate: false,
      menuDate2: false,
      menuDate3: false,
      menuDate4: false,
      recontract: false,
      edit: false,
      dialog: false,
      selectedIndex: -1,
      tableEmployee: "",
      tablePosition: "",
      tablePositionFree: 0,
      tableEmployeeFree: 0,
      tableSalary: "",
      tableData: [],
      selectedItem: {
        start_date: "",
        end_date: "",
        retirement_date: "",
        rrhh_cite_date: ""
      },
      selectedSchedule: {},
      juridica: false,
      minDate: this.$moment().format('YYYY')+'-01-01',
      showMore: true,
      deactivateContract: false
    };
  },
  created() {
    if (this.$store.getters.role == "juridica") {
      this.juridica = true;
    }
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1
        ? "Nuevo contrato"
        : this.recontract == true ? "Recontratar" : "Editar contrato";
    },
    formatDateStart() {
      if (this.$moment(this.selectedItem.start_date).isValid()) {
        return this.$moment(this.selectedItem.start_date).format("DD/MM/YYYY");
      }
    },
    formatDateEnd() {
      if (this.$moment(this.selectedItem.end_date).isValid()) {
        return this.$moment(this.selectedItem.end_date).format("DD/MM/YYYY");
      }
    },
    formatDateRetirement() {
      if (this.$moment(this.selectedItem.retirement_date).isValid()) {
        return this.$moment(this.selectedItem.retirement_date).format(
          "DD/MM/YYYY"
        );
      }
    },
    formatDateCite() {
      if (this.$moment(this.selectedItem.rrhh_cite_date).isValid()) {
        return this.$moment(this.selectedItem.rrhh_cite_date).format(
          "DD/MM/YYYY"
        );
      }
    }
  },
  watch: {
    date(val) {
      this.selectedItem.start_date = this.date;
      if (!this.edit) {
        this.date2 = this.$moment(this.date).add(6, 'months').subtract(1, 'days').format('YYYY-MM-DD')
      }
      this.monthSalaryCalc()
    },
    date2(val) {
      this.selectedItem.end_date = this.date2;
    },
    date3(val) {
      this.selectedItem.retirement_date = this.date3;
      if (this.selectedItem.retirement_date != null) {
        this.selectedItem.active = false
      }
    },
    date4(val) {
      this.selectedItem.rrhh_cite_date = this.date4;
    }
  },
  methods: {
    async initialize() {
      try {
        let employees = await axios.get("/employee");
        this.employees = employees.data;
        let positions = await axios.get("/position");
        this.positions = positions.data;
        let contractTypes = await axios.get("/contract_type");
        this.contractTypes = contractTypes.data;
        let contractModes = await axios.get("/contract_mode");
        this.contractModes = contractModes.data;
        let retirementReasons = await axios.get("/retirement_reason");
        this.retirementReasons = retirementReasons.data;
        let insuranceCompanies = await axios.get("/insurance_company");
        this.insuranceCompanies = insuranceCompanies.data;
        let jobSchedules = await axios.get("/jobs_chedule");
        this.jobSchedules = jobSchedules.data;
      } catch (e) {
        console.log(e);
      }
    },
    close() {
      this.dialog = false;
      this.$validator.reset();
      this.bus.$emit("closeDialog");
      this.selectedItem = {
        start_date: "",
        end_date: "",
        retirement_date: "",
        rrhh_cite_date: ""
      };
      this.date = null;
      this.date2 = null;
      this.date3 = null;
      this.date4 = null;
      this.selectedSchedule = {};
      this.tableEmployee = "";
      this.tablePosition = "";
      this.tablePositionFree = 0;
      this.tableEmployeeFree = 0;
      this.tableSalary = "";
      this.tableData = [];
      this.recontract = false;
      this.edit = false;
      this.selectedIndex = -1;
      this.last_end_date = null;
      this.showMore = true
      this.deactivateContract = false
    },
    async save() {
      try {
        let valid = await this.$validator.validateAll();
        if (valid) {
          this.selectedItem.active = true;
          if (this.selectedIndex != -1) {
            if (this.selectedItem.retirement_date) {
              this.selectedItem.active = false;
            }
            let res = await axios.patch(
              "/contract/" + this.selectedItem.id,
              $.extend({}, this.selectedItem, { schedule: this.selectedSchedule })
            );
            this.close();
            this.toastr.success("Editado correctamente");
          } else {
            let res = await axios.post(
              "/contract",
              $.extend({}, this.selectedItem, { schedule: this.selectedSchedule })
            );
            this.close();
            this.toastr.success("Registrado correctamente");
          }
        }
      } catch (e) {
        console.log(e);
      }
    },
    async saveRecontract() {
      try {
        let valid = await this.$validator.validateAll();
        if (valid) {
          let newres = await axios.post(
            "/contract",
            $.extend({}, this.selectedItem, { schedule: this.selectedSchedule })
          );
          let editres = await axios.patch(
            "/contract/" + this.selectedItem.id,
            { active: false }
          );
          this.close();
          this.toastr.success("Recontratado correctamente");
        }
      } catch (e) {
        console.log(e);
      }
    },
    async saveDate(date) {
      this.$refs.menu.save(date);
    },
    async onSelectEmployee(v, item) {
      if (v) {
        this.tableEmployeeFree = 0;
        let employee = await axios.get("/employee/" + v);
        this.tableEmployee = employee.data;
        let employeeFree = await axios.get("/contract/last_contract/" + v);        
        if (employeeFree.data.active == true) {
          if (this.selectedIndex == -1) {
            this.tableEmployeeFree = 1;
          } else {
            if (this.selectedItem.employee_id != this.selectedItem.employee.id) {
              this.tableEmployeeFree = 1;
            } 
          }
        }
      }
    },
    async onSelectPosition(v) {
      if (v) {
        this.tablePositionFree = 0;
        let position = await axios.get("/position/" + v);
        let positionFree = await axios.get(
          "/contract/position_free/" + v
        );
        if (positionFree.data) { 
          if (this.selectedIndex == -1) {
            this.tablePositionFree = 1;
          } else {
            if (this.selectedItem.position_id != this.selectedItem.position.id) {
              this.tablePositionFree = 1;
            }
          }
        }
        this.tablePosition = position.data.name;
        let charge = await axios.get(
          "/charge/" + position.data.charge_id
        );
        this.tableSalary = charge.data.base_wage;
      }
    },
    monthSalaryCalc() {
      if (this.date && this.date2 && this.tableSalary) {
        let months = []

        let startDate = this.$moment(this.date)
        let endDate = this.$moment(this.date2)
        let diary = this.tableSalary / 30

        let count = 30 - startDate.format('D') + 1
        months.push({
          month: startDate.format('MMMM'),
          day: count,
          salary: (diary * count).toFixed(2)
        })

        while (!endDate.isSame(startDate, 'month', 'year')) {
          if (!endDate.isSame(startDate.add(1, 'month'), 'month', 'year')) {
            months.push({
              month: startDate.format('MMMM'),
              day: 30,
              salary: Number(this.tableSalary).toFixed(2)
            })
          } else {
            count = Number(endDate.format('D'))

            let lastDayOfMonth = Number(endDate.endOf('month').format('D'))
            if (lastDayOfMonth != 30 && count == lastDayOfMonth) {
              count = 30
            }

            months.push({
              month: endDate.format('MMMM'),
              day: count,
              salary: (diary * count).toFixed(2)
            })
          }
        }

        this.tableData = months;
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
    dateRetirementNull(){
      this.selectedItem.retirement_date = null;
    },
    dateEndNull() {
      this.selectedItem.end_date = null;
    },
    dateCiteNull() {
      this.selectedItem.rrhh_cite_date = null;
    }    
  },
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      this.date = item.start_date;
      this.date2 = item.end_date;
      this.tableSalary = item.position.charge.base_wage;
      this.monthSalaryCalc();
      this.dialog = true;
      this.selectedIndex = item;
      if (item.mode == "recontract") {
        this.recontract = true;
        this.last_end_date = item.end_date;
        this.date = this.$moment(item.end_date).add(1, 'days').format('YYYY-MM-DD');
        this.date2 = '';
        this.tableSalary = "";
        this.tableData = [];
        this.selectedItem.contract_number = null;
        this.selectedItem.rrhh_cite = null;
        this.selectedItem.rrhh_cite_date = null;
        this.selectedItem.description = null;
        this.selectedItem.performance_cite = null;
        this.selectedItem.retirement_reason_id = null;
        this.selectedItem.retirement_date = null;
        this.showMore = false
      } else if (item.mode == "edit") {
        this.edit = true;
        this.showMore = false
      }
      if (this.selectedItem.retirement_date) this.deactivateContract = true
      this.onSelectEmployee(item.employee_id);
      this.onSelectPosition(item.position_id);
      if (item.job_schedules[0]) {
        this.selectedSchedule = item.job_schedules[0];
      }
    });
    this.initialize();
  }
};
</script>