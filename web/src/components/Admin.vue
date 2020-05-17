<template>
    <div class="form main-panel border rounded p-3">
        <table class="table b-table table-striped table-hover table-bordered">
            <tr class="thead-dark">
                <th>Nazwa ankiety</th>
                <th>Data rozpoczęcia</th>
                <th>Data zakończenia</th>
                <th>Wypełnij</th>
            </tr>

            <tr v-for="questionnaire in this.data['questionaires']">
                <td>{{questionnaire.title}}</td>
                <td>{{questionnaire.start_time}}</td>
                <td>{{questionnaire.end_time}}</td>
                <td>
                    <center>
                        <router-link v-bind:to="'/admin/check/'+questionnaire.id" class="btn btn-success my-2 my-sm-0">Wyniki</router-link>
                    </center>
                </td>
            </tr>

        </table>
    </div>
</template>

<script>
    import router from "../router";

    export default {
        name: 'admin',
        data() {
            return {
                data: {}
            }
        },
        methods: {
            fetchQuestionnaires() {
                this.$http.get('http://127.0.0.1:8000/api/admin')
                    .then(response => response.json())
                    .then(result => this.data = result.data);
            },
        },
        created: function () {
          this.fetchQuestionnaires();
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
