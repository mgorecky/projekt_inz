<template>
    <div class="form main-panel border rounded p-5">
        <div class="form-group">
            <label for="email">E-mail</label>
            <input v-model="credentials.email" class="form-control" type="email" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="password">Hasło</label>
            <input v-model="credentials.password" class="form-control" type="password" id="password" name="password">
        </div>
        <div class="form-group">
            <button v-on:click="login" class="btn btn-primary">Zaloguj</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'login',
        data() {
            return {
                credentials: {
                    email: '',
                    password: ''
                }
            }
        },
        methods: {
            login() {
                this.$store.dispatch('login', this.credentials);
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
