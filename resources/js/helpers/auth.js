import { setAuthorization } from './general.js';

export function login(credentials) {
    return new Promise((result, reject) => {
        axios.post('/api/auth/login', credentials)
            .then((response) => {
                setAuthorization(response.data.access_token);
                result(response.data);
            })
            .catch((error)=>{
                reject('Mauvais email ou mot de passe');
            })
    })
}

export function getLocalUser() {
    const userStr = localStorage.getItem('user');

    if(!userStr) {
        return null;
    }

    return JSON.parse(userStr);
}
