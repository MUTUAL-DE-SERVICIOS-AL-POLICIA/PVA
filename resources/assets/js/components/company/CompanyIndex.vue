<template>
  <v-container >
    <v-toolbar flat color="white">
        <v-toolbar-title>Compañia</v-toolbar-title>        
        <v-spacer></v-spacer>        
        <v-dialog persistent v-model="dialog" max-width="900px">
            <v-tooltip slot="activator" top>
              <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
              <span>Nuevo Contrato</span>
            </v-tooltip>
            <v-card>
            <v-toolbar dark color="primary" dense flat>
              <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
            </v-toolbar> 
            <v-card-text>
              <v-container grid-list-md>
                <v-form ref="form">
                  <v-text-field
                    v-validate="'required|min:3|max:255'"
                    v-model="editedItem.name"
                    label="Nombre"
                    name="nombre"
                    :error-messages="errors.collect('nombre')"
                    required
                  ></v-text-field>
                  <v-text-field
                    v-validate="'required|min:3|max:255'"
                    v-model="editedItem.shortened"
                    label="Sigla"
                    name="sigla"
                    :error-messages="errors.collect('sigla')"
                    required
                  ></v-text-field>
                  <v-text-field
                    v-validate="'required|min:3|max:255'"
                    v-model="editedItem.tax_number"
                    label="NIT"
                    name="nit"
                    :error-messages="errors.collect('nit')"
                    required
                  ></v-text-field>
                  <v-divider></v-divider>
                  <v-select
                    :items="document_types"
                    item-text="name" 
                    item-value="id"
                    v-model="editedItemDoc.document_type_id"
                    label="Tipo de documento"
                    v-validate="'required'"
                    name="Tipo"
                    :error-messages="errors.collect('Tipo')"
                  ></v-select>
                  <v-text-field
                    v-validate="'required|min:5|max:255'"
                    v-model="editedItemDoc.name"
                    label="Nombre de Documento"
                    name="documento"
                    :error-messages="errors.collect('documento')"
                    required
                  ></v-text-field>
                  <v-textarea
                    name="descripcion"
                    label="Descripción"
                    v-model="editedItemDoc.description"
                  ></v-textarea>
                </v-form>
              </v-container>
            </v-card-text>  
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="error" block @click="close"><v-icon>close</v-icon> Cancelar</v-btn>
              <v-btn color="success" block :disabled="!valid" @click="save()"><v-icon>check</v-icon> Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
    </v-toolbar>      
    <v-data-table
        :headers="headers"
        :items="companies"
        :search="search"
        class="elevation-1">
        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.name }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.shortened }}</td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.tax_number }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.document.document_type.name }} </td>
            <td class="text-xs-center" @click="props.expanded = !props.expanded"> {{ props.item.document.name }} </td>
            <td class="justify-center layout px-0">
              <v-tooltip top>
                <v-btn slot="activator" flat icon color="accent" @click="editItem(props.item)">
                  <v-icon>edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
            </td>
          </tr>
        </template>
        <template slot="expand" slot-scope="props">
          <v-card flat>
            <v-card-text>
              <v-list>
                <v-list-tile-content><p><strong>Descripción: </strong>{{ props.item.document.description }}</p></v-list-tile-content>
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
        text:     'Tipo de documento',
        align:    'center',
        sortable: false
      },
      {
        text:     'Documentos',
        align:    'center',
        sortable: false
      },
      { 
        text:     'Opciones',  
        align:    'center',
        sortable: false 
      }
    ],    
    editedItem:   {
                    name: '',
                    shortened: '',
                    tax_number: '',
                    document_id: ''
                  },
    defaultItem:  {
                    name: '',
                    shortened: '',
                    tax_number: '',
                    document_id: ''
                  },
    editedItemDoc:   {
                    document_type_id: '',
                    name: '',
                    description: ''
                  },
    defaultItemDoc:   {
                    document_type_id: '',
                    name: '',
                    description: ''
                  },
    companies:    [],
    editedIndex:  -1,
    document_types: [],
    expanded: {},
    valid:        true,
    menu:         false,
    dialog:       false,
    search:       ''
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
      axios
        .get('api/v1/document_type')
        .then(response => {
          this.document_types = response.data
        })
        .catch(error => {
          console.log(error)
        })
    },
    editItem(item) {
      this.editedIndex = this.companies.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.editedItemDoc = Object.assign({}, item.document)
      this.dialog = true
    },

    close() {
      this.dialog = false
      this.editedItem = Object.assign({}, this.defaultItem)
      this.editedItemDoc = Object.assign({}, this.defaultItemDoc)
      this.editedIndex = -1
      this.errors.clear();
    },

    async save() {
        try {
          await this.$validator.validateAll()
          if (this.editedIndex > -1) {
            let resDoc = await axios.patch('/api/v1/document/' + this.editedItemDoc.id, this.editedItemDoc)
            let res = await axios.patch('/api/v1/company/' + this.editedItem.id, this.editedItem)
            this.initialize()
            this.close()
            this.toastr.success('Editado correctamente')
          } else {
            let resDoc = await axios.post('/api/v1/document', this.editedItemDoc)
            this.editedItem.document_id = resDoc.data.id
            let res = await axios.post('/api/v1/company', this.editedItem)
            this.initialize()
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