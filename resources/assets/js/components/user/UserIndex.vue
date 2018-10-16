<template>
  <v-container>
    <v-toolbar>
      <v-toolbar-title>Usuarios</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-flex xs2>
        <v-select
          :items="sources"
          xs3 md3
          label="Origen"
          v-model="sourceSelected"
        ></v-select>
      </v-flex>
    </v-toolbar>
    <UserRegistered v-if="sourceSelected == 'Registrados'"/>
    <UserLdap v-if="sourceSelected == 'LDAP'"/>
  </v-container>
</template>

<script>
import UserRegistered from "./UserRegistered";
import UserLdap from "./UserLdap";

export default {
  name: "userIndex",
  components: {
    UserRegistered,
    UserLdap
  },
  data() {
    return {
      sources: ["Registrados", "LDAP"],
      sourceSelected: ""
    };
  },
  mounted() {
    this.sourceSelected = this.sources[0];
  }
};
</script>

