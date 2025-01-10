<template>
    <v-container fluid>
        <v-toolbar>
            <v-toolbar-title>
                <v-select :items="['Todas las Solicitudes', 'En Revisión', 'Aceptadas', 'Cancelado']"
                    v-model="employeeType" class="title font-weight-medium" label="Filtrar por Estado"></v-select>
            </v-toolbar-title>
            <v-btn color="success" @click="openForm">Realizar Nueva Solicitud</v-btn>
        </v-toolbar>
        <v-data-table :headers="mainHeaders" :items="items" item-value="id" class="elevation-1" hide-default-footer>
            <template v-slot:items="props">
                <tr>
                    <td align="center">{{ props.item.number_note }}</td>
                    <td align="center">{{ props.item.request_date }}</td>
                    <td>
                        <div :class="['status-button', getStateClass(props.item.state)]">
                            {{ props.item.state }}
                        </div>
                    </td>
                    <td align="center">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on" @click.stop="toggleMaterials(props.item)"
                                    class="expand-btn">
                                    <v-icon class="expand-icon" color="success">menu</v-icon>
                                </v-btn>
                            </template>
                            <span>Tus Productos</span>
                        </v-tooltip>

                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on" @click.native="print_form(props.item)">
                                    <v-icon color="info">print</v-icon>
                                </v-btn>
                            </template>
                            <span>Imprimir Formulario 1</span>
                        </v-tooltip>

                        <v-tooltip bottom v-if="props.item.state !== 'Finalizado'">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on" @click.native="navigateNext(props.item)">
                                    <v-icon color="info">check</v-icon>
                                </v-btn>
                            </template>
                            <span>Continuar con el Fomurlario 2</span>
                        </v-tooltip>

                        <v-tooltip bottom v-if="props.item.state !== 'En Revision'">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on" @click.native="print_form_discharge(props.item)">
                                    <v-icon color="success">print</v-icon>
                                </v-btn>
                            </template>
                            <span>Imprimir Formulario 2</span>
                        </v-tooltip>
                    </td>
                </tr>
                <tr v-if="props.item.showProducts">
                    <td colspan="4">
                        <v-data-table :headers="detailsHeaders2" :items="props.item.products" class="elevarion-1"
                            hide-default-footer>
                            <template v-slot:items="productProps">
                    <td align="left">{{ productProps.item.description }}</td>
                    <td align="center">{{ productProps.item.pivot.amount_request }}</td>
                    <td align="center">{{ productProps.item.pivot.costDetails }}</td>
                    <td align="center">{{ (productProps.item.pivot.amount_request *
                        productProps.item.pivot.costDetails) }}</td>
            </template>
        </v-data-table>
        </td>
        </tr>
</template>
</v-data-table>

<v-dialog v-model="dialogDetails" max-width="1200px">
    <v-card>
        <v-card-title>
            <span class="headline">Descargo - Caja Chica (Bolivianos)</span>
        </v-card-title>
        <v-form v-model="valid">
            <v-card-text>
                <p><strong>Número de Solicitud:</strong> {{ selectedItem.number_note }}</p>
                <p><strong>Fecha de Solicitud:</strong> {{ selectedItem.request_date }}</p>
                <p><strong>Concepto:</strong> {{ selectedItem.concept }}</p>

                <v-data-table :headers="detailsHeaders" :items="selectedItem.products" hide-default-footer>
                    <template v-slot:items="productProps">
                        <td align="left">{{ productProps.item.description }}</td>
                        <td>
                            <v-text-field v-model="productProps.item.supplier" label="Proveedor" dense hide-details
                                class="table-input">
                            </v-text-field>
                        </td>
                        <td>
                            <v-text-field v-model="productProps.item.numer_invoice" label="N° de Factura" type="number"
                                dense hide-details class="table-input">
                            </v-text-field>
                        </td>
                        <td align="center">{{ productProps.item.cost_object }}</td>
                        <td>
                            <v-select v-model="productProps.item.id_group" :items="budgetGroups" item-text="details"
                                item-value="id" label="Partida Presupuestaria" dense hide-details class="table-input">
                            </v-select>
                        </td>
                        <td>
                            <v-text-field v-model="productProps.item.total" label="Total" type="number" dense
                                hide-details class="table-input">
                            </v-text-field>
                        </td>
                    </template>
                </v-data-table>

            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="red" @click="dialogDetails = false">Cerrar</v-btn>
                <v-btn color="success" @click="submitDetails">Guardar</v-btn>
            </v-card-actions>
        </v-form>
    </v-card>
</v-dialog>


<form-petty-cash :dialog="dialog" @close="dialog = false" @save="updateRequests"></form-petty-cash>
</v-container>
</template>

<script>
import FormPettyCash from "./FormPettyCash.vue";

