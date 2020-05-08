import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'

import Login from '@/components/Login'
import Questionnaires from '@/components/Questionnaires'
import Register from '@/components/Register'
import Main from '@/components/Main'
import FillQuestionnaire from '@/components/FillQuestionnaire'

Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'main',
            component: Main
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/questionnaires',
            name: 'questionnaires',
            component: Questionnaires
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/questionnaire/fill/:id',
            name: 'fillQuestionnaire',
            component: FillQuestionnaire
        }
    ]
})
