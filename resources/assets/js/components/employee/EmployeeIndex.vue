<template>
  <v-container >
    <v-alert
        :value="alert"
        :type="alert_type"
        dismissible
        transition="scale-transition"
      >
        {{alert_msg}}
      </v-alert>
      <v-toolbar flat color="white">
        <v-toolbar-title>Empleados</v-toolbar-title>        
        <v-spacer></v-spacer>        
        <v-dialog persistent v-model="dialog" max-width="900px">
          <v-btn slot="activator" color="primary" dark class="mb-2">Nuevo Empleado</v-btn>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>  
            <v-card-text>
              <v-container grid-list-md>
                <v-form ref="form" v-model="valid" lazy-validation>
                  <v-layout wrap>                  
                    <v-flex xs12 sm6 md6>
                      <v-card-text>
                        <v-text-field name="identity_card" v-model="editedItem.identity_card" :rules="[v => !!v || 'Es requerido']" label="C.I."></v-text-field>
                        <v-select
                          name="city_identity_card"
                          :items="expedido"
                          :rules="[v => !!v || 'Es requerido']"
                          item-text="shortened" 
                          item-value="id"
                          v-model="editedItem.city_identity_card_id"
                          label="extension"
                        ></v-select>
                        <v-text-field name="first_name" v-model="editedItem.first_name" :rules="[v => !!v || 'Es requerido']" label="Primer Nombre"></v-text-field>
                        <v-text-field name="second_name" v-model="editedItem.second_name" label="Segundo Nombre"></v-text-field>
                        <v-text-field name="last_name" v-model="editedItem.last_name" label="Primer Apellido"></v-text-field>
                        <v-text-field name="mothers_last_name" v-model="editedItem.mothers_last_name" label="Segundo Apellido"></v-text-field>
                        <v-menu
                          ref="menu"
                          :close-on-content-click="false"
                          v-model="menu"
                          :nudge-right="40"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <v-text-field
                            name="birth_date"
                            slot="activator"
                            v-model="date"
                            label="Fecha de Nacimiento"
                            prepend-icon="event"
                            readonly
                          ></v-text-field>
                          <v-date-picker
                            ref="picker"
                            v-model="date"
                            :max="new Date().toISOString().substr(0, 10)"
                            min="1950-01-01"
                            @change="savedate"
                          ></v-date-picker>
                        </v-menu>
                        <v-select
                          name="city_birth_id"
                          :items="lugar"
                          :rules="[v => !!v || 'Es requerido']"
                          item-text="name" 
                          item-value="id"
                          v-model="editedItem.city_birth_id"
                          label="Lugar de Nacimiento"
                        ></v-select>
                        <v-select
                          name="gender"
                          :items="[{ id: 'M', name: 'Masculino' },{ id: 'F', name: 'Femenino' }]"
                          :rules="[v => !!v || 'Es requerido']"
                          item-text="name" 
                          item-value="id"
                          v-model="editedItem.gender"
                          label="Genero"
                        ></v-select>
                      </v-card-text>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                      <v-card-text>
                        <v-text-field name="location" v-model="editedItem.location" label="Localidad"></v-text-field>
                        <v-text-field name="zone" v-model="editedItem.zone" label="Zona"></v-text-field>
                        <v-text-field name="street" v-model="editedItem.street" label="Calle / Avenida"></v-text-field>
                        <v-text-field name="number" v-model="editedItem.number" label="Numero de puerta"></v-text-field>
                        <v-text-field v-model="editedItem.account_number" label="Numero de cuenta"></v-text-field>
                        <v-text-field name="nua_cua" v-model="editedItem.nua_cua" label="NUA / CUA"></v-text-field>
                        <v-select
                          :items="managementEntity"
                          :rules="[v => !!v || 'Es requerido']"
                          item-text="name" 
                          item-value="id"
                          v-model="editedItem.management_entity_id"
                          label="AFP"
                        ></v-select>
                        <v-select
                          :items="insuranceCompany"
                          :rules="[v => !!v || 'Es requerido']"
                          item-text="name" 
                          item-value="id"
                          v-model="editedItem.insurance_company_id"
                          label="Seguro de salud"
                        ></v-select>
                      </v-card-text>
                    </v-flex>
                  </v-layout>
                </v-form>
              </v-container>
            </v-card-text>  
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="close">Cancelar</v-btn>
              <v-btn color="blue darken-1" flat :disabled="!valid" @click="save">Guardar</v-btn>
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
        <v-dialog
          v-model="dialogDel"
          max-width="290">
          <v-card>
            <v-card-title class="headline">Esta seguro de eliminar este registro?</v-card-title>    
            <v-card-actions>
              <v-spacer></v-spacer>    
              <v-btn
                color="green darken-1"
                flat="flat"
                @click="dialogDel = false"
              >Cancelar
              </v-btn>    
              <v-btn
                color="green darken-1"
                flat="flat"
                @click="deleteItem(props.item)"
              >
                Eliminar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      
      <v-data-table
        :headers="headers"
        :items="desserts"
        :search="search"
        class="elevation-1"
      >
        <template slot="items" slot-scope="props">
          
          <td class="text-xs-center">{{ props.item.identity_card + ' ' + props.item.city_identity_card.shortened }} </td>
          <td class="text-xs-left">{{ props.item.first_name + ' ' + props.item.second_name + ' ' + props.item.last_name + ' ' + props.item.mothers_last_name }}</td>
          <td class="text-xs-center">{{ props.item.birth_date | moment("DD/MM/YYYY") }} </td>
          <td class="text-xs-center">{{ props.item.account_number }} </td>
          <td class="text-xs-center">{{ props.item.management_entity.name }} </td>
          <td class="text-xs-center">{{ props.item.nua_cua }} </td>
          <td class="text-xs-center">{{ props.item.status }} </td>

          <td class="justify-center layout px-0">
            <v-icon small class="material-icons" @click="editItem(props.item)">edit</v-icon>
            <v-icon small @click="deleteItem(props.item)"> close </v-icon>
          </td>
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
    valid: true,
    date: null,
    menu: false,
    dialog: false,
    search: '',
    headers: [
      { text: 'C.I.', value: 'identity_card'},
      { text: 'Funcionario', value: 'last_name'},
      { text: 'Fecha de Nacimiento', value: 'birth_date'},
      { text: '# Cuenta', value: 'account_number'},
      { text: 'AFP', value: 'name'},
      { text: 'CUA/NUA', value: 'nua_cua'},
      { text: 'Estado', value: 'status'},
      { text: 'Actions', value: 'name', sortable: false }
    ],
    desserts: [],
    editedIndex: -1,
    editedItem: {
    },
    defaultItem: {
    },
    expedido: [],
    lugar: [],
    managementEntity: [],
    insuranceCompany: [],
    alert: false,
    alert_type: null,
    alert_msg: null,
    dialogDel: false,
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Nuevo Empleado' : 'Editar Empleado'
    }
  },

  watch: {
    dialog (val) {
      val || this.close()
    },
    menu (val) {
      val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
    }
  },

  created () {
    this.initialize()
    this.cities()
    this.managementEntities()
    this.insuranceCompanies()
  },

  methods: {
    initialize () {
      axios
        .get('api/v1/employee')
        .then(response => {
          this.desserts = response.data
        })
        .catch(error => {
          console.log(error)
        })
    },

    editItem (item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.date = item.birth_date
      this.dialog = true
    },

    deleteItem (item) {
      const index = this.desserts.indexOf(item)
      //confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
      this.dialogDel = true
    },

    close () {
      this.dialog = false
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      }, 300)
      this.$refs.form.reset()
    },

    save () {
      if (this.$refs.form.validate()) {
        if (this.editedIndex > -1) {
          //Object.assign(this.desserts[this.editedIndex], this.editedItem)
          console.log('edit')
          console.log(this.editedItem)
        } else {
          //this.desserts.push(this.editedItem)
          axios.post('api/v1/employee', this.editedItem)
            .then(function (response) {
              console.log(response.data);
              this.alert = true;
              this.alert_type = "success";
              this.alert_msg = 'ok';
            })
            .catch(function (error) {
              console.log(error);
            });
          console.log(this.editedItem)
          
          
          //this.dialog = false
        }
        
      }
    },
    savedate (date) {
      this.$refs.menu.save(date)
    },
    cities () {
      axios
        .get('api/v1/city')
        .then(response => {
          this.expedido = response.data
          this.lugar = response.data
        })
        .catch(error => {
          console.log(error)
        })
    },
    managementEntities () {
      axios
        .get('api/v1/management_entity')
        .then(response => {
          this.managementEntity = response.data
        })
        .catch(error => {
          console.log(error)
        })
    },
    insuranceCompanies () {
      axios
        .get('api/v1/insurance_company')
        .then(response => {
          this.insuranceCompany = response.data
        })
        .catch(error => {
          console.log(error)
        })
    }
  }
	}
</script>