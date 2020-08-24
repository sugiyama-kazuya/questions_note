const state = {
    visible: false
};

const mutations = {
    setVisible(state, isVisible) {
        state.visible = isVisible;
    },
    setClearFlashMsg(state, isVisible) {
        state.visible = isVisible;
    }
};
const actions = {
    hideFlashMsg(context, speed) {
        setTimeout(function() {
            context.commit("setClearFlashMsg", false);
        }, speed);
    },
    showFlashMsg(context) {
        context.commit("setVisible", true);
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
};
