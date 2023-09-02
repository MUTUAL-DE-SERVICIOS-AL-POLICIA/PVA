<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top v-if="$store.getters.role == 'admin'">
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo Contacto</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">{{ formTitle }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-container grid-list-md layout>
          <v-layout wrap>
            <v-flex xs12>
              <v-form ref="form">                
                <v-autocomplete
                  v-model="selectedItem.position_group_id"
                  :items="position_groups"
                  item-text="name"
                  item-value="id"
                  label="Dirección/Unidad"                  
                  clearable
                  v-validate="'required'"
                  name="Dirección/Unidad"
                  :error-messages="errors.collect('Dirección/Unidad')"
                  >
                </v-autocomplete>
                <v-text-field
                  v-model="selectedItem.name"
                  label="Ubicación"
                  v-validate="'required'"
                  name="Ubicación"
                  :error-messages="errors.collect('Ubicación')"
                ></v-text-field>
                <v-text-field
                  v-model="selectedItem.phone_number"
                  label="Número de telefono"
                  autocomplete='cc-number'
                  v-validate="'required'"
                  name="Número de telefono"
                  :error-messages="errors.collect('Número de telefono')"
                ></v-text-field>
              </v-form>              
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="!valid" @click="save()"><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "PhonebookForm",
  props: ["item", "bus"],
  data() {
    return {
      dialog: false,
      valid: true,
      selectedIndex: -1,
      selectedItem: {
        position_group_id: null,
        name: null,
        phone_number: null,        
      },
      position_groups: []
    };
  },
  created() {
   
  },
  computed: {
    formTitle() {
      return this.selectedIndex === -1? "Nuevo Contacto" : "Editar contacto";
    }    
  },
  watch: {
    
  },
  methods: {    
    async initialize() {
      try {
        let position_groups = await axios.get('/position_group');
        this.position_groups = position_groups.data;
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
        name: null,
        phone_number: null
      };
      this.selectedIndex = -1
    },
    async save() {
      try {
        let valid = await this.$validator.validateAll();
        if (valid) {
          if (this.selectedIndex != -1) {
            console.log(this.selectedItem);
            let res = await axios.patch("/location/" + this.selectedItem.id, this.selectedItem);
            this.close();
            this.toastr.success("Editado correctamente");
          } else {
            let res = await axios.post("/location", this.selectedItem);
            this.close();
            this.toastr.success("Registrado correctamente");
          }
        }
      } catch (e) {
        console.log(e);
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
  },
  mounted() {
    this.bus.$on("openDialog", item => {
      this.selectedItem = item;
      this.dialog = true;
      this.selectedIndex = item;      
    });
    this.initialize();
  }
};
</script>