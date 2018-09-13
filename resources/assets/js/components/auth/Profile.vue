<template>
  <v-container>
    <v-toolbar>
      <v-toolbar-title>Perfil</v-toolbar-title>
    </v-toolbar>
    <v-card>
      <v-card-title>
        <v-flex xs2 font-weight-bold>
          Usuario:
        </v-flex>
        <v-flex xs10>
          {{ $store.getters.currentUser.username }}
        </v-flex>
        <v-flex xs2 font-weight-bold>
          Rol:
        </v-flex>
        <v-flex xs10>
          {{ $store.getters.currentUser.roles[0].display_name }}
        </v-flex>
      </v-card-title>
      <v-card-text v-if="!$store.getters.ldapAuth">
        <span class="info--text">Cambiar Contraseña</span>
        <v-flex xs3>
          <v-form>
            <v-text-field
              v-validate="'required|min:5|max:255'"
              v-model="auth.oldPassword"
              label="Contraseña Anterior"
              type="password"
              autocomplete="off"
              ref="oldPassword"
              name="contraseña anterior"
              :error-messages="errors.collect('contraseña anterior')"
              @keyup.enter="focusPassword('newPassword')"
            ></v-text-field>
            <v-text-field
              v-validate="'required|min:5|max:255'"
              v-model="auth.newPassword"
              label="Contraseña Nueva"
              type="password"
              autocomplete="off"
              ref="newPassword"
              name="contraseña nueva"
              :error-messages="errors.collect('contraseña nueva')"
              @keyup.enter="focusPassword('confirmPassword')"
            ></v-text-field>
            <v-text-field
              v-validate="'required|min:5|max:255'"
              v-model="auth.confirmPassword"
              label="Repita la Contraseña"
              type="password"
              autocomplete="off"
              ref="confirmPassword"
              name="confirmación de contraseña"
              :error-messages="errors.collect('confirmación de contraseña')"
              @keyup.enter="changePassword(auth)"
            ></v-text-field>
          </v-form>
        </v-flex>
      </v-card-text>
      <v-card-actions>
        <v-flex xs3>
          <v-btn
            @click="changePassword(auth)"
            primary
            large
            block
            color="success"
          > Cambiar Contraseña </v-btn>
        </v-flex>
      </v-card-actions>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: "Profile",
  data() {
    return {
      auth: {
        oldPassword: "",
        newPassword: "",
        confirmPassword: ""
      }
    };
  },
  methods: {
    focusPassword(key) {
      this.$refs[key].focus();
    },
    async changePassword(auth) {
      try {
        if (await this.$validator.validateAll()) {
          if (this.auth.newPassword != this.auth.confirmPassword) {
            this.auth.newPassword = "";
            this.auth.confirmPassword = "";
            this.focusPassword("newPassword");
            this.toastr.error("Las contraseñas no coinciden");
          } else {
            await axios.patch(
              `/api/v1/user/${this.$store.getters.currentUser.id}`,
              this.auth
            );
            this.toastr.success("Contraseña actualizada correctamente");
            this.$store.dispatch("logout");
            this.$router.push("login");
          }
        }
      } catch (e) {
        this.auth = {
          oldPassword: "",
          newPassword: "",
          confirmPassword: ""
        };
        this.focusPassword("oldPassword");
        for (let key in e.data.errors) {
          e.data.errors[key].forEach(error => {
            this.toastr.error(error);
          });
        }
      }
    }
  }
};
</script>
