<template>
  <div>asdfasdf</div>
</template>

<script>
export default {
  name: "ProcedureEdit",
  mounted() {
    this.getPayroll()
  },
  methods: {
    async getPayroll() {
      try {
        let res = await axios.get(`/api/v1/procedure/${this.$route.params.id}`)
        console.log(res.data)
      } catch(e) {
        consoloe.log(e)
      }
    }
  }
}
</script>