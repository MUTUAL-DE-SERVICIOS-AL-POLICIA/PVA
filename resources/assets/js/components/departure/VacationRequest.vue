<template>
    <v-dialog
        v-model="dialog"
        :max-width="max_width"
        @keydown.esc="clearAll"
        persistent
    >
        <!-- B O T Ó N   I N I C I A   D I Á L O G O -->
        <v-tooltip slot="activator" top>
            <v-icon large slot="activator" dark color="success">beach_access</v-icon>
            <span>Nueva solicitud de vacaciones</span>
        </v-tooltip>
        <!-- U T I L I T A R I O S  D E L  D I Á L O G O -->
        <v-card>
            <v-toolbar dark color="secondary">
                <v-toolbar-title class="white--text">Solicitud de Vacaciones</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon dark @click.native="clearAll">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <!-- P A S O S  S O L I C I T U D -->
            <v-stepper v-model="step">
                <v-stepper-items>
                    <v-stepper-content step="1" class="step-content">
                        <v-card>
                            <v-card-text>
                                <v-layout>
                                    <v-flex>
                                        <h4 pb-1>Fecha tentativa</h4>
                                        <v-menu
                                            v-model="menu"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on }">
                                                <v-text-field
                                                    clearable
                                                    v-model="tentative_date"
                                                    label="Fecha inicial estimada"
                                                    prepend-icon="event"
                                                    readonly
                                                    :hint="hint"
                                                    :rules="rules"
                                                    v-on="on"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                v-model="tentative_date"
                                                @input="menu = false"
                                                locale="es"
                                                first-day-of-week="1"
                                                :min="date_min"
                                                :max="date_max"
                                            ></v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                    </v-stepper-content>

                    <v-stepper-content step="2">
                        <v-card flat class="text-xs-center">
                            <v-layout wrap>
                                <v-flex
                                    sm12
                                    lg9
                                    class="pr-3"
                                >
                                    <v-sheet height="550">
                                        <v-calendar
                                            ref="calendar"
                                            locale="es"
                                            v-model="date"
                                            :weekdays="[1,2,3,4,5,6,0]"
                                            color="success"
                                            :short-weekdays="false"
                                            class="custom-class"
                                        >
                                            <template v-slot:day="day">
                                                <div
                                                    v-if="$moment(day.date, 'YYYY-MM-DD').isBusinessDay() &&
                                                    (day.date >= $store.getters.dateNow && day.date <= date_max) &&
                                                    (day.date >= tentative_date) &&
                                                    (!busy_days.find((obj) => obj.date === day.date))"
                                                    class="father"
                                                >
                                                    <div
                                                        v-for="turn in ['mañana', 'tarde']"
                                                        :key="turn"
                                                        @click="toggleTurnSelection(day, turn)"
                                                        :class="['day-div',
                                                            (isTurnSelected(day, turn) && turn == 'mañana') ? 'morning' : (isTurnSelected(day, turn) && turn == 'tarde') ? 'afternoon' : '',
                                                            amount_days == 0.0 ? 'do-not-select' : '']"
                                                    >
                                                        <span>{{ turn.toUpperCase() }}</span>
                                                    </div>
                                                </div>
                                                <div v-else-if="busy_days.some((obj) => obj.date === day.date) && day.date >= tentative_date" class="father">
                                                    <div
                                                        v-for="turn in ['mañana', 'tarde']"
                                                        :key="turn"
                                                        @click="toggleTurnSelection(day, turn)"
                                                        :class="['day-div',
                                                            (busy_days.find((obj) => obj.date === day.date).morning && turn == 'mañana') ? 'busy-morning do-not-select' : '',
                                                            (busy_days.find((obj) => obj.date === day.date).afternoon && turn == 'tarde') ? 'busy-afternoon do-not-select' : '',
                                                            ((isTurnSelected(day, turn) && turn == 'mañana') ? 'morning' : (isTurnSelected(day, turn) && turn == 'tarde') ? 'afternoon': ''),

                                                            amount_days == 0.0 ? 'do-not-select' : '',
                                                            { 'clickable': (!(busy_days.find((obj) => obj.date === day.date).morning && turn == 'mañana') ||
                                                                           !(busy_days.find((obj) => obj.date === day.date).afternoon && turn == 'tarde')) && amount_days != 0.0 }
                                                        ]"
                                                    >
                                                        <span>{{ turn.toUpperCase() }}</span>
                                                    </div>
                                                </div>
                                            </template>
                                        </v-calendar>
                                    </v-sheet>
                                </v-flex>
                                <v-flex
                                    sm12
                                    lg3
                                    class="feature-pane"
                                >
                                    <v-btn
                                        fab
                                        outline
                                        small
                                        absolute
                                        left
                                        color="primary"
                                        @click="$refs.calendar.prev()"
                                    >
                                        <v-icon dark class="icon">
                                            keyboard_arrow_left
                                        </v-icon>
                                    </v-btn>
                                    <v-btn color="primary" class="mt-0 pt-0">
                                        {{ names_month[$moment(this.date).format('MM')] + " - " + $moment(this.date).format('YYYY') }}
                                    </v-btn>
                                    <v-btn
                                        fab
                                        outline
                                        small
                                        absolute
                                        right
                                        color="primary"
                                        @click="$refs.calendar.next()"
                                    >
                                        <v-icon dark class="icon">
                                            keyboard_arrow_right
                                        </v-icon>
                                    </v-btn>
                                    <br><br><br>
                                    <div class="count">
                                        <v-card width="130" height="130" color="error" class="format white--text">
                                            <v-card-title class="font-weight-regular pa-0 mb-0 mt-2 subheading border-bottom">DÍAS DISPONIBLES</v-card-title>
                                            <v-card-text class="font-weight-black display-3 pa-1 ma-0">{{  amount_days }}</v-card-text>
                                        </v-card>
                                    </div>
                                    <v-text-field
                                        clearable
                                        label="Introduzca el CITE"
                                        density="compact"
                                        v-model="cite"
                                        class="mt-5 mb-5 pl-5 pr-5"
                                        :rules="rules"
                                    ></v-text-field>
                                    <v-spacer></v-spacer>
                                    <v-alert
                                        class="ml-2 border-left"
                                        :value="true"
                                        text
                                        dense
                                        color="degrade"
                                        icon="info"
                                        border="left"
                                    >
                                        <div class="text-left text-color">
                                            Considere no tomar en cuenta <b>feriados</b>.<br />
                                        </div>
                                    </v-alert>
                                    <v-alert
                                        class="ml-2 mt-2 border-left-error"
                                        :value="value"
                                        text
                                        color="degrade_alert"
                                        icon="warning"
                                        dense
                                        border="left"
                                        :transition="value ? 'scale-transition': ''"
                                    >
                                        <div class="text-left text-color">
                                            {{ message_alert }}
                                        </div>
                                    </v-alert>
                            </v-flex>
                            </v-layout>
                        </v-card>
                    </v-stepper-content>
                </v-stepper-items>
                <div class="text-xs-right mt-3">
                    <v-btn color="primary" v-if="step > 1" @click.native="returnStep">
                        Volver
                    </v-btn>
                    <v-btn color="error" v-if="step < 2" @click.native="nextStep" :disabled="!is_valid">
                        Siguiente
                    </v-btn>
                    <v-btn color="error" v-if="step == 2" @click.native="reconfirmation = true" :disabled="validate">
                        Imprimir
                    </v-btn>
                </div>
            </v-stepper>
        </v-card>
        <v-dialog
            v-model="reconfirmation"
            max-width="500"
            persistent
        >
            <v-card>
                <v-card-title>
                    <h2 class="text">¿Está seguro de realizar la solicitud?</h2>
                </v-card-title>
                <v-card-actions>
                <v-spacer> </v-spacer>
                    <v-btn
                        color="success"
                        text
                        @click="reconfirmation = false"
                    >
                    Cancelar
                    </v-btn>
                    <v-btn
                        color="error"
                        text
                        @click="makeRequest()"
                    >
                    Aceptar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div v-if="loading">
            <Loading/>
        </div>
    </v-dialog>

