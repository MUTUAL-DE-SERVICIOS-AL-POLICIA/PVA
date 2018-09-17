<template>
  <v-dialog persistent v-model="dialog" max-width="500px" @keydown.esc="close">
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
            <v-flex xs12 sm12 md12>
              <v-card-text>
                <v-text-field
                  v-model="selectedItem.name"
                  label="Nombre"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.item"
                  label="Numero de item"
                ></v-text-field>
                <v-select
                    v-model="selectedItem.charge_id"
                    :items="charges"
                    item-text="name"
                    item-value="id"
                    label="Tipo de Cargo"
                  ></v-select>
                <v-select
                    v-model="selectedItem.position_group_id"
                    :items="positionGroups"
                    item-text="name"
                    item-value="id"
                    label="Dirección/Unidad"
                  ></v-select>
                <v-checkbox                    
                  v-model="selectedItem.active"
                  label="Activo"
                  color="primary"      
                ></v-checkbox>
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
  name: "ChargeForm",
  props: ["item", "bus"],
  data() {
    return {
      charges:    [],
      positionGroups: [],
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
      if (this.$store.getters.menuLeft[i].href == 'chargeIndex') {
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
      let charges = await axios.get('/api/v1/charge')
      this.charges = charges.data
      let positionGroups = await axios.get('/api/v1/position_group')
      this.positionGroups = positionGroups.data
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
            let res = await axios.put('/api/v1/charge/' + this.selectedItem.id, this.selectedItem)
            this.close()
            this.toastr.success('Editado correctamente')
          } else {             
            let res = await axios.post('/api/v1/charge', this.selectedItem)
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