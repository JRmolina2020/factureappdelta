<template>
    <div>
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn bg-success btn-sm"
            data-toggle="modal"
            :data-target="'#model' + cod"
            @click="getlistProducts"
        >
            <i class="fi fi-file-1"></i>
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
                        <div id="facture">
                            <div class="text-center">
                                <strong>{{ company }}</strong>
                                <p>
                                    <strong>NIT: {{ nit }}</strong>
                                </p>

                                <p>
                                    <em>{{ phone }}</em>
                                </p>
                                <p>{{ direction }}</p>
                            </div>

                            <div class="table-responsive">
                                <strong>Datos cliente</strong>
                                <VTable
                                    :data="facUnique"
                                    class="table table-borderless"
                                >
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
                                <strong>Detalle factura</strong>
                                <VTable
                                    :data="details"
                                    class="table table-borderless table-striped"
                                >
                                    <template #head>
                                        <tr>
                                            <th>Ref</th>
                                            <th>Precio</th>
                                            <th>Cant.</th>
                                            <th>Sub</th>
                                            <th>Des</th>
                                            <th>Total</th>
                                        </tr>
                                    </template>
                                    <template #body="{ rows }">
                                        <tr v-for="row in rows" :key="row.idd">
                                            <td>{{ row.name }}</td>
                                            <td>${{ row.price | currency }}</td>
                                            <td>{{ row.quantity }}</td>

                                            <td>${{ row.sub | currency }}</td>
                                            <td>${{ row.disc | currency }}</td>
                                            <td>${{ row.tot | currency }}</td>
                                        </tr>
                                    </template>
                                </VTable>

                                <div class="table-responsive">
                                    <strong>Totalizable de factura</strong>
                                    <VTable
                                        :data="facUnique"
                                        class="table table-borderless"
                                    >
                                        <template #head>
                                            <tr>
                                                <th>Cant</th>
                                                <th>Desc</th>
                                                <th>Sub</th>
                                                <th>Tot</th>
                                                <th>Estado</th>
                                            </tr>
                                        </template>

                                        <template #body="{ rows }">
                                            <tr
                                                v-for="row in rows"
                                                :key="row.id"
                                            >
                                                <td>{{ sumProducts }}</td>
                                                <td>
                                                    ${{ row.disc | currency }}
                                                </td>
                                                <td>
                                                    ${{ row.sub | currency }}
                                                </td>
                                                <td>
                                                    ${{ row.tot | currency }}
                                                </td>
                                                <td v-if="row.state">Pagado</td>
                                                <td v-else>Pendiente</td>
                                            </tr>
                                        </template>
                                    </VTable>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button
                                type="button"
                                @click="print()"
                                class="btn btn-primary"
                            >
                                Factura <i class="fi fi-table-2"></i>
                            </button>
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
            company: "BOLSOSVALLEDUPAR23",
            nit: "1003242515",
            direction: "CENTRO COMERCIAL LOS PAISAS LOCAL #22",
            phone: "3012074828",
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
        print() {
            this.$htmlToPaper("facture");
        },
        getlistProducts() {
            this.$store.dispatch("FactureDetailactions", this.cod);
            this.$store.dispatch("FactureUniquections", this.cod);
        },
    },
};
</script>
