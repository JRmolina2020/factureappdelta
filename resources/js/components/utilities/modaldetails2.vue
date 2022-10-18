<template>

    <div>
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn bg-warning btn-sm"
            data-toggle="modal"
            :data-target="'#model2' + cod"
            @click="getlistProducts"
        >
            <i class="fi fi-file-1"></i>
        </button>

        <div
            class="modal fade"
            :id="'model2' + cod"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modelTitleId"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg "  role="document">
                <div class="modal-content">
                    <div class="modal-body">
                          <div>
                            <img  ref="outputImage" class="img-fluid rounded mx-auto d-block"  :src="output">
                          </div>
                        <div  id="facture2" ref="pdf">
                            <center>
                            <h3 class="text">{{company}}</h3>
                             <p class="text">Nit.<strong>{{nit}}</strong></p>
                             <p class="text">{{direction}}<br>{{phone}}</p>
                              
                            </center>
                             
                            <table class="table table-dark">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nit</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Tel</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr  v-for="(item, index) in facUnique"
                                  :key="'g'+index">
                                    <th scope="row">{{item.id}}</th>
                                    <td>{{item.nit}}</td>
                                    <td>{{item.fullname}}</td>
                                    <th>{{item.phone}}</th>
                                  </tr>
                                  
                                </tbody>
                              </table>
                              <table  class="table table-borderless table-dark table-striped">
                                <thead>
                                  <tr>
                                    <th scope="col">Art.</th>
                                    <th scope="col">Pre.</th>
                                    <th scope="col">Cant.</th>
                                    <th scope="col">Sub.</th>
                                    <th scope="col">Desc.</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr  v-for="(item, index) in details"
                                  :key="'d'+index">
                                    <th scope="row">{{item.name}}</th>
                                    <td>${{item.price | currency }}</td>
                                    <td>{{item.quantity}}</td>
                                    <td>${{item.sub | currency}}</td>
                                    <td>${{item.disc | currency }}</td>
                                  </tr>
                                
                                </tbody>
                              </table>
                              <table class="table table-dark">
                                <thead>
                                  <tr>
                                    <th scope="col">Sub.</th>
                                    <th scope="col">Desc.</th>
                                    <th scope="col">Tot.</th>
                                    <th scope="col">Cantp.</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr  v-for="(item, index) in facUnique"
                                  :key="'g2'+index">
                                    <th>${{item.sub | currency}}</th>
                                    <td>${{item.disc | currency}}</td>
                                    <td scope="row">${{item.tot | currency}}</td>
                                    <th>{{sumProducts}}</th>
                                  </tr>
                                </tbody>
                              </table>
                              <center>
                                <div>
                                    <p class="text-center mix textf">
                                        Verificar el estado del producto o pedido, los cambios se hacen con previo aviso dentro de las 24 hrs despúes de la compra.
                                        
                                    </p>
                                </div>
                            </center>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button
                                type="button"
                                @click="print()"
                                class="btn btn-primary"
                            >
                              Imprimir <i class="fi fi-table-2"></i>
                            </button>
                            <button
                            type="button"
                            @click="imageCap()"
                            class="btn btn-primary"
                        >
                            Imagen <i class="fi fi-table-2"></i>
                        </button>
                        <br></br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </template>
<script>
import { mapState } from "vuex";

import VueHtml2Canvas from 'vue-html2canvas';
Vue.use(VueHtml2Canvas);

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
            output: null,
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
            this.$htmlToPaper("facture2");
        },
        getlistProducts() {
            this.$store.dispatch("FactureDetailactions", this.cod);
            this.$store.dispatch("FactureUniquections", this.cod);
           

        },
      imageCap(){
      const el = this.$refs.pdf;
      const options = {
        type: "dataURL"
      };
      (async () => {
        this.output = await this.$html2canvas(el, options);
      })();
        }
   
    },
};
</script>
<style>
   
    @import url('https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@100&family=Manrope:wght@200;600&display=swap');

        
   
    p.text{
        color:black
    }
    h3.text{
        color:black
    }
   
</style>