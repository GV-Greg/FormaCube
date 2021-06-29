const state = {
    user: {},
    errors: {},
};
const getters = {};
const actions = {
    loginUser( {}, user ) {
        axios.post('/api/user/login', {
            email: user.email,
            password: user.password,
        })
            .then( response => {
                if( response.data.access_token ) {
                    // Sauve le token dans le localStorage (voir dans Application)
                    localStorage.setItem("log_token", response.data.access_token);
                    window.location.replace('/app');
                }
            })
            .catch( error => {
                this.errors = error.response.data.errors;
            })
    },
    getUser( { commit } ) {
        axios.get('/api/user/current')
            .then( response => {
                commit('setUser', response.data);
            });
    },
    logoutUser() {
        // Détruit le token qui est stocké de localStorage
        localStorage.removeItem('log_token');

        window.location.replace('/login');
    }
};
const mutations = {
    setUser( state, data ) {
        state.user = data;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
