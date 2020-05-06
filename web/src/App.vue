<template>
    <div id="app" class="container-fluid">
        <nav class="navbar navbar-toggleable-md navbar-dark bg-dark">
            <a class="navbar-brand" href="/questionnaires">System anonimowych ankiet</a>

            <button  class="btn btn-outline-success my-2 my-sm-0">Wyloguj</button>
        </nav>
     <router-view/>
    </div>
</template>

<script>
    export default {
        name: 'App'
    }
</script>

<style>
</style>
