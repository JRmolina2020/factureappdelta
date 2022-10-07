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
                               nit.{{nit}}<br>
                               {{phone}}

                                </p>
                              
                            </div>
                            
                            <div
                                v-for="(item, index) in facUnique"
                                :key="'g'+index"
                            >
                            ***************************************
                                <p>
                                    <i class="fi fi-male"></i>
                                    {{ item.fullname }}<br />
                                    <i class="fi fi-phone"></i>
                                    {{ item.phone }}<br>
                                    Fac:#{{item.id}}
                                </p>
                            </div>
                            <div class="text-center">
                            ---------------------------------------------------------
                            </div>
                        
                            <div v class="text-center"   v-for="(item, index) in facUnique"
                            :key="'r'+index">
                            <div v-if="item.note">
                           
                                <p class="mix"><strong>Nota:</strong> {{item.note}}</p>

                            ---------------------------------------------------------
                           
                            </div>
                            </div>
                            <div v-for="(item, index) in details" :key="index">
                                <p>
                                    {{ item.quantity }}X {{ item.name }} *{{
                                        item.price | currency
                                    }}<br />
                                    <strong>{{ item.sub | currency }}</strong>
                              
                                </p>
                            </div>
                            ***************************************
                            <div  v-for="(item, index) in facUnique" :key="'f'+index">
                                <p class="mix2">
                               <strong>Sub</strong> ${{ item.sub | currency }}<br>
                                <strong>Iva%</strong> $ 0.00<br>
                                <strong>Desc</strong> ${{item.disc| currency }}<br>
                                <strong>Tot</strong> ${{item.tot| currency }}<br>
                                <strong>Cant:</strong>{{sumProducts}}
                                </p>
                                
                                
                            </div>
                            ---------------------------------------------------------
                           
                            <div>
                                <p class="text-center mix">
                                    Verificar el estado del producto o pedido, los cambios se hacen con previo aviso dentro de las 24 hrs despúes de la compra.
                                    
                                </p>
                            </div>
                            ----------------------------------------------------------
                            <center>
                                <img height="80" width="80" src="data:image/png;base64,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" alt=""></img>
                           <p>whatsapp</p>
                           <p><strong>Gracias por tu compra</strong></p>
                            </center>
                            
                       
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
            direction: "Pasaje comercial los paisas local #22",
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
<style>
   
    @import url('https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@100&family=Manrope:wght@200;600&display=swap');

        
    p.mix {font-size: 12px;}
    p.mix2 { font-size: 18px;}
</style>