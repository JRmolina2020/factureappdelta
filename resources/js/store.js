import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
import { type } from "jquery";
Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        users: [],
        roles: [],
        permissions: [],
        products: [],
        clients: [],
        factures: [],
        typeSale: [],
        typeSale_one: [],
        details: [],
        facUnique: [],
        status: false,
        urlusers: "/api/users",
        urlroles: "/api/roles",
        urlpermissions: "/api/permissions",
        urlproducts: "/api/products",
        urlclients: "/api/clients",
        urlfactures: "/api/factures",
        urltype_sale: "/api/type_sale",
        urltype_sale_one: "/api/type_sale_one",
        urldetails: "/api/details",
        urlfactureUnique: "/api/factureUnique",
    },
    mutations: {
        Usermutations(state, item) {
            state.users = item;
        },
        Rolemutations(state, item) {
            state.roles = item;
        },
        Permissionmutations(state, item) {
            state.permissions = item;
        },
        Productmutations(state, item) {
            state.products = item;
        },
        Clientmutations(state, item) {
            state.clients = item;
        },
        Facturemutations(state, item) {
            state.factures = item;
        },
        TypeSalemutations(state, item) {
            state.typeSale = item;
        },
        TypeSale_one_mutations(state, item) {
            state.typeSale_one = item;
        },
        FactureDetailmutations(state, item) {
            state.details = item;
        },
        FactureUniquemutations(state, item) {
            state.facUnique = item;
        },
    },
    actions: {
        async Useractions({ commit, state }) {
            try {
                let response = await axios.get(state.urlusers);
                commit("Usermutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Roleactions({ commit, state }) {
            try {
                let response = await axios.get(state.urlroles);
                commit("Rolemutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Permissionactions({ commit, state }) {
            try {
                let response = await axios.get(state.urlpermissions);
                commit("Permissionmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },

        async Productactions({ commit, state }) {
            try {
                let response = await axios.get(state.urlproducts);
                commit("Productmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Clientactions({ commit, state }) {
            try {
                let response = await axios.get(state.urlclients);
                commit("Clientmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async Factureactions({ commit, state }, date) {
            try {
                let response = await axios.get(`${state.urlfactures}/${date}`);
                commit("Facturemutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async TypeSaleactions({ commit, state }, date) {
            try {
                let response = await axios.get(`${state.urltype_sale}/${date}`);
                commit("TypeSalemutations", response.data);

                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        //type banca
        async TypeSale_one_actions({ commit, state }, obj) {
            try {
                let response = await axios.get(
                    `${state.urltype_sale_one}/${obj.prop1}/${obj.prop2}`
                );
                commit("TypeSale_one_mutations", response.data);

                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async FactureDetailactions({ commit, state }, id) {
            try {
                let response = await axios.get(`${state.urldetails}/${id}`);
                commit("FactureDetailmutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
        async FactureUniquections({ commit, state }, id) {
            try {
                let response = await axios.get(
                    `${state.urlfactureUnique}/${id}`
                );
                commit("FactureUniquemutations", response.data);
                state.status = true;
            } catch (error) {
                console.log(error);
            }
        },
    },
});
