import Vue from 'vue'
import Home from "./Home";
import router from "./router";
import store from "./store";
import './bootstrap';

const createApp = async () => {
    await store.dispatch('auth/currentUser')

    new Vue({
        el : '#app',
        router,
        store,
        components: {
            Home
        },
    })
}

createApp()

