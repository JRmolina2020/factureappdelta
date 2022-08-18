<template>
    <div>
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn bg-secondary btn-sm"
            data-toggle="modal"
            :data-target="'#model' + cod"
            @click="getlistProducts"
        >
            Detalle
        </button>

        <div
            class="modal fade"
            :id="'model' + cod"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modelTitleId"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="table-responsive">
                            <strong>Información cliente</strong>
                            <VTable :data="facUnique" class="table table-light">
                                <template #head>
                                    <tr>
                                        <th>Nit</th>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                    </tr>
                                </template>
                                <template #body="{ rows }">
                                    <tr v-for="row in rows" :key="row.id">
                                        <td>{{ row.nit }}</td>
                                        <td>{{ row.fullname }}</td>
                                        <td>{{ row.phone }}</td>
                                    </tr>
                                </template>
                            </VTable>
                        </div>
                        <div class="table-responsive">
                            <strong>DETALLE FACTURA</strong>
                            <VTable :data="details" class="table table-striped">
                                <template #head>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </template>
                                <template #body="{ rows }">
                                    <tr v-for="row in rows" :key="row.idd">
                                        <td>{{ row.name }}</td>
                                        <td>{{ row.quantity }}</td>
                                        <td>{{ row.price | currency }}</td>
                                        <td>${{ row.sub | currency }}</td>
                                    </tr>
                                </template>
                            </VTable>
                            <div class="table-responsive">
                                <VTable
                                    :data="facUnique"
                                    class="table table-light"
                                >
                                    <template #head>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                        </tr>
                                    </template>
                                    <template #body="{ rows }">
                                        <tr v-for="row in rows" :key="row.id">
                                            <td>{{ row.date_facture }}</td>
                                            <td>{{ row.disc }}</td>
                                            <td>{{ row.sub }}</td>
                                            <td>{{ row.tot }}</td>
                                            <td>{{ row.state }}</td>
                                        </tr>
                                    </template>
                                </VTable>
                            </div>

                            <!-- <div class="col-lg-3 mt-3">
                                <table class="table table-light">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                               {{}}
                                            </td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
export default {
    name: "modaldetails",
    props: {
        cod: {
            type: Number,
            required: true,
        },
    },

    data() {
        return {
            currentPage: 1,
            totalPages: 0,
        };
    },

    computed: {
        ...mapState(["details", "facUnique"]),
        sumProducts() {
            let tot = 0;
            this.details.map((data) => {
                tot = parseInt(tot) + parseInt(data.quantity);
            });
            return tot;
        },
    },
    methods: {
        getlistProducts() {
            this.$store.dispatch("FactureDetailactions", this.cod);
            this.$store.dispatch("FactureUniquections", this.cod);
        },
    },
};
</script>
