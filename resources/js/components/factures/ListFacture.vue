<template>
    <div v-if="status">
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
        </div>

        <div class="table-responsive">
            <VTable
                :data="factures"
                :filters="filters"
                :page-size="5"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table mt-3"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="date_facture">Fecha</VTh>
                        <VTh sortKey="nit">Nit</VTh>
                        <VTh sortKey="fullname">Nombre</VTh>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Op</th>
                        <th>D</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.date_facture }}</td>
                        <td>{{ row.nit }}</td>
                        <td>{{ row.fullname }}</td>
                        <td>${{ row.tot | currency }}</td>
                        <th v-if="row.state == 1">
                            <button type="button" class="btn bg-success btn-sm">
                                <i class="fi fi-toggle-on"></i>
                            </button>
                        </th>
                        <th v-else>
                            <button type="button" class="btn bg-danger btn-sm">
                                <i class="fi fi-toggle-off"></i>
                            </button>
                        </th>
                        <td>
                            <button
                                type="button"
                                @click="destroy(row.id)"
                                class="btn bg-danger btn-sm"
                            >
                                <i class="fi fi-trash"></i>
                            </button>
                        </td>
                        <td>
                            <Modal-Details v-bind:cod="row.id"></Modal-Details>
                        </td>
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
        <div v-if="factures.length == 0">
            <div class="alert alert-danger" role="alert">
                No existe información de venta para este dia
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
import ModalDetails from "../utilities/modaldetails";
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
            filters: {
                name: { value: "", keys: ["name"] },
            },
        };
    },
    mixins: [MgetList],
    components: {
        ModalDetails,
    },
    computed: {
        ...mapState(["factures", "status", "urlfactures"]),
    },

    created() {
        this.getList();
    },
    methods: {
        getDate() {
            let date = this.date;
            this.$store.dispatch("Factureactions", date);
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
