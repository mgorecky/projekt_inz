<template>
    <div id="app" class="container-fluid">
        <nav class="navbar navbar-toggleable-md navbar-dark bg-dark">
            <a class="navbar-brand" href="/questionnaires">System anonimowych ankiet</a>

            <div v-if="isLogged">
                <button v-on:click="logout" class="btn btn-outline-success my-2 my-sm-0">Wyloguj</button>
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
            'isLogged'
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
            }
        }
    }
</script>

<style>
</style>
