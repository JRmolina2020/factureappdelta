<template>
    <div>
        <!-- detailsx -->
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Subtotal</th>
                        <th>Op</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item, index) in formFacture.dataDetails"
                        :value="item.id"
                        :key="index"
                    >
                        <td>{{ item.name }}</td>
                        <td>${{ item.price | currency }}</td>
                        <td>
                            <button
                                type="button"
                                @click="decrementDetail(index)"
                                class="btn bg-danger btn-xs"
                            >
                                <i class="fi fi-angle-down"></i>
                            </button>
                            {{ item.quantity }}
                            <button
                                type="button"
                                @click="incrementDetail(index)"
                                class="btn bg-success btn-xs"
                            >
                                <i class="fi fi-angle-up"></i>
                            </button>
                        </td>
                        <td>
                            {{
                                (formFacture.dataDetails[index].sub =
                                    item.price * item.quantity) | currency
                            }}
                        </td>
                        <td>
                            <button
                                type="button"
                                @click="removeDetail()"
                                class="btn bg-danger btn-xs"
                            >
                                <i class="fi fi-close-a"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- facture main -->
        <form
            method="POST"
            @submit.enter.prevent="addFacture()"
            autocomplete="off"
            onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
        >
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for>Clientes</label>
                        <select
                            class="form-control form-control-sm"
                            :class="{
                                'is-invalid':
                                    submitted && errors.has('cliente'),
                            }"
                            name="cliente"
                            id="cliente"
                            v-model="formFacture.client_id"
                            v-validate="'required'"
                        >
                            <option
                                v-for="(item, index) in clients"
                                :value="item.id"
                                :key="index"
                            >
                                {{ item.fullname }}
                            </option>
                        </select>
                        <div
                            v-if="submitted && errors.has('clientes')"
                            class="invalid-feedback"
                        >
                            {{ errors.first("cliente") }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for>Subtotal</label>
                        <input
                            type="number"
                            :value="onViewSub"
                            class="form-control form-control-sm"
                        />
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label for>Descuento</label>
                        <input
                            type="number"
                            v-model.number="formFacture.disc"
                            class="form-control form-control-sm"
                        />
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for>Total</label>
                        <input
                            type="number"
                            class="form-control form-control-sm"
                            :value="onViewTot"
                        />
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for>Estado</label>
                        <select
                            :v-model="formFacture.state"
                            class="form-control form-control-sm"
                        >
                            <option value="0">No pago</option>
                            <option value="1">Pagado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        data-toggle="modal"
                        data-target="#exampleDetail"
                    >
                        Productos
                    </button>
                </div>
                <div class="col-lg-2">
                    <button
                        v-if="!send"
                        :hidden="errors.any()"
                        type="submit"
                        v-bind:class="{
                            'btn btn btn-primary btn-sm ': !this.formFacture.id,
                            'btn btn-danger-danger btn-sm ':
                                this.formFacture.id,
                        }"
                    >
                        Guardar
                    </button>
                </div>
            </div>
        </form>
        <!-- modal details -->
        <div
            class="modal fade"
            id="exampleDetail"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Detalle de factura
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input
                                type="text"
                                class="form-control"
                                v-model="filters.name.value"
                                placeholder="Buscar productos"
                            />
                        </div>
                        <div class="table-responsive">
                            <VTable
                                :data="products"
                                :filters="filters"
                                :page-size="5"
                                :currentPage.sync="currentPage"
                                @totalPagesChanged="totalPages = $event"
                                class="table"
                            >
                                <template #head>
                                    <tr>
                                        <VTh sortKey="name">Nombre</VTh>
                                        <th>Precio</th>
                                        <th>Op</th>
                                    </tr>
                                </template>
                                <template #body="{ rows }">
                                    <tr v-for="row in rows" :key="row.id">
                                        <td>{{ row.name }}</td>
                                        <td>${{ row.price_two | currency }}</td>
                                        <td>
                                            <button
                                                type="button"
                                                @click="addRow(row)"
                                                class="btn bg-success btn-sm"
                                            >
                                                <i class="fi fi-heart"></i>
                                            </button>
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
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";

export default {
    $_veeValidate: {
        validator: "new",
    },
    name: "add",
    components: {},
    computed: {
        ...mapState(["urlfactures", "clients", "products"]),
        onViewSub() {
            let subtot = 0;
            this.formFacture.dataDetails.map((data) => {
                subtot = subtot + data.quantity * data.price;
            });
            return subtot;
        },
        onViewTot() {
            let sub = this.onViewSub;
            let disc = this.formFacture.disc;
            if (sub >= disc) {
                let tot = parseInt(sub) - parseInt(disc);
                return tot;
            }
        },
    },
    data() {
        return {
            actions: "Factureactions",
            submitted: true,
            send: false,
            totalPages: 1,
            currentPage: 1,
            filters: {
                name: { value: "", keys: ["name"] },
            },
            formFacture: {
                id: 0,
                client_id: 0,
                sub: 0,
                disc: 0,
                tot: 0,
                state: 0,
                dataDetails: [],
            },
        };
    },
    created() {
        this.getData();
    },
    methods: {
        async addFacture() {
            this.formFacture.sub = this.onViewSub;
            this.formFacture.tot = this.onViewTot;

            let response = await axios.post(this.urlfactures, this.formFacture);
            try {
                console.log(this.formFacture);
                console.log(response);
            } catch (error) {
                console.log(error.response);
            }
        },
        getData() {
            this.$store.dispatch("Clientactions");
            this.$store.dispatch("Productactions");
        },
        addRow(row) {
            this.formFacture.dataDetails.push({
                product_id: row.id,
                name: row.name,
                price: row.price_two,
                quantity: 1,
                sub: 0,
            });
            this.filters.name.value = "";
        },
        show(row) {},
        clear() {
            this.$validator.reset();
            this.send = false;
        },
        incrementDetail(index) {
            this.formFacture.dataDetails[index].quantity++;
        },
        decrementDetail(index) {
            let num = this.formFacture.dataDetails[index].quantity;
            if (num != 1) {
                this.formFacture.dataDetails[index].quantity--;
            }
        },
        removeDetail(index) {
            this.formFacture.dataDetails.splice(index, 1);
        },
    },
};
</script>
