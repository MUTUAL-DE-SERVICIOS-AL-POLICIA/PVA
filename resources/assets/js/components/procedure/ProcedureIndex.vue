<template>
  <v-container>
    qwerqwer
  </v-container>
</template>

<script>
export default {
  name: "ProcedureIndex",
  data() {
    return {
      
    };
  },
  async mounted() {
    this.getProcedures();
  },
  methods: {
    async getProcedures(active = true) {
      try {
        let res = await axios.get(`/api/v1/procedure`);
        console.log(res.data);
      } catch (e) {
        console.log(e);
      }
    },
  }
};
</script>