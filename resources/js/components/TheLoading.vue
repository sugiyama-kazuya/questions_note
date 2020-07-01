<template>
  <loading
    :active.sync="isLoading"
    :height="40"
    :width="40"
    color="#007bff"
    :opacity="opacity ? opacity : 0.5"
    :is-full-page="fullPage"
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
</style>
