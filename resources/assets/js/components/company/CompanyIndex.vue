<template>
  <v-container >
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
              <v-btn color="danger" block @click="close">Cancelar</v-btn>
              <v-btn color="success" block :disabled="!valid" @click="save()">Guardar</v-btn>
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
            <td>
              <v-icon 
                small 
                color="success"
                v-html="expanded[props.item] ? 'remove_circle_outline' : 'add_circle_outline'" 
                @click="expanded[props.item] = !expanded[props.item]">
                  
              </v-icon> {{ props.index + 1 }} 
            </td>
            <td class="text-xs-center"> {{ props.item.name }} </td>
            <td class="text-xs-center">{{ props.item.shortened }}</td>
            <td class="text-xs-center">{{ props.item.tax_number }} </td>
            <td class="text-xs-center"> {{ props.item.document.document_type.name }} </td>
            <td class="text-xs-center"> {{ props.item.document.name }} </td>
            <td class="justify-center layout px-0">
              <v-btn color="primary" dark @click="editItem(props.item)">
                <v-icon dark>edit</v-icon>
              </v-btn>
            </td>
          </tr>
          <tr class="expand" v-show="expanded[props.item]">
            <td colspan="100%">
              <v-expansion-panel>
                <v-expansion-panel-content v-model="expanded[props.item]">
                  <v-card>
                    <v-card-text> Desctipción: {{ props.item.document.description }} </v-card-text>
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
          this.companies.forEach(i => {
            this.$set(this.expanded, i, false) 
          })
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
            let resDoc = await axios.put('/api/v1/document/' + this.editedItemDoc.id, this.editedItemDoc)
            let res = await axios.put('/api/v1/company/' + this.editedItem.id, this.editedItem)
            Object.assign(this.companies[this.editedIndex], res.data)
            this.close()
            this.toastr.success('Editado correctamente')
          } else {
            let resDoc = await axios.post('/api/v1/document', this.editedItemDoc)
            this.editedItem.document_id = resDoc.data.id
            let res = await axios.post('/api/v1/company', this.editedItem)
            this.companies.push(res.data)
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