<template>
  <v-dialog
    v-model="dialog"
    width="300"
  >
    <template v-slot:activator="{ on }">
      <v-btn
        color="gray"
        v-on="on"
        flat
        v-if="worked_days"
      >
        <div class="caption pr-2">Dias laborales:</div>
        <div class="body-1 font-weight-bold">{{ worked_days }}</div>
      </v-btn>
      <v-btn
        color="info"
        v-on="on"
        v-else
      >
        <div class="caption">{{ message }}</div>
      </v-btn>
    </template>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Días laborales</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon dark @click.native="close()">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-card-text>
        <v-text-field
          class="px-4 mx-4"
          label="Número de días"
          type="number"
          :min="10"
          :max="25"
          v-validate="'required|numeric|min_value:10|max_value:25'"
          autocomplete="off"
          ref="worked_days"
          name="días trabajados"
          data-vv-name="días trabajados"
          :error-messages="errors.collect('días trabajados')"
          v-model="worked_days"
        ></v-text-field>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          @click="storeProcedure"
          :disabled="!valid"
        >
          Aceptar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'procedure-register',
  props: {
    message: {
      type: String,
      default: 'REGISTRAR'
    }
  },
  data() {
    return {
      dialog: false,
      worked_days: null,
      valid: false
    }
  },
  watch: {
    async worked_days(val) {
      this.valid = await this.$validator.validateAll()
    }
  },
  methods: {
    close() {
      this.dialog = false
    },
    setWorkedDays(value) {
      this.worked_days = value
    },
    async storeProcedure() {
      try {
        if (this.valid) {
          this.$emit('storeProcedure', this.worked_days)
          this.dialog = false
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.worked_days = null
        this.close()
      }
    }
  }
}
</script>