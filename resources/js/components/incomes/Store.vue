<template>
    <div>
        <Modal-Resource
            v-on:clear="clear"
            title="Registro de entradas"
            sone="modal-dialog modal-sm"
        >
            <section slot="titlebutton">Registrar entradas</section>
            <section v-if="!form.id" slot="title">Registro de entrada</section>
            <section v-else slot="title">Editar entrada</section>
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
                    @submit.enter.prevent="add(form.id)"
                    autocomplete="off"
                    onKeyPress="if(event.keyCode == 13) event.returnValue = false;"
                >
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Producto</label>
                                <select
                                    class="form-control"
                                    v-model="form.product_id"
                                    required
                                >
                                    <option
                                        v-for="(item, index) in products"
                                        :key="index"
                                        :value="item.id"
                                    >
                                        {{ item.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">cant.</span>
                                </div>
                                <input
                                    class="form-control"
                                    type="number"
                                    min="1"
                                    v-model="form.quantity"
                                    placeholder="Cantidad"
                                    required
                                />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group mt-3">
                                <input
                                    class="form-control"
                                    type="date"
                                    v-model="form.date_income"
                                />
                            </div>
                        </div>
                    </div>
                    <button
                        v-if="!send"
                        class="btn btn-primary mt-3"
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
                            'btn btn-outline-primary mt-3 ': !this.form.id,
                            'btn btn-outline-danger mt-3 ': this.form.id,
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
import date_now from "../../mixins/date";

export default {
    $_veeValidate: {
        validator: "new",
    },
    name: "add",
    components: {
        ModalResource,
    },
    computed: {
        ...mapState(["urlincome", "products"]),
    },
    data() {
        return {
            actions: "Incomeactions",
            submitted: true,
            send: true,
            form: {
                id: null,
                product_id: null,
                quantity: 1,
                date_income: "",
            },
        };
    },
    created() {
        this.getProduct();
    },

    methods: {
        getList() {
            $("#model").modal("hide");
            this.$store.dispatch(this.actions, date_now);
            this.$store.dispatch(this.actions, date_now);
            this.clear();
        },
        getProduct() {
            this.$store.dispatch("Productactions");
        },
        add(id) {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.addIncome(id);
                }
            });
        },
        async addIncome(id) {
            this.send = false;
            if (id) {
                let response = await axios.put(
                    this.urlincome + "/" + id,
                    this.form
                );
                try {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    this.getList();
                } catch (error) {
                    console.log(error.response);
                }
            } else {
                let response = await axios.post(this.urlincome, this.form);
                try {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1600,
                    });
                    this.getList();
                } catch (error) {
                    console.log(error.response);
                }
            }
        },
        show(row) {
            this.form.id = row.id;
            this.form.product_id = row.product_id;
            this.form.quantity = row.quantity;
            this.form.date_income = row.date_income;
            $("#model").modal("show");
            this.send = true;
        },
        clear() {
            this.form.id = "";
            this.form.product_id = "";
            this.form.quantity = 1;
            this.$validator.reset();
            this.send = true;
        },
    },
};
</script>
