import {OK, UNPROCESSABLE_ENTITY, CREATED} from '../util'

const state = {
    user: null,
    apiStatus: null,
    loginErrorMessages: null,
    registerErrorMessages: null
}

const getters = {}

const mutations = {
    setUser(state, data) {
        state.user = data
    },
    setApiStatus(state, status) {
        state.apiStatus = status
    },
    setLoginErrorMessages(state, messages) {
        state.loginErrorMessages = messages
    },
    setRegisterErrorMessages(state, messages) {
        state.registerErrorMessages = messages
    }

}

const actions = {
    async register(context, data) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/api/register', data)
            .catch(error => error.response || error)

        console.log(response.data)

        if (response.status === CREATED) {
            context.commit('apiStatus', true)
            context.commit('setUser')
        }

        context.commit('setApiStatus', false)
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit('setRegisterErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, {root: true})
        }

    },
    async login(context, data) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/api/login', data)
            .catch(error => error.response || error)

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', response.data)
        }

        context.commit('setApiStatus', false)
        if (response.status === UNPROCESSABLE_ENTITY) {
            console.log(response.data.errors)
            context.commit('setLoginErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, {root: true})
        }
    },
    async logout(context) {
        const response = await axios.post('/api/logout')
        context.commit('setUser', null)
    },
    async currentUser(context) {
        context.commit('setApiStatus', null)
        const response = await axios.get('/api/user')
            .catch(error => error.response || error)
        const user = response.data || null

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', user)
        }

        context.commit('setApiStatus', false)
        context.commit('error/setCode', response.status, { root: true })
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
