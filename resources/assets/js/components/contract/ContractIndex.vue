<template>
  <v-container >
    <v-toolbar flat color="white">
        <v-toolbar-title>Contratos</v-toolbar-title>        
        <v-spacer></v-spacer>
        <v-toolbar-title>
          <v-switch :label="`Contratos ${contracState}`" v-model="switch1" @click.native="contractStatus()" color="primary" class="mt-4"></v-switch>
        </v-toolbar-title>
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
    <v-data-table
        :headers="headers"
        :items="contracts"
        :search="search"
        :rows-per-page-items="[10,20]"
        disable-initial-sort
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr v-if="props.item.active==switch1" v-bind:class="[checkEnd(props.item.end_date)? 'red lighten-1' : '']">
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.employee.identity_card }} {{ props.item.employee.city_identity_card.shortened }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ fullName(props.item.employee) }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.position.name }}</td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.start_date | moment("DD/MM/YYYY") }} </td>
            <td class="text-xs-left" @click="props.expanded = !props.expanded"> {{ props.item.end_date | moment("DD/MM/YYYY") }}</td>
            <td class="text-xs-center">
              <v-icon 
                small 
                v-html="(props.item.active==true)? 'check' : 'close'" 
                >                  
              </v-icon>
            </td>
            <td class="justify-center layout">
              <v-menu offset-y>
                <v-btn slot="activator" flat icon color="info">
                  <v-icon>print</v-icon><v-icon small>arrow_drop_down</v-icon>
                </v-btn>
                <v-list>
                  <v-list-tile @click="print(props.item)"> Contrato</v-list-tile>
                  <v-list-tile @click="printUp(props.item)"> Alta del seguro</v-list-tile>
                  <v-list-tile @click="printLow(props.item)"> Baja del seguro</v-list-tile>
                </v-list>
              </v-menu>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="info" @click="editItem(props.item, 'recontract')">
                    <v-icon>autorenew</v-icon>
                  </v-btn>
                  <span>Recontratar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn slot="activator" flat icon color="primary" @click="editItem(props.item, 'edit')">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top>
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
import Vue from 'vue'
import ContractForm from "./ContractForm";
import RemoveItem from "../RemoveItem";
export default {
  name: "ContractIndex",
  components: {
    ContractForm,
    RemoveItem
  },
  data: () => ({ 
    bus: new Vue(),   
    headers: [
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
    contracts:    [],
    search:       '',    
    switch1:      true,
    contracState:'vigentes' 
  }),
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo contrato' : 'Editar contrato'
    },
    
  },  
  created() {
    this.initialize()
    this.bus.$on('closeDialog', () => {
      this.initialize()
    })
  }, 
  methods: {    
    async initialize() {
      try {
        let contracts = await axios.get('/api/v1/contract')
        this.contracts = contracts.data
      } catch(e) {
        console.log(e)
      }
    },
    editItem(item, mode) {
      this.bus.$emit('openDialog', $.extend({}, item, {'mode': mode}))
    },
    async removeItem(item) {
      let payroll = await axios.get('/api/v1/payroll/getpayrollcontract/' + item.id)
      if (payroll.data) {
        alert("No se puede eliminar. Porque este contrato ya se encuentra en PLANILLAS")
      } else {
        this.bus.$emit('openDialogRemove', `/api/v1/contract/${item.id}`)
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
    checkEnd(end_date) {      
      if (this.$moment().format() > end_date) {
        return true
      } else {
        return false
      }      
    },
    print (item) {
      printJS({printable:"api/v1/contract/print/" + item.id + "/printEventual", type:"pdf", showModal:true, modalMessage: "Generando documento por favor espere un momento."})
    },
    printUp (item) {
      printJS({printable:"api/v1/contract/print/" + item.id + "/printUp", type:"pdf", showModal:true, modalMessage: "Generando documento por favor espere un momento."})
    },
    printLow (item) {
      printJS({printable:"api/v1/contract/print/" + item.id + "/printLow", type:"pdf", showModal:true, modalMessage: "Generando documento por favor espere un momento."})
    }
  }
}
</script>
