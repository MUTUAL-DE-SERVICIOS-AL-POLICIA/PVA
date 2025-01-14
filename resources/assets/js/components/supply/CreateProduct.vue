<template>
    <v-dialog v-model="dialogVisible" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="text-h5">Crear Producto</span>
            </v-card-title>
            <v-card-text>
                <v-text-field v-model="name" label="Nombre del Producto" outlined></v-text-field>
                <v-text-field v-model="unit" label="Unidad" outlined></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
                <v-btn color="green darken-1" text @click="createProduct">
                    Crear
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: {
        dialogVisible: Boolean, // Cambiado de 'dialog' a 'dialogVisible'
    },
    data() {
        return {
            name: "",
            unit: "",
        };
    },
    methods: {
        close() {
            this.$emit("close");
        },
        createProduct() {
            if (this.name && this.unit) {
                const newProduct = { id: Date.now(), name: this.name, unit: this.unit };
                this.$emit("product-created", newProduct);
                this.name = "";
                this.unit = "";
                this.close();
            }
        },
    },
};
</script>
