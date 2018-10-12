<template>
  <v-dialog persistent v-model="dialog" max-width="1100px" @keydown.esc="close" scrollable>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Certificado de haberes y aportes laborales</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <p v-if="contract.employee" style="font-weight: bold;" align="center">{{ contract.employee.last_name }} {{ contract.employee.mothers_last_name }} {{ contract.employee.first_name }} con C.I. {{ contract.employee.identity_card }} {{ contract.employee.city_identity_card.shortened }},
          <span v-if="contract.act==false" color=""> ocupó </span>
          <span v-if="contract.act==true" color=""> actualmente ocupa </span>
          el cargo de {{ contract.position.name }}
        </p>
        <p>Detalle de haberes y descuentos:</p>
        <v-data-table
        :headers="headers"
        :items="payrolls"
        :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
        disable-initial-sort
        class="elevation-1">
          <template slot="items" slot-scope="props">
            <tr>
              <td class="text-xs-left"> {{ props.item.month_shortened }}-{{ props.item.year }} </td>
              <td class="text-xs-left"> {{ props.item.position }} </td>
              <td class="text-xs-left"> {{ props.item.worked_days }} </td>
              <td class="text-xs-left"> {{ props.item.base_wage.toFixed(2) }} </td>
              <td class="text-xs-left"> {{ props.item.quotable.toFixed(2) }} </td>
              <td class="text-xs-left"> {{ props.item.discount_old.toFixed(2) }} </td>
              <td class="text-xs-left"> {{ props.item.discount_common_risk.toFixed(2) }} </td>
              <td class="text-xs-left"> {{ props.item.discount_commission.toFixed(2) }} </td>
              <td class="text-xs-left"> {{ props.item.discount_solidary.toFixed(2) }} </td>
              <td class="text-xs-left"> {{ props.item.discount_national.toFixed(2) }} </td>
              <td class="text-xs-left">  </td>
              <td class="text-xs-left"> {{ props.item.total_amount_discount_law.toFixed(2) }} </td>
              <td class="text-xs-left"> {{ props.item.net_salary.toFixed(2) }} </td>
            </tr>
          </template>
        </v-data-table>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="this.errors.any()" @click.prevent="print(`/api/v1/payroll/print/certificate/${contract.employee_id}`)"><v-icon>check</v-icon> Imprimir</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "EmployeeCertificate",
  props: ["employee", "bus"],
  data() {
    return {
      payrolls: [],
      contract: [],
      dialog: false,
      headers: [
        {
          text: "MES Y AÑO",
          value: "",
          align: "center"
        },
        {
          text: "CARGO",
          value: "",
          align: "center"
        },
        {
          text: "DIAS TRABAJADOS",
          value: "",
          align: "center"
        },
        {
          text: "HABER BASICO",
          value: "",
          align: "center"
        },
        {
          text: "TOTAL GANADO",
          value: "",
          align: "center"
        },
        {
          text: "RENTA VEJEZ 10%",
          value: "",
          align: "center"
        },
        {
          text: "RIESGO COMUN 1.71%",
          value: "",
          align: "center"
        },
        {
          text: "COMISION 0.5%",
          value: "",
          align: "center"
        },
        {
          text: "APORTE SOLIDARIO DEL ASEGURADO 0.5%",
          value: "",
          align: "center"
        },
        {
          text: "APORTE NACIONAL SOLIDARIO 1% 5% 10%",
          value: "",
          align: "center"
        },
        {
          text: "OTROS DESC.",
          value: "",
          align: "center"
        },
        {
          text: "TOTAL DESC.",
          value: "",
          align: "center"
        },
        {
          text: "LIQUIDO PAGABLE",
          value: "",
          align: "center"
        }
      ],
    };
  },
  created() {
    for (var i = 0; i < this.$store.getters.menuLeft.length; i++) {
      if (this.$store.getters.menuLeft[i].href == 'employeeCertificate') {
        this.options = this.$store.getters.menuLeft[i].options
      }
    }
  },
  methods: {    
    close() {
      this.dialog = false;
      this.$validator.reset();

      this.bus.$emit("closeDialog");
    },
    async getPayrolls(id){
      let res = await axios.get('/api/v1/payroll/certificate/'+id);
      this.payrolls = res.data;
    },
    async getContract(id){
      let res = await axios.get('/api/v1/contract/last_contract/'+id);
      this.contract = res.data;
      console.log(this.contract);
    },
    async print(url) {
      try {
        this.loading = true;
        let res = await axios({
          method: "GET",
          url: url,
          responseType: "arraybuffer"
        });
        let blob = new Blob([res.data], {
          type: "application/pdf"
        });
        printJS(window.URL.createObjectURL(blob));
        this.loading = false;
      } catch (e) {
        this.loading = false;
        console.log(e);
      }
    }
  },
  mounted() {
    this.bus.$on("openDialogCertificate", employee => {
      this.edit = employee;
      this.birth_date = this.edit.birth_date;
      this.dialog = true;
      this.newEmployee = false;
      this.getPayrolls(employee.id);
      this.getContract(employee.id);
    });    
  },
  watch: {
  }
};
</script>