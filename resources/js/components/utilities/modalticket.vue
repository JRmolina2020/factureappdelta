wwww
<template>
    <div>
        <!-- Button trigger modal -->
        <button
            type="button"
            class="btn bg-success btn-sm"
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
            <div id="facture">
                <div>
                    <br />

                    <strong class="text-center">{{ company }}</strong>

                    <p
                        id="estilo"
                        v-for="(item, index) in facUnique"
                        :key="'a' + index"
                    >
                        fecha: {{ item.created_at }}<br />
                        fac: #000000{{ item.id }}<br />
                        ------------------------------<br />
                        {{ item.fullname }}<br />
                        {{ item.phone }}<br />
                        ------------------------------<br />
                        {{ item.note }}
                    </p>
                </div>
                <strong>Productos:</strong>

                <p v-for="(item, index) in details" :key="index">
                    {{ item.quantity }}X {{ item.name }} *{{
                        item.price | currency
                    }}<br />
                    ${{ item.sub | currency }}<br />
                    .......................................
                </p>

                ********************
                <div v-for="(item, index) in facUnique" :key="'f' + index">
                    <p class="bodyText">
                        <strong>Sub</strong> ${{ item.sub | currency }}<br />
                        <strong>Iva%</strong> $ 0.00<br />
                        <strong>Desc</strong> ${{ item.disc | currency }}<br />
                        <strong>Tot</strong> ${{ item.tot | currency }}<br />
                        <strong>Cant:</strong>{{ sumProducts }}
                    </p>
                </div>
                ********************
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
export default {
    props: {
        cod: {
            type: Number,
            required: true,
        },
    },
    components: {},
    data() {
        return {
            currentPage: 1,
            totalPages: 0,
            company: "BOLSOSVALLEDUPAR",
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
        print(areaID) {
            var printContent = document.getElementById(areaID);
            var WinPrint = window.open("", "", "width=900,height=750");
            WinPrint.document.write(printContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        },
        getlistProducts() {
            Swal.fire({
                html: "Por favor espera un momento",
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const b = Swal.getHtmlContainer().querySelector("b");
                    timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft();
                    }, 100);
                },
                willClose: () => {
                    this.$store.dispatch("FactureDetailactions", this.cod);
                    this.$store.dispatch("FactureUniquections", this.cod);
                    this.print("facture");
                },
            });
        },
    },
};
</script>
<style type="text/css">
<!-- @media screen {
    p.bodyText {
        font-family: verdana, arial, sans-serif;
    }
}

@media print {
    p.bodyText {
        font-family: verdana, arial, sans-serif;
    }
}
@media screen, print {
    p.bodyText {
        font-size: 10pt;
    }
}
</style>
