<template>
  <div>
    <RemoveItem :bus="bus"/>
    <v-data-table
      :headers="headers"
      :items="users"
      :search="search"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
      disable-initial-sort
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-xs-center">
            <v-tooltip right v-if="props.item.name">
              <span slot="activator">
                {{ props.item.username }}
              </span>
              <span>
                <div>{{ props.item.name }}</div>
                <div>{{ props.item.position }}</div>
              </span>
            </v-tooltip>
            <span v-else>
              {{ props.item.username }}
            </span>
          </td>
          <td
            v-for="(role, index) in roles"
            v-bind:item="role"
            v-bind:index="index"
            v-bind:key="role.id"
            class="text-xs-center"
          >
            <v-layout row wrap>
              <v-flex xs6></v-flex>
              <v-checkbox @click.native="updateRole(props.item.id, role.id, verifyRole(props.item.roles, role))" :input-value="verifyRole(props.item.roles, role)" readonly></v-checkbox>
            </v-layout>
          </td>
          <td class="text-xs-center">
            <v-tooltip top>
              <v-btn medium slot="activator" flat icon color="red darken-3" @click.native="removeItem(props.item)">
                <v-icon>delete</v-icon>
              </v-btn>
              <span>Eliminar</span>
            </v-tooltip>
          </td>
        </tr>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import Vue from "vue";
import RemoveItem from "../RemoveItem";

export default {
  name: "userRegistered",
  components: {
    RemoveItem
  },
  data() {
    return {
      bus: new Vue(),
      roles: [],
      users: [],
      headers: [
        {
          align: "center",
          text: "Usuario",
          value: "username",
          sortable: true
        }
      ],
      search: ""
    };
  },
  mounted() {
    this.getRoles();
    this.getUsers();
    this.bus.$on("closeDialog", () => {
      this.$router.go(0);
    });
  },
  methods: {
    verifyRole(roles, role) {
      if (
        roles.find(obj => {
          return obj.id == role.id;
        })
      ) {
        return true;
      } else {
        return false;
      }
    },
    async getRoles() {
      try {
        let res = await axios.get("/role");
        this.roles = res.data;
        this.roles.forEach(role => {
          this.headers.push({
            text: role.display_name,
            value: "",
            sortable: false,
            align: "center"
          });
        });
        this.headers.push({
          text: "Acciones",
          value: "",
          sortable: false,
          align: "center"
        });
      } catch (e) {
        console.log(e);
      }
    },
    async getUsers() {
      try {
        let res = await axios.get("/user");
        this.users = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    async updateRole(userId, roleId, status) {
      try {
        if (status) {
          await axios.delete(`/user/${userId}/role/${roleId}`);
        } else if (!status) {
          await axios.patch(`/user/${userId}/role/${roleId}`);
        }
        this.toastr.success("Actualizado correctamente");
      } catch (e) {
        console.log(e);
      } finally {
        this.getUsers();
      }
    },
    removeItem(user) {
      this.bus.$emit("openDialogRemove", `/user/${user.id}`);
    }
  }
};
</script>
