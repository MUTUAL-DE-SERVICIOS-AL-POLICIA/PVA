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
        <tr v-if="props.item.roles[0].name != 'admin'">
          <td class="text-xs-center">
            <v-tooltip right v-if="props.item.employee">
              <span slot="activator">
                {{ props.item.employee.first_name }} {{ props.item.employee.second_name }} {{ props.item.employee.last_name }} {{ props.item.employee.mothers_last_name }} <span class="font-weight-bold font-italic">[{{ props.item.roles[0].name }}]</span>
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
            v-for="(permission, index) in permissions"
            v-bind:item="permission"
            v-bind:index="index"
            v-bind:key="index"
            class="text-xs-center"
          >
            <v-layout row wrap>
              <v-flex xs6></v-flex>
              <v-checkbox @click.native="updatePermission(props.item.id, permission.id, verifyPermission(props.item.permissions, permission))" :input-value="verifyPermission(props.item.permissions, permission)" readonly></v-checkbox>
            </v-layout>
          </td>
        </tr>
      </template>
    </v-data-table>
  </div>
</template>

<script>
export default {
  name: "userPermission",
  data() {
    return {
      permissions: [],
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
    this.getPermissions();
    this.getUsers();
  },
  methods: {
    verifyPermission(permissions, permission) {
      if (permissions.includes(permission.id)) {
        return true;
      } else {
        return false;
      }
    },
    async getPermissions() {
      try {
        let res = await axios.get("permission")
        this.permissions = res.data
        this.permissions.forEach(permission => {
          this.headers.push({
            text: permission.display_name,
            value: "",
            sortable: false,
            align: "center"
          });
        })
      } catch (e) {
        console.log(e);
      }
    },
    async getUsers() {
      try {
        let res = await axios.get("user");
        this.users = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    async updatePermission(userId, permissionId, status) {
      try {
        if (status) {
          await axios.delete(`/user/${userId}/permission/${permissionId}`);
        } else if (!status) {
          await axios.patch(`/user/${userId}/permission/${permissionId}`);
        }
        this.toastr.success("Actualizado correctamente");
      } catch (e) {
        console.log(e);
      } finally {
        this.getUsers();
      }
    }
  }
};
</script>
