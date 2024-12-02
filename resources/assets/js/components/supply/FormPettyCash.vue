<template>
    <v-dialog v-model="dialog" persistent max-width="1200px">
        <v-card>
            <v-card-title>
                <span class="headline">Nueva Solicitud - Caja Chica (Bolivianos)</span>
            </v-card-title>
            <v-form v-model="valid">
                <v-text-field v-model="concept" label="Concepto de Compra" class="form-field" required dense
                    hide-details>
                </v-text-field>
                <v-layout row wrap>
                    <v-flex xs9>
                        <v-autocomplete v-model="selectedProductId" :items="products" item-value="id"
                            item-text="description" label="Seleccionar Producto" :loading="loadingProducts"
                            loading-text="Cargando..." @change="onProductSelect" class="form-field"
                            dense></v-autocomplete>
                    </v-flex>
                    <v-flex xs3>
                        <v-btn color="primary" class="ma-2" @click="openCreateProductDialog">
                            Crear nuevo producto
                        </v-btn>
                    </v-flex>
                </v-layout>

                <v-data-table :headers="productTableHeaders" :items="selectedProducts" class="elevation-1"
                    hide-default-footer dense>
                    <template v-slot:items="props">
                        <tr>
                            <td>{{ props.index + 1 }}</td>
                            <td>{{ props.item.description }}</td>
                            <td>
                                <v-text-field v-model="props.item.quantity" type="number" min="1" dense hide-details
                                    class="table-input" :rules="[v => v > 0 || 'Cantidad debe ser mayor a 0']"
                                    @input="updateTotal(props.item)">
                                </v-text-field>
                            </td>
                            <td>
                                <v-text-field v-model="props.item.price" type="number" min="0" dense hide-details
                                    class="table-input" :rules="[v => v >= 0 || 'El precio no puede ser negativo']"
                                    @input="updateTotal(props.item)">
                                </v-text-field>
                            </td>
                            <td>{{ (props.item.quantity * props.item.price || 0).toFixed(2) }}</td>
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
                    <v-spacer></v-spacer>
                    <v-btn color="blue" @click="verify">Verificar</v-btn>
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
                        <v-text-field v-model="newProduct.description" label="Descripción" required></v-text-field>
                        <v-text-field v-model="newProduct.unit" label="Objeto de Gasto" required></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
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
                    <v-data-table :headers="similarMaterialsHeaders" :items="similarMaterials" class="elevation-1"
                        dense>
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
                    <v-spacer></v-spacer>
                    <v-btn color="red" @click="closeSimilarMaterialsDialog">Cerrar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-dialog>
</template>
<script>
export default {
    props: {
        dialog: Boolean,
    },
    data() {
        return {
            verificar: 0,
            similarMaterialsDialog: false,
            similarMaterials: [],
            userId: null,
            concept: "",
            createProductDialog: false,
            selectedProductId: null,
            selectedProducts: [],
            products: [],
            loadingProducts: false,
            valid: false,
            search: "",
            newProduct: {
                description: "",
                unit: "",
            },
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
                { text: "Descripción del Producto Solicitado", value: "product_description", align: "center" },
                { text: "Material en Almacén", value: "material_description", align: "center" },
            ],
        };
    },
    mounted() {
        this.getProduct();
        this.userId = localStorage.getItem('id')
    },
    methods: {
        updateTotal(item) {
            if (!item.quantity || !item.price) {
                item.quantity = item.quantity || 0;
                item.price = item.price || 0;
            }
        },
        onProductSelect(productId) {
            const selectedProduct = this.products.find(p => p.id === productId);
            if (selectedProduct && !this.selectedProducts.some(p => p.id === productId)) {
                this.selectedProducts.push({ ...selectedProduct, quantity: 1, price: 0 });
            }
        },
        openCreateProductDialog() {
            this.createProductDialog = true;
        },
        closeCreateProductDialog() {
            this.createProductDialog = false;
            this.newProduct = { description: "", unit: "" };
        },
        async getProduct() {
            this.loadingProducts = true;
            try {
                const res = await axios2.get("/list_product");
                this.products = res.data;
            } catch (error) {
                console.error("Error al obtener los productos:", error);
            } finally {
                this.loadingProducts = false;
            }
        },
        async addNewProduct() {
            if (this.newProduct.description && this.newProduct.unit) {
                try {
                    await axios2.post('createproduct', {
                        description: this.newProduct.description,
                        object: this.newProduct.unit
                    });
                    this.toastr.success("Producto Creado Correctamente")
                    await this.getProduct();
                    this.closeCreateProductDialog();
                } catch (error) {

                }
            } else {
                this.toastr.error("Llene los espacios correctamente")
            }
        },
        removeProduct(productId) {
            this.selectedProducts = this.selectedProducts.filter(
                (item) => item.id !== productId
            );
        },
        async verify() {
            try {
                const response = await axios2.post('verifyPettyCash', {
                    product: this.selectedProducts,
                });
                this.similarMaterials = response.data.similar_products || [];
                this.similarMaterialsDialog = true;
                this.verificar = 1;
            } catch (error) {
                console.error("Error al verificar los productos:", error);
                this.toastr.error("Error al verificar los productos.");
            }
        },
        closeSimilarMaterialsDialog() {
            this.similarMaterialsDialog = false;
        },
        close() {
            this.$emit("close");
        },
        async save() {
            if (!this.concept) {
                this.toastr.error("Llene todos los espacios correctamente");
                return;
            }

            const invalidProducts = this.selectedProducts.some(product => product.price <= 0);

            if (invalidProducts) {
                this.toastr.error("Todos los productos deben tener un precio mayor a 0.");
                return;
            }
            if (this.verificar == 0) {
                this.toastr.error("Todos los productos deben ser verificados.");
                return;
            }

            try {
                await axios2.post('createNotePettyCash', {
                    id: this.userId,
                    product: this.selectedProducts,
                    concept: this.concept,
                });
                this.toastr.success("Solicitud guardada correctamente");
                this.$emit("save");
                this.close();
            } catch (error) {
                this.toastr.error("Ocurrió un error al guardar la solicitud");
                console.error("Error al guardar la solicitud:", error);
            }
        },
    },
    computed: {
        totalPrice() {
            return this.selectedProducts
                .reduce((sum, item) => sum + (item.quantity * item.price || 0), 0)
                .toFixed(2);
        },
    },
};
</script>
<style scoped>
.header-container {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 16px;
}

.expand-btn {
    background-color: #f5f5f5;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s;
    padding: 8px;
}

.expand-btn:hover {
    background-color: #e0e0e0;
}

.expand-icon {
    font-size: 24px;
    color: #1976d2;
}

.status-button {
    display: inline-block;
    border-radius: 4px;
    padding: 4px 8px;
    font-weight: bold;
    color: white;
    text-align: center;
}

.state-en-revision {
    border: 2px solid #fbc02d;
    background-color: #fbc02d;
}

.state-cancelado {
    border: 2px solid #d32f2f;
    background-color: #d32f2f;
}

.state-aceptado {
    border: 2px solid #388e3c;
    background-color: #388e3c;
}

.v-card {
    padding: 20px;
}

.v-card-title {
    font-size: 24px;
    font-weight: bold;
}

.form-field {
    margin-bottom: 16px;
}

.submit-btn {
    margin-right: 8px;
}

.cancel-btn {
    margin-left: 8px;
}

.table-input {
    max-width: 80px;
    text-align: center;
}

.v-data-table tr:last-child td {
    font-weight: bold;
    font-size: 1rem;
    background-color: #f5f5f5;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}
</style>
