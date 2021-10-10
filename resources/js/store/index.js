import { getLocalUser } from '../helpers/auth';

const user = getLocalUser();

export default {
    state: {
        currentUser: user,
        isLoggedIn: !!user, // le double !! renvoi un boolean
        loading: false,
        auth_error: null,
        users: [],
        archives: [],
    },
    getters: {
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        currentUser(state) {
            return state.currentUser;
        },
        authError(state) {
            return state.auth_error;
        },
        users(state) {
            return state.users;
        },
        archives(state) {
            return state.archives;
        },
    },
    mutations: {
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload) {
            state.auth_error = null;
            state.isLoggedIn = true;
            state.loading = false;
            state.currentUser= Object.assign({}, payload.user, {token: payload.access_token});

            localStorage.setItem('user', JSON.stringify(state.currentUser));
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem('user');
            state.isLoggedIn = false;
            state.currentUser = null;
        },
        updateUsers(state, payload) {
            state.users = payload;
        },
        updateUser(state, payload) {
            state.currentUser = payload;
        },
        updateArchives(state, payload) {
            state.archives = payload;
        },
    },
    actions: {
        login(context) {
            context.commit('login');
        },
        getUsers(context) {
            axios.get('api/users')
                .then((response) => {
                    context.commit('updateUsers', response.data.users)
                })
                .catch((error) => {

                })
        },
        getArchives(context) {
            axios.get('api/users/archives')
                .then((response) => {
                    context.commit('updateArchives', response.data.archives)
                })
                .catch((error) => {

                })
        }
    },
};
