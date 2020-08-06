<template>
    <loading
        :active.sync="isLoading"
        :height="28"
        :width="28"
        color="#777"
        :opacity="opacity ? opacity : 0"
        :background-color="backgroundColor ? backgroundColor : ''"
        class="most-preferred"
    ></loading>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { integer } from "vee-validate/dist/rules";
export default {
    name: "TheLoading",
    components: {
        Loading
    },
    props: {
        loading: {
            type: Boolean
        },
        opacity: {
            type: Number
        },
        fullPage: {
            type: Boolean
        },
        backgroundColor: {
            type: String
        }
    },
    computed: {
        isLoading() {
            return this.loading;
        }
    }
};
</script>

<style scoped>
.most-preferred {
    z-index: 9999;
}
</style>
