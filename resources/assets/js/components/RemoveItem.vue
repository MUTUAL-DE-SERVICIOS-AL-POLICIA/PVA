<template>
  <v-dialog persistent v-model="dialog" max-width="28%" @keydown.esc="close">
    <v-card>
      <v-card-text>
        <div class="headline font-weight-regular">¿Está seguro que desea eliminar el registro?</div>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="success" small @click="close"><v-icon small>check</v-icon> Cancelar</v-btn>
        <v-btn color="error" small @click="remove"><v-icon small>close</v-icon> Eliminar</v-btn>
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