<template>
    <v-container fluid>
        <h1 class="title">Solicitudes</h1>

        <div class="header-container">
            <!-- Selector de Fecha -->
            <v-menu ref="menu" v-model="menu" :close-on-content-click="false" transition="scale-transition" offset-y
                min-width="auto">
                <template v-slot:activator="{ on, attrs }">
                    <v-text-field v-model="selectedDate" label="Seleccionar Fecha" prepend-icon="mdi-calendar" readonly
                        v-bind="attrs" v-on="on"></v-text-field>
                </template>
                <v-date-picker v-model="selectedDate" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="clearDate">Borrar</v-btn>
                    <v-btn text color="primary" @click="$refs.menu.save(selectedDate)">OK</v-btn>
                </v-date-picker>
            </v-menu>

            <!-- Botón Nueva Solicitud -->
            <v-btn color="primary" dark @click="openDialogSupply" class="new-request-btn">
                <v-icon left>add_circle</v-icon>
                Nueva Solicitud
            </v-btn>
        </div>

        <!-- Tabla Principal -->
        <v-data-table :headers="headers" :items="filteredItems" class="elevation-1" hide-default-footer>
            <template v-slot:items="props">
                <tr>
                    <td v-if="props.item.number_note === 0">N/A</td>
                    <td v-else>{{ props.item.number_note }}</td>
                    <td>{{ props.item.request_date }}</td>
                    <td>
                        <div :class="['status-button', getStateClass(props.item.state)]">
                            {{ props.item.state }}
                        </div>
                    </td>
                    <td>
                        <v-btn icon @click.stop="toggleMaterials(props.item)" class="expand-btn">
                            <v-icon class="expand-icon" color="success">menu</v-icon>
                        </v-btn>
                        <v-btn icon @click.native="print_form(props.item)">
                            <v-icon color="info">print</v-icon>
                        </v-btn>
                    </td>
                </tr>
                <tr v-if="props.item.showMaterials">
                    <td colspan="4">
                        <v-data-table :headers="materialHeaders" :items="props.item.materials" class="elevation-1"
                            hide-default-footer>
                            <template v-slot:items="materialProps">
                <tr>
                    <td>{{ materialProps.item.code_material }}</td>
                    <td>{{ materialProps.item.description }}</td>
                    <td>{{ materialProps.item.amount_request }}</td>
                    <td>{{ materialProps.item.delivered_quantity }}</td>
                </tr>
            </template>
        </v-data-table>
        </td>
        </tr>
</template>
</v-data-table>

<!-- Modal Nueva Solicitud -->
<v-dialog v-model="dialog" max-width="800px">
    <v-card>
        <v-card-title>
            <span class="headline">Nueva Solicitud</span>
        </v-card-title>
        <v-card-text>
            <v-form v-model="valid">
                <!-- Selección de Material -->
                <v-autocomplete v-model="selectedMaterial" :items="materials" item-value="id" item-text="description"
                    label="Seleccionar Material" :search-input.sync="search" :loading="loadingMaterials"
                    loading-text="Cargando..." @change="selectMaterial" class="form-field" dense></v-autocomplete>

                <!-- Tabla de Materiales Seleccionados -->
                <v-data-table :headers="materialTableHeaders" :items="selectedMaterials" class="elevation-1"
                    hide-default-footer>
                    <template v-slot:items="props">
                        <tr>
                            <td>{{ props.item.code_material }}</td>
                            <td>{{ props.item.description }}</td>
                            <td>{{ props.item.unit_material }}</td>
                            <td>
                                <v-text-field v-model.number="props.item.quantity" label="Cantidad Solicitada"
                                    type="number" min="1" step="1" class="quantity-field"
                                    :error="hasQuantityError(props.item.quantity)"
                                    :error-messages="quantityErrorMessage(props.item.quantity)"></v-text-field>
                            </td>
                            <td>
                                <v-btn icon @click="removeMaterial(props.item)">
                                    <v-icon color="error">delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </template>
                </v-data-table>

                <!-- Comentarios -->
                <v-textarea v-model="comments" label="Comentarios" rows="3" class="form-field"></v-textarea>

                <!-- Botones -->
                <v-btn color="primary" @click="submitRequest"
                    :disabled="!valid || selectedMaterials.length === 0 || !isValidQuantity" class="submit-btn">
                    Enviar
                </v-btn>
                <v-btn color="grey" @click="cancelDialog" class="cancel-btn">
                    Cancelar
                </v-btn>
            </v-form>
        </v-card-text>
    </v-card>
</v-dialog>
</v-container>
</template>

<script>
import Vue from 'vue'

