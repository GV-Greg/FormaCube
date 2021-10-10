const state = {
    archives: {},
};
const getters = {};

const actions = {
    getList( { commit }, pageNumber ) {
        axios.get('/api/user/all?page=' + pageNumber)
            .then( response => {
                commit('setArchives', response.data);
            });
    },
};

const mutations = {
    setArchives( state, data ) {
        state.archives = data;
    },
    setCurrentPage ( state, data ) {
        state.archives.current_page = data;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
