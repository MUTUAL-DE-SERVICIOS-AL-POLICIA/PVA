<template>
  <v-dialog persistent v-model="dialog" max-width="1100px" @keydown.esc="close" scrollable>
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">Haberes y aportes laborales - {{ fullName }}</v-toolbar-title>
      </v-toolbar>
      <v-card-text>        
        <p v-if="contract.employee" style="font-weight: bold;" align="center">
          <span v-if="contract.act==false" color="">  </span>
          <span v-if="contract.act==true" color=""> </span>          
        </p>
        <v-data-table
        :headers="headers"
        :items="payrolls"
        :rows-per-page-items="[12,24,36,{text:'TODO',value:-1}]"
        disable-initial-sort
        class="elevation-1"
        style="width:2500px;height:500px;">
          <template slot="items" slot-scope="props">
            <tr v-model="props.selected">
              <td class="cell text-xs-center"> {{ props.item.month_shortened }}-{{ props.item.year }} </td>
              <td class="cell text-xs-left"> {{ props.item.position }} </td>
              <td class="cell text-xs-center"> {{ props.item.worked_days }} </td>
              <td class="cell text-xs-right"> {{ props.item.base_wage.toFixed(2) }} </td>
              <td class="cell text-xs-right"> {{ props.item.quotable.toFixed(2) }} </td>
              <td class="cell text-xs-right"> {{ props.item.discount_old.toFixed(2) }} </td>
              <td class="cell text-xs-right"> {{ props.item.discount_common_risk.toFixed(2) }} </td>
              <td class="cell text-xs-right"> {{ props.item.discount_commission.toFixed(2) }} </td>
              <td class="cell text-xs-right"> {{ props.item.discount_solidary.toFixed(2) }} </td>
              <td class="cell text-xs-right"> {{ props.item.discount_national.toFixed(2) }} </td>
              <td class="cell text-xs-right"> - </td>
              <td class="cell text-xs-right"> {{ props.item.total_amount_discount_law.toFixed(2) }} </td>
              <td class="text-xs-right"> {{ props.item.net_salary.toFixed(2) }} </td>
            </tr>
          </template>
        </v-data-table>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="success" :disabled="this.errors.any()" @click.prevent="print(`/payroll/print/certificate/${contract.employee_id}`)"><v-icon>check</v-icon> Imprimir</v-btn>
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
      fullName: null,
      payrolls: [],
      contract: [],
      dialog: false,
      selected: [],
      headers: [
        {
          text: "MES-AÑO",
          align: "center",
          class: "cell"         
        },
        {
          text: "CARGO",
          align: "center",
          class: "cell"
        },
        {
          text: "DIAS TRAB.",
          align: "center",
          class: "cell"
        },
        {
          text: "HABER BASICO",
          align: "center",
          class: "cell"
        },
        {
          text: "TOTAL GANADO",
          align: "center",
          class: "cell"
        },
        {
          text: "RENTA VEJEZ 10%",
          align: "center",
          class: "cell"
        },
        {
          text: "RIESGO COMUN 1.71%",
          align: "center",
          class: "cell"
        },
        {
          text: "COMISION 0.5%",
          align: "center",
          class: "cell"
        },
        {
          text: "AP. SOL. DEL ASEGURADO 0.5%",
          align: "center",
          class: "cell"
        },
        {
          text: "AP. NAL. SOL. 1% 5% 10%",
          align: "center",
          class: "cell"
        },
        {
          text: "OTROS DESC.",
          align: "center",
          class: "cell"
        },
        {
          text: "TOTAL DESC.",
          align: "center",
          class: "cell"
        },
        {
          text: "LIQ. PAGABLE",
          align: "center",
          class: "cell"
        }
      ],
    };
  },
  methods: {
    close() {
      this.dialog = false;
      this.$validator.reset();

      this.bus.$emit("closeDialog");
    },
    async getPayrolls(id){
      let res = await axios.get('/payroll/certificate/'+id);
      this.payrolls = res.data;
    },
    async getContract(id){
      let res = await axios.get('/contract/last_contract/'+id);
      this.contract = res.data;
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
      this.fullName = [employee.last_name, employee.mothers_last_name, employee.first_name, employee.second_name].join(" ");
    });    
  },
  watch: {
  }
};
</script>
<style type="text/css">
  .cell {
    margin: -5px !important;
    padding: -15px !important;
  }
</style>