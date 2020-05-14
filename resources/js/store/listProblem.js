const state = {
    problemCardData: null
}

const mutations = {
    setProblmeCard(state,cardData){
        state.problemCardData = cardData
    }
}

const actions = {
    async getProblmeCard(context){
        const response = await axios.get('api/problems')
            .catch(error => error.response || error)

        context.commit("setProblmeCard", response.data)
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}
