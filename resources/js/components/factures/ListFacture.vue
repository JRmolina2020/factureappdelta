<template>
    <div v-if="status">
        <div class="row">
            <div class="col-lg-4 col-xs-12 col-sm-12 col-md-12">
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >
                    <strong>Hola :)</strong> Estas son las ventas del dia
                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="input-group">
                    <input
                        class="form-control form-control-sm"
                        type="date"
                        v-model="date"
                        placeholder=".form-control-sm"
                    />
                    <div class="input-group-append">
                        <button
                            class="btn btn-outline-secondary btn-sm"
                            @click="getDate()"
                            type="button"
                        >
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="input-group">
                    <select
                        v-model="search_sale"
                        class="form-control form-control-sm"
                    >
                        <option value="Bancolombia">Bancolombia</option>
                        <option value="Nequi R">Nequi R</option>
                        <option value="Nequi M">Nequi M</option>
                        <option value="Daviplata">Daviplata</option>
                    </select>
                    <div class="input-group-append">
                        <button
                            class="btn btn-success btn-sm"
                            @click="getTypeSale()"
                            type="button"
                        >
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <VTable
                :data="factures"
                :page-size="5"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table table-striped table-borderless mt-3"
            >
                <template #head>
                    <tr style="color: #fff; background: black">
                        <th>Total</th>
                        <th>E</th>
                        <th>O</th>
                        <th>Banco</th>
                        <th>Vendedor</th>
                        <th>Estado</th>
                        <th>POS</th>
                        <th>D</th>
                        <th>E</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.tot | currency }}</td>
                        <td>{{ row.efecty | currency }}</td>
                        <td>{{ row.other | currency }}</td>
                        <td v-if="row.type_sale == 1">
                            <i class="fi fi-dollar"></i>
                        </td>
                        <td v-else>{{ row.type_sale }}</td>
                        <th>{{ row.name }}</th>
                        <td v-if="row.status">
                            <span class="badge badge-success">Pagado</span>
                        </td>
                        <td v-else>
                            <span
                                @click="statusModified(row.id)"
                                class="badge badge-danger"
                                >Deuda</span
                            >
                        </td>
                        <td>
                            <Modal-Ticket v-bind:cod="row.id"></Modal-Ticket>
                        </td>
                        <td>
                            <Modal-Details2
                                v-bind:cod="row.id"
                            ></Modal-Details2>
                        </td>
                        <td v-can="'eliminar factura'">
                            <button
                                type="button"
                                @click="destroy(row.id)"
                                class="btn bg-danger btn-sm"
                            >
                                <i class="fi fi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </VTable>
            <div class="text-xs-center">
                <VTPagination
                    :currentPage.sync="currentPage"
                    :total-pages="totalPages"
                    :boundary-links="true"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Monto</td>
                            <td>Efectivo</td>
                            <td>Otros</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr v-for="(item, index) in typeSale" :key="index">
                            <th>{{ item.tot | currency }}</th>
                            <th>{{ item.efecty | currency }}</th>
                            <th>{{ item.other | currency }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-lg-2">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Gasto total</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr v-for="(item, index) in billstot" :key="index">
                            <th>{{ item.price | currency }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-lg-2">
                <table class="table table-bordered table-dark">
                    <tbody>
                        <tr>
                            <td>Banco total</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr v-for="(item, index) in typeSale_one" :key="index">
                            <th>{{ item.other | currency }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div v-if="factures.length == 0">
            <div class="alert alert-danger" role="alert">
                No existe información de venta para este dia
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import ModalDetails2 from "../utilities/modaldetails2";
import ModalTicket from "../utilities/modalticket";
import VueHtmlToPaper from "vue-html-to-paper";

const options = {
    name: "_blank",
    specs: ["fullscreen=yes", "titlebar=yes", "scrollbars=yes"],
    styles: ["https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"],

    timeout: 1000,
    autoClose: true,
    windowTitle: "Vue Html To Paper - Vue mixin for html elements printing.",
};

Vue.use(VueHtmlToPaper, options);
import MgetList from "../../mixins/dateFacture";

export default {
    data() {
        return {
            date: "",

            totalPages: 1,
            currentPage: 1,
            search_sale: "",
        };
    },
    mixins: [MgetList],
    components: {
        ModalDetails2,
        ModalTicket,
    },
    computed: {
        ...mapState([
            "factures",
            "typeSale",
            "typeSale_one",
            "status",
            "urlfactures",
            "status",
            "billstot",
        ]),
    },

    created() {
        this.getList();
    },
    methods: {
        getDate() {
            let date = this.date;
            this.$store.dispatch("Factureactions", date);
            this.$store.dispatch("TypeSaleactions", date);
            this.$store.dispatch("Billtotactions", date);
        },
        getTypeSale() {
            let obj = {
                prop1: this.date,
                prop2: this.search_sale,
            };
            this.$store.dispatch("TypeSale_one_actions", obj);
        },
        async statusModified(id) {
            let url = this.urlfactures + "/" + id;
            let response = await axios.put(url);
            try {
                this.getList();
                Swal.fire({
                    title: `${response.data.message}`,
                    icon: "success",
                });
            } catch (error) {
                console.log(error);
            }
        },

        async destroy(id) {
            let url = this.urlfactures + "/" + id;
            let response = await axios.delete(url);
            try {
                this.getList();
                Swal.fire({
                    title: `${response.data.message}`,
                    icon: "success",
                });
            } catch (error) {
                console.log(error);
            }
        },
    },
};
</script>
