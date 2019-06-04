<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="roles"
      :search="search"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
      disable-initial-sort
    >
      <template slot="items" slot-scope="props">
        <tr v-if="props.item.name != 'admin'">
          <td class="text-xs-center">
            {{ props.item.display_name }}
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
  name: "rolePermission",
  data() {
    return {
      permissions: [],
      roles: [],
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
    this.getRoles();
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
    async getRoles() {
      try {
        let res = await axios.get("role");
        this.roles = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    async updatePermission(roleId, permissionId, status) {
      try {
        if (status) {
          await axios.delete(`/role/${roleId}/permission/${permissionId}`);
        } else if (!status) {
          await axios.patch(`/role/${roleId}/permission/${permissionId}`);
        }
        this.toastr.success("Actualizado correctamente");
      } catch (e) {
        console.log(e);
      } finally {
        this.getRoles();
      }
    }
  }
};
</script>
