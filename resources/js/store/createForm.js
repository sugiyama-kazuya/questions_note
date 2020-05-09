import { UNPROCESSABLE_ENTITY } from "../util";

const state = {
    isCategory: null,
    isExercise: null,
    isNewProblem: null,
    errorMessage: null
};

const getters = {};

const mutations = {
    setCategoryModal(state, status) {
        state.isCategory = status;
    },
    setProblemModal(state, status) {
        state.isExercise = status;
    },
    setCloseModal(state, status) {
        state.isExercise = status;
        state.isCategory = status;
    },
    setNewProblemModal(state, status) {
        state.isNewProblem = status;
    },
    setCreateProblemErrorMsg(state, message) {
        state.errorMessage = message;
    }
};

const actions = {
    async createProblem(context, formData) {
        const response = await axios
            .post("api/problems", formData)
            .catch(error => error.response || error);

        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setCreateProblemErrorMsg", resoponse.data.errors);
        }
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
