<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title> 
        Salario Mínimo Nacional 
      </v-toolbar-title>  
    </v-toolbar>
    <MinimumSalaryList />
    <v-toolbar>
      <v-toolbar-title>
        Escala de Porcentajes de Bono de Antiguedad
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-divider class="mx-2" inset vertical></v-divider>
    </v-toolbar>
    <SeniorityBonusList />
  </v-container>
</template>

<script>
import MinimumSalaryList from "./MinimumSalaryList";
import SeniorityBonusList from "./SeniorityBonusList";

export default {
  name: "DepartureConfig",
  components: {
    MinimumSalaryList,
    SeniorityBonusList,
  },
  data: () => ({}),
  mounted() {
    this.getMinimumSalaries();
    this.getBonusCalculate();
  },

};
</script>

<style>
.centered-input input {
  text-align: center;
  margin-bottom: -5px;
  padding-top: 15px;
}
.text-medium {
  font-size: 25px;
}
.pd {
  padding-right: 20px;
}
</style>
