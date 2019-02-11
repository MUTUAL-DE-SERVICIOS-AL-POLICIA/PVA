<template>
  <v-dialog
    v-model="manteinanceDialog"
    persistent
    width="500"
  >
    <v-card>
      <v-card-title
        class="headline danger"
        primary-title
      >
        MÓDULO EN MANTENIMIENTO
      </v-card-title>

      <v-card-text>
        Comuníquese con {{ this.positionGroup }} para mas información.
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          flat
          @click="manteinanceDialog = false"
          to="dashboard"
        >
          Cerrar
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "ManteinanceDialog",
  props: {
    positionGroup: {
      type: String,
      required: false,
      default: 'el área correspondiente'
    },
  },
  data() {
    return {
      manteinanceDialog: true,
    }
  }
}
</script>