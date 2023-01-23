<template>
    <div>
        <div class="row">
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="alert alert-primary" role="alert">
                    Resultado de ventas, ganancias y total
                </div>
                <div class="input-group">
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="date"
                        placeholder=".form-control-sm"
                    />
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="datetwo"
                        min=""
                        placeholder=".form-control-sm"
                    />

                    <button
                        v-if="this.datetwo != ''"
                        class="btn btn-outline-secondary btn-sm"
                        @click="getDate()"
                        type="button"
                    >
                        Buscar
                    </button>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="alert alert-warning" role="alert">
                    Resultado de ventas por vendedor
                </div>
                <div class="input-group">
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="dateuser"
                        placeholder=".form-control-sm"
                    />
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="datetwo"
                        min=""
                        placeholder=".form-control-sm"
                    />

                    <button
                        v-if="this.datetwouser != ''"
                        class="btn btn-outline-secondary btn-sm"
                        @click="getDateUser()"
                        type="button"
                    >
                        Buscar
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="table-responsive mt-3">
                    <VTable
                        :data="gain"
                        :filters="filters"
                        :page-size="5"
                        :currentPage.sync="currentPage"
                        @totalPagesChanged="totalPages = $event"
                        class="table table-dark"
                    >
                        <template #head>
                            <tr>
                                <VTh sortKey="name">Nombre</VTh>
                                <th>Cant</th>
                                <th>Total</th>
                                <th>Costo</th>
                                <th>Ganancia</th>
                            </tr>
                        </template>
                        <template #body="{ rows }">
                            <tr v-for="row in rows" :key="row.name">
                                <td>{{ row.name }}</td>
                                <td>{{ row.quantity }}</td>
                                <td>{{ row.tot | currency }}</td>
                                <td>{{ row.cost | currency }}</td>
                                <td>{{ row.gain | currency }}</td>
                            </tr>
                        </template>
                    </VTable>
                </div>

                <div class="text-xs-center">
                    <VTPagination
                        :currentPage.sync="currentPage"
                        :total-pages="totalPages"
                        :boundary-links="true"
                    />
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <table class="table table-dark mt-3">
                    <thead>
                        <tr>
                            <th scope="col">Vendedor</th>
                            <th scope="col">total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in usertot" :key="'u' + index">
                            <td>{{ item.name }}</td>
                            <td>{{ item.tot | currency }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div
                    class="alert alert-dark"
                    v-for="(item, index) in gaintot"
                    :key="'b' + index"
                    role="alert"
                >
                    <p>TOT HOY ${{ item.gaintot | currency }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import date_now from "../mixins/date";

export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
            date: "",
            datetwo: "",
            dateuser: "",
            datetwouser: "",
            filters: {
                name: { value: "", keys: ["name"] },
            },
        };
    },
    computed: {
        ...mapState(["gain", "gaintot", "usertot"]),
    },
    created() {
        this.getList();
        this.getListUser();
    },
    methods: {
        getList() {
            let obj = {
                prop1: date_now,
                prop2: date_now,
            };
            this.$store.dispatch("Gainactions", obj);
            this.$store.dispatch("Gaintotactions", obj);
        },
        getListUser() {
            let obj = {
                prop1: date_now,
                prop2: date_now,
            };
            this.$store.dispatch("Usertotactions", obj);
        },
        getDate() {
            let obj = {
                prop1: this.date,
                prop2: this.datetwo,
            };
            this.$store.dispatch("Gainactions", obj);
            this.$store.dispatch("Gaintotactions", obj);
        },
        getDateUser() {
            let obj = {
                prop1: this.dateuser,
                prop2: this.datetwouser,
            };
            this.$store.dispatch("Useractions", obj);
            this.$store.dispatch("Usertotactions", obj);
        },
    },
};
</script>
