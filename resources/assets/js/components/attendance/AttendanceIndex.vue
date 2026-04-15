<template>
  <v-container fluid>
    <template v-if="!manteinanceMode">
      <v-toolbar v-if="!loading">
        <v-toolbar-title>
          {{ (limits.start && limits.end) ? `Asistencia del ${$moment(limits.start).format('L')} al ${$moment(limits.end).format('L')}` : `Asistencia del mes de ${$moment(date).format('MMMM')}` }}
        </v-toolbar-title>

        <v-tooltip color="white" role="button" bottom>
          <v-icon slot="activator" class="ml-4">info</v-icon>
          <div>
            <v-alert :value="true" type="success" color="green darken-2">INGRESO</v-alert>
            <v-alert :value="true" type="info" color="blue darken-2">SALIDA</v-alert>
            <v-alert :value="true" type="error" color="red darken-2">RETRASO</v-alert>
            <v-alert :value="true" type="warning" color="yellow darken-4">FUERA DE RANGO</v-alert>
          </div>
        </v-tooltip>

        <v-spacer></v-spacer>

        <AttendanceSync v-if="$store.getters.role == 'admin'"/>
        <AttendanceErase v-if="$store.getters.role == 'admin'"/>
        <ReportPrint v-if="['admin', 'rrhh'].includes($store.getters.role)" url="attendance/print"/>

        <v-btn color="primary" @click="showDate = !showDate">
          {{ $moment(date).format('MMMM') }}
        </v-btn>
      </v-toolbar>

      <div v-if="loading">
        <Loading/>
      </div>

      <v-flex v-else>
        <v-card class="pa-1" v-if="isAdmin">
          <v-container grid-list-md text-xs-center>
            <v-layout row wrap>
              <v-flex xs10>
                <v-autocomplete
                  :items="employees"
                  item-text="fullName"
                  label="Empleado"
                  v-model="selectedEmployee"
                  item-value="id"
                  clearable
                  prepend-icon="person"
                  :hint="selectedEmployeePosition"
                  persistent-hint
                  flat
                ></v-autocomplete>
              </v-flex>

              <v-flex xs2>
                <template class="justify-center layout" v-if="selectedEmployee">
                  <v-tooltip top>
                    <v-btn medium slot="activator" flat icon color="info" @click="getChecks(selectedEmployee, true)">
                      <v-icon>print</v-icon>
                    </v-btn>
                    <span>Imprimir asistencia del funcionario</span>
                  </v-tooltip>

                  <AttendanceAdd
                    v-if="$store.getters.role == 'admin' && $route.query.add"
                    :id="selectedEmployee"
                    :limits="limits"
                    :bus="bus"
                  ></AttendanceAdd>
                </template>
              </v-flex>
            </v-layout>
          </v-container><div>{{ limits }}</div>
        </v-card>
        <Loading v-if="loading"></Loading>
        <!--<v-card v-else-if="(safeChecks.length > 0 || safeDepartures.length > 0) && limits.start && limits.end">-->
          <!--<v-card v-else-if="safeChecks.length > 0 || safeDepartures.length > 0">-->
          <v-calendar
            :start="limits.start"
            :end="limits.end"
            :now="$store.getters.dateNow"
            :short-months="false"
            locale="es-bo"
            type="custom-weekly"
            :weekdays="[1,2,3,4,5,6,0]"
          >
            <template v-slot:day="{ date }">
              <div
                v-for="event in departuresByDate(date)"
                :key="'dep-' + (event.id || event.code || date)"
              >
                <div
                  v-if="date && $moment(date, 'YYYY-MM-DD').isBusinessDay()"
                  class="text-xs text-xs-center white--text grey darken-3 mb-2"
                >
                  <div>{{ event.reason }} [ {{ event.code }} ]</div>
                  <div>{{ event.description }}</div>
                  <div>{{ departureScheduleText(event, date) }}</div>
                  <div>{{ verifyDepartureState(event.approved) }}</div>
                </div>
              </div>

              <div
                v-for="(event, index) in checksByDate(date)"
                :key="'chk-' + date + '-' + index"
              >
                <div
                  v-if="index > 0 && checksByDate(date)[index - 1] && event.shift != checksByDate(date)[index - 1].shift"
                  class="text-xs-center event subheading grey darken-1 mt-3 mb-3"
                ></div>
                <div
                  class="text-xs-center white--text event subheading"
                  :class="event.color + ' darken-' + event.shift"
                >
                  {{ event.time }}
                </div>
              </div>
            </template>
          </v-calendar>
        <!--</v-card>

        <v-card v-else>
          <v-card-text v-if="!loading">
            <h2 class="red--text">No existen registros de asistencia ni permisos</h2>
          </v-card-text>
        </v-card>-->
      </v-flex>
    </template>

    <template v-else>
      <ManteinanceDialog positionGroup="la Unidad de Recursos Humanos"></ManteinanceDialog>
    </template>

    <v-dialog
      v-model="showDate"
      width="460"
    >
      <v-date-picker
        v-model="date"
        :landscape="true"
        type="month"
        locale="es-BO"
        :max="maxDate"
      ></v-date-picker>
    </v-dialog>
  </v-container>
