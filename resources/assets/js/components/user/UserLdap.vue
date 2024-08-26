<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="users"
      :search="search"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
      disable-initial-sort
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-xs-center">{{ props.item.uid }}</td>
          <td class="text-xs-center">{{ props.item.givenName }} {{ props.item.sn }}</td>
          <td class="text-xs-center">{{ props.item.title }}</td>
          <td class="text-xs-center">
            <v-tooltip top v-if="!props.item.added">
              <v-btn slot="activator" flat icon color="primary" @click.native="createUser(props.item.employeeNumber)">
                <v-icon>add_circle</v-icon>
              </v-btn>
              <span>Agregar usuario</span>
            </v-tooltip>
            <v-tooltip top>
              <v-btn slot="activator" flat icon color="info" @click.native="updateUser(props.item.employeeNumber)">
                <v-icon>cached</v-icon>
              </v-btn>
              <span>Actualizar</span>
            </v-tooltip>
            <v-tooltip top>
              <v-btn slot="activator" flat icon color="error" @click.native="resetPassword(props.item.employeeNumber)">
                <v-icon>vpn_key</v-icon>
              </v-btn>
              <span>Reiniciar Contraseña</span>
            </v-tooltip>
          </td>
        </tr>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import { log } from 'util';
export default {
  name: "userLdap",
  data() {
    return {
      users: [],
      registeredUsers: [],
      headers: [
        {
          align: "center",
          text: "Usuario",
          value: "uid",
          sortable: true
        },
        {
          align: "center",
          text: "Nombre",
          value: "cn",
          sortable: true
        },
        {
          align: "center",
          text: "Cargo",
          value: "title",
          sortable: true
        },
        {
          align: "center",
          text: "Acciones",
          value: "sn",
          sortable: false
        }
      ],
      search: ""
    };
  },
  mounted() {
    this.getUsers();
    this.getEntries();
  },
  methods: {
    async getUsers() {
      try {
        try {
          let res = await axios.get("/user");
          res.data.forEach(user => {
            this.registeredUsers.push(user.username);
          });
        } catch (e) {
          console.log(e);
        }
      } catch (e) {
        console.log(e);
      }
    },
    async getEntries() {
      try {
        let res = await axios.get("/ldap");
        this.users = _.sortBy(res.data, "uid");

        this.registeredUsers.forEach(uid => {
          let obj = this.users.find(el => el.uid == uid);
          if (obj) {
            let index = this.users.indexOf(obj);
            this.users.fill((obj.added = true), index, index++);
          }
        });
      } catch (e) {
        console.log(e);
      }
    },
    async createUser(id) {
      try {
        let res = await axios.post("/user", {
          employee_id: id
        });
        this.$router.go({
          name: "userIndex"
        });
      } catch (e) {
        console.log(e);
      }
    },
    async resetPassword(id) {
      try {
        let res = await axios.delete(`/ldap/${id}`)
        this.toastr.success(`Contraseña reiniciada para el usuario: ${res.data.uid}`)
      } catch (e) {
        console.log(e)
      }
    },
    async updateUser(id) {
      try {
        let res = await axios.patch(`/ldap/${id}`);

        if (res.data.updated) {
          this.toastr.success("Actualizado con éxito");
        } else {
          this.toastr.error("Error al actualizar");
        }

        let found = this.users.findIndex(obj => {
          return obj.employeeNumber == res.data.employee.id;
        });

        this.users[found].givenName = res.data.employee.first_name;
        if (res.data.employee.second_name) {
          this.users[found].givenName += ` ${res.data.employee.second_name}`;
        }
        this.users[found].sn = res.data.employee.last_name;
        if (res.data.employee.mothers_last_name) {
          this.users[found].sn += ` ${res.data.employee.mothers_last_name}`;
        }
      } catch (e) {
        console.log(e);
      }
    }
  }
};
</script>
