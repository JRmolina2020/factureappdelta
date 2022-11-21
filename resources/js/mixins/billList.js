const { billList } = require("lodash");
let date = new Date();
let day = date.getDate();
let month = date.getMonth() + 1;
let year = date.getFullYear();
if (month < 10) {
    var date_now = `${year}-0${month}-${day}`;
} else {
    var date_now = `${year}-${month}-${day}`;
}

module.exports = {
    methods: {
        getList() {
            this.$store.dispatch("Billactions", date_now);
            this.$store.dispatch("Billtotactions", date_now);
        },
    },
};
