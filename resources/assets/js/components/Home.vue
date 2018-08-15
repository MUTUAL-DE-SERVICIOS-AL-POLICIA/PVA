<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-default">
          <div class="card-header">SPA</div>

          <div class="card-body">
            <h2>{{ welcome }}</h2>
            <input type="text" name="name" :value="name">
            <h2>{{ info }} </h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "home",
  data() {
    return {
      info: null,
      name: 0
    };
  },
  async mounted() {
    try {
      let res = await axios.get("api/v1/company");
      this.info = res.data.bpi;
    } catch (e) {
      console.log(e)
    }
  },
  computed: {
    welcome() {
      // return this.$store.getters.welcome;
      return 'Bienvenido'
    }
  }
};
</script>