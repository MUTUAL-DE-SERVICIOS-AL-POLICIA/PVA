<template>
    <v-dialog v-model="dialog" persistent max-width="1000px">
        <v-card>
            <v-card-title>
                <span class="headline">Nueva Solicitud - Caja Chica</span>
            </v-card-title>
            <v-form v-model="valid">
                <v-autocomplete v-model="selectedProductId" :items="products" item-value="id" item-text="description"
                    label="Seleccionar Material" :search-input.sync="search" :loading="loadingProducts"
                    loading-text="Cargando..." @change="onProductSelect" class="form-field" dense></v-autocomplete>

                <v-data-table :headers="productTableHeaders" :items="selectedProducts" class="elevation-1"
                    hide-default-footer dense>
                    <template v-slot:items="props">
                        <tr>
                            <td>{{ props.index + 1 }}</td>
                            <td>{{ props.item.description }}</td>
                            <td>
                                <v-text-field v-model="props.item.quantity" type="number" min="1" dense hide-details
                                    class="table-input" @input="updateTotal(props.item)"></v-text-field>
                            </td>
                            <td>
                                <v-text-field v-model="props.item.price" type="number" min="0" dense hide-details
                                    class="table-input" @input="updateTotal(props.item)"></v-text-field>
                            </td>
                            <td>{{ props.item.quantity * props.item.price }}</td>
                            <td>
                                <v-btn icon @click="removeProduct(props.item.id)">
                                    <v-icon color="error">delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </template>
                    <!-- Add footer for totals -->
                    <template v-slot:footer>
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold">Totales</td>
                            <td></td>
                            <td class="text-center">{{ totalPrice }}</td>
                            <td></td>
                        </tr>
                    </template>
                </v-data-table>

                <!-- <v-data-table :headers="productTableHeaders" :items="selectedProducts" class="elevation-1"
                    hide-default-footer>
                    <template v-slot:items="props">
                        <tr>
                            <td>{{ props.index + 1 }}</td>
                            <td>{{ props.item.description }}</td>
                            <td>
                                <v-text-field v-model="props.item.quantity" type="number" min="1" dense hide-details
                                    @input="updateTotal(props.item)"></v-text-field>
                            </td>
                            <td>
                                <v-text-field v-model="props.item.price" type="number" min="0" dense hide-details
                                    @input="updateTotal(props.item)"></v-text-field>
                            </td>
                            <td>{{ props.item.quantity * props.item.price }}</td>
                            <td>
                                <v-btn icon @click="removeProduct(props.item.id)">
                                    <v-icon color="error">delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </template>
                </v-data-table> -->

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" @click="close">Cancelar</v-btn>
                    <v-btn color="green" @click="save">Guardar</v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>

export default {
    props: {
        dialog: Boolean,
    },
    data() {
        return {
            concept: "",
            selectedProductId: null,
            selectedProducts: [],
            products: [],
            loadingProducts: false,
            valid: false,
            search: "",
            productTableHeaders: [
                { text: "Nro", value: "nro" },
                { text: "Descripción", value: "description" },
                { text: "Cantidad", value: "quantity" },
                { text: "Precio Estimado", value: "price" },
                { text: "Total", value: "total" },
                { text: "Acciones", value: "actions" },
            ],
        };
    },
    mounted() {
        this.getProduct();
    },
    methods: {
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
        onProductSelect(productId) {
            if (productId) {
                const material = this.products.find((m) => m.id === productId);
                if (material && !this.selectedProducts.some((m) => m.id === material.id)) {
                    this.selectedProducts.push({
                        ...material,
                        quantity: 1,
                        price: 0,
                    });
                }
            }
            this.selectedProductId = null;
        },
        removeProduct(productId) {
            this.selectedProducts = this.selectedProducts.filter(
                (item) => item.id !== productId
            );
        },
        close() {
            this.$emit("close");
        },
        save() {
            this.$emit("save", {
                concept: this.concept,
                products: this.selectedProducts,
            });
            this.close();
        },
    },
    computed: {
        totalPrice() {
            return this.selectedProducts.reduce((sum, item) => sum + (item.quantity * item.price || 0), 0).toFixed(2);
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
