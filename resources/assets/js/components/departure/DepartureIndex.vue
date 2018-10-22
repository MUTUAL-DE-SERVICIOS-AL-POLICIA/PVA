<template>
  <v-container>
    <v-toolbar>
        <v-toolbar-title>Solicitud de salidas</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
        <v-toolbar-title>
          
        </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider>
    </v-toolbar>
    <v-card>
      <v-card-title>
        FORMULARIO DE SALIDA
      </v-card-title>
      <v-card-text>
        <v-container grid-list-md layout>
          <v-layout wrap>
            <v-flex xs12>
              <v-form ref="form">
                <v-layout row wrap>
                  <v-flex xs8>

                  </v-flex>
                  <v-flex xs4>
                    <v-menu
                      :close-on-content-click="true"
                      v-model="menuDate"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      max-width="290px"
                      min-width="290px"
                    >
                      <v-text-field
                        slot="activator"
                        v-model="selectedItem.date"
                        label="Fecha de inicio"
                        prepend-icon="event"
                        v-validate="'required'"
                        name="Fecha de inicio"
                        :error-messages="errors.collect('Fecha de inicio')"
                        autocomplete='cc-exp-month'
                      ></v-text-field>
                      <v-date-picker v-model="date" no-title locale="es-bo"></v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs8>
                  </v-flex>
                  <v-flex xs4>
                    <v-radio-group 
                      v-model="selectedItem.type"
                      :mandatory="false" 
                      @change="getDepartureReason"
                      v-validate="'required'"
                      name="Tipo"
                      :error-messages="errors.collect('Tipo')"
                      >
                      <span v-for="type in types">
                        <v-radio :label="type.name" :value="type.id"></v-radio>
                      </span>
                    </v-radio-group>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field
                      v-model="selectedItem.name"
                      label="Nombre"
                      disabled
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field
                      v-model="selectedItem.place"
                      label="Oficina"
                      disabled
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field
                      v-model="selectedItem.destiny"
                      label="Lugar de destino"
                      v-validate="'required'"
                      name="Destino"
                      :error-messages="errors.collect('Destino')"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12>
                    <v-autocomplete
                      v-model="selectedItem.reason"
                      :items="reasons"
                      item-text="description"
                      item-value="id"
                      label="Motivo"
                      v-validate="'required'"
                      name="Motivo"
                      :error-messages="errors.collect('Motivo')"
                      :disabled="disabledReason">
                    </v-autocomplete>
                  </v-flex>
                  <v-flex xs2>
                    <v-menu
                      ref="menu1"
                      :close-on-content-click="false"
                      v-model="menuTimeEntry"
                      transition="scale-transition"
                      full-width
                      offset-y
                    >
                      <v-text-field
                        slot="activator"
                        v-model="selectedItem.timeEntry"
                        label="Hora de entrada"
                        prepend-icon="access_time"
                        readonly
                        clearable
                      ></v-text-field>
                      <v-time-picker
                        v-if="menuTimeEntry"
                        v-model="selectedItem.timeEntry"
                        full-width
                        format="24hr"
                        min="07:30"
                        max="18:30"
                        no-title
                        @change="$refs.menu1.save(selectedItem.timeEntry)"
                      ></v-time-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs2>
                    <v-menu
                      ref="menu2"
                      :close-on-content-click="false"
                      v-model="menuTimeDeparture"
                      transition="scale-transition"
                      full-width
                      offset-y
                    >
                      <v-text-field
                        slot="activator"
                        v-model="selectedItem.timeDeparture"
                        label="Hora de salida"
                        prepend-icon="access_time"
                        readonly
                        clearable
                      ></v-text-field>
                      <v-time-picker
                        v-if="menuTimeDeparture"
                        v-model="selectedItem.timeDeparture"
                        full-width
                        format="24hr"
                        min="07:30"
                        max="18:30"
                        no-title
                        @change="$refs.menu2.save(selectedItem.timeDeparture)"
                      ></v-time-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs2>
                    <v-menu
                      ref="menu3"
                      :close-on-content-click="false"
                      v-model="menuTimeReturn"
                      transition="scale-transition"
                      full-width
                      offset-y
                    >
                      <v-text-field
                        slot="activator"
                        v-model="selectedItem.timeReturn"
                        label="Hora de retorno"
                        prepend-icon="access_time"
                        readonly
                        clearable
                      ></v-text-field>
                      <v-time-picker
                        v-if="menuTimeReturn"
                        v-model="selectedItem.timeReturn"
                        format="24hr"
                        min="07:30"
                        max="18:30"
                        no-title
                        @change="$refs.menu3.save(selectedItem.timeReturn)"
                      ></v-time-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs12>
                    <v-textarea
                      v-model="selectedItem.observation"
                      label="Observaciones"
                      hint=""
                    ></v-textarea>
                  </v-flex>
                </v-layout>
              </v-form>
              <v-alert
                v-model="alert"
                dismissible
                type="info"
              >
                Particular: ...<br>
                Comisiones: ...<br>
                Licencias: ...<br>
                Otros: ...
              </v-alert>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="success" :disabled="!valid" @click="save()"><v-icon>check</v-icon> Solicitar</v-btn>
      </v-card-actions>
    </v-card>
  </v-container>
</template>
<script type="text/javascript">
import Vue from "vue";
export default {
  name: "ContractIndex",
  components: {
  },
  data: () => ({
    menuDate: null,
    date: null,
    selectedItem: {
      date: null,
      type: null,
      name: null,
      place: null,
      destiny: null,
      reason: null,
      timeEntry: null,
      timeDeparture: null,
      timeReturn: null,
      observation: null
    },
    disabledReason: true,
    types: [],
    reasons: [],
    menuTimeEntry: false,
    menuTimeDeparture: false,
    menuTimeReturn: false,
    alert: true,
    valid: true,
    options: []
  }),
  computed: {
    
  },
  watch: {
    date(val) {
      this.selectedItem.date = this.$moment(this.date).format("DD/MM/YYYY");
    },
  },
  async created() {
    this.getDepartureType();
    console.log(this.$store.getters);
    this.selectedItem.date = this.$moment().format("DD/MM/YYYY");
    this.selectedItem.name = this.$store.getters.currentUser.username;  // set name with LDAP
    this.selectedItem.place = this.$store.getters.currentUser.username;  // set place with LDAP
    
  },
  methods: {
    async getDepartureType() {
      try{
        let res = await axios.get('/departure_type');
        this.types = res.data;
      } catch(e){
        console.log(e);
      }
    },
    async getDepartureReason() {
      try{
        let res = await axios.get('/departure_reason/get_reason/' + this.selectedItem.type);
        this.reasons = res.data;
        this.disabledReason = false;
      } catch(e){
        console.log(e);
      }
    },
    async save() {
      try {
        await this.$validator.validateAll();
        let res = await axios.get('/departure_reason/get_reason/', this.selectedItem.type);
      } catch (e) {
        console.log(e);
      }
    },
  }
};
</script>