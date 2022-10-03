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
            <div class="modal-dialog " style="width:300px;" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="facture">
                            <div>
                                <p class="text-center">
                               <strong>{{company}}</strong><br>{{direction}}<br>
                               nit.{{nit}}
                                </p>
                              
                            </div>
                            <div
                                v-for="(item, index) in facUnique"
                                :key="'g'+index"
                            >
                            ***********************************
                                <p>
                                    <i class="fi fi-male"></i>
                                    {{ item.fullname }}<br />
                                    <i class="fi fi-phone"></i>
                                    {{ item.phone }}<br>
                                    Fac:#{{item.id}}
                                </p>
                                  ***********************************
                            </div>
                            <div v-for="(item, index) in details" :key="index">
                                <p>
                                    {{ item.quantity }}X {{ item.name }} *{{
                                        item.price | currency
                                    }}<br />
                                    <strong>{{ item.sub | currency }}</strong>
                              
                                </p>
                            </div>
                            ***********************************
                            <div  v-for="(item, index) in facUnique" :key="'f'+index">
                                <p>
                                   <strong>Sub</strong> {{ item.sub | currency }}<br>
                                <strong>Disc</strong> {{item.disc| currency }}<br>
                                <strong>Tot</strong> {{item.tot| currency }}<br>
                                </p>
                                
                            </div>
                            ***********************************
                       
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
            direction: "Centro comercial los paisas local #22",
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
