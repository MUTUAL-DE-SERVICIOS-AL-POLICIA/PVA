<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
          <span>Nuevo escala salarial</span>
    </v-tooltip>
    <v-card>
    <v-toolbar dark color="secondary">
      <v-toolbar-title class="white--text">{{ form_title }}</v-toolbar-title>
    </v-toolbar>
    <v-card-text>
      <v-container grid-list-md layout>
        <v-layout wrap>
          <v-flex xs12>
            <v-form ref="form">
              <v-text-field
                v-if="selected_index !== -1"
                v-model="selected.name"
                label="Cargo"
                v-validate="'required'"
                name="Cargo"
                :error-messages="errors.collect('Cargo')"
              ></v-text-field>
              <v-autocomplete
                v-else
                v-model="selected_name"
                :items="charges"
                item-text="name"
                item-value="name"
                label="Cargo"
                clearable
                v-validate="'required'"
                name="Cargo"
                :error-messages="errors.collect('Cargo')"
              ></v-autocomplete>
              <v-text-field
                v-model="selected.base_wage"
                label="Sueldo base"
                v-validate="'required'"
                name="Sueldo base"
                :error-messages="errors.collect('Sueldo base')"
              ></v-text-field>
              <v-autocomplete
                v-model="active"
                :items="status"
                item-text="name"
                item-value="value"
                label="Estado"
                clearable
                v-validate="'required'"
                name="Estado"
                :error-messages="errors.collect('Estado')"
              ></v-autocomplete>
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
  name: "SalaryScaleForm",
  props: ["item", "bus", "charges"],
  data() {
    return {
      dialog: false,
      valid: true,
      selected: {
        name: null,
        base_wage: null,
        active: false
      },
      selected_index: -1,
      status: [
        {
          name: "ACTIVO",
          value: true
        }, {
          name: "INACTIVO",
          value: false
        }
      ],
      active: false,
      selected_name: ""
    }
  },
  computed: {
    form_title() {
      return this.selected_index === -1 ? "Nueva escala salarial" : "Editar escala salarial"
    },
  },
  mounted() {
    this.bus.$on("openDialog", item => {
        this.dialog = true
        this.selected= item
        this.selected_index = item
    })
  },
  methods: {
    async save() {
      try {
        let valid = await this.$validator.validateAll()
        if(valid) {
          this.selected.active = this.active
          if(this.selected_index != -1) {
            let res = await axios.patch(`/charge/${this.selected.id}`, this.selected)
            this.toastr.success("Editado correctamente")
            this.bus.$emit("rechargeList")
          } else {
            this.selected.name = this.selected_name
            let res = await axios.post("/charge", this.selected)
            this.toastr.success("Registrado correctamente")
            this.bus.$emit("rechargeList")
          }
          this.close()
        }
      } catch (e) {
        console.log(e)
      }
    },
    close() {
        this.dialog = false
        this.$validator.reset()
        this.bus.$emit("closeDialog")
        this.selected = {
          name: null,
          base_wage: null,
          active: null
        }
        this.active = null
        this.selected_name = null
        this.selected_index = -1
    }
  }
}
</script>