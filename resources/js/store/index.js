import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth";
import error from "./error";
import createForm from "./createForm";
import listProblem from "./listProblem";
import playProblem from "./playProblem";
import flashMessage from "./flashMessage";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        error,
        createForm,
        listProblem,
        playProblem,
        flashMessage
    }
});

export default store;
