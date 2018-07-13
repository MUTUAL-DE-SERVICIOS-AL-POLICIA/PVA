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
    }
  }
</script>