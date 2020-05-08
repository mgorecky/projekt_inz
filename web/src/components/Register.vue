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
            <label for="password">Imie</label>
            <input v-model="credentials.first_name" class="form-control" type="text" id="first_name" name="first_name">
        </div>
        <div class="form-group">
            <label for="password">Nazwisko</label>
            <input v-model="credentials.last_name" class="form-control" type="text" id="last_name" name="last_name">
        </div>
        <div class="form-group">
            <button v-on:click="register" class="btn btn-primary">Rejestracja</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'register',
        data() {
            return {
                credentials: {
                    email: '',
                    password: '',
                    first_name: '',
                    last_name: ''
                }
            }
        },
        methods: {
            register() {
                this.$store.dispatch('register', this.credentials);
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
