<template>
  <v-dialog persistent v-model="dialog" max-width="30%" @keydown.esc="close">
    <v-card>
      <v-card-text>
        <div class="title font-weight-regular">¿Seguro que desea eliminar el registro?</div>
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
        this.bus.$emit("removed", Number(this.path.split('/').pop()));
        this.bus.$emit("rechargeList")
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