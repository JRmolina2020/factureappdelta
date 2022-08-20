<template>
    <div>
        <section class="section">
            <store-facture ref=""></store-facture>
            <list-facture v-on:show="show" class="mt-3"></list-facture>
        </section>
    </div>
</template>
<script>
import ListFacture from "./ListFacture.vue";
import StoreFacture from "./StoreFacture.vue";

export default {
    components: {
        ListFacture,
        StoreFacture,
    },
    methods: {
        show(row) {
            // this.$refs.form_client.show(row);
            console.log(row);
        },
    },
};
</script>
