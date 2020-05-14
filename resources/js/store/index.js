import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth";
import error from "./error";
import createForm from "./createForm";
import listProblem from "./listProblem";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        error,
        createForm,
        listProblem
    }
});

export default store;
