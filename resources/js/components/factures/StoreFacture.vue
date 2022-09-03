<template>
    <div>
        <!-- detailsx -->
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Descuento</th>
                            <th scope="col">Total</th>
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
                                <div class="form-group row">
                                    <div class="form-group row">
                                        <currency-input
                                            name="descuento"
                                            id="descuento"
                                            v-validate="{
                                                required: true,
                                                min_value: 0,
                                                max_value:
                                                    formFacture.dataDetails[
                                                        index
                                                    ].sub,
                                            }"
                                            :class="{
                                                'is-invalid':
                                                    submitted &&
                                                    errors.has('descuento'),
                                            }"
                                            v-model="discDetail[index]"
                                            v-on:keyup="calculateDisc()"
                                            class="form-control form-control-sm col-xs-2"
                                            v-currency="{
                                                currency: 'USD',
                                                precision: 0,
                                                locale: 'en',
                                            }"
                                        />
                                    </div>
                                </div>
                            </td>
                            <td>
                                ${{
                                    (formFacture.dataDetails[index].tot =
                                        onviewTotDetail[index]) | currency
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
                        <label for>Venta</label>
                        <select
                            class="form-control form-control-sm"
                            v-model="type_sale"
                            required
                            @change="typeSale()"
                        >
                            <option value="1">Mayorista</option>
                            <option value="0">Detal</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for>Clientes</label>
                        <select
                            class="form-control form-control-sm"
                            v-model="formFacture.client_id"
                            required
                        >
                            <option
                                v-for="(item, index) in clients"
                                :value="item.id"
                                :key="index"
                            >
                                {{ item.fullname }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for>Subtotal</label>
                        <currency-input
                            disabled
                            :value="onViewSub"
                            class="form-control form-control-sm"
                            v-currency="{
                                currency: 'USD',
                                precision: 0,
                                locale: 'en',
                            }"
                        />
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label for>Descuento</label>
                        <currency-input
                            v-model.number="formFacture.disc"
                            class="form-control form-control-sm"
                            v-currency="{
                                currency: 'USD',
                                precision: 0,
                                locale: 'en',
                            }"
                        />
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for>Total</label>
                        <currency-input
                            disabled
                            class="form-control form-control-sm"
                            :value="onViewTot"
                            v-currency="{
                                currency: 'USD',
                                precision: 0,
                                locale: 'en',
                            }"
                        />
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Estado</label>
                        <select
                            v-model="formFacture.state"
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

                <div
                    v-if="
                        formFacture.dataDetails.length != 0 &&
                        onViewSub >= formFacture.disc
                    "
                    class="col-lg-2"
                >
                    <button
                        type="submit"
                        :disabled="errors.any()"
                        class="btn btn btn-primary btn-sm"
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
                                class="table table-striped table-borderless"
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
                                        <td v-if="type_sale">
                                            ${{ row.price | currency }}
                                        </td>
                                        <td v-else>
                                            ${{ row.price_two | currency }}
                                        </td>
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
import date_facture from "../../mixins/dateFacture";
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
        onviewTotDetail() {
            let tot = [];
            for (let i = 0; i < this.formFacture.dataDetails.length; i++) {
                tot[i] =
                    this.formFacture.dataDetails[i].sub - this.discDetail[i];
            }
            return tot;
        },
    },
    data() {
        return {
            actions: "Factureactions",
            submitted: true,
            totalPages: 1,
            currentPage: 1,
            filters: {
                name: { value: "", keys: ["name"] },
            },
            type_sale: 1,
            discDetail: [],
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
    mixins: [date_facture],
    created() {
        this.getData();
    },
    methods: {
        async addFacture() {
            //agregando valores calculados fuction en el form
            this.formFacture.sub = this.onViewSub;
            this.formFacture.tot = this.onViewTot;
            let response = await axios.post(this.urlfactures, this.formFacture);
            try {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: `${response.data.message}`,
                    showConfirmButton: false,
                    timer: 1500,
                });

                this.getList();
                this.clear();
            } catch (error) {
                console.log(error.response);
            }
        },
        getData() {
            this.$store.dispatch("Clientactions");
            this.$store.dispatch("Productactions");
        },
        addRow(row) {
            let price = 0;
            if (this.type_sale == 1) {
                price = row.price;
            } else {
                price = row.price_two;
            }
            this.formFacture.dataDetails.push({
                product_id: row.id,
                name: row.name,
                price: price,
                quantity: 1,
                sub: 0,
                disc: 0,
                tot: 0,
            });
            this.filters.name.value = "";
            //para cuandro se agregue a la lista del detalle el descuento tome valor 0
            for (let i = 0; i < this.formFacture.dataDetails.length; i++) {
                this.discDetail[i] = 0;
            }
        },
        calculateDisc() {
            let totDisc = 0;
            for (let i = 0; i < this.formFacture.dataDetails.length; i++) {
                totDisc = parseInt(totDisc + this.discDetail[i]);
                this.formFacture.dataDetails[i].disc = this.discDetail[i];
            }

            this.formFacture.disc = totDisc;
        },

        clear() {
            this.formFacture.id = 0;
            this.formFacture.client_id = 0;
            this.formFacture.sub = 0;
            this.formFacture.disc = 0;
            this.formFacture.tot = 0;
            this.formFacture.state = 0;
            this.formFacture.dataDetails = [];
        },
        typeSale() {
            if (this.type_sale == 1) {
                this.type_sale = 1;
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Usted ha escogido venta mayorista",
                    showConfirmButton: true,
                });
            } else {
                this.type_sale = 0;
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Usted ha escogido venta al detal",
                    showConfirmButton: true,
                });
            }
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
