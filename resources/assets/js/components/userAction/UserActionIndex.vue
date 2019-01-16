<template>
  <v-container fluid>
    <v-toolbar>
      <v-toolbar-title>Historial de Actividad</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-flex xs2>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
          clearable
          single-line
          hide-details
          full-width
        ></v-text-field>
      </v-flex>
      <RemoveItem :bus="bus"/>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="actions"
      :search="search"
      :rows-per-page-items="[10,20,30,{text:'TODO',value:-1}]"
      disable-initial-sort
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-md-center">
            <span v-if="!props.item.user_id">{{ JSON.parse(props.item.data).username }}</span>
            <div v-else>
              <span v-if="!props.item.user.employee_id">{{ props.item.user.username }}</span>
              <v-tooltip right v-else>
                <span slot="activator">{{ props.item.user.employee.first_name }} {{ props.item.user.employee.second_name }} {{ props.item.user.employee.last_name }} {{ props.item.user.employee.mothers_last_name }}</span>
                <span>{{ props.item.user.position }}</span>
              </v-tooltip>
            </div>
          </td>
          <td class="text-md-center">{{ props.item.action }}</td>
          <td class="text-md-center">{{ props.item.created_at | moment('LL') }}</td>
          <td class="text-md-center">{{ props.item.created_at | moment('hh:mm a') }}</td>
          <td class="text-md-center" v-if="$store.getters.permissions.includes('delete-user-action') || $store.getters.role == 'admin'">
            <v-tooltip top>
              <v-btn medium slot="activator" flat icon color="red darken-3" @click="removeItem(props.item.id)">
                <v-icon>delete</v-icon>
              </v-btn>
              <span>Eliminar</span>
            </v-tooltip>
          </td>
        </tr>
      </template>
      <v-alert slot="no-results" :value="true" color="error">
        La búsqueda de "{{ search }}" no encontró resultados.
      </v-alert>
    </v-data-table>
  </v-container>
</template>

<script>
import Vue from "vue";
import RemoveItem from "../RemoveItem";

export default {
  name: "UserActionIndex",
  components: {
    RemoveItem
  },
  data() {
    return {
      bus: new Vue(),
      actions: [],
      headers: [
        { align: "center", text: "Usuario", value: "user.username" },
        { align: "center", text: "Detalle", value: "action" },
        { align: "center", text: "Fecha", value: "created_at" },
        { align: "center", text: "Hora", value: "data" }
      ],
      search: ""
    };
  },
  mounted() {
    this.getActions();
    this.bus.$on("closeDialog", () => {
      this.getActions();
    });
    if (this.$store.getters.permissions.includes('delete-user-action') || this.$store.getters.role == 'admin') {
      this.headers.push({ align: "center", text: "Acciones", sortable: false })
    }
  },
  methods: {
    async getActions() {
      try {
        let res = await axios.get(`/user_action`);
        this.actions = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    removeItem(id) {
      this.bus.$emit("openDialogRemove", `/user_action/${id}`);
    },
  }
};
</script>
