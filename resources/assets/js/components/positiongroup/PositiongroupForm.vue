<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top v-if="options.includes('new')">
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo </span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <v-form ref="form">
          <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-card-text>
                <v-text-field
                  v-model="selectedItem.name"
                  label="Nombre"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.shortened"
                  label="Sigla"
                ></v-text-field>
                <v-select
                    v-model="selectedItem.company_address_id"
                    :items="companyAddress"
                    item-text="address"
                    item-value="id"
                    label="Ciudad"
                  ></v-select>
              </v-card-text>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs12 sm6 md6>
              <v-card-text>
                <!-- <v-select
                    v-model="selectedDocument.document_type_id"
                    :items="documentTypes"
                    item-text="name"
                    item-value="id"
                    label="Tipo de documento"
                  ></v-select>
                <v-text-field
                    v-model="selectedDocument.name"
                    label="documento"
                  ></v-text-field>
                <v-text-field
                    v-model="selectedDocument.description"
                    label="Descripción"
                  ></v-text-field> -->
              </v-card-text>
            </v-flex>
          </v-layout>
        </v-form>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="!valid" @click="save()" ><v-icon>check</v-icon> Guardar</v-btn>
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
      companyAddress:    [],
      valid: true,
      dialog: false,
      selectedIndex:  -1,     
      selectedItem:   {
                    
                  },
      selectedDocument: {
                    
      },
      options: ""
    };
  },
  created() {    
    for (var i = 0; i < this.$store.getters.menuLeft.length; i++) {
      if (this.$store.getters.menuLeft[i].href == 'positiongroupIndex') {
        this.options = this.$store.getters.menuLeft[i].options
      }
    }
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1 ? 'Nuevo ' : 'Editar '
    }    
  },
  methods: {
    async initialize() {
      try {
        let companyAddress = await axios.get('/api/v1/company_address')
        this.companyAddress = companyAddress.data        
      } catch(e) {
        console.log(e)
      }
    },
    close() {
      this.dialog = false;
      this.$validator.reset();
      this.bus.$emit("closeDialog");
      this.selectedItem = {
        name: "",
        shortened: "",
        company_address_id: ""
      }
      this.selectedDocument = {}
    },
    async save() {
        try {
          await this.$validator.validateAll()
          var doc = null
          if (this.selectedIndex != -1) {
            let res = await axios.put('/api/v1/position_group/' + this.selectedItem.id, this.selectedItem)
            this.close()
            this.toastr.success('Editado correctamente')
          } else {             
            let res = await axios.post('/api/v1/position_group', this.selectedItem)
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
  },
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      this.dialog = true;
      this.selectedIndex = item;
    });
    this.initialize()
  },
};
</script>