<template>
  <div id="main">
    <app-header/>
    <router-view></router-view>
  </div>
</template>

<script>
  import AppHeader from './AppHeader.vue';

  export default {
    name: 'app-main',
    components: {
      'app-header': AppHeader,
    },
    mounted() {
      axios.get('/api/auth/me').then((response) => {
        console.log('OK')
        console.log(JSON.stringify(response.data, null, 2))
      }).catch((error) => {
        console.log('BAD')
        console.log(JSON.stringify(error, null, 2))
      })
    }
  }
</script>