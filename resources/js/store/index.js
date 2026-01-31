import { defineStore } from 'pinia';
import { getLocalUser } from '../helpers/auth';

const user = getLocalUser();

export const useMainStore = defineStore('main', {
    state: () => ({
        currentUser: user,
        isLoggedIn: !!user,
        loading: false,
        auth_error: null,
        users: [],
        archives: [],
    }),

    getters: {
        // Getters access state directly via store instance
    },

    actions: {
        login() {
            this.loading = true;
            this.auth_error = null;
        },

        loginSuccess(payload) {
            this.auth_error = null;
            this.isLoggedIn = true;
            this.loading = false;
            this.currentUser = { ...payload.user, token: payload.access_token };
            localStorage.setItem('user', JSON.stringify(this.currentUser));
        },

        loginFailed(payload) {
            this.loading = false;
            this.auth_error = payload.error;
        },

        logout() {
            localStorage.removeItem('user');
            this.isLoggedIn = false;
            this.currentUser = null;
        },

        updateUsers(payload) {
            this.users = payload;
        },

        updateUser(payload) {
            this.currentUser = payload;
        },

        updateArchives(payload) {
            this.archives = payload;
        },

        async fetchUsers() {
            try {
                const response = await axios.get('api/users');
                this.users = response.data.users;
            } catch (error) {
                console.error('Error fetching users:', error);
            }
        },

        async fetchArchives() {
            try {
                const response = await axios.get('api/users/archives');
                this.archives = response.data.archives;
            } catch (error) {
                console.error('Error fetching archives:', error);
            }
        },
    },
});

