<template>
  <v-dialog
    v-model="dialog"
    max-width="900px"
    @keydown.esc="dialog = false"
    persistent
  >
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nueva Solicitud</span>
    </v-tooltip>

    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Solicitud de Salida</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon dark @click.native="dialog = false">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>

      <v-stepper v-model="step">
        <v-stepper-header>
          <v-stepper-step :complete="step > 1" step="1">Razón</v-stepper-step>

          <v-divider></v-divider>

          <v-stepper-step :complete="step > 2" step="2">Name of step 2</v-stepper-step>

          <v-divider></v-divider>

          <v-stepper-step step="3">Name of step 3</v-stepper-step>
        </v-stepper-header>

        <v-stepper-items>
          <v-stepper-content step="1">
            <v-card>
              <v-card-text>
                <v-layout>
                  <v-flex xs5 pr-5>
                    <v-select
                      :items="groups"
                      item-text="name"
                      item-value="id"
                      v-model="departure.group"
                      label="Tipo de permiso"
                      :hint="groupDescription"
                      persistent-hint
                      v-validate="'required'"
                      name="Tipo de permiso"
                      :error-messages="errors.collect('Tipo de permiso')"
                    ></v-select>
                  </v-flex>
                  <v-flex xs7>
                    <v-select
                      :items="reasons"
                      item-text="name"
                      item-value="id"
                      v-model="departure.departure_reason_id"
                      label="Razón"
                      :hint="reasonDescription"
                      persistent-hint
                      v-validate="'required'"
                      name="Razón"
                      :error-messages="errors.collect('Razón')"
                      :disabled="!departure.group"
                    ></v-select>
                  </v-flex>
                </v-layout>
                <v-textarea
                  v-show="departure.description_needed"
                  label="Detalle"
                  v-model="departure.description"
                  class="mt-4"
                  rows="3"
                  v-validate="departure.description_needed ? 'required' : ''"
                  name="Detalle"
                  :error-messages="errors.collect('Detalle')"
                  :disabled="!departure.departure_reason_id"
                ></v-textarea>
              </v-card-text>
            </v-card>
          </v-stepper-content>

          <v-stepper-content step="2">
            <v-card>
              <v-card-text>
                <v-layout>
                  <v-flex xs12>

                  </v-flex>
                </v-layout>
              </v-card-text>
            </v-card>
          </v-stepper-content>

          <v-stepper-content step="3">
            <v-card
              class="mb-5"
              color="grey lighten-3"
              height="400px"
            ></v-card>
          </v-stepper-content>
        </v-stepper-items>
      </v-stepper>

      <div class="text-xs-right mt-3">
        <v-btn color="primary" v-if="step > 1" @click.native="step -= 1">
          Volver
        </v-btn>
        <v-btn color="error" v-if="step < 3" @click.native="nextStep">
          Siguiente
        </v-btn>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'Form',
  data() {
    return {
      dialog: false,
      step: 1,
      groups: [],
      reasons: [],
      departure: {
        description_needed: false,
        group: null,
        departure_reason_id: null,
        description: null
      }
    }
  },
  mounted() {
    this.getDepartureGroups()
  },
  computed: {
    groupDescription() {
      let group = this.groups.find(o => o.id == this.departure.group)
      if(group) return group.description
    },
    reasonDescription() {
      let reason = this.reasons.find(o => o.id == this.departure.departure_reason_id)
      if(reason) return reason.description
    },
  },
  watch: {
    'departure.group'(val) {
      this.getDepartureReasons(val)
      this.departure.description = null
      this.departure.description_needed = false
    },
    'departure.departure_reason_id'(val) {
      this.departure.description_needed = this.reasons.find(o => o.id == val).description_needed
      this.departure.description = null
    }
  },
  methods: {
    async nextStep() {
      try {
        let valid = await this.$validator.validateAll();
        if (valid) {
          this.step += 1
        }
      } catch (e) {
        console.log(e)
      }
    },
    async getDepartureGroups() {
      try {
        let res = await axios.get(`departure_group`)
        this.groups = res.data
      } catch (e) {
        console.log(e)
      }
    },
    async getDepartureReasons(groupId) {
      try {
        let res = await axios.get(`departure_group/${groupId}`)
        this.reasons = res.data.departure_reasons
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>



