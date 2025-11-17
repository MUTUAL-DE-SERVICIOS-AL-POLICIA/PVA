<template>
  <v-dialog v-model="visibleProxy" persistent max-width="1200px">
    <v-card>
      <v-toolbar dark color="secondary">
        <v-toolbar-title class="white--text">
          {{ title }}
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon dark @click="close">
          <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>

      <v-form v-model="valid">
        <v-text-field
          v-model="concept"
          label="Concepto de Compra"
          class="form-field"
          required
          dense
          hide-details
          :prefix="type === 'rep' ? '(REEMBOLSO)' : ''"
        />

        <v-layout row wrap>
          <v-flex xs9>
            <v-autocomplete
              v-model="selectedProductId"
              :items="products"
              item-value="id"
              item-text="description"
              label="Seleccionar Producto"
              :loading="loadingProducts"
              loading-text="Cargando..."
              @change="onProductSelect"
              class="form-field"
              dense
            />
          </v-flex>
          <v-flex xs3>
            <v-btn color="primary" class="ma-2" @click="openCreateProductDialog">
              Crear nuevo producto
            </v-btn>
          </v-flex>
        </v-layout>

        <v-data-table
          :headers="productHeaders"
          :items="selectedProducts"
          class="elevation-1"
          hide-default-footer
          dense
        >
          <template v-slot:items="props">
            <tr>
              <td>{{ props.index + 1 }}</td>
              <td>{{ props.item.description }}</td>

              <td>
                <v-text-field
                  v-model.number="props.item.quantity"
                  type="number"
                  min="1"
                  dense
                  hide-details
                  class="table-input"
                  :rules="[rules.qty]"
                  @input="coerceNumbers(props.item)"
                />
              </td>

              <td>
                <v-text-field
                  v-model.number="props.item.price"
                  type="number"
                  min="0"
                  dense
                  hide-details
                  class="table-input"
                  :rules="[rules.price]"
                  @input="coerceNumbers(props.item)"
                />
              </td>

              <td v-if="type === 'rep'">
                <v-text-field
                  v-model="props.item.provider"
                  dense
                  hide-details
                  :rules="[rules.provider]"
                  placeholder="Razón social / Proveedor"
                />
              </td>
              <td v-if="type === 'rep'">
                <v-text-field
                  v-model="props.item.invoice"
                  dense
                  hide-details
                  :rules="[rules.invoice]"
                  placeholder="Ej: 001-12345678"
                />
              </td>

              <td>{{ lineTotal(props.item) }}</td>
              <td>
                <v-btn icon @click="removeProduct(props.item.id)">
                  <v-icon color="error">delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </template>

          <template v-slot:footer>
            <tr>
              <td colspan="4" class="text-right font-weight-bold">Totales</td>
              <td class="text-center">{{ totalPrice }}</td>
              <td></td>
            </tr>
          </template>
        </v-data-table>
        <v-card-actions>
          <v-spacer />
          <v-btn color="red" @click="close">Cancelar</v-btn>
          <v-btn color="green" @click="save">Guardar</v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
    <v-dialog v-model="createProductDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Nuevo Producto</span>
        </v-card-title>
        <v-card-text>
          <v-form v-model="valid">
            <v-text-field v-model="newProduct.description" label="Descripción" required />
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="red" @click="closeCreateProductDialog">Cancelar</v-btn>
          <v-btn color="green" @click="addNewProduct">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="similarMaterialsDialog" max-width="800px">
      <v-card>
        <v-card-title class="headline">
          Materiales Similares Disponibles en Almacén
        </v-card-title>
        <v-card-text>
          <v-data-table
            :headers="similarMaterialsHeaders"
            :items="similarMaterials"
            class="elevation-1"
            dense
          >
            <template v-slot:items="props">
              <tr>
                <td>{{ props.index + 1 }}</td>
                <td>{{ props.item.product_description }}</td>
                <td>{{ props.item.material_description }}</td>
              </tr>
            </template>
          </v-data-table>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="red" @click="closeSimilarMaterialsDialog">Entendido</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-dialog>
</template>

