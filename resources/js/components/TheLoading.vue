<template>
    <loading
        :active.sync="isLoading"
        :height="28"
        :width="28"
        :opacity="opacity ? opacity : 0"
        :background-color="backgroundColor ? backgroundColor : ''"
        color="#777"
        class="most-preferred"
    ></loading>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
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