export default {
    components: {
        FormPettyCash,
    },
    data() {
        return {
            dialog: false,
            dialogDetails: false,
            selectedItem: {},
            employeeType: "Todas las Solicitudes",
            detailsVisible: null,
            valid: false,
            items: [],
            budgetGroups: [],
            mainHeaders: [
                { text: "Nro Solicitud", value: "id", align: "center" },
                { text: "Fecha de Solicitud", value: "date", align: "center" },
                { text: "Estado", value: "status", align: "center" },
                { text: "Acciones", value: "actions", sortable: false, align: "center" },
            ],
            detailsHeaders2: [
                { text: "Descripcion", value: "description", align: "center" },
                { text: "Cantidad", value: "quantity", align: "center" },
                { text: "Precio Estimado (Bs)", value: "estimatedPrice", align: "center" },
                { text: "Total (Bs)", value: "total", align: "center" },
            ],
            detailsHeaders: [
                { text: "Descripcion", value: "description", align: "center" },
                { text: "Proveedor", value: "supplier", align: "center" },
                { text: "N° de Factura", value: "numer_invoice", align: "center" },
                { text: "Objeto de Gasto", value: "cost_object", align: "center" },
                { text: "Partida Presupuestaria", value: "id_group", align: "center" },
                { text: "Total", value: "total", align: "center" },
            ],
        };
    },
    mounted() {
        this.userId = localStorage.getItem('id')
        this.getProductsUser()
        this.getGroup()
    },
    methods: {
        async getProductsUser() {
            try {
                const res = await axios2.get(`notePettyCash/${this.userId}`)
                this.items = res.data.map(item => ({ ...item, showProducts: false }))
                console.log(this.items);
            } catch (error) {
                console.error(error)
            }
        },
        async getGroup() {
            try {
                const res = await axios2.get("list_group");
                this.budgetGroups = res.data;
            } catch (error) {
                console.error("Error al cargar grupos presupuestarios:", error);
            }
        },
        async submitDetails() {
            try {
                const invalidProduct = this.selectedItem.products.find(product =>
                    !product.supplier ||
                    !product.numer_invoice ||
                    !product.id_group ||
                    !product.total
                );

                if (invalidProduct) {
                    this.toastr.error("Todos los campos deben ser llenados.");
                    return;
                }
                const payload = {
                    requestId: this.selectedItem.id,
                    products: this.selectedItem.products.map(product => ({
                        description: product.description,
                        supplier: product.supplier,
                        numer_invoice: product.numer_invoice,
                        cost_object: product.cost_object,
                        id_group: product.id_group,
                        total: product.total,
                    })),
                };
                await axios2.post("/savePettyCashDetails", payload);
                this.dialogDetails = false;
                this.updateRequests();
            } catch (error) {
                console.error("Error al guardar detalles:", error);
            }
        },
        openForm() {
            this.dialog = true;
        },
        updateRequests() {
            this.getProductsUser();
        },
        toggleDetails(item) {
            this.detailsVisible = this.detailsVisible === item.id ? null : item.id;
        },
        editRequest(item) {
            console.log("Editar solicitud:", item);
        },
        printRequest(item) {
            console.log("Imprimir solicitud:", item);
        },
        saveRequest(newRequest) {
            this.requests.push(newRequest);
            this.dialog = false;
        },
        viewRequests() {
            console.log("Contenido de requests:", this.requests);
        },
        getStateClass(state) {
            switch (state) {
                case 'En Revision':
                    return 'state-en-revision'
                case 'Cancelado':
                    return 'state-cancelado'
                case 'Finalizado':
                    return 'state-aceptado'
                default:
                    return ''
            }
        },
        toggleMaterials(item) {
            item.showProducts = !item.showProducts
        },
        async print_form(item) {
            let res = await axios2({
                method: "GET",
                url: `/printPettCash/${item.id}`,
                responseType: "arraybuffer",
            });
            let blob = new Blob([res.data], {
                type: "application/pdf"
            });
            printJS(window.URL.createObjectURL(blob));
        },
        async print_form_discharge(item) {
            let res = await axios2({
                method: "GET",
                url: `/printPettCashDischarge/${item.id}`,
                responseType: "arraybuffer",
            });
            let blob = new Blob([res.data], {
                type: "application/pdf"
            });
            printJS(window.URL.createObjectURL(blob));
        },
        async navigateNext(item) {
            this.selectedItem = item;
            this.dialogDetails = true;
        },
        async print_form_discharge_form() {
            let res = await axios2({
                method: "GET",
                url: `/AccountabilitySheet2/`,
                responseType: "arraybuffer",
            });
            let blob = new Blob([res.data], {
                type: "application/pdf"
            });
            printJS(window.URL.createObjectURL(blob));
        },
    },
};
</script>
<style scoped>
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
</style>