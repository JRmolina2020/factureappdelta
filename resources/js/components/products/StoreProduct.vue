<template>
    <div>
        <Modal-Resource v-on:clear="clear" title="Registro de productos">
            <section slot="titlebutton">Registrar productos</section>
            <section v-if="!form.id" slot="title">
                Registro de productis
            </section>
            <section v-else slot="title">Editar producto</section>
            <section slot="closebtn">
                <button
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
                    @submit.enter.prevent="add(form.id, actions, urlproducts)"
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for>Nombre</label>
                                <input
                                    type="text"
                                    ref="name"
                                    v-validate="'required|max:30|min:3'"
                                    class="form-control form-control-sm"
                                    :class="{
                                        'is-invalid':
                                            submitted && errors.has('nombre'),
                                    }"
                                    placeholder="Nombre"
                                    v-model="form.name"
                                    name="nombre"
                                />
                                <div
                                    v-if="submitted && errors.has('nombre')"
                                    class="invalid-feedback"
                                >
                                    {{ errors.first("nombre") }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for>Costo</label>
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
                                            submitted && errors.has('costo'),
                                    }"
                                    v-model.number="form.cost"
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
                        <div class="col-lg-4">
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
                                    v-model.number="form.price"
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
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for>Precio de venta</label>
                                <currency-input
                                    v-validate="{
                                        required: true,
                                        min_value: form.price + price_default,
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
                                    v-model.number="form.price_two"
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
                    </div>

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
                            'btn btn-outline-primary ': !this.form.id,
                            'btn btn-outline-danger ': this.form.id,
                        }"
                    >
                        <i
                            v-bind:class="{
                                'fi fi-wink': !this.form.id,
                                'fi fi-like': this.form.id,
                            }"
                            aria-hidden="true"
                        ></i>
                    </button>
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
        ...mapState(["urlproducts"]),
    },
    data() {
        return {
            actions: "Productactions",
            submitted: true,
            send: 1,
            price_default: 1000,
            form: {
                id: null,
                name: "",
                price: 0,
                price_two: 0,
                cost: 0,
            },
        };
    },
    mixins: [add],
    methods: {
        show(row) {
            this.form.id = row.id;
            this.form.name = row.name;
            this.form.price = parseFloat(row.price);
            this.form.price_two = parseFloat(row.price_two);
            this.form.cost = parseFloat(row.cost);
            $("#model").modal("show");
            this.send = true;
        },
        clear() {
            this.form.id = null;
            this.form.name = null;
            this.form.price = 0;
            this.form.price_two = 0;
            this.form.cost = 0;
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
