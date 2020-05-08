import Vue from 'vue'
import Vuex from 'vuex'

import router from './router'

Vue.use(Vuex);

const types = {
    LOGIN: 'LOGIN',
    LOGOUT: 'LOGOUT'
};

const state = {
    logged: localStorage.getItem('token')
};

const getters = {
    isLogged: state => state.logged
};

const actions = {
    login({commit}, credentials) {
        Vue.http.post('http://127.0.0.1:8000/api/login', credentials)
            .then((response) => response.json())
            .then((result) => {
                localStorage.setItem('token', result.data.access_token);
                commit(types.LOGIN);
                router.push({
                    path: '/questionnaires'
                })
            });
    },
    logout({commit}) {
        Vue.http.get('http://127.0.0.1:8000/api/logout')
            .then((response) => response.json())
            .then(() => {
                localStorage.removeItem('token');
                commit(types.LOGOUT);
                router.push({
                    path: '/login'
                });
            });
    },
    pageLogin() {
        router.push({
            path: '/login'
        });
    },
    pageRegister() {
        router.push({
            path: '/register'
        });
    },
    register({commit}, credential) {
        Vue.http.post('http://127.0.0.1:8000/api/register', credential)
            .then((response) => response.json())
            .then((result) => {
            });
    }
};

const mutations = {
    [types.LOGIN](state) {
        state.logged = 1;
    },
    [types.LOGOUT](state) {
        state.logged = 0;
    }
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
})
