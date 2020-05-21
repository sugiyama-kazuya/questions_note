<template>
    <loading
        :active.sync="isLoading"
        :height="40"
        :width="40"
        color="#007bff"
    ></loading>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
export default {
    components: {
        Loading
    },
    props: {
        loading: {
            type: Boolean
        }
    },
    data() {
        return {
            fullPage: false
        };
    },
    computed: {
        isLoading() {
            return this.loading;
        }
    }
};
</script>

<style></style>
