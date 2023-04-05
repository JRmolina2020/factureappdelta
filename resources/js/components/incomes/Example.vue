<template>
    <div>
        <section class="section">
            <store-income ref="form_income"></store-income>
            <list-income v-on:show="show" class="mt-3"></list-income>
        </section>
    </div>
</template>
<script>
import StoreIncome from "./Store.vue";
import ListIncome from "./List.vue";

export default {
    components: {
        StoreIncome,
        ListIncome,
    },
    methods: {
        show(row) {
            this.$refs.form_income.show(row);
        },
    },
};
</script>
