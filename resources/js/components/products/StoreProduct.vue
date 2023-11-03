<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            sone="modal-dialog modal-lg"
            title="Registro de productos"
        >
            <section slot="titlebutton">Registrar productos</section>
            <section v-if="!form.products[0].id" slot="title">
                Registro de productos
            </section>
            <section v-else slot="title">
                Editar producto {{ form.products[0].id }}
            </section>
            <section slot="closebtn">
                <button
                    @click="reset()"
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </section>
            <section slot="body">
                <form
                    method="POST"
                    @submit.enter.prevent="
                        add(form.products[0].id, actions, urlproducts)
                    "
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div v-for="(item, index) in form.products" :key="index">
                        <div class="row">
                            <div class="col-lg-2 col-12">
                                <div class="form-group">
                                    <label for>Ref</label>
                                    <input
                                        type="text"
                                        ref="name"
                                        required
                                        class="form-control form-control-sm"
                                        placeholder="Referencia"
                                        v-model="form.products[index].name"
                                        name="nombre"
                                    />
                                </div>
                            </div>
                            <div class="col-lg-2 col-12">
                                <div class="form-group">
                                    <label>Costo</label>
                                    <currency-input
                                        v-validate="{
                                            required: true,
                                        }"
                                        class="form-control form-control-sm"
                                        v-currency="{
                                            currency: 'USD',
                                            precision: 0,
                                            locale: 'en',
                                        }"
                                        :class="{
                                            'is-invalid':
                                                submitted &&
                                                errors.has('costo'),
                                        }"
                                        v-model.number="
                                            form.products[index].cost
                                        "
                                        name="costo"
                                    />
                                    <div
                                        v-if="submitted && errors.has('costo')"
                                        class="invalid-feedback"
                                    >
                                        {{ errors.first("costo") }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-12">
                                <div class="form-group">
                                    <label for>Precio mayorista</label>
                                    <currency-input
                                        v-validate="'|required|min_value:0'"
                                        class="form-control form-control-sm"
                                        v-currency="{
                                            currency: 'USD',
                                            precision: 0,
                                            locale: 'en',
                                        }"
                                        :class="{
                                            'is-invalid':
                                                submitted &&
                                                errors.has('precio mayorista'),
                                        }"
                                        v-model.number="
                                            form.products[index].price
                                        "
                                        name="precio de compra"
                                    />
                                    <div
                                        v-if="
                                            submitted &&
                                            errors.has('precio mayorista')
                                        "
                                        class="invalid-feedback"
                                    >
                                        {{ errors.first("precio mayorista") }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-12">
                                <div class="form-group">
                                    <label for>Precio de venta</label>
                                    <currency-input
                                        v-validate="{
                                            min_value: 0,
                                        }"
                                        class="form-control form-control-sm"
                                        v-currency="{
                                            currency: 'USD',
                                            precision: 0,
                                            locale: 'en',
                                        }"
                                        :class="{
                                            'is-invalid':
                                                submitted &&
                                                errors.has('precio de venta'),
                                        }"
                                        v-model.number="
                                            form.products[index].price_two
                                        "
                                        name="precio de venta"
                                    />
                                    <div
                                        v-if="
                                            submitted &&
                                            errors.has('precio de venta')
                                        "
                                        class="invalid-feedback"
                                    >
                                        {{ errors.first("precio de venta") }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-12">
                                <div class="form-group">
                                    <label for>Color</label>
                                    <input
                                        type="text"
                                        ref="color"
                                        class="form-control form-control-sm"
                                        v-model="form.products[index].color"
                                        name="color"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-12">
                                <div class="form-group">
                                    <label>Categoria</label>
                                    <select
                                        required
                                        v-model="
                                            form.products[index].categorie_id
                                        "
                                    >
                                        <option
                                            v-for="item in categories"
                                            :key="item.id"
                                            :value="item.id"
                                        >
                                            {{ item.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button
                            v-if="form.products.length != 1"
                            @click="removeItem(index)"
                            class="btn btn-danger btn-xs"
                            type="button"
                        >
                            <i class="fi fi-trash"></i>
                        </button>
                    </div>
                    {{ form.products.id }}
                    <div class="mt-3">
                        <button
                            v-if="form.products[0].id == null"
                            @click="addItem()"
                            class="btn btn-success"
                            type="button"
                        >
                            <i class="fi fi-nav-icon-list"></i>
                        </button>
                        <button
                            v-if="!send"
                            class="btn btn-primary"
                            type="button"
                            disabled
                        >
                            <span
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Loading...
                        </button>
                        <button
                            v-if="send"
                            :hidden="errors.any()"
                            type="submit"
                            v-bind:class="{
                                'btn btn-outline-primary ':
                                    !this.form.products.id,
                                'btn btn-outline-danger ':
                                    this.form.products.id,
                            }"
                        >
                            <i
                                v-bind:class="{
                                    'fi fi-wink': !this.form.products.id,
                                    'fi fi-like': this.form.products.id,
                                }"
                                aria-hidden="true"
                            ></i>
                        </button>
                    </div>
                </form>
            </section>
        </Modal-Resource>
    </div>
</template>
<script>
import { mapState } from "vuex";
import ModalResource from "../utilities/modal.vue";
import add from "../../mixins/add";

export default {
    $_veeValidate: {
        validator: "new",
    },
    name: "add",
    components: {
        ModalResource,
    },
    computed: {
        ...mapState(["urlproducts", "categories"]),
    },
    data() {
        return {
            actions: "Productactions",
            submitted: true,
            send: 1,
            disabled: 0,
            price_default: 1000,
            barcodeint2: 0,
            tag: "svg",
            options: {
                lineColor: "#ff7069",
                fontSize: 32,
                font: "Courier",
                width: 3,
                height: 60,
                marginBottom: 60,
                format: "MSI",
                background: "#ccffff",
            },
            form: {
                products: [
                    {
                        id: "",
                        name: "",
                        price: 0,
                        price_two: 0,
                        cost: 0,
                        color: 0,
                        categorie_id: null,
                    },
                ],
            },
        };
    },

    mixins: [add],
    created() {
        this.getCategorie();
    },
    methods: {
        getCategorie() {
            this.$store.dispatch("Categorieactions");
        },
        reset() {
            this.form.products.name = "";
        },
        addItem() {
            this.form.products.push({
                id: null,
                name: this.form.products.name,
                price: this.form.products.price,
                price_two: this.form.products.price_two,
                cost: this.form.products.cost,
                color: this.form.products.color,
                categorie_id: this.form.products.categorie_id,
            });
        },
        removeItem(index) {
            this.form.products.splice(index, 1);
        },

        show(row) {
            this.form.products.length = 1;
            this.form.products[0].id = row.id;
            this.form.products[0].name = row.name;
            this.form.products[0].price = parseFloat(row.price);
            this.form.products[0].price_two = parseFloat(row.price_two);
            this.form.products[0].cost = parseFloat(row.cost);
            this.form.products[0].color = parseFloat(row.color);
            this.form.products[0].categorie_id = parseFloat(row.idc);
            $("#model").modal("show");
            this.send = true;
            this.disabled = true;
        },
        clear() {
            this.form.products[0].id = null;
            this.form.products[0].name = null;
            this.form.products[0].price = 0;
            this.form.products[0].price_two = 0;
            this.form.products[0].cost = 0;
            this.form.products[0].color = "";
            this.form.products[0].categorie_id = "";
            this.$validator.reset();
            this.send = true;
            this.barcodeint2 = 0;
            this.form.products.length = 1;
            this.disabled = false;
        },
    },
};
</script>
