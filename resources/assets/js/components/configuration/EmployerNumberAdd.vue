<template>
  <v-dialog persistent v-model="dialog" max-width="500px" @keydown.esc="close">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo Número</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">{{ employer_number.id ? 'Actualizar' : 'Nuevo' }} Número de Empleador</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-text-field
          v-validate="{required: true, min: 11, max: 14, regex: /^[0-9]{2}-[0-9]{3}-[0-9]{4,7}$/}"
          :error-messages="errors.collect('Número')"
          data-vv-name='Número'
          v-model="employer_number.number"
          label="Número"
          class="px-4"
          :hint="'##-###-####...'"
          persistent-hint
          clearable
          @keyup.enter.prevent="saveEmployerNumber"
        ></v-text-field>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click.native="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="this.errors.any()" @click.native="saveEmployerNumber"><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'employer_number_add',
  data() {
    return {
      dialog: false,
      employer_number: {
        id: null,
        number: null
      }
    }
  },
  watch: {
    'employer_number.number': function(val) {
      if (val) {
        if (new RegExp('^[0-9]{2}$').test(val) || new RegExp('^[0-9]{2}-[0-9]{3}$').test(val)) {
          this.employer_number.number += '-'
        }
      }
    }
  },
  methods: {
    close() {
      this.dialog = false
      this.employer_number = {
        id: null,
        number: null
      }
      this.$emit('updateEmployerNumbersList')
    },
    openDialog(value) {
      this.employer_number = value
      this.dialog = true
    },
    async saveEmployerNumber() {
      try {
        let valid = await this.$validator.validateAll();
        if (valid) {
          let res
          if (this.employer_number.id) {
            res = await axios.get(`employer_number/${this.employer_number.id}`)
            if (res.data.number != this.employer_number.number) {
              res = await axios.patch(`employer_number/${this.employer_number.id}`, this.employer_number)
            }
            this.toastr.success('Actualizado correctamente')
          } else {
            res = await axios.post(`employer_number`, this.employer_number)
            this.toastr.success('Insertado correctamente')
          }
        }
        this.close()
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>