<template>
  <v-container >
    <v-alert
        :value="alert"
        :type="alert_type"
        dismissible
        transition="scale-transition">
        {{alert_msg}}
    </v-alert>
    <v-toolbar flat color="white">
        <v-toolbar-title>Compañia</v-toolbar-title>        
        <v-spacer></v-spacer>        
        <v-dialog persistent v-model="dialog" max-width="900px">
            <v-btn slot="activator" color="primary" dark class="mb-2">Nueva Compañia</v-btn>
            <v-card>
            <v-card-title>
                <span class="headline">{{ formTitle }}</span>
            </v-card-title>  
            <v-card-text>
              <v-container grid-list-md>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-layout wrap>
                        <v-card-text>
                            <v-text-field v-model="editedItem.name" :rules="nameRules" label="Nombre de la compañia"></v-text-field>
                            <v-text-field v-model="editedItem.shortened" :rules="shortenedRules" label="Sigla"></v-text-field>
                            <v-text-field v-model="editedItem.tax_number" :rules="tax_numberRules" label="Numero"></v-text-field>
                            <v-text-field v-model="editedItem.document_id" label="Documento"></v-text-field>
                        </v-card-text>
                    </v-layout>
                </v-form>
              </v-container>
            </v-card-text>  
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="close">Cancelar</v-btn>
              <v-btn color="blue darken-1" flat :disabled="!valid" @click="save(editedItem.id)">Guardar</v-btn>
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
        :items="companies"
        :search="search"
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center"> {{ props.item.name }} </td>
            <td class="text-xs-center">{{ props.item.shortened }}</td>
            <td class="text-xs-center">{{ props.item.tax_number }} </td>
            <td class="text-xs-center">{{ props.item.document_id }} </td>
            <td class="justify-center layout px-0">
              <v-icon small class="material-icons" @click="editItem(props.item)">edit</v-icon>
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
        text:     'Nombre',
        value:    'name',
        align:    'center'
      },
      {
        text:     'Sigla',
        value:    'shortened',
        align:    'center'
      },
      {
        text:     'Numero',
        value:    'tax_number',
        align:    'center'
      },
      {
        text:     'Documento',
        value:    'document_id',
        align:    'center'
      },
      { 
        text:     'Opciones',  
        align:    'center',
        sortable: false 
      }
    ],
    nameRules:    [
                    v => !!v || 'El Nombre es requerido',
                    v => (v && v.length >= 3) || 'El Nombre debe tener un minimo de 3 caracteres',
                    v => (v && v.length <= 112) || 'El Nombre debe tener un maximo de 10 caracteres'
                  ],
    shortenedRules:    [
                    v => !!v || 'La sigla es requerido',
                    v => (v && v.length >= 3) || 'El Nombre debe tener un minimo de 3 caracteres',
                    v => (v && v.length <= 112) || 'El Nombre debe tener un maximo de 10 caracteres'
                  ],                  
    tax_numberRules:    [
                    v => !!v || 'El Numero es requerido',
                    v => (v && v.length >= 1) || 'El Nombre debe tener un minimo de 1 caracteres',
                    v => (v && v.length <= 20) || 'El Nombre debe tener un maximo de 20 caracteres',
                    v => /^[0-9]+$/.test(v) || 'Solo debe contener numeros'
                  ],
    companies:    [],
    editedIndex:  -1,
    editedItem:   {},
    defaultItem:  {},
    valid:        true,
    menu:         false,
    dialog:       false,
    search:       '',
    alert:        false,
    alert_type:   null,
    alert_msg:    null,
    dialogDel:    false,
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nueva Institución' : 'Editar Institución'
    }
  },

  created() {
    this.initialize()    
  },
  methods: {
    initialize() {
      axios
        .get('api/v1/company')
        .then(response => {
          this.companies = response.data
        })
        .catch(error => {
          console.log(error)
        })
    },

    editItem(item) {
      this.editedIndex = this.companies.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    close() {
      this.dialog = false
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      }, 300)
      this.$refs.form.reset()
    },

    save(id) {
        this.alert = false
        if (this.$refs.form.validate()) {
            if (this.editedIndex > -1) {
                axios.put('api/v1/company/' + id, this.editedItem)
                    .then(response=>{
                        Object.assign(this.companies[this.editedIndex], response.data)
                        this.alert = true
                        this.alert_type = 'success'
                        this.alert_msg = 'Compañia editada correctamente.'
                    })
                    .catch(error=> {
                        console.log(error)
                    })
                this.dialog = false
            } else {
                axios.post('api/v1/company', this.editedItem)
                    .then(response=>{
                        this.companies.push(this.editedItem)
                        this.alert = true
                        this.alert_type = 'success'
                        this.alert_msg = 'Compañia creada correctamente'
                    })
                    .catch(error=> {
                        console.log(error)
                    })
                this.dialog = false
            }
        }
    }
  }
}
</script>