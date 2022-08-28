const { add } = require("lodash");

module.exports = {
    methods: {
        add(id, actions, url) {
            this.$validator.validate().then((valid) => {
                if (valid) {
                    this.route(id, actions, url);
                }
            });
        },
        async route(id, actions, url) {
            if (id) {
                let response = await axios.put(url + id, this.form);
                try {
                    this.$store.dispatch(actions);
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    $("#model").modal("hide");
                    this.clear();
                } catch (error) {
                    console.log(error.response);
                }
            } else {
                let response = await axios.post(url, this.form);
                try {
                    console.log(response);
                    this.send = true;
                    $("#model").modal("hide");
                    this.$store.dispatch(actions);
                } catch (error) {
                    console.log(error.response);
                }
            }
        },
    },
};
