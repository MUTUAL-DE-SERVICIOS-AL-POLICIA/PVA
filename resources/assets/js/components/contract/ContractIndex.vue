<template>
  <v-container >
    <v-toolbar flat color="white">
        <v-toolbar-title>Contratos</v-toolbar-title>        
        <v-spacer></v-spacer>
        <v-toolbar-title>
          <v-switch :label="`Contratos ${contracState}`" v-model="switch1" @click.native="contractStatus()" class="mt-3"></v-switch>
        </v-toolbar-title>
        <v-spacer></v-spacer>        
        <v-dialog persistent v-model="dialog" max-width="900px">            
            <v-btn slot="activator" color="primary" dark class="mb-2">Nuevo contrato</v-btn>
            <v-card>
            <v-toolbar dark color="primary" dense flat>
              <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
              <v-container grid-list-md layout>
                <v-flex xs6>
                  <v-form ref="form">
                    <v-autocomplete
                      v-model="selectedItem.employee_id"
                      :items="employees"
                      item-text="identity_card"
                      item-value="id"
                      label="Empleado"
                      v-on:change="onSelect"
                      v-validate="'required'"
                      name="Empleado"
                      :error-messages="errors.collect('Empleado')"
                      :disabled="recontract==true">
                    </v-autocomplete>
                    <v-autocomplete
                      v-model="selectedItem.position_id"
                      :items="positions"
                      item-text="name" 
                      item-value="id"
                      label="Puesto"
                      v-validate="'required'"
                      name="Puesto"
                      :error-messages="errors.collect('Puesto')"
                      :disabled="recontract==true">
                    </v-autocomplete>
                    <v-select
                      v-model="selectedItem.contract_type_id"
                      :items="contract_types"
                      item-text="name" 
                      item-value="id"                    
                      label="Tipo de contratación"
                      v-validate="'required'"
                      name="Tipo de contratacion"
                      :error-messages="errors.collect('Tipo de contratacion')"
                    ></v-select>
                    <v-select
                      v-model="selectedItem.contract_mode_id"
                      :items="contract_modes"
                      item-text="name" 
                      item-value="id"                    
                      label="Modalidad de contratación"
                      v-validate="'required'"
                      name="Modalidad de contratacion"
                      :error-messages="errors.collect('Modalidad de contratacion')"
                    ></v-select>
                    <v-menu
                      :close-on-content-click="true"
                      v-model="menuDate"
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
                        v-model="this.$moment(selectedItem.start_date).format('DD-MM-YYYY')"
                        label="Fecha de inicio"
                        v-validate="'required'"
                        name="Fecha de inicio"
                        :error-messages="errors.collect('Fecha de inicio')"
                        readonly
                      ></v-text-field>
                      <v-date-picker v-model="date" no-title @input="menuDate = false"></v-date-picker>
                    </v-menu>
                    <v-menu
                      :close-on-content-click="true"
                      v-model="menuDate2"
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
                        v-model="this.$moment(selectedItem.end_date).format('DD-MM-YYYY')"
                        label="Fecha de conslusión"
                        readonly
                      ></v-text-field>
                      <v-date-picker v-model="date2" no-title @input="menuDate2 = false"></v-date-picker>
                    </v-menu>
                    <v-menu v-if="selectedIndex!=-1"
                      :close-on-content-click="true"
                      v-model="menuDate3"
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
                        v-model="this.$moment(selectedItem.retirement_date).format('DD-MM-YYYY')"
                        label="Fecha de retiro"
                        readonly
                      ></v-text-field>
                      <v-date-picker v-model="date3" no-title @input="menuDate3 = false"></v-date-picker>
                    </v-menu>
                    <v-select v-if="selectedIndex!=-1"
                      v-model="selectedItem.retirement_reason_id"
                      :items="retirement_reasons"
                      item-text="name" 
                      item-value="id"                    
                      label="Razón del retiro"
                    ></v-select>
                    <v-text-field
                      v-model="selectedItem.rrhh_cite"
                      label="Cite de Recursos Humanos"
                    ></v-text-field>
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
                    >
                      <v-text-field
                        slot="activator"
                        v-model="this.$moment(selectedItem.rrhh_cite_date).format('DD-MM-YYYY')"
                        label="Fecha de cite de Recursos Humanos"
                        readonly
                      ></v-text-field>
                      <v-date-picker v-model="date4" no-title @input="menuDate4 = false"></v-date-picker>
                    </v-menu>
                    <v-text-field
                      v-model="selectedItem.performance_cite"
                      label="Cite de evaluacion"
                    ></v-text-field>
                    <v-text-field
                      v-model="selectedItem.insurance_number"
                      label="Numero de asegurado"
                    ></v-text-field>
                    <v-text-field
                      v-model="selectedItem.hiring_reference_number"
                      label="Referencia de contratación"
                    ></v-text-field>
                    <v-textarea
                      v-model="selectedItem.description"
                      label="Descripción/Observaciones"
                    ></v-textarea>
                    <v-checkbox                    
                      v-model="selectedItem.active"
                      label="Vigente"
                      input-value="true" 
                      value                
                    ></v-checkbox>
                  </v-form>
                </v-flex>
                <v-flex xs6>
                  <v-card>
                    <v-card-text>
                      <p><strong>Empleado: </strong> </p>
                    </v-card-text>
                  </v-card>
                </v-flex>
              </v-container>
            </v-card-text>  
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="danger" block @click.native="close">Cancelar</v-btn>
              <v-btn color="success" block :disabled="!valid" @click="save()" v-if="recontract==false">Guardar</v-btn>
              <v-btn color="success" block :disabled="!valid" @click="saveRecontract()" v-if="recontract==true">Recontratar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog persistent v-model="dialogDelete" max-width="250px">
          <v-card>
            <v-toolbar dark color="primary" dense flat>
              <v-toolbar-title class="white--text">Eliminar</v-toolbar-title>
            </v-toolbar>
            <v-card-text>Esta seguro de eliminar este contrato?</v-card-text>
            <v-card-actions class="pt-0">
              <v-spacer></v-spacer>
              <v-btn color="primary darken-1" flat="flat" @click="del()">Si</v-btn>
              <v-btn color="grey" flat="flat" @click.native="close">Cancelar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-toolbar-title slot="extension" class="white--text">
          <v-text-field
            v-model="search"
            append-icon="fa fa-search"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
        </v-toolbar-title>        
    </v-toolbar>      
    <v-data-table
        :headers="headers"
        :items="contracts"
        :search="search"
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr v-if="props.item.active==switch1">
            <td>
              <v-btn flat icon color="blue" @click="expanded[props.item.id] = !expanded[props.item.id]">
                <v-icon small v-html="expanded[props.item.id] ? 'remove' : 'add'"></v-icon>
              </v-btn>
            </td>
            <td class="text-xs-left"> {{ props.item.employee.identity_card }} {{ props.item.employee.city_identity_card.shortened }} </td>
            <td class="text-xs-left"> {{ fullName(props.item.employee) }} </td>
            <td class="text-xs-left"> {{ props.item.position.name }}</td>
            <td class="text-xs-left"> {{ props.item.start_date | moment("DD/MM/YYYY") }} </td>
            <td class="text-xs-left"> {{ props.item.end_date | moment("DD/MM/YYYY") }}</td>
            <td class="text-xs-center">
              <v-icon 
                small 
                v-html="(props.item.active==true)? 'check' : 'close'" 
                >                  
              </v-icon>
            </td>
            <td class="justify-center layout">
              <v-menu offset-y>
                <v-btn slot="activator" flat icon color="indigo">
                  <v-icon>print</v-icon><v-icon small>arrow_drop_down</v-icon>
                </v-btn>
                <v-list>
                  <v-list-tile
                    v-for="(item, index) in [{ title: 'Contrato' },{ title: 'Afiliación al seguro' },{ title: 'Baja del seguro' }]"
                    :key="index"
                    @click=""                  >
                    <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                  </v-list-tile>
                </v-list>
              </v-menu>

                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="selectItem(props.item, 'reedit')">
                    <v-icon>refresh</v-icon>
                  </v-btn>
                  <span>Recontratar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="selectItem(props.item, 'edit')">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="indigo" @click="selectItem(props.item, 'del')">
                    <v-icon>delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip>
            </td>
          </tr>
          <tr class="expand" v-show="expanded[props.item.id]">
            <td colspan="100%">
              <v-expansion-panel>
                <v-expansion-panel-content v-model="expanded[props.item.id]">
                  <v-card>   
                    <v-card-text>
                      <v-list>
                        <v-list-tile-content><p><strong>Cargo: </strong>{{ props.item.position.charge.name }}</p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Lugar: </strong>{{ props.item.position.position_group.name }}</p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Tipo de contrato: </strong>{{ props.item.contract_type.name }}</p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Modalidad de contratacion: </strong>{{ props.item.contract_mode.name }}</p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Numero de contrato: </strong>{{ props.item.contract_number }}</p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Referencia de contratación: </strong>{{ props.item.hiring_reference_number }}</p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Cite de Recursos Humanos: </strong>{{ props.item.rrh_cite }}</p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Fecha de cite de recursos Humanos: </strong>{{ props.item.rrhh_cite_date }}</p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Cite de evaluacion: </strong>{{ props.item.performance_cite }}</p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Numero de asegurado: </strong>{{ props.item.insurance_number }}</p></v-list-tile-content>
                        <v-list-tile-content v-if="props.item.retirement_reason"><p><strong>Motivo de retiro: </strong> {{ props.item.retirement_reason.name }} </p></v-list-tile-content>
                        <v-list-tile-content v-if="props.item.retirement_reason"><p><strong>Fecha de retiro: </strong> {{ props.item.retirement_date }} </p></v-list-tile-content>
                        <v-list-tile-content><p><strong>Descripción: </strong> {{ props.item.description }} </p></v-list-tile-content>
                      </v-list>
                    </v-card-text>
                  </v-card>
                </v-expansion-panel-content>
              </v-expansion-panel>
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
export default {
  data: () => ({    
    headers: [
      {
        text:     '#',
        sortable: false
      },
      {
        text:     'C.I.',
        value:    'employee.identity_card',
        align:    'center'
      },
      {
        text:     'Nombres',
        value:    'employee.last_name',
        align:    'center'
      },
      {
        text:     'Puesto',
        value:    'position.name',
        align:    'center'
      },
      {
        text:     'Fecha de Inicio',
        align:    'center',
        sortable: false 
      },
      {
        text:     'Fecha de Conclusión',
        align:    'center',
        sortable: false 
      },
      { 
        text:     'Activo',  
        align:    'center',
        sortable: false 
      },
      { 
        text:     'Opciones',  
        align:    'center',
        sortable: false 
      }
    ],    
    selectedItem:   {
                    employee_id: '',
                    position_id: '',
                    contract_type_id: '',
                    contract_mode_id: '',
                    start_date: '',
                    end_date: '',
                    retirement_date: '',
                    retirement_reason_id: '',
                    active: '',
                    rrhh_cite: '',
                    rrhh_cite_date: '',
                    performance_cite: '',
                    insurance_number: '',
                    contract_number: '',
                    hiring_reference_number: '',
                    description: '',
                  },
    defaultItem:  {
                    employee_id: '',
                    position_id: '',
                    contract_type_id: '',
                    contract_mode_id: '',
                    start_date: '',
                    end_date: '',
                    retirement_date: '',
                    retirement_reason_id: '',
                    active: '',
                    rrhh_cite: '',
                    rrhh_cite_date: '',
                    performance_cite: '',
                    insurance_number: '',
                    contract_number: '',
                    hiring_reference_number: '',
                    description: '',
                  },
    contracts:    [],
    employees:    [],
    positions:    [],
    contract_types:    [],
    contract_modes:    [],
    retirement_reasons:    [],
    selectedIndex:  -1,
    document_types: [],
    expanded: {},
    valid:        true,
    menu:         false,
    dialog:       false,
    dialogDelete: false,
    search:       '',
    date:         null,
    date2:        null,
    date3:        null,
    date4:        null,
    menuDate:     false,
    menuDate2:    false,
    menuDate3:    false,
    menuDate4:    false,
    switch1:      true,
    contracState:'vigentes',
    recontract: false,
  }),
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo contrato' : 'Editar contrato'
    }
  },
  watch: {
    date (val) {
      this.selectedItem.start_date = this.date
    },
    date2 (val) {
      this.selectedItem.end_date = this.date2
    },
    date3 (val) {
      this.selectedItem.retirement_date = this.date3
    },
    date4 (val) {
      this.selectedItem.rrhh_cite_date = this.date4
    }
  },
  created() {
    this.initialize()

  },
  mounted () {
    this.$validator.validateAll()
  },
  methods: {    
    async initialize() {
      try {
        let contracts = await axios.get('/api/v1/contract')
        this.contracts = contracts.data
        this.contracts.forEach(i => {
          this.$set(this.expanded, i.id, false) 
        })
        let employees = await axios.get('/api/v1/employee')
        this.employees = employees.data
        let positions = await axios.get('/api/v1/position')
        this.positions = positions.data
        let contract_types = await axios.get('/api/v1/contract_type')
        this.contract_types = contract_types.data
        let contract_modes = await axios.get('/api/v1/contract_mode')
        this.contract_modes = contract_modes.data
        let retirement_reasons = await axios.get('/api/v1/retirement_reason')
        this.retirement_reasons = retirement_reasons.data
      } catch(e) {
        console.log(e)
      }
    },
    selectItem(item, mode) {
      this.selectedIndex = this.contracts.indexOf(item)
      this.selectedItem = Object.assign({}, item)
      if (mode == 'edit') {        
        this.dialog = true
      }
      if (mode == 'reedit') {
        this.recontract = true        
        this.dialog = true
      }
      if (mode == 'del') {
        this.dialogDelete = true
      }      
    },
    close() {
      this.dialog = false
      this.dialogDelete = false
      this.selectedItem = Object.assign({}, this.defaultItem)
      this.selectedIndex = -1
      this.recontract = false
      this.$validator.reset()

    },
    async save() {
        try {
          await this.$validator.validateAll()
          if (this.selectedIndex > -1) {
            let res = await axios.put('/api/v1/contract/' + this.selectedItem.id, this.selectedItem)
            this.initialize()
            //Object.assign(this.contracts[this.selectedIndex], res.data)
            this.close()
            this.toastr.success('Editado correctamente')
          } else {
            let res = await axios.post('/api/v1/contract', this.selectedItem)
            this.initialize()
            //this.contracts.push(this.selectedItem)
            this.close()
            this.toastr.success('Registrado correctamente')
          }
        } catch(e) {
          console.log(e)
            for (let key in e.data.errors) {
              e.data.errors[key].forEach(error => {
                this.toastr.error(error)
              });
            }
        }
    },
    async saveRecontract() {
        try {
          await this.$validator.validateAll()            
            let newres = await axios.post('/api/v1/contract', this.selectedItem)
            let editres = await axios.put('/api/v1/contract/' + this.selectedItem.id, {"active":false})
            this.initialize()
            //Object.assign(this.contracts[this.selectedIndex], editres.data)
            this.close()
            this.toastr.success('Recontratado correctamente')
        } catch(e) {
          console.log(e)
            for (let key in e.data.errors) {
              e.data.errors[key].forEach(error => {
                this.toastr.error(error)
              });
            }
        }
    },
    async del() {
        try {           
          let res = await axios.delete('/api/v1/contract/' + this.selectedItem.id)
          this.initialize()
          // this.contracts.splice(res.data, 1)
          this.close()
        } catch(e) {
          console.log(e)
            for (let key in e.data.errors) {
              e.data.errors[key].forEach(error => {
                this.toastr.error(error)
              });
            }
        }
    },
    contractStatus() {
      if(this.switch1 == true) {
        this.contracState = "vigentes"
        this.switch1 = true
        
      } else {
        this.contracState = "concluidos"
        this.switch1 = false
        
      }      
    },
    fullName(employee){
          let names = `${employee.last_name || ''} ${employee.mothers_last_name || ''} ${employee.surname_husband || ''} ${employee.first_name || ''} ${employee.second_name || ''} `
          names = names.replace(/\s+/gi, ' ').trim().toUpperCase();
          return names;
    },
    onSelect(v) {
      // console.log(v)
    }
  }
}
</script>
<style>
tr.expand td {
  padding: 0 !important;
}

tr.expand .expansion-panel {
  box-shadow: none;
}

tr.expand .expansion-panel li {
  border: none;
}
</style>