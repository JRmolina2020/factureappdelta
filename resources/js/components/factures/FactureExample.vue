<template>
    <div>
        <section class="section">
            <!-- <store-client ref="form_client"></store-client> -->
            <list-facture v-on:show="show" class="mt-3"></list-facture>
        </section>
    </div>
</template>
<script>
import ListFacture from "./ListFacture.vue";

export default {
    components: {
        ListFacture,
    },
    methods: {
        show(row) {
            // this.$refs.form_client.show(row);
            console.log(row);
        },
    },
};
</script>
