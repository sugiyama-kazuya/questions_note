import { INTERNAL_SERVER_ERROR, NotFound, Forbidden } from "./util";

export default {
    computed: {
        $_authCheck() {
            return this.$store.state.auth.user ? true : false;
        }
    },

    methods: {
        $_scrollTop() {
            window.scrollTo({
                top: 0,
                behavior: "instant"
            });
        },

        $_internalServerError(status) {
            if (INTERNAL_SERVER_ERROR === status) {
                this.$router.push("/500");
                return;
            }
        },

        $_notFoundError(status) {
            if (NotFound === status) {
                this.$router.push("/notFound");
                return;
            }
        },

        $_forbidden(status) {
            if (Forbidden === status) {
                this.$router.push("/403");
                return;
            }
        },

        $_goExerciseBooksIndex() {
            this.$router.push("/exercise-books");
            return;
        }
    }
};
