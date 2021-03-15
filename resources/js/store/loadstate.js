// state
const state = () => ({
    loadstate: false
})

// mutation
const mutations = {
    setLoadState (state, bool) {
        state.loadstate = bool
    }
}

export default {
    namespaced: true,
    state,
    mutations
}
