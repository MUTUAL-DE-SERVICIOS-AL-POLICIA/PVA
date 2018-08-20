<template>
  <v-dialog persistent v-model="dialog" max-width="900px" @keydown.esc="close">
    <v-card>
      <v-card-title>
        <span class="headline">¿Está seguro que desea eliminar el registro?</span>
      </v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="success" @click="close"><v-icon>close</v-icon> Cancelar</v-btn>
        <v-btn color="error" @click="remove"><v-icon>check</v-icon> Eliminar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "RemoveItem",
  props: ["url", "bus"],
  data() {
    return {
      path: '',
      dialog: false,
    };
  },
  methods: {
    resetVariables() {
      this.path = '';
    },
    close() {
      this.resetVariables();
      this.dialog = false;
      this.bus.$emit("closeDialog");
    },
    async remove() {
      try {
        let res = await axios.delete(this.path);
        this.toastr.success('Eliminado correctamente')
        this.close()
      } catch (e) {
        console.log(e);
        for (let key in e.data.errors) {
          e.data.errors[key].forEach(error => {
            this.toastr.error(error);
          });
        }
      }
    }
  },
  mounted() {
    this.bus.$on("openDialogRemove", url => {
      this.path = url;
      this.dialog = true;
    });
  }
};
</script>