<template>
  <v-container>
    <v-toolbar>
      <v-toolbar-title>Historial de Actividad</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-flex xs2>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar"
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
      expand
    >
      <template slot="items" slot-scope="props">
        <tr>
          <td class="text-md-center" @click="props.expanded = !props.expanded">{{ (props.item.user) ? props.item.user.username : '-' }}</td>
          <td class="text-md-center" @click="props.expanded = !props.expanded">{{ props.item.method }}</td>
          <td class="text-md-center" @click="props.expanded = !props.expanded">{{ props.item.path }}</td>
          <td class="text-md-center" @click="props.expanded = !props.expanded">{{ props.item.created_at | moment('LL') }}</td>
          <td class="text-md-center" @click="props.expanded = !props.expanded">{{ props.item.created_at | moment('hh:mm a') }}</td>
          <td class="text-md-center">
            <v-tooltip top>
              <v-btn medium slot="activator" flat icon color="red darken-3" @click="removeItem(props.item.id)">
                <v-icon>delete</v-icon>
              </v-btn>
              <span>Eliminar</span>
            </v-tooltip>
          </td>
        </tr>
      </template>
      <template slot="expand" slot-scope="{ item }">
        <v-card>
          <v-card-text>
            <table>
              <tr>
                <td>
                  <v-list-tile-content class="font-weight-bold">Datos:</v-list-tile-content>
                </td>
                <td>
                  <v-list-tile-content>
                    <pre>{{ JSON.stringify(JSON.parse(item.data.toString()), null, 2) }}</pre>
                  </v-list-tile-content>
                </td>
              </tr>
            </table>
          </v-card-text>
        </v-card>
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
        { align: "center", text: "Acción", value: "method" },
        { align: "center", text: "Ruta", value: "path" },
        { align: "center", text: "Fecha", value: "created_at" },
        { align: "center", text: "Hora", value: "data" },
        { align: "center", text: "Acciones", sortable: false }
      ],
      search: ""
    };
  },
  mounted() {
    this.getActions();
    this.bus.$on("closeDialog", () => {
      this.getActions();
    });
  },
  methods: {
    async getActions() {
      try {
        let res = await axios.get(`/api/v1/user_action`);
        this.actions = res.data;
      } catch (e) {
        console.log(e);
      }
    },
    removeItem(id) {
      this.bus.$emit("openDialogRemove", `/api/v1/user_action/${id}`);
    },
  }
};
</script>
