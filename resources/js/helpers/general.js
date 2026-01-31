export function initialize(store, router) {
    router.beforeEach((to, from, next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const currentUser = store.currentUser;

        // Éviter les boucles de redirection
        if (to.path === from.path) {
            next();
            return;
        }

        if (requiresAuth && !currentUser) {
            next({ path: '/login', replace: true });
        } else if (to.path === '/login' && currentUser) {
            next({ path: '/home', replace: true });
        } else {
            next();
        }
    });

    axios.interceptors.response.use(null, (error) => {
        if (error.response && error.response.status === 401) {
            store.logout();
            router.push('/login');
        }

        return Promise.reject(error);
    });

    if (store.currentUser) {
        setAuthorization(store.currentUser.token);
    }
}

export function setAuthorization(token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