export default {
    name: 'SupplyReqNew',
    data() {
        return {
            headers: [
                { text: 'Nro Nota', value: 'number_note' },
                { text: 'Fecha de pedido', value: 'request_date' },
                { text: 'Estado', value: 'state' },
                { text: 'Acciones', value: 'actions' },
            ],
            materialHeaders: [
                { text: 'Código Material', value: 'code_material' },
                { text: 'Descripción', value: 'description' },
                { text: 'Cantidad Solicitada', value: 'amount_request' },
                { text: 'Cantidad Entregada', value: 'delivered_quantity' },
            ],
            items: [],
            userId: null,
            dialog: false,
            newRequest: {
                number_note: '',
                request_date: '',
                state: ''
            },
            valid: false,
            rules: {
                required: value => !!value || 'Required.'
            },
            stateOptions: ['En Revision', 'Cancelado', 'Aceptado'],
            materials: [],
            selectedMaterial: null,
            selectedMaterials: [],
            comments: '',
            materialTableHeaders: [
                { text: 'Código Material', value: 'code_material' },
                { text: 'Descripción', value: 'description' },
                { text: 'Unidad', value: 'unit_material' },
                { text: 'Cantidad', value: 'quantity' },
                { text: 'Acciones', value: 'actions' },
            ],
            search: '',
            loadingMaterials: false,
            selectedDate: null,
            menu: false
        }
    },
    computed: {
        filteredItems() {
            if (this.selectedDate) {
                return this.items.filter(item => item.request_date === this.selectedDate)
            }
            return this.items
        },
        isValidQuantity() {
            return this.selectedMaterials.every(item => item.quantity <= 12);
        }
    },
    mounted() {
        this.userId = localStorage.getItem('id')
        this.fetchData()
        this.getMaterial()
    },
    methods: {
        clearDate() {
            this.selectedDate = null; // Restablece la fecha seleccionada
        },
        validateIntegerInput(item) {
            item.quantity = item.quantity.replace(/[^0-9]/g, '');
            if (parseInt(item.quantity) > 12) {
                item.quantity = '12';
            }
        },
        hasQuantityError(quantity) {
            return !/^\d+$/.test(quantity) || parseInt(quantity) > 12 || quantity === '';
        },
        quantityErrorMessage(quantity) {
            if (!/^\d+$/.test(quantity)) {
                return 'La cantidad debe ser un número entero';
            } else if (parseInt(quantity) > 12) {
                return 'No puedes pedir más de 12 unidades';
            } else if (quantity === '') {
                return 'Este campo es obligatorio';
            }
            return '';
        },
        async fetchData() {
            try {
                const res = await axios2.get(`noteRequest/${this.userId}`)
                this.items = res.data.map(item => ({ ...item, showMaterials: false }))
            } catch (error) {
                console.error(error)
            }
        },
        async getMaterial() {
            this.loadingMaterials = true
            try {
                const res = await axios2.get('/pva_list_material')
                this.materials = res.data
                this.loadingMaterials = false
            } catch (error) {
                console.error(error)
                this.loadingMaterials = false
            }
        },
        selectMaterial() {
            if (this.selectedMaterial) {
                const material = this.materials.find(m => m.id === this.selectedMaterial)
                if (material && !this.selectedMaterials.some(m => m.id === material.id)) {
                    this.selectedMaterials.push({
                        ...material,
                        quantity: 1
                    })
                }
                this.selectedMaterial = null
            }
        },
        removeMaterial(material) {
            this.selectedMaterials = this.selectedMaterials.filter(m => m.id !== material.id)
        },
        toggleMaterials(item) {
            item.showMaterials = !item.showMaterials
        },
        openDialogSupply() {
            this.dialog = true
        },
        async print_form(item) {
            let res = await axios2({
                method: "GET",
                url: `/printRequest/${item.id}`,
                responseType: "arraybuffer",
            });
            let blob = new Blob([res.data], {
                type: "application/pdf"
            });
            printJS(window.URL.createObjectURL(blob));
        },
        async submitRequest() {
            if (this.valid && this.selectedMaterials.length > 0) {
                try {
                    await axios2.post('createNoteRequest', {
                        id: this.userId,
                        material_request: this.selectedMaterials,
                        comments: this.comments
                    });

                    this.dialog = false;
                    this.selectedMaterials = [];
                    this.comments = '';

                    await this.fetchData();
                } catch (error) {
                    console.error(error);
                }
            }
        }
        ,
        cancelDialog() {
            this.dialog = false
            this.selectedMaterials = []
        },
        getStateClass(state) {
            switch (state) {
                case 'En Revision':
                    return 'state-en-revision'
                case 'Cancelado':
                    return 'state-cancelado'
                case 'Aceptado':
                    return 'state-aceptado'
                default:
                    return ''
            }
        }
    }
}
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
    color: #1976D2;
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
</style>