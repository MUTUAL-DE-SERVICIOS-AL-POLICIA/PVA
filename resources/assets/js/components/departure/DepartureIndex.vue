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
                  <v-flex xs4>
                    <v-radio-group 
                      v-model="type"
                      :mandatory="false" 
                      @change="getDepartureReason"
                      v-validate="'required'"
                      name="Tipo"
                      :error-messages="errors.collect('Tipo')"
                      >
                      <span v-for="type in types" :key="type.id">
                        <v-radio :label="type.name" :value="type.id"></v-radio>
                      </span>
                    </v-radio-group>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field
                      v-model="name"
                      label="Nombre"
                      disabled
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field
                      v-model="place"
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
                      v-model="selectedItem.departure_reason_id"
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
                        v-model="selectedItem.entry_time"
                        label="Hora de entrada"
                        prepend-icon="access_time"
                        readonly
                        clearable
                      ></v-text-field>
                      <v-time-picker
                        v-if="menuTimeEntry"
                        v-model="selectedItem.entry_time"
                        full-width
                        format="24hr"
                        min="07:30"
                        max="18:30"
                        no-title
                        @change="$refs.menu1.save(selectedItem.entry_time)"
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
                        v-model="selectedItem.departure_time"
                        label="Hora de salida"
                        prepend-icon="access_time"
                        readonly
                        clearable
                      ></v-text-field>
                      <v-time-picker
                        v-if="menuTimeDeparture"
                        v-model="selectedItem.departure_time"
                        full-width
                        format="24hr"
                        min="07:30"
                        max="18:30"
                        no-title
                        @change="$refs.menu2.save(selectedItem.departure_time)"
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
                        v-model="selectedItem.return_time"
                        label="Hora de retorno"
                        prepend-icon="access_time"
                        readonly
                        clearable
                      ></v-text-field>
                      <v-time-picker
                        v-if="menuTimeReturn"
                        v-model="selectedItem.return_time"
                        format="24hr"
                        min="07:30"
                        max="18:30"
                        no-title
                        @change="$refs.menu3.save(selectedItem.return_time)"
                      ></v-time-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs2>
                    <v-divider
                      class="mx-2"
                      inset
                      vertical
                    ></v-divider>
                  </v-flex>
                  <v-flex xs2>
                    <v-menu
                      :close-on-content-click="true"
                      v-model="menuDateStart"
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
                        v-model="selectedItem.start_date"
                        label="Fecha de salida"
                        prepend-icon="event"
                        v-validate="'required'"
                        name="Fecha de inicio"
                        :error-messages="errors.collect('Fecha de inicio')"
                        autocomplete='cc-exp-month'
                        clearable
                      ></v-text-field>
                      <v-date-picker v-model="dateStart" no-title locale="es-bo"></v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs2>
                    <v-menu
                      :close-on-content-click="true"
                      v-model="menuDateEnd"
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
                        v-model="selectedItem.end_date"
                        label="Fecha de retorno"
                        prepend-icon="event"
                        v-validate="'required'"
                        name="Fecha de retorno"
                        :error-messages="errors.collect('Fecha de retorno')"
                        autocomplete='cc-exp-month'
                        clearable
                      ></v-text-field>
                      <v-date-picker v-model="dateEnd" no-title locale="es-bo"></v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs12>
                    <v-textarea
                      v-model="selectedItem.description"
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
    selectedItem: {
      employee_id: null,
      departure_reason_id: null,
      destiny: null,
      entry_time: null,
      departure_time: null,
      return_time: null,
      start_date: null,
      end_date: null,
      description: null,
      approved: false      
    },
    type: null,
    name: null,
    place: null,
    disabledReason: true,
    types: [],
    reasons: [],
    menuDateStart: null,
    dateStart: null,
    menuDateEnd: null,
    dateEnd: null,
    menuTimeEntry: false,
    menuTimeDeparture: false,
    menuTimeReturn: false,
    alert: true,
    valid: true
  }),
  computed: {
    
  },
  watch: {
    dateStart(val) {
      this.selectedItem.start_date = this.$moment(this.dateStart).format("DD/MM/YYYY");
    },
    dateEnd(val) {
      this.selectedItem.end_date = this.$moment(this.dateEnd).format("DD/MM/YYYY");
    }
  },
  async created() {
    this.getDepartureType();
    console.log(this.$store.getters);
    this.selectedItem.dateDeparture = this.$moment().format("DD/MM/YYYY");
    this.selectedItem.employee_id = this.$store.getters.currentUser.username;  // set name with LDAP
    this.name = this.$store.getters.currentUser.username;  // set place with LDAP
    this.place = this.$store.getters.currentUser.username;  // set place with LDAP
    
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
        let res = await axios.get('/departure_reason/get_reason/' + this.type);
        this.reasons = res.data;
        this.disabledReason = false;
      } catch(e){
        console.log(e);
      }
    },
    async save() {
      try {
        await this.$validator.validateAll();
        let res = await axios.post('/departuere', this.selectedItem);
        this.toastr.success(
          `Solicitado correctamente`
        );
      } catch (e) {
        console.log(e);
      }
    },
  }
};
</script>