<template>
    <div>
        <div class="table-responsive">
            <VTable :data="company" class="table">
                <template #head>
                    <tr>
                        <VTh sortKey="nit">Nit</VTh>
                        <th>Empresa</th>
                        <th>R</th>
                        <th>Tel</th>
                        <th>Dirección</th>
                        <th>Ciudad</th>
                        <th>Op</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td>{{ row.nit }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.representative }}</td>
                        <td>{{ row.phone }}</td>
                        <td>{{ row.direction }}</td>
                        <td>{{ row.city }}</td>
                        <td>
                            <button
                                type="button"
                                @click="$emit('show', row)"
                                class="btn bg-warning btn-sm"
                            >
                                <i class="fi fi-eye"></i>
                            </button>
                            <button
                                type="button"
                                @click="destroy(row.id)"
                                class="btn bg-danger btn-sm"
                            >
                                <i class="fi fi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </VTable>
        </div>
    </div>
</template>
<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            totalPages: 1,
            currentPage: 1,
        };
    },
    computed: {
        ...mapState(["company", "status", "urlcompany", "urlcompanies"]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            this.$store.dispatch("Companyactions");
        },
        async destroy(id) {
            let url = this.urlcompanies + "/" + id;
            let response = await axios.delete(url);
            try {
                this.getList();
                Swal.fire({
                    title: `${response.data.message}`,
                    icon: "success",
                });
            } catch (error) {
                console.log(error);
            }
        },
    },
};
</script>
