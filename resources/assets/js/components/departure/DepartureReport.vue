<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-btn color="error" slot="activator"><v-icon>print</v-icon>&nbsp;&nbsp;Reporte</v-btn>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-container grid-list-md layout>
          <v-layout wrap>
            <v-flex xs12>
              <v-form ref="form">
                <v-layout row wrap>
                  <v-flex xs6>
                    <v-autocomplete
                      v-model="selectedItem.position_group_id"
                      :items="position_groups"
                      item-text="name"
                      item-value="id"
                      label="Dirección/Unidad"
                      hint="Dejar en blanco para selecciona Todos"
                      persistent-hint
                      @change="getEmployees"
                      clearable
                      >
                    </v-autocomplete>
                  </v-flex>
                  <v-flex xs6>
                    <v-autocomplete
                      v-model="selectedItem.employee_id"
                      :items="employees"
                      :item-text="fullName"
                      item-value="employee.id"
                      label="Empleado"
                      hint="Dejar en blanco para seleccionar Todos"
                      persistent-hint
                      clearable
                      >
                    </v-autocomplete>
                  </v-flex>                  
                  <v-flex xs3>
                    <v-select
                      :items="types"
                      item-text="name"
                      item-value="id"
                      v-model="selectedItem.type"
                      label="Tipo"
                      hint="Dejar en blanco para seleccionar Todos"
                      persistent-hint
                      clearable
                    ></v-select>
                  </v-flex>
                  <v-flex xs3>
                    <v-select
                      :items="status"
                      item-text="name"
                      item-value="state"
                      v-model="selectedItem.state"
                      label="Estado"
                      hint="Dejar en blanco para seleccionar Todos"
                      persistent-hint
                      clearable
                    ></v-select>
                  </v-flex>
                  <v-flex xs3>
                    <v-menu
                      :close-on-content-click="false"
                      v-model="menuDateStart"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                    >
                      <v-text-field
                        slot="activator"
                        v-model="selectedItem.start_date"
                        label="Desde"
                        prepend-icon="event"
                        v-validate="'required'"
                        name="Desde"
                        :error-messages="errors.collect('Desde')"
                        autocomplete='cc-exp-month'
                        clearable
                      ></v-text-field>
                      <v-date-picker v-model="dateStart" @input="menuDateStart=false" no-title locale="es-bo"></v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs3>
                    <v-menu
                      :close-on-content-click="false"
                      v-model="menuDateEnd"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                    >
                      <v-text-field
                        slot="activator"
                        v-model="selectedItem.end_date"
                        label="Hasta"
                        prepend-icon="event"
                        v-validate="'required'"
                        name="Hasta"
                        :error-messages="errors.collect('Hasta')"
                        autocomplete='cc-exp-month'
                        clearable
                      ></v-text-field>
                      <v-date-picker v-model="dateEnd" @input="menuDateEnd=false" no-title locale="es-bo"></v-date-picker>
                    </v-menu>
                  </v-flex>
                </v-layout>
              </v-form>
              
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="!valid" @click="print()"><v-icon>check</v-icon> Generar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "DepartureForm",
  props: ["item", "bus"],
  data() {
    return {
      dialog: false,
      valid: true,
      selectedIndex: -1,
      selectedItem: {
        position_group_id: null,
        employee_id: null,
        state: null,
        type: null,
        start_date: this.$moment().format("DD/MM/YYYY"),
        end_date: this.$moment().format("DD/MM/YYYY")
      },
      menuDateStart: null,
      dateStart: null,
      menuDateEnd: null,
      dateEnd: null,
      position_groups: [],
      employees: [],
      status: [
                {state: 'true', name: 'Aprobado'},
                {state: 'false', name: 'Rechazado'}
              ],
      types: [],
      options: []
    };
  },
  created() {
    for (var i = 0; i < this.$store.getters.menuLeft.length; i++) {
      if (this.$store.getters.menuLeft[i].href == "contractIndex") {
        this.options = this.$store.getters.menuLeft[i].options;
      }
    }
    if (this.$store.getters.currentUser.roles[0].name == "juridica") {
      this.juridica = 1;
    }
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1? "Reportes de Salidas/Licencias" : "";
    }    
  },
  watch: {
    dateStart(val) {
      this.selectedItem.start_date = this.$moment(this.dateStart).format("DD/MM/YYYY");
      this.selectedItem.end_date = this.$moment(this.dateStart).format("DD/MM/YYYY");
    },
    dateEnd(val) {
      this.selectedItem.end_date = this.$moment(this.dateEnd).format("DD/MM/YYYY");
    }
  },
  methods: {    
    async initialize() {
      try {
        let position_groups = await axios.get('/position_group');
        this.position_groups = position_groups.data;
        let types = await axios.get('/departure_type');
        this.types = types.data;
      } catch (e) {
        console.log(e);
      }
    },
    close() {
      this.dialog = false;
      this.$validator.reset();
      this.bus.$emit("closeDialog");      
      this.selectedItem = {
        position_group_id: null,
        employee_id: null,
        state: null,
        type: null,
        start_date: this.$moment().format("DD/MM/YYYY"),
        end_date: this.$moment().format("DD/MM/YYYY")
      };      
    },    
    async getEmployees() {
      try{
        let res = await axios.get('/contract/contract_position_group/' + this.selectedItem.position_group_id);
        this.employees = res.data;
      } catch(e){
        console.log(e);
      }
    },
    async checkUsed () {      
      let departure_used = await axios.get('/departure/get_departures_used/' + this.$store.getters.currentUser.employee_id);

      var h1 = this.selectedItem.departure_time.split(":");
      var h2 = this.selectedItem.return_time.split(":");
      var rest = (h2[0] * 60 + h2[1]) - (h1[0] * 60 + h1[1]);
      if (rest > departure_used.data.total_minutes_month_rest) {
        console.log("no");
      }
    },
    async print() {
      try {
        await this.$validator.validateAll();
        let res = await axios({
          method: "POST",
          url: '/departure/print_report',
          data: this.selectedItem,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {
          type: "application/pdf"
        });
        printJS(window.URL.createObjectURL(blob));        
      } catch (e) {
        console.log(e);
      }
    },
    fullName: 
      item => item.employee.first_name + ' ' + item.employee.last_name + ' ' + item.employee.mothers_last_name
  },
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      this.dialog = true;
      this.selectedIndex = item;
      this.departure_type_id = item.departure_reason.departure_type_id;
      this.getDepartureReason();
      this.selectedItem.departure_date = this.$moment(item.dateDeparture).format("DD/MM/YYYY");
      this.selectedItem.return_date = this.$moment(item.dateDeparture).format("DD/MM/YYYY");
    });
    this.initialize();
  }
};
</script>