</template>

<script>
import Vue from "vue";
import Loading from "../Loading";
import ManteinanceDialog from "../ManteinanceDialog";
import AttendanceSync from "./AttendanceSync";
import AttendanceErase from "./AttendanceErase";
import AttendanceAdd from "./AttendanceAdd";
import ReportPrint from "../ReportPrint";

export default {
  name: "AttendanceIndex",
  components: {
    Loading,
    ManteinanceDialog,
    AttendanceSync,
    AttendanceErase,
    AttendanceAdd,
    ReportPrint
  },
  data() {
    return {
      loading: true,
      checks: [],
      departures: [],
      limits: {
        start: null,
        end: null
      },
      bus: new Vue(),
      employees: [],
      selectedEmployee: null,
      selectedEmployeePosition: null,
      date: null,
      showDate: false
    };
  },
  watch: {
    selectedEmployee: function(val) {
      if (val != null) {
        if (typeof val === "number") {
          this.getChecks(val);
        }

        if (
          this.selectedEmployee &&
          typeof this.selectedEmployee === "object" &&
          this.selectedEmployee.hasOwnProperty("id")
        ) {
          this.selectedEmployee = this.selectedEmployee.id;
        }

        var employee = this.employees.find(function(o) {
          return o.id == this.selectedEmployee;
        }.bind(this));

        if (employee) {
          this.selectedEmployeePosition = employee.position || "Nombre del empleado";
        }
      }
    },
    date: function() {
      if (typeof this.selectedEmployee === "object") {
        if (this.selectedEmployee === null) {
          this.selectedEmployee = this.$store.getters.id;
        } else {
          this.selectedEmployee = this.selectedEmployee.id;
        }
      } else {
        this.getChecks(this.selectedEmployee);
      }

      this.showDate = false;
    },
    checks: function(val) {
      if ((!val || val.length === 0) && (!this.departures || this.departures.length === 0)) {
        this.limits = {
          start: null,
          end: null
        };
      }
    }
  },
  computed: {
    manteinanceMode() {
      return JSON.parse(process.env.MIX_ATTENDANCE_MANTEINANCE_MODE);
    },
    isAdmin() {
      return this.$store.getters.role == "admin" || this.$store.getters.role == "rrhh";
    },
    maxDate() {
      var now = this.$moment(this.$store.getters.dateNow);

      if (now.date() <= 20) {
        return now.format();
      } else {
        now = now.add(1, "months").date(1).format("YYYY-MM-DD");
        if (!this.date) {
          this.date = now;
        }
        return now;
      }
    },
    safeDepartures() {
      if (!Array.isArray(this.departures)) {
        return [];
      }

      return this.departures.filter(function(o) {
        if (!o) return false;

        if (parseInt(o.departure_reason_id) === 24) {
          return Array.isArray(o.days) && o.days.length > 0;
        }

        return !!(
          o.from &&
          o.to &&
          o.from.date &&
          o.to.date
        );
      });
    },
    safeChecks() {
      if (!Array.isArray(this.checks)) {
        return [];
      }

      return this.checks.filter(function(o) {
        return !!(o && o.date && o.time);
      });
    }
  },
  mounted() {
    this.date = this.$store.getters.dateNow;

    if (this.isAdmin) {
      this.getEmployees();
    } else {
      this.getChecks();
    }

    this.bus.$on("newCheck", function() {
      this.getChecks(this.selectedEmployee);
    }.bind(this));
  },
  methods: {
    verifyDepartureState(state) {
      if (state === null) {
        return "PENDIENTE";
      } else if (state === true) {
        return "APROBADO";
      } else if (state === false) {
        return "RECHAZADO";
      }
      return "";
    },

    departuresByDate(date) {
      if (!date) {
        return [];
      }

      return this.safeDepartures.filter(function(o) {
        if (!o) return false;

        if (parseInt(o.departure_reason_id) === 24 && Array.isArray(o.days)) {
          return o.days.some(function(d) {
            return d && d.date == date;
          });
        }

        return o.from &&
          o.to &&
          o.from.date &&
          o.to.date &&
          this.$moment(date).isBetween(
            this.$moment(o.from.date).startOf("day"),
            this.$moment(o.to.date).endOf("day"),
            null,
            "[]"
          );
      }.bind(this));
    },

    checksByDate(date) {
      if (!date) {
        return [];
      }

      return this.safeChecks.filter(function(o) {
        return o.date == date;
      });
    },

    departureDayInfo(event, date) {
      if (!event || !date) {
        return null;
      }

      if (parseInt(event.departure_reason_id) !== 24 || !Array.isArray(event.days)) {
        return null;
      }

      return event.days.find(function(d) {
        return d && d.date == date;
      }) || null;
    },

    departureScheduleText(event, date) {
      if (!event || !date) {
        return "";
      }

      if (parseInt(event.departure_reason_id) === 24 && Array.isArray(event.days)) {
        var dayInfo = this.departureDayInfo(event, date);

        if (!dayInfo) {
          return "";
        }

        if (dayInfo.label) {
          return dayInfo.label;
        }

        if (dayInfo.period === "todo el día") {
          return "TODO EL DÍA";
        }

        if (dayInfo.period === "mañana") {
          return "HASTA 12:30";
        }

        if (dayInfo.period === "tarde") {
          return "DESDE 14:30";
        }

        return "";
      }

      if (event.from && event.to) {
        return event.from.time + " - " + event.to.time;
      }

      return "";
    },

    async getDepartures(id) {
      try {
        var res = await axios.get("employee/" + id + "/departure", {
          params: {
            month: this.date
          }
        });

        this.departures = Array.isArray(res.data) ? res.data : [];

        var dateCandidates = [];

        this.departures.forEach(function(o) {
          if (!o) return;

          if (parseInt(o.departure_reason_id) === 24 && Array.isArray(o.days)) {
            o.days.forEach(function(d) {
              if (d && d.date) {
                dateCandidates.push(d.date);
              }
            });
            return;
          }

          if (o.from && o.from.date) {
            dateCandidates.push(o.from.date);
          }

          if (o.to && o.to.date) {
            dateCandidates.push(o.to.date);
          }
        });

        if ((!this.checks || this.checks.length === 0) && dateCandidates.length > 0) {
          dateCandidates.sort();

          this.limits = {
            start: dateCandidates[0],
            end: dateCandidates[dateCandidates.length - 1]
          };
        }
        
      } catch (e) {
        console.log(e);
        this.departures = [];
      }
    },

    async getChecks(id, print) {
      if (typeof id === "undefined" || id === null) {
        id = this.$store.getters.id;
      }

      if (typeof print === "undefined") {
        print = false;
      }

      try {
        this.loading = true;

        if (!print) {
          this.checks = [];
          this.departures = [];
        }

        var res = await axios.get("employee/" + id + "/attendance", {
          responseType: print ? "arraybuffer" : "json",
          params: {
            month: this.date,
            type: print ? "pdf" : "json",
            with_discounts: print
          }
        });

        if (print) {
          var blob = new Blob([res.data], {
            type: "application/pdf"
          });
          printJS(window.URL.createObjectURL(blob));
        } else {
          this.checks = Array.isArray(res.data.checks) ? res.data.checks : [];
          this.limits = {
            start: res.data.from || null,
            end: res.data.to || null
          };

          await this.getDepartures(id);
        }
      } catch (e) {
        console.log(e);
        this.checks = [];
        this.departures = [];
      } finally {
        this.loading = false;
      }
    },

    async getEmployees() {
      try {
        var res = await axios.get("employee");
        this.employees = Array.isArray(res.data) ? res.data : [];

        this.employees.forEach(function(e) {
          e.fullName =
            (e.last_name || "") + " " +
            (e.mothers_last_name || "") + " " +
            (e.first_name || "") + " " +
            (e.second_name || "");
        });

        this.loading = false;
      } catch (e) {
        console.log(e);
        this.loading = false;
      }
    }
  }
};
</script>

<style>
.event {
  overflow: hidden;
  border-radius: 8px;
  width: 50%;
  padding: 3px;
  margin-left: 25%;
  margin-bottom: 6px;
}
</style>