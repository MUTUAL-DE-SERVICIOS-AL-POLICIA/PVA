<template>
  <v-container fluid fill-height id="background-page">
    <v-layout row align-center justify-center>
      <v-flex d-flex xs12 sm6 md4>
        <v-card class="pa-5 ma-5">
          <v-img
            src="/img/logo.png"
            aspect-ratio="3"
            contain
          ></v-img>
          <v-card-title primary-title class="justify-center">
            <div class="display-1 font-weight-thin">MÓDULO RR.HH.</div>
          </v-card-title>
          <v-form>
            <v-text-field
              v-validate="'required|min:5|max:255'"
              @keyup.enter="focusPassword()"
              v-model="auth.username"
              prepend-icon="person"
              label="Usuario"
              name="usuario"
              :error-messages="errors.collect('usuario')"
              autocomplete="on"
              autofocus
              required
            ></v-text-field>
            <v-text-field
              v-validate="'required|min:5|max:255'"
              @keyup.enter="authenticate(auth)"
              v-model="auth.password"
              prepend-icon="lock"
              label="Contraseña"
              type="password"
              autocomplete="on"
              ref="password"
              name="contraseña"
              :error-messages="errors.collect('contraseña')"
              required
            ></v-text-field>
            <v-card-actions>
              <v-btn
                @click="authenticate(auth)"
                primary
                large
                block
                color="success"
              > Ingresar </v-btn>
            </v-card-actions>
          </v-form>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  export default {
    name: 'login',
    data() {
      return {
        auth: {
          username: '',
          password: ''
        },
        error: null
      }
    },
    methods: {
      focusPassword() {
        this.$refs.password.focus()
      },
      async authenticate(auth) {
        try {
          await this.$validator.validateAll()
          let res = await axios.post('/api/v1/auth', auth)
          localStorage.setItem('token', res.data.token)
          localStorage.setItem('token_type', res.data.token_type)
          localStorage.setItem('user', JSON.stringify(res.data.user))
          this.$router.go({
            name: 'home'
          })
        } catch(e) {
          auth.password = ''
          this.focusPassword()
          for (let key in e.data.errors) {
            e.data.errors[key].forEach(error => {
              this.toastr.error(error)
            })
          }
        }
      }
    },
  }
</script>

<style>
  #background-page {
    background: rgba(107,168,114,1);
    background: -moz-linear-gradient(top, rgba(107,168,114,1) 0%, rgba(55,124,62,1) 47%, rgba(7,84,15,1) 90%);
    background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(107,168,114,1)), color-stop(47%, rgba(55,124,62,1)), color-stop(90%, rgba(7,84,15,1)));
    background: -webkit-linear-gradient(top, rgba(107,168,114,1) 0%, rgba(55,124,62,1) 47%, rgba(7,84,15,1) 90%);
    background: -o-linear-gradient(top, rgba(107,168,114,1) 0%, rgba(55,124,62,1) 47%, rgba(7,84,15,1) 90%);
    background: -ms-linear-gradient(top, rgba(107,168,114,1) 0%, rgba(55,124,62,1) 47%, rgba(7,84,15,1) 90%);
    background: linear-gradient(to bottom, rgba(107,168,114,1) 0%, rgba(55,124,62,1) 47%, rgba(7,84,15,1) 90%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6ba872', endColorstr='#07540f', GradientType=0 );
  }
</style>