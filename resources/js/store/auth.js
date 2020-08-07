import { OK, UNPROCESSABLE_ENTITY, CREATED } from "../util";

const state = {
    user: null,
    apiStatus: null,
    loginErrorMessages: null,
    registerErrorMessages: null,
    isPromptToRegisterOrLoginModal: false
};

const getters = {
    loginCheck: state => (state.user ? true : false)
};

const mutations = {
    setUser(state, data) {
        state.user = data;
    },
    setApiStatus(state, status) {
        state.apiStatus = status;
    },
    setLoginErrorMessages(state, messages) {
        state.loginErrorMessages = messages;
    },
    setRegisterErrorMessages(state, messages) {
        state.registerErrorMessages = messages;
    },
    setIsPromptToRegisterOrLoginModal(state, status) {
        state.isPromptToRegisterOrLoginModal = status;
    }
};

const actions = {
    async register(context, data) {
        context.commit("setApiStatus", null);
        const response = await axios
            .post("/api/register", data)
            .catch(error => error.response || error);

        if (response.status === CREATED) {
            context.commit("setApiStatus", true);
            context.commit("setUser", response.data);
            return;
        }

        context.commit("setApiStatus", false);
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setRegisterErrorMessages", response.data.errors);
        } else {
            context.commit("error/setCode", response.status, { root: true });
        }
    },

    async login(context, data) {
        context.commit("setApiStatus", null);
        const response = await axios
            .post("/api/login", data)
            .catch(error => error.response || error);

        if (response.status === OK) {
            console.log("成功です");
            context.commit("setApiStatus", true);
            context.commit("setUser", response.data);
            return;
        }

        context.commit("setApiStatus", false);
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit("setLoginErrorMessages", response.data.errors);
            return;
        } else {
            context.commit("error/setCode", response.status, { root: true });
            return;
        }
    },
    async logout(context) {
        const response = await axios.post("/api/logout");
        context.commit("setUser", null);
    },
    async currentUser(context) {
        context.commit("setApiStatus", null);
        const response = await axios
            .get("/api/user")
            .catch(error => error.response || error);
        const user = response.data || null;

        if (response.status === OK) {
            context.commit("setApiStatus", true);
            context.commit("setUser", user);
        }

        context.commit("setApiStatus", false);
        context.commit("error/setCode", response.status, { root: true });
    },

    openPromptToRegisterOrLoginModal(context) {
        context.commit("setIsPromptToRegisterOrLoginModal", true);
    },

    closePromptToRegisterOrLoginModal(context) {
        context.commit("setIsPromptToRegisterOrLoginModal", false);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
