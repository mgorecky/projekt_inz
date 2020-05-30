import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'

import Login from '@/components/Login'
import Questionnaires from '@/components/Questionnaires'
import Register from '@/components/Register'
import Main from '@/components/Main'
import FillQuestionnaire from '@/components/FillQuestionnaire'
import CheckQuestionnaire from '@/components/CheckQuestionnaire'
import Admin from '@/components/Admin'
import AdminCheck from '@/components/AdminCheck'
import AdminCreate from '@/components/AdminCreate'

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
        },
        {
            path: '/questionnaire/check/:id/:key',
            name: 'checkQuestionnaire',
            component: CheckQuestionnaire
        },
        {
            path: '/admin',
            name: 'admin',
            component: Admin
        },
        {
            path: '/admin/check/:id',
            name: 'adminCheck',
            component: AdminCheck
        },
        {
            path: '/admin/create',
            name: 'adminCreate',
            component: AdminCreate
        }
    ]
})
