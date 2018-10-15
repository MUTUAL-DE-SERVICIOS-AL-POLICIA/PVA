<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close" scrollable>
    <v-tooltip slot="activator" top>
      <v-icon large slot="activator" dark color="primary">add_circle</v-icon>
      <span>Añadir contrato</span>
    </v-tooltip>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Datos del Empleado</v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-flex>
          <v-autocomplete
            :items="contracts"
            hide-selected
            clearable
            label="Buscar empleado"
            :readonly="false"
            item-text="employee.last_name"
            item-value="id"
            @change="employeeChange"
            :auto-select-one-item="false"
            v-model="selected"
            single-line
          >
            <template
              slot="selection"
              slot-scope="{ item, selected }"
            >
              {{ `${item.employee.last_name} ${item.employee.mothers_last_name} ${item.employee.first_name} ${(item.employee.second_name) ? item.employee.second_name : ''}` }}
            </template>
            <template
              slot="item"
              slot-scope="{ item, tile }"
            >
              <v-list-tile-content>
                <v-layout wrap row>
                  <v-flex>
                    <v-list-tile-title v-text="`${item.employee.last_name} ${item.employee.mothers_last_name} ${item.employee.first_name} ${(item.employee.second_name) ? item.employee.second_name : ''}`"></v-list-tile-title>
                    <v-list-tile-sub-title v-text="`${item.position.name}`"></v-list-tile-sub-title>
                  </v-flex>
                </v-layout>
              </v-list-tile-content>
              <v-divider
                class="mx-4"
                inset
                vertical
              ></v-divider>
              <v-list-tile-action>
                <v-list-tile-sub-title v-text="`${$moment(item.start_date).format('L')}`"></v-list-tile-sub-title>
                <v-list-tile-sub-title v-text="`${(item.retirement_date) ? $moment(item.retirement_date).format('L') : ((item.end_date) ? $moment(item.end_date).format('L') : 'indefinido')}`"></v-list-tile-sub-title>
              </v-list-tile-action>
            </template>
          </v-autocomplete>
          <v-container v-if="this.contract.start_date">
            <v-list>
              <v-list-tile v-if="codeExists" class="warning">
                <v-list-tile-content class="font-weight-bold">Este contrato ya está registrado para este mes con la boleta:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ this.contract.code }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile v-else class="tertiary">
                <v-list-tile-content class="font-weight-bold">Este contrato no está registrado aún para este mes</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content class="font-weight-bold">Carnet de identidad:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ this.contract.employee.identity_card }} {{ this.contract.employee.city_identity_card.shortened }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content class="font-weight-bold">Fecha de inicio:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ $moment(this.contract.start_date).format('L') }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content class="font-weight-bold">Fecha de conclusión:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ (this.contract.end_date) ? ($moment(this.contract.end_date).format('L')) : 'Indefinido' }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content class="font-weight-bold">Fecha de retiro:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ (this.contract.retirement_date) ? ($moment(this.contract.retirement_date).format('L')) : '-' }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content class="font-weight-bold">Puesto:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ this.contract.position.name }}</v-list-tile-content>
              </v-list-tile>
              <v-list-tile>
                <v-list-tile-content class="font-weight-bold">Unidad:</v-list-tile-content>
                <v-list-tile-content class="align-end">{{ this.contract.position.position_group.name }}</v-list-tile-content>
              </v-list-tile>
            </v-list>
          </v-container>
        </v-flex>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" @click="addPayroll" :disabled="typeof this.contract.code == 'string' || !this.contract.employee.identity_card"><v-icon>check</v-icon> Añadir</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "PayrollAdd",
  props: ["contracts", "procedure", "bus"],
  data() {
    return {
      dialog: false,
      search: null,
      contract: null,
      codeExists: false,
      selected: ""
    };
  },
  methods: {
    close() {
      this.dialog = false;
      this.eraseSelected();
      this.bus.$emit("closeDialog");
    },
    async addPayroll() {
      try {
        if (this.contract.employee.identity_card) {
          let res = await axios.post(`/api/v1/payroll`, {
            procedure_id: this.procedure.id,
            contract_id: this.contract.id,
            employee_id: this.contract.employee.id,
            charge_id: this.contract.position.charge.id,
            position_group_id: this.contract.position.position_group.id,
            position_id: this.contract.position.id
          });
          this.toastr.success("Registro añadido correctamente");
        }
      } catch (e) {
        console.log(e);
      } finally {
        this.eraseSelected();
        this.close();
      }
    },
    eraseSelected() {
      this.selected = "";
      this.contract = {
        code: null,
        employee: {
          identity_card: null,
          city_identity_card: {
            shortened: null
          }
        },
        start_date: null,
        end_date: null,
        retirement_date: null,
        retirement_date: null,
        position: {
          name: null,
          position_group: {
            name: null
          }
        }
      };
    },
    async employeeChange(value) {
      try {
        if (!value) {
          this.eraseSelected();
          this.codeExists = false;
        } else {
          this.eraseSelected();
          this.contract = await this.contracts.find(obj => {
            return obj.id == value;
          });
          let res = await axios.get(
            `/api/v1/payroll/procedure/${this.procedure.id}`,
            {
              params: {
                contract_id: this.contract.id,
                employee_id: this.contract.employee.id,
                charge_id: this.contract.position.charge.id,
                position_group_id: this.contract.position.position_group.id,
                position_id: this.contract.position.id
              }
            }
          );
          this.contract.code = await res.data.code;
          this.$forceUpdate();
          this.codeExists = true;
        }
      } catch (e) {
        console.log(e);
      }
    }
  },
  created() {
    this.eraseSelected();
  },
  mounted() {
    this.bus.$on("openDialog", () => {
      this.eraseSelected();
      this.dialog = true;
    });
  }
};
</script>

