<template>
  <v-container fluid fill-height style="margin-top: 15%;">
    <v-layout row align-center justify-center>
      <v-progress-circular
        :size="100"
        :width="8"
        color="primary"
        indeterminate
      ></v-progress-circular>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: 'Loading'
}
</script>
