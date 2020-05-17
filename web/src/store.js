import Vue from 'vue'
import Vuex from 'vuex'

import router from './router'

Vue.use(Vuex);

const types = {
    LOGIN: 'LOGIN',
    LOGOUT: 'LOGOUT',
};

const state = {
    logged: localStorage.getItem('token'),
    admin: parseInt(localStorage.getItem('role'))
};

const getters = {
    isLogged: state => state.logged,
    isAdmin: state => {
        return state.admin
    }
};

const actions = {
    clearLogin({commit}) {
        localStorage.removeItem('token');
        commit(types.LOGOUT);
        commit('ADMIN', 0);
        router.push({
            path: '/'
        });
    },
    login({commit}, credentials) {
        Vue.http.post('http://127.0.0.1:8000/api/login', credentials)
            .then((response) => response.json())
            .then((result) => {
                localStorage.setItem('token', result.data.access_token);
                localStorage.setItem('role', result.data.role);
                commit(types.LOGIN);
                if (result.data.role == 1)
                    commit('ADMIN', 1);
                else
                    commit('ADMIN', 0);
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
                localStorage.removeItem('role');
                commit(types.LOGOUT);
                commit('ADMIN', 0);
                router.push({
                    path: '/'
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
                router.push({
                    path: '/login'
                });
            });
    },
    fillquestionnaire({commit}, result) {
        Vue.http.post('http://127.0.0.1:8000/api/questionnaires', result)
            .then((response) => response.json())
            .then((result) => {
                router.push({
                    path: '/questionnaires'
                })
            });
    },
    admin() {
        router.push({
            path: '/admin'
        });
    },
};

const mutations = {
    [types.LOGIN](state) {
        state.logged = 1;
    },
    [types.LOGOUT](state) {
        state.logged = 0;
    },

    ADMIN(state, role) {
        state.admin = role
    }
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
})
