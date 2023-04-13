<template>
    <div>
        <div class="form-group">
            <input
                type="text"
                class="form-control"
                v-model="filters.name.value"
                placeholder="Buscar productos"
            />
        </div>
        <div class="table-responsive">
            <VTable
                :data="products"
                :filters="filters"
                :page-size="5"
                :currentPage.sync="currentPage"
                @totalPagesChanged="totalPages = $event"
                class="table"
            >
                <template #head>
                    <tr>
                        <VTh sortKey="name">Nombre</VTh>
                        <th>Costo</th>
                        <th>Precio M</th>
                        <th>Precio D</th>
                        <th>%GM</th>
                        <th>%GD</th>

                        <th>Op</th>
                    </tr>
                </template>
                <template #body="{ rows }">
                    <tr v-for="row in rows" :key="row.id">
                        <td class="bg-danger" v-if="row.cost == 0">
                            {{ row.name }}
                        </td>
                        <td v-else>{{ row.name }}</td>
                        <td>{{ row.cost | currency }}</td>
                        <td>
                            {{ row.price | currency }}
                        </td>

                        <td>{{ row.price_two | currency }}</td>
                        <td v-can="'gestion producto'" class="bg-warning">
                            {{ (row.price - row.cost) | currency }}
                        </td>
                        <td v-can="'gestion producto'" class="bg-info">
                            {{ (row.price_two - row.cost) | currency }}
                        </td>

                        <td v-can="'gestion producto'">
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
            <div class="text-xs-center">
                <VTPagination
                    :currentPage.sync="currentPage"
                    :total-pages="totalPages"
                    :boundary-links="true"
                />
            </div>
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
            filters: {
                name: { value: "", keys: ["name"] },
            },
        };
    },
    computed: {
        ...mapState(["products", "status", "urlproducts"]),
    },
    created() {
        this.getList();
    },
    methods: {
        getList() {
            this.$store.dispatch("Productactions");
        },
        destroy(id) {
            Swal.fire({
                title: "Deseas eliminar el producto?",
                showCancelButton: true,
                confirmButtonText: "Si",
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = this.urlproducts + "/" + id;
                    let response = axios.delete(url);
                    try {
                        this.getList();
                        Swal.fire({
                            title: `${response.data.message}`,
                            icon: "success",
                        });
                    } catch (error) {
                        console.log(error);
                    }
                }
            });
        },
    },
};
</script>
