import { INTERNAL_SERVER_ERROR } from "./util";

export default {
    methods: {
        scrollTop() {
            window.scrollTo({
                top: 0,
                behavior: "instant"
            });
        },

        internalServerError(status) {
            if (INTERNAL_SERVER_ERROR === status) {
                this.$router.push("/500");
                return;
            }
        }
    }
};
