import Vue from "vue";
import Home from "./Home";
import router from "./router";
import store from "./store";
import "./bootstrap";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import VueScrollReveal from "vue-scroll-reveal";

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueScrollReveal);

const createApp = async () => {
    await store.dispatch("auth/currentUser");

    new Vue({
        el: "#app",
        router,
        store,
        components: {
            Home
        }
    });
};

createApp();
