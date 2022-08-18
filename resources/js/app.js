require("./bootstrap");

window.Vue = require("vue").default;
window.Axios = require("axios");
import store from "./store";
//utils
//table
import SmartTable from "vuejs-smart-table";
Vue.use(SmartTable);
//end
//alert
import Swal from "sweetalert2";
window.Swal = Swal;
//end
// star currency
import VueCurrencyInput from "vue-currency-input";
import VueCurrencyFilter from "vue-currency-filter";
Vue.use(VueCurrencyInput);
Vue.use(VueCurrencyFilter);
//end
//start validate
import es from "vee-validate/dist/locale/es";
import VeeValidate, { Validator } from "vee-validate";
Vue.use(VeeValidate);
Validator.localize("es", es);
//end validate
//end utils
//components
Vue.component(
    "user_example",
    require("./components/users/UserExample.vue").default
);
Vue.component(
    "profile_example",
    require("./components/users/ProfileUser.vue").default
);
Vue.component("login", require("./components/login.vue").default);
Vue.component(
    "product_example",
    require("./components/products/ProductExample.vue").default
);
Vue.component(
    "client_example",
    require("./components/clients/ClientExample.vue").default
);

Vue.component(
    "facture_example",
    require("./components/factures/FactureExample.vue").default
);

import auth from "./mixins/Auth.js";
Vue.mixin(auth);
const app = new Vue({
    el: "#app",
    store,
});
