<template>
    <div id="app" class="container-fluid">
        <nav class="navbar navbar-toggleable-md navbar-dark bg-dark">
            <div v-if="isLogged">
                <a class="navbar-brand" href="/questionnaires">System anonimowych ankiet</a>
            </div>
            <div v-else>
                <a class="navbar-brand" href="/">System anonimowych ankiet</a>
            </div>

            <div v-if="isLogged">
                <button v-on:click="logout" class="btn btn-outline-success my-2 my-sm-0">Wyloguj</button>
                <button v-if="isAdmin" v-on:click="admin" class="btn btn-outline-success my-2 my-sm-0">Admin</button>
            </div>
            <div v-else>
                <button v-on:click="login" class="btn btn-outline-success my-2 my-sm-0">Zaloguj</button>
                <button v-on:click="register" class="btn btn-outline-success my-2 my-sm-0">Zarejestruj</button>
            </div>
        </nav>
     <router-view/>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        name: 'App',
        computed: mapGetters([
            'isLogged',
            'isAdmin'
        ]),
        methods: {
            logout() {
                this.$store.dispatch('logout');
            },
            login() {
                this.$store.dispatch('pageLogin');
            },
            register() {
                this.$store.dispatch('pageRegister');
            },
            admin() {
                this.$store.dispatch('admin');
            },
        }
    }
</script>

<style>
</style>
