<template>
  <v-dialog
    :key="dialogKey"
    v-model="visibleProxy"
    persistent
    width="1400px"
    @keydown.esc="close"
    scrollable
  >
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">
          SOLICITUD DE PASAJES — AGREGAR PERMISOS
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon dark @click="close">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>

      <v-card-text>
        <v-container fluid>
          <v-layout row wrap>
            <v-flex xs12 md4>
              <v-card class="pa-3 mb-4" color="blue lighten-5" elevation="1">
                <v-layout align-center>
                  <v-flex xs12>
                    <h3 class="subtitle-1 font-weight-bold mb-2">
                      Guía para llenar permisos de pasajes
                    </h3>
                    <ol class="mb-0" style="line-height: 1.6">
                      <li>
                        Ingresa el <b>código de permiso</b>, ubicado en el lado superior
                        derecho de la boleta.
                      </li>
                      <li>Completa los campos del tramo <b>Desde</b> y <b>Hasta</b>.</li>
                      <li>
                        Coloca el <b>importe</b> no sea mayor a <b>5 Bs.</b> por tramo
                      </li>
                    </ol>
                  </v-flex>
                </v-layout>
              </v-card>
            </v-flex>
            <v-flex xs12 md8>
              <v-data-table
                :headers="headers"
                :items="orderedTransfers"
                item-key="index"
                class="elevation-1"
                hide-actions
                :rows-per-page-items="[{ text: '', value: -1 }]"
                :pagination.sync="pagination"
              >
                <template v-slot:items="props">
                  <td class="text-xs-center">{{ props.item.index + 1 }}</td>

                  <td>
                    <v-text-field
                      v-validate="'required|min:3|max:20'"
                      :error-messages="errors.collect(`code${props.index}`)"
                      :data-vv-name="`code${props.index}`"
                      data-vv-as="código de permiso"
                      v-model="props.item.permCode"
                      clearable
                      placeholder="p.ej. 12345"
                    />
                  </td>

                  <td>
                    <v-text-field
                      v-validate="'required|min:3|max:255'"
                      :error-messages="errors.collect(`from${props.index}`)"
                      :data-vv-name="`from${props.index}`"
                      data-vv-as="desde"
                      v-model="props.item.from"
                      clearable
                      placeholder="Desde"
                    />
                  </td>

                  <td>
                    <v-text-field
                      v-validate="'required|min:3|max:255'"
                      :error-messages="errors.collect(`to${props.index}`)"
                      :data-vv-name="`to${props.index}`"
                      data-vv-as="hasta"
                      v-model="props.item.to"
                      clearable
                      placeholder="Hasta"
                    />
                  </td>

                  <td>
                    <v-text-field
                      v-validate="'required|decimal:2|min_value:0.5|max_value:5'"
                      :error-messages="errors.collect(`cost${props.index}`)"
                      :data-vv-name="`cost${props.index}`"
                      data-vv-as="importe (Bs)"
                      v-model="props.item.cost"
                      type="number"
                      step="2.40"
                      min="0.5"
                      max="5"
                      clearable
                      placeholder="2.4 – 2.4"
                    />
                  </td>

                  <td class="text-xs-center">
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          class="pa-0 ma-0"
                          v-on="on"
                          flat
                          icon
                          :disabled="transfers.length <= 1 || props.index === 0"
                          color="info"
                          @click="move('prev', props.item.index)"
                        >
                          <v-icon>arrow_drop_up</v-icon>
                        </v-btn>
                      </template>
                      <span>Mover arriba</span>
                    </v-tooltip>
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          class="pa-0 ma-0"
                          v-on="on"
                          flat
                          icon
                          :disabled="!canAddReturn(props.item)"
                          color="success"
                          @click="addReturn(props.item.index)"
                        >
                          <v-icon>sync</v-icon>
                        </v-btn>
                      </template>
                      <span>Duplicar con retorno</span>
                    </v-tooltip>
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          class="pa-0 ma-0"
                          v-on="on"
                          flat
                          icon
                          :disabled="
                            transfers.length <= 1 || props.index === transfers.length - 1
                          "
                          color="info"
                          @click="move('next', props.item.index)"
                        >
                          <v-icon>arrow_drop_down</v-icon>
                        </v-btn>
                      </template>
                      <span>Mover abajo</span>
                    </v-tooltip>

                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          class="pa-0 ma-0"
                          v-on="on"
                          flat
                          icon
                          color="error"
                          @click="dropTransfer(props.item.index)"
                        >
                          <v-icon>cancel</v-icon>
                        </v-btn>
                      </template>
                      <span>Eliminar</span>
                    </v-tooltip>
                  </td>
                </template>

                <template v-slot:no-data>
                  <div class="pa-4 text-xs-center">
                    <div class="mb-2">No hay recorridos. Agrega el primero.</div>
                    <v-btn color="info" @click="addTransfer">
                      <v-icon class="mr-2">add</v-icon> Agregar recorrido
                    </v-btn>
                  </div>
                </template>

                <template v-slot:footer v-if="transfers.length">
                  <td class="text-xs-center font-weight-bold" colspan="4">Total</td>
                  <td class="font-weight-bold subheading">{{ total.toFixed(1) }} Bs.</td>
                  <td></td>
                </template>
              </v-data-table>

              <v-btn
                :disabled="maxTransfers === transfers.length"
                color="info"
                class="mt-3"
                @click="addTransfer"
              >
                <v-icon class="mr-2">add</v-icon> Agregar Permiso
              </v-btn>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click="close">
          <v-icon class="mr-2">close</v-icon> Cancelar
        </v-btn>
        <v-btn
          color="success"
          :disabled="!canPrint || loading"
          :loading="loading"
          @click="print"
        >
          <v-icon class="mr-2">print</v-icon> Guardar / Imprimir
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "FormRequestTransportFull",
  props: {
    value: { type: Boolean, default: false },
  },
  data() {
    return {
      dialogInternal: false,
      dialogKey: 0,
      transfers: [],
      maxTransfers: 12,
      headers: [
        { text: "Nº", value: "index", align: "center", sortable: false, width: "1%" },
        {
          text: "Código permiso",
          value: "permCode",
          align: "center",
          sortable: false,
          width: "18%",
        },
        { text: "Desde", value: "from", align: "center", sortable: false, width: "27%" },
        { text: "Hasta", value: "to", align: "center", sortable: false, width: "27%" },
        {
          text: "Importe (Bs)",
          value: "cost",
          align: "center",
          sortable: false,
          width: "12%",
        },
        { text: "Acciones", value: "", align: "center", sortable: false, width: "15%" },
      ],
      pagination: { sortBy: "index", descending: false },
      loading: false,
    };
  },
  computed: {
    visibleProxy: {
      get() {
        return this.value;
      },
      set(v) {
        this.$emit("input", v);
      },
    },
    orderedTransfers() {
      return this.transfers.slice().sort((a, b) => a.index - b.index);
    },
    total() {
      return this.orderedTransfers.reduce((sum, t) => {
        const n = Number(t.cost);
        return sum + (isNaN(n) ? 0 : n);
      }, 0);
    },
    canPrint() {
      return this.transfers.some(
        (t) =>
          t.permCode &&
          t.permCode.toString().trim().length >= 3 &&
          t.from &&
          t.to &&
          Number(t.cost) > 0 &&
          Number(t.cost) <= 5
      );
    },
  },
  watch: {
    value: {
      immediate: true,
      handler(v) {
        this.dialogInternal = v;
        if (v) {
          this.openInit();
        } else {
          this.resetState();
        }
      },
    },
  },
  methods: {
    openInit() {
      this.transfers = [{ permCode: null, from: null, to: null, cost: null, index: 0 }];
      if (this.$validator && typeof this.$validator.reset === "function") {
        this.$nextTick(() => this.$validator.reset());
      }
      if (this.errors && typeof this.errors.clear === "function") {
        this.errors.clear();
      }
    },
    close() {
      this.resetState();
      this.visibleProxy = false;
      this.$emit("close");
    },
    addTransfer() {
      if (this.transfers.length >= this.maxTransfers) return;
      this.transfers.push({
        permCode: null,
        from: null,
        to: null,
        cost: null,
        index: this.transfers.length,
      });
    },
    canAddReturn(item) {
      return (
        !!(item && item.permCode && item.from && item.to && Number(item.cost) > 0) &&
        Number(item.cost) <= 5 &&
        this.transfers.length < this.maxTransfers
      );
    },
    addReturn(index) {
      const t = this.transfers.find((o) => o.index === index);
      if (!t) return;
      this.transfers.push({
        permCode: t.permCode,
        from: t.to,
        to: t.from,
        cost: t.cost,
        index: this.transfers.length,
      });
    },
    move(order, index) {
      const arr = this.orderedTransfers;
      const fromPos = arr.findIndex((o) => o.index === index);
      if (fromPos < 0) return;
      const toPos = order === "prev" ? fromPos - 1 : fromPos + 1;
      if (toPos < 0 || toPos >= arr.length) return;
      const a = arr[fromPos],
        b = arr[toPos];
      const ai = a.index,
        bi = b.index;
      a.index = bi;
      b.index = ai;
      this.transfers = arr.slice();
    },
    dropTransfer(index) {
      let arr = this.orderedTransfers.filter((o) => o.index !== index);
      arr = arr.map((t, i) => ({ ...t, index: i }));
      this.transfers = arr;
    },
    async print() {
      try {
        this.loading = true;

        const ok = await this.$validator.validateAll();
        if (!ok) {
          this.toastr.error("Corrige los campos marcados antes de guardar.");
          return;
        }

        const cleanTransfers = this.orderedTransfers
          .filter(
            (t) =>
              t.permCode && t.from && t.to && Number(t.cost) > 0 && Number(t.cost) <= 5
          )
          .map((t) => ({
            permCode: t.permCode.trim(),
            from: t.from.trim(),
            to: t.to.trim(),
            cost: Number(t.cost),
            index: t.index,
          }));

        if (!cleanTransfers.length) {
          this.toastr.error("Agrega al menos un recorrido válido (≤ 5 Bs).");
          return;
        }

        const total = cleanTransfers.reduce((s, t) => s + t.cost, 0);

        const payload = {
          employeeId: this.userId,
          transfers: cleanTransfers,
          total: total.toFixed(1),
        };

        try {
          const { data } = await axios2.get("disableFunds");
          if (data) {
            this.toastr.warning("No se puede Crear una solicitud por Cierre de Gestión");
            return;
          }
        } catch (e) {
          console.error("Error al verificar:", e);
          return;
        }

        const res = await axios2.post("/personal_transport_tickets", payload);
        if (res.status == 201) {
          console.log(res.data);
          this.toastr.error(res.data.message);
        } else {
          this.close();
          this.toastr.success(res.data.message);
        }
      } catch (e) {
        console.error(e);
        this.toastr.error("No se pudo guardar/imprimir el ticket.");
      } finally {
        this.loading = false;
      }
    },
    resetState() {
      this.transfers = [];
      this.pagination = { sortBy: "index", descending: false };
      this.loading = false;

      if (this.$validator && typeof this.$validator.reset === "function") {
        this.$validator.reset();
      }
      if (this.errors && typeof this.errors.clear === "function") {
        this.errors.clear();
      }
      this.dialogKey += 1;
    },
  },
  mounted() {
    const id = localStorage.getItem("id");
    this.userId = id && !isNaN(Number(id)) ? Number(id) : id;
  },
};
</script>
