<template>
    <v-container fluid>
        <v-toolbar>
            <v-toolbar-title>
                <v-select :items="['Todas las Solicitudes', 'En Revisión', 'Aceptadas', 'Cancelado']"
                    v-model="employeeType" class="title font-weight-medium" label="Filtrar por Estado"></v-select>
            </v-toolbar-title>
            <v-btn color="success" @click="openForm">Realizar Nueva Solicitud</v-btn>
        </v-toolbar>

        <v-data-table :headers="mainHeaders" :items="requests" item-value="id" class="elevation-1"
            @click:row="toggleDetails">
            <template v-slot:body="{ items }">
                <tr v-for="item in items" :key="item.id">
                    <td>{{ item.id }}</td>
                    <td>{{ item.date }}</td>
                    <td>{{ item.status }}</td>
                    <td>
                        <v-btn small color="primary" @click.stop="editRequest(item)">Editar</v-btn>
                        <v-btn small color="secondary" @click.stop="printRequest(item)">Imprimir</v-btn>
                    </td>
                </tr>
                <tr v-if="detailsVisible === item.id" class="details-row">
                    <td colspan="4">
                        <v-data-table dense :headers="detailsHeaders" :items="item.details" class="elevation-1">
                            <template v-slot:body="{ items: detailItems }">
                <tr v-for="detail in detailItems" :key="detail.nro">
                    <td>{{ detail.nro }}</td>
                    <td>{{ detail.description }}</td>
                    <td>{{ detail.quantity }}</td>
                    <td>{{ detail.estimatedPrice }}</td>
                    <td>{{ detail.total }}</td>
                    <td>{{ detail.status }}</td>
                </tr>
            </template>
        </v-data-table>
        </td>
        </tr>
</template>
</v-data-table>

<form-petty-cash :dialog="dialog" @close="dialog = false" @save="saveRequest"></form-petty-cash>
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
            employeeType: "Todas las Solicitudes",
            detailsVisible: null,
            mainHeaders: [
                { text: "Nro Solicitud", value: "id" },
                { text: "Fecha de Solicitud", value: "date" },
                { text: "Estado", value: "status" },
                { text: "Acciones", value: "actions", sortable: false },
            ],
            detailsHeaders: [
                { text: "NRO", value: "nro" },
                { text: "Descripcion", value: "description" },
                { text: "Cantidad", value: "quantity" },
                { text: "Precio Estimado", value: "estimatedPrice" },
                { text: "Total", value: "total" },
                { text: "Estado", value: "status" },
            ],
            requests: [
                {
                    id: 1,
                    date: "2024-11-20",
                    status: "En Revisión",
                    details: [
                        { nro: 1, description: "Item A", quantity: 10, estimatedPrice: 50, total: 500, status: "Pendiente" },
                        { nro: 2, description: "Item B", quantity: 5, estimatedPrice: 30, total: 150, status: "Completado" },
                    ],
                },
                {
                    id: 2,
                    date: "2024-11-19",
                    status: "Aceptadas",
                    details: [
                        { nro: 1, description: "Item C", quantity: 2, estimatedPrice: 100, total: 200, status: "Pendiente" },
                    ],
                },
            ],
        };
    },
    methods: {
        openForm() {
            this.dialog = true;
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
    },
};
</script>