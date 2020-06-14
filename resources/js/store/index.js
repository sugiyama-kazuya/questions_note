import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth";
import error from "./error";
import problem from "./problem";
import listProblem from "./listProblem";
import flashMessage from "./flashMessage";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        error,
        problem,
        listProblem,
        flashMessage
    }
});

export default store;