<script>
export default {
  name: "FormRequest",
  props: {
    value: { type: Boolean, default: false },
    type: {
      type: String,
      default: "petty",
      validator: (v) => ["petty", "rep"].includes(v),
    },
  },
  data() {
    return {
      valid: false,
      userId: null,
      concept: "",
      selectedProductId: null,
      selectedProducts: [],
      products: [],
      loadingProducts: false,

      createProductDialog: false,
      newProduct: { description: "", unit: "" },

      similarMaterialsDialog: false,
      similarMaterials: [],

      productTableHeaders: [
        { text: "Nro", value: "nro" },
        { text: "Descripción", value: "description" },
        { text: "Cantidad", value: "quantity" },
        { text: "Precio Estimado", value: "price" },
        { text: "Total", value: "total" },
        { text: "Acciones", value: "actions" },
      ],
      similarMaterialsHeaders: [
        { text: "Nro", value: "nro", align: "center" },
        {
          text: "Descripción del Producto Solicitado",
          value: "product_description",
          align: "center",
        },
        { text: "Material en Almacén", value: "material_description", align: "center" },
      ],
      rules: {
        qty: (v) => Number(v) > 0 || "Cantidad debe ser mayor a 0",
        price: (v) => Number(v) >= 0 || "El precio no puede ser negativo",
        provider: (v) => !!String(v || "").trim() || "Proveedor requerido",
        invoice: (v) => !!String(v || "").trim() || "Nro. de factura requerido",
      },
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
    title() {
      return this.type === "petty"
        ? "Nueva Solicitud - Caja Chica (Bolivianos)"
        : "Nueva Solicitud - Reembolsos de Gastos (Bolivianos)";
    },
    totalPrice() {
      const total = this.selectedProducts.reduce(
        (sum, i) => sum + Number(i.quantity || 0) * Number(i.price || 0),
        0
      );
      return total.toFixed(2);
    },
    productHeaders() {
      if (this.type === "rep") {
        return [
          { text: "Nro", value: "nro" },
          { text: "Descripción", value: "description" },
          { text: "Cantidad", value: "quantity" },
          { text: "Precio Estimado", value: "price" },
          { text: "Proveedor", value: "provider" },
          { text: "Nro. de factura", value: "invoice" },
          { text: "Total", value: "total" },
          { text: "Acciones", value: "actions" },
        ];
      }
      return this.productTableHeaders;
    },
    totalColspan() {
      return this.type === "rep" ? 6 : 4;
    },
  },
  mounted() {
    this.userId = localStorage.getItem("id");
    this.getProduct();
  },
  methods: {
    buildFinalConcept() {
      const raw = (this.concept || "").trim();

      if (this.type !== "rep") return raw;

      const already = raw.toUpperCase().startsWith("(REEMBOLSO)");
      return already ? raw : `(REEMBOLSO) ${raw}`;
    },
    coerceNumbers(item) {
      item.quantity = Number(item.quantity || 0);
      item.price = Number(item.price || 0);
    },
    lineTotal(item) {
      const t = Number(item.quantity || 0) * Number(item.price || 0);
      return t.toFixed(2);
    },
    async getProduct() {
      this.loadingProducts = true;
      try {
        const res = await axios2.get("/list_product");
        this.products = Array.isArray(res.data) ? res.data : [];
      } catch (e) {
        console.error("Error al obtener los productos:", e);
      } finally {
        this.loadingProducts = false;
      }
    },
    onProductSelect(productId) {
      const p = this.products.find((x) => x.id === productId);
      if (p && !this.selectedProducts.some((x) => x.id === productId)) {
        this.selectedProducts.push({
          ...p,
          quantity: 1,
          price: 0,
          provider: "",
          invoice: "",
        });
      }
    },
    removeProduct(productId) {
      this.selectedProducts = this.selectedProducts.filter((i) => i.id !== productId);
    },
    openCreateProductDialog() {
      this.createProductDialog = true;
    },
    closeCreateProductDialog() {
      this.createProductDialog = false;
      this.newProduct = { description: "", unit: "" };
    },
    async addNewProduct() {
      if (!this.newProduct.description) {
        this.toastr.error("Llene los espacios correctamente");
        return;
      }
      try {
        await axios2.post("createproduct", {
          description: this.newProduct.description,
          object: this.newProduct.unit,
        });
        this.toastr.success("Producto creado correctamente");
        await this.getProduct();
        this.closeCreateProductDialog();
      } catch (e) {
        console.error(e);
        this.toastr.error("No se pudo crear el producto");
      }
    },
    closeSimilarMaterialsDialog() {
      this.similarMaterialsDialog = false;
    },
    resetForm() {
      this.concept = "";
      this.selectedProductId = null;
      this.selectedProducts = [];
      this.newProduct = { description: "", unit: "" };
    },
    close() {
      this.resetForm();
      this.visibleProxy = false;
      this.$emit("close");
    },
    async save() {
      const finalConcept = this.buildFinalConcept();
      if (!finalConcept.trim()) {
        this.toastr.error("Llene todos los espacios correctamente");
        return;
      }
      if (this.selectedProducts.length === 0) {
        this.toastr.error("Agregue al menos un producto");
        return;
      }
      const invalid = this.selectedProducts.some(
        (p) => Number(p.price) <= 0 || Number(p.quantity) <= 0
      );
      if (invalid) {
        this.toastr.error("Cantidad y precio deben ser mayores a 0.");
        return;
      }

      if (this.type === "rep") {
        const missing = this.selectedProducts.some(
          (p) => !String(p.provider || "").trim() || !String(p.invoice || "").trim()
        );
        if (missing) {
          this.toastr.error(
            "En reembolso, Proveedor y Nro. de factura son obligatorios."
          );
          return;
        }
      }

      try {
        const { data } = await axios2.post("verifyPettyCash", {
          product: this.selectedProducts,
        });
        this.similarMaterials = data.similar_products || [];
        if (this.similarMaterials.length > 0) {
          this.similarMaterialsDialog = true;
          this.toastr.warning(
            "Se detectaron materiales similares en almacén. Elimine los ítems coincidentes antes de continuar."
          );
          return;
        }
      } catch (e) {
        console.error("Error al verificar productos:", e);
        return;
      }

      try {
        await axios2.post("createNotePettyCash", {
          id: this.userId,
          product: this.selectedProducts,
          concept: finalConcept,
          type: this.type === "petty" ? 1 : 2,
        });
        this.toastr.success("Solicitud guardada correctamente");
        this.getProductsUser();
        this.resetForm();
        this.close();
      } catch (e) {
        console.error("Error al guardar la solicitud:", e);
      }
    },
    async getProductsUser() {
      try {
        console.log("entro ");
        const res = await axios2.get(`notePettyCash/${this.userId}`);
        this.items = res.data.map((item) => ({ ...item, showProducts: false }));
        this.items.forEach((i) => {
          i._overdueAlerted = false;
          i._soonAlerted = false;
        });
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>

<style scoped>
.form-field {
  margin-bottom: 16px;
  padding: 10px;
}
.table-input {
  max-width: 80px;
  text-align: center;
}
.text-right {
  text-align: right;
}
.text-center {
  text-align: center;
}
.v-data-table tr:last-child td {
  font-weight: bold;
  font-size: 1rem;
  background-color: #f5f5f5;
}
</style>