</template>
<script>
import Loading from '../Loading'
export default {
    name: 'VacationRequest',
    components: {
        Loading,
    },
    props: ["bus"],
    data() {
        return {
            dialog: false,
            max_width: '500px',
            step: 1,
            tentative_date: null,
            hint: 'Introduzca la fecha inicial de su vacación',
            is_valid: null,
            rules: [v => !!v || 'Este campo es requerido'],
            amount_days: 0.0,
            selected_days: [],
            departure_reasons: [],
            date: null,
            date_max: null,
            date_min: null,
            cite: '',
            reconfirmation: false,
            validate: true,
            names_month: {'01':'ENE', '02':'FEB', '03':'MAR', '04':'ABR',
                         '05':'MAY', '06':'JUN', '07':'JUL', '08':'AGO',
                         '09':'SEP', '10':'OCT', '11':'NOV', '12':'DIC'},
            message_alert: '',
            value: false,
            vacation_id: null,
            loading: false,
            menu: false,
            busy_days: [],
        }
    },
    mounted() {
        this.date = this.$store.getters.dateNow
        let date_aux = new Date(this.date)
        this.date_min = date_aux.toISOString().slice(0,10)
        this.date_max = date_aux.getFullYear() + 2 + "-12-31"
        this.getDepartureReasons()
    },
    watch: {
        tentative_date(newValue, oldValue) {
            if(newValue !== null) {
                this.is_valid = this.$moment(newValue, 'YYYY-MM-DD').isBusinessDay()
                if(!this.is_valid) {
                    this.hint = "No puede escoger ni sabado ni domingo para su inicio de vacación"
                } else this.hint = "Introduzca la fecha inicial de su vacación"
            } else {
                this.is_valid = null
                this.hint = "Introduzca la fecha inicial de su vacación"
            }
        },
        step(newValue, oldValue) {
            if(newValue == 1) { // Primer paso
                this.max_width = '500px'
            } else if (newValue == 2) {
                this.max_width = '1300px'
            }
        },
        cite(newValue, oldValue) {
            this.validate = !this.selected_days.length > 0 || !newValue
        }
    },
    methods: {
        async getDepartureReasons() {
            try {
                let response = await axios.get('departure_reason')
                this.departure_reasons = response.data
            } catch(e) {
                this.message_alert = "Hubo un error"
                console.log(e)
            }
        },
        async getApproximateDays() {
            try {
                let response = await axios.get(`count_days/`, {
                    params: {
                        employee_id: this.$store.getters.id,
                        date: this.tentative_date
                    }
                });
                this.amount_days = parseFloat(response.data.count).toFixed(1)
                this.busy_days = this.discardEqualElement(response.data.days)
                console.log(this.busy_days)
            } catch(e) {
                this.message_alert = "Hubo un error"
                console.log(e)
            }
        },
        async makeRequest() {
            this.reconfirmation = true
            this.value = false
            try {
                this.selected_days.sort((a, b) => {
                    const date_A = new Date(a.date);
                    const date_B = new Date(b.date);
                    return date_A - date_B;
                })

                const [ departure_reason ] = this.departure_reasons.filter(departure => departure.name === 'VACACIONES')
                let start = this.selected_days[0]
                let end = this.selected_days[this.selected_days.length - 1]
                let time = ''


                if(start['date'] == end['date']) { // Es la misma fecha?
                    let date_aux = null
                    let date_aux_two = null
                    if(start['morning'] && end['afternoon']) { // Escogío ambos?
                        let time_morning = '08:30:00'
                        let time_afternoon = '14:30:00'
                        let [hour_m, minute_m, second_m] = time_morning.split(':')
                        let [hour_a, minute_a, second_a] = time_afternoon.split(':')
                        date_aux = this.$moment(start['date'])
                        date_aux.set({hour: hour_m, minute: minute_m, second: second_m})
                        start = date_aux.format("YYYY-MM-DD HH:mm:ss")

                        date_aux_two = this.$moment(end['date'])
                        date_aux_two.set({hour: hour_a, minute: minute_a, second: second_a})
                        end = date_aux_two.format("YYYY-MM-DD HH:mm:ss")
                    } else {
                        if(start['morning']) { // Escogio solo en la mañana
                            time = '08:30:00'
                            date_aux = this.$moment(start['date'])
                        } else
                        if(start['afternoon']) { // Escogio solo en la tarde
                            time = '14:30:00'
                            date_aux = this.$moment(end['date'])
                        }
                        let [ hour, minute, second ] = time.split(':')
                        date_aux.set({ hour, minute, second })
                        start = date_aux.format("YYYY-MM-DD HH:mm:ss")
                        end = date_aux.format("YYYY-MM-DD HH:mm:ss")
                    }
                } else {
                    // Si es día completo?
                    time = start['morning'] && start['afternoon'] ? '08:30:00' : (start['morning'] ? '08:30:00' : '14:30:00')
                    let date_morning = this.$moment(start['date'])
                    let [ hour, minute, second ] = time.split(':')
                    date_morning.set({ hour, minute, second })
                    start = date_morning.format("YYYY-MM-DD HH:mm:ss")

                    time = end['morning'] && end['afternoon'] ? '14:30:00' : (end['morning'] ? '08:30:00' : '14:30:00')
                    let date_afternoon = this.$moment(end['date'])
                    let [ hours, minutes, seconds ] = time.split(':')
                    date_afternoon.set({ hour: hours, minute: minutes, second: seconds })
                    end = date_afternoon.format("YYYY-MM-DD HH:mm:ss")
                }

                let response = await axios.post('vacation_departure', {
                    departure_reason_id: departure_reason.id,
                    employee_id: this.$store.getters.id,
                    departure: start,
                    return: end,
                    cite: this.cite,
                    days: this.selected_days
                });
                this.reconfirmation = false
                this.vacation_id = response.data.departure.id
                this.message_alert = response.data.message
                if(this.vacation_id) this.getVacationRequest()
                this.clearAll();

            } catch(e) {
                this.reconfirmation = false
                this.value = true
                this.message_alert = "Hubo un error en la generación de la solicitud"
                console.log(e)
            }
        },
        async getVacationRequest() {
            try {
                this.loading = true
                let response = await axios({
                    method: 'GET',
                    url: `departure_vacation/print/${this.vacation_id}`,
                    responseType: "arraybuffer"
                })
                let blob = new Blob([response.data], {
                    type: "application/pdf"
                })
                printJS(window.URL.createObjectURL(blob))
                this.clearAll()
                this.bus.$emit('updatePermissionList')
                this.loading = false
            } catch(e) {
                console.log(e)
                this.message_alert = "Hubo un error en la generación del documento"
                this.loading = false
            }
        },
        returnStep() {
            this.step -= 1
            this.selected_days = []
            this.cite = ''
            this.tentative_date = null
            this.date = this.$store.getters.dateNow
        },
        nextStep() {
            if(this.step === 1) {
                this.getApproximateDays()
            }
            this.step += 1
        },
        toggleTurnSelection(selected, turn) {
            if(turn == 'mañana') turn = 'morning'; else turn = 'afternoon'
            const selectedDay = this.selected_days.find(item => item.date === selected.date);

            if (!selectedDay) {
                this.selected_days.push({ date: selected.date, morning: false, afternoon: false });
            }

            const selectedDayIndex = this.selected_days.findIndex(item => item.date === selected.date);

            this.selected_days[selectedDayIndex][turn] = !this.selected_days[selectedDayIndex][turn];

            if(this.selected_days[selectedDayIndex][turn] === false) {
                this.amount_days = parseFloat(this.amount_days) + 0.5
            } else this.amount_days = parseFloat(this.amount_days) - 0.5

            if(this.selected_days[selectedDayIndex]['morning'] === false && this.selected_days[selectedDayIndex]['afternoon'] === false) {
                this.selected_days.splice(selectedDayIndex, 1)
            }
            this.validate = !this.selected_days.length > 0 || !this.cite
        },
        isTurnSelected(selected, turn) {
            if(turn == 'mañana') turn = 'morning'; else turn = 'afternoon';
            const selected_day = this.selected_days.find(item => item.date === selected.date)

            if (selected_day) {
                return selected_day[turn]
            }

            return false;
        },
        allowedDates(date) {
            const day_of_week = date.getDay()
            return day_of_week !== 0 && day_of_week !== 6
        },
        clearAll(){
            this.reconfirmation = false
            this.dialog = false
            this.tentative_date = null
            this.step = 1
            this.cite = ''
            this.selected_days = []
            this.date = this.$store.getters.dateNow
        },
        discardEqualElement(days) {
            const days_vacation = new Set();
            for (const day of days) {
                const existing_day = Array.from(days_vacation).find((d) => d.date === day.date)
                if (existing_day) {
                    existing_day.afternoon = day.afternoon !== undefined ? true : false
                    existing_day.morning = day.morning !== undefined ? true: false
                } else {
                    days_vacation.add(day)
                }
            }
            return Array.from(days_vacation)
        }
    }
}
</script>
<style scoped>
.feature-pane {
    position: relative;
    box-shadow: 0 0 5px rgba(0,0,0,0.3);
    border-radius: 5px;
}
.day-div {
    border-bottom: 5px;
    margin: 0px;
    padding: 5px;
    cursor: pointer;
    color:#afaaaa;
    transition: transform 50ms, box-shadow 50ms;
}
.custom-class {
    box-shadow: 0 0 5px rgba(0,0,0,0.3);
    border-radius: 5px;
}
.father > div:first-child {
    border-bottom: 2px solid #afaaaa;
}
.day-div:hover {
    background-color:#65c7bd;
    transition-duration: initial;
    color: black;
    /* transform: translate(1px, 1px); */
    transform: scale(0.96);
}
.icon {
    padding-top: 8px;
}
.count {
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
}
.format {
    border-radius: 7px;
    font-family: sans-serif;
}
.border-bottom {
    border-bottom: 2px solid #fff;
}
.morning {
    background-color: #005D53;
    color: white !important;
}
.afternoon {
    background-color: #099e8f;
    color: white !important;
}
.do-not-select:not(.afternoon):not(.morning) {
    pointer-events: none;
}
.text {
    padding-top: 10px;
    padding-bottom: 10px;
}
.text-left {
    text-align: left;
}
.border-left {
    border-top: none;
    border-left: 7px solid #008acc;
    color:#008ACC
}
.border-left-error {
    border-top: none;
    border-left: 7px solid #be4617;
}
.busy-morning {
    background-color: #F24508;
    color: white !important;
    margin: 0px;
    padding: 5px;
}
.busy-afternoon {
    background-color: #FF5F00;
    color: white !important;
    margin: 0px;
    padding: 5px;
}
.clickable {
    cursor:pointer;
}
.text-color {
    color: black;
}

</style>