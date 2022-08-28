import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        users: [],
        products: [],
        clients: [],
        factures: [],
        details: [],
        facUnique: [],
        status: false,
        urlusers: "/api/users/",
        urlproducts: "/api/products",
        urlclients: "/api/clients/",
        urlfactures: "/api/factures/",
        urldetails: "/api/details",
        urlfactureUnique: "/api/factureUnique",
    },
    mutations: {
        Usermutations(state, item) {
            state.users = item;
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
        async Factureactions({ commit, state }) {
            try {
                let response = await axios.get(state.urlfactures);
                commit("Facturemutations", response.data);
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
