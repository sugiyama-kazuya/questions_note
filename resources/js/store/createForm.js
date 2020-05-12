import { UNPROCESSABLE_ENTITY } from "../util";

const state = {
    isCategory: null,
    isExercise: null,
    isNewProblem: null,
    errorMessage: null,
    newExerciseName: null,
    newExerciseNameErrMsg: null,
    displayData: null
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
    },
    setCreateExerciseBooksName(state, data) {
        state.newExerciseName = data;
    },
    setCreateExerciseBooksNameErr(state, message) {
        state.newExerciseNameErrMsg = message;
    },
    setCreationScreen(state, data) {
        state.displayData = data;
    }
};

const actions = {
    async displayCreatingScreen(context) {
        const response = await axios
            .get("api/problems/create")
            .catch(error => error.response || error);

        console.log(response.data);

        context.commit("setCreationScreen", response.data);
    },
    async createProblem(context, formData) {
        const response = await axios
            .post("api/problems", formData)
            .catch(error => error.response || error);

        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setCreateProblemErrorMsg", resoponse.data.errors);
        }
    },
    async createExerciseBooksName(context, data) {
        console.log(data);
        const response = await axios
            .post("api/problems/newExerciseName", data)
            .catch(error => error.response || error);

        console.log(response);

        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit(
                "setCreateExerciseBooksNameErr",
                response.data.errors
            );
            return;
        }
        context.commit("setCreateExerciseBooksName", response.data);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
