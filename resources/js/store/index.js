import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth";
import error from "./error";
import listProblem from "./listProblem";
import flashMessage from "./flashMessage";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        error,
        listProblem,
        flashMessage
    }
});

export default store;
