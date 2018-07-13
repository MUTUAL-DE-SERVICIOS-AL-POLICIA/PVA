<template>
  <div class="login row justify-content-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">Login</div>
        <div class="card-body">
          <div>
            <div class="form-group-row">
              <label for="username">Usuario: </label>
              <input id="username" type="text" class="form-control" v-model="auth.username" name="usuario" v-validate="'required'" autofocus="">
              <p v-if="errors.has('usuario')" class="alert-danger">
                {{ errors.first('usuario') }}
              </p>
            </div>
            <div class="form-group-row">
              <label for="password">password: </label>
              <input v-on:keyup.enter="authenticate(auth)" id="password" type="password" class="form-control" v-model="auth.password" name="contraseña" v-validate="'required'" ref="password">
              <p v-if="errors.has('contraseña')" class="alert-danger">
                {{ errors.first('contraseña') }}
              </p>
            </div>
            <div class="form-group-row">
              <input type="submit" v-on:click="authenticate(auth)" value="Ingresar">
            </div>
          </div>
        </div>
        </div>
    </div>
  </div>
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
      authenticate(auth) {
        this.$validator.validateAll().then(() => {
          this.$store.dispatch('login')
          return axios.post('/api/auth/login', auth)
        }).then(res => {
          if (res.status == 200) {
            localStorage.setItem('token', res.data.token)
            localStorage.setItem('token_type', res.data.token_type)
            localStorage.setItem('user', JSON.stringify(res.data.user))
            this.$router.go({
              name: 'home'
            })
          } else {
            return new Promise((resolve, reject) => {
              return reject(res)
            })
          }
        }).catch(error => {
          for (let key in error.response.data.errors) {
            error.response.data.errors[key].forEach(error => {
              this.toastr.error(error)
            });
          }
          auth.password = ''
          this.$refs.password.focus();
        })
      }
    },
  }
</script>