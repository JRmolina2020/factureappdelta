<template>
    <div>
        <div class="form-group">
            <input
                type="text"
                class="form-control"
                v-model="filters.name.value"
                placeholder="Buscar clientes"
            />
        </div>
        <div class="table-responsive">
            <VTable
                :data="factures"
                :filters="filters"
                :page-size="5"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table"
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
                            <span class="badge badge-primary">Pagó</span>
                        </th>
                        <th v-else>
                            <span class="badge badge-danger">No pagó</span>
                        </th>
                        <td>
                            <button
                                type="button"
                                @click="$emit('show', row)"
                                class="btn bg-warning btn-sm"
                            >
                                <i class="fi fi-eye"></i>
                            </button>
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
export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
            filters: {
                name: {
                    value: "",
                    keys: ["id", "date_facture", "fullname", "nit"],
                },
            },
        };
    },
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
        getList() {
            this.$store.dispatch("Factureactions");
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
