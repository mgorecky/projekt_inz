<template>
    <div class="form main-panel border rounded p-3">
        <table class="table b-table table-striped table-hover table-bordered">
            <tr class="thead-dark">
                <th>Nazwa ankiety</th>
                <th>Data rozpoczęcia</th>
                <th>Data zakończenia</th>
                <th>Wypełnij</th>
                <th>Sprawdź</th>
            </tr>

            <tr v-for="questionnaire in this.data['questionaires']">
                <td>{{questionnaire.title}}</td>
                <td>{{questionnaire.start_time}}</td>
                <td>{{questionnaire.end_time}}</td>
                <td>
                    <center>
                        <div v-if="!CanFillDate(questionnaire.id) && CanFill(questionnaire.id)">
                            <button class="btn btn-danger my-2 my-sm-0">Ankieta zamknięta</button>
                        </div>
                        <div v-else-if="CanFill(questionnaire.id)">
                            <router-link v-bind:to="'/questionnaire/fill/'+questionnaire.id" class="btn btn-success my-2 my-sm-0">Wypełnij</router-link>
                        </div>
                        <div v-else>
                            <button class="btn btn-danger my-2 my-sm-0">Wypełniona</button>
                        </div>
                    </center>
                </td>
                <td>
                    <center>
                        <div v-if="CanCheck(questionnaire.id)">
                            <input type="text" class="form-control" v-model="data['keys'][questionnaire.id]"  :id="'area-'+questionnaire.id" rows="1"><br/>
                            <router-link v-bind:to="'/questionnaire/check/'+questionnaire.id+'/'+data['keys'][questionnaire.id]" class="btn btn-success my-2 my-sm-0">Sprawdź odpowiedzi</router-link>
                        </div>
                        <div v-else>
                            <button class="btn btn-danger my-2 my-sm-0">Brak odpowiedzi</button>
                        </div>
                    </center>
                </td>
            </tr>

        </table>
    </div>
</template>

<script>
    import router from "../router";

    export default {
        name: 'questionnaires',
        data() {
            return {
                data: {}
            }
        },
        methods: {
            fetchQuestionnaires() {
                this.$http.get('http://api.endymion.pl/api/questionnaires')
                    .then(response => response.json())
                    .then(result => this.data = result.data);
            },
            CanFillDate(id) {
                for (var i = 0; i < this.data.questionaires.length; ++i){
                    if (this.data.questionaires[i].id == id){
                        if (this.data.questionaires[i].end_time > new Date().toJSON().slice(0, 19).replace('T', ' '))
                            return true;
                        return false;
                    }
                }
                return true;
            },
            CanFill(id) {
                for (var i = 0; i < this.data.userAnswers.length; ++i){
                    if (this.data.userAnswers[i].questionaire_id == id)
                        return false;
                }
                return true;
            },
            CheckQuestionnaire(id) {
                router.push({
                    path: 'questionnaires/check/' + id + '/JDJ5JDEwJGFkS2xPZGJFbVdVUWxUaGg0dnJ5aGVUMEw5T1E1UjlCREpIRUJ4ZnVoYUZBNU1Bc3hNQWNL'
                })
            },
            CanCheck(id) {
                for (var i = 0; i < this.data.userAnswers.length; ++i){
                    if (this.data.userAnswers[i].questionaire_id == id)
                        return true;
                }
                return false;
            }
        },
        created: function () {
          this.fetchQuestionnaires();
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
