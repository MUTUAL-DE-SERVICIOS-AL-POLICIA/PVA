<template>
  <v-dialog persistent v-model="dialog" max-width="900px">
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Nuevo Empleado</span>
    </v-tooltip>
      <v-card>
      <v-card-title>
        <span class="headline">Datos del Empleado</span>
      </v-card-title>
      <v-card-text>
        <form ref="form">
          <v-layout wrap>
            <v-flex xs12 sm6 md6>
              <v-layout wrap>
                <v-flex>
                  <v-text-field
                    v-validate="'required'"
                    :error-messages="errors.collect('Carnet de Identidad')"
                    data-vv-name="Carnet de Identidad"
                    v-model="edit.identity_card"
                    label="C.I."
                  ></v-text-field>
                </v-flex>
                <v-flex>
                  <v-select
                    :items="cities"
                    item-text="shortened"
                    item-value="id"
                    label="Ciudad"
                    v-model="edit.city_identity_card_id"
                    single-line
                  ></v-select>
                </v-flex>
              </v-layout>
              <v-text-field
                v-validate="'required|alpha_spaces'"
                :error-messages="errors.collect('Nombre')"
                data-vv-name="Nombre"
                v-model="edit.first_name"
                label="Nombres"
              ></v-text-field>
              <v-text-field
                v-validate="'required|alpha_spaces'"
                :error-messages="errors.collect('Apellido Paterno')"
                data-vv-name="Apellido Paterno"
                v-model="edit.last_name"
                label="Apellido Paterno"
              ></v-text-field>
              <v-text-field
                v-validate="'required|alpha_spaces'"
                :error-messages="errors.collect('Apellido Materno')"
                data-vv-name="Apellido Materno"
                v-model="edit.mothers_last_name"
                label="Apellido Materno"
              ></v-text-field>
              <v-menu
                ref="menu"
                :close-on-content-click="false"
                v-model="menu"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
              >
                <v-text-field
                  slot="activator"
                  v-model="this.$moment(edit.birth_date).format('DD/MM/YYYY') || '1980-01-01'"
                  label="Birthday date"
                  prepend-icon="event"
                  readonly
                ></v-text-field>
                <v-date-picker
                  ref="picker"
                  v-model="edit.birth_date"
                  :max="new Date().toISOString().substr(0, 10)"
                  min="1950-01-01"
                  @change="saveDate"
                ></v-date-picker>
              </v-menu>
            </v-flex>
          </v-layout>
        </form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="this.errors.any()" @click="saveEmployee"><v-icon>check</v-icon> Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "EmployeeEdit",
  props: ["employee", "bus"],
  data() {
    return {
      edit: {},
      cities: [],
      dialog: false,
      date: null,
      menu: false
    };
  },
  methods: {
    close() {
      this.dialog = false;
      this.edit = {};
      this.bus.$emit("closeDialog");
    },
    async getCities() {
      try {
        let res = await axios.get(`/api/v1/city`);
        this.cities = res.data;
      } catch (e) {
        this.dialog = false;
        console.log(e);
      }
    },
    saveEmployee() {
      console.log(this.edit);
    },
    saveDate(date) {
      this.$refs.menu.save(date);
    }
  },
  mounted() {
    this.bus.$on("openDialog", employee => {
      this.edit = employee;
      this.dialog = true;
    });
    this.getCities();
  },
  watch: {
    menu(val) {
      val && this.$nextTick(() => (this.$refs.picker.activePicker = "YEAR"));
    }
  }
};
</script>