const state = {
    problemData: null
};

const mutations = {
    setProblemData(state, problemData) {
        state.problemData = problemData;
    }
};

const actions = {
    async getProblemAndAnswer(context, exerciseBooksId) {
        const url = `/api/problems/${exerciseBooksId}`;
        const response = await axios
            .get(url)
            .catch(error => response.error || error);

        context.commit("setProblemData", response.data);
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
};
