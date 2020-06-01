<template>
    <div class="form main-panel border rounded p-3">
        <div class="text-center">
            <h1>
                {{this.data['main-information']['title']}}
            </h1>
        </div>
        <hr>
        <div v-if="this.data['status'] == 0" class="list-group">
            <div v-for="question in this.data['questions']">
                <div class="list-group-item list-group-item-dark bg-dark text-light">
                    {{question.question}}
                </div>
                <div class="list-group-item list-group-item-light">
                    <div v-for="answer in question.answers" class="custom-control custom-radio">
                        <input v-if="answer.answer_id == question.user_answer" type="radio" class="custom-control-input" :id="answer.answer_id" :name="'answer-'+question.id" :value="answer.answer_id" checked>
                        <input v-else type="radio" class="custom-control-input" :id="answer.answer_id" :name="'answer-'+question.id" :value="answer.answer_id" disabled>
                        <label class="custom-control-label" :for="answer.answer_id">{{answer.value}}</label>
                    </div>
                </div>
                <br/> <br/>
            </div>
        </div>
        <div v-else-if="this.data['status'] == 1" class="alert alert-danger" role="alert">
            Nie znaleziono odpowiedzi przypisanych do tego tokenu.
        </div>
        <div v-else class="alert alert-danger" role="alert">
            Odpowiedzi w Twojej ankiecie zostały zmodyfikowane!
        </div>
        <center>
            <button v-on:click="back()" class="btn btn-success my-2 my-sm-0">Powrót</button>
        </center>
    </div>
</template>

<script>
    import router from "../router";

    export default {
        name: 'checkQuestionnaire',
        data() {
            return {
                data: {
                    'main-information' : {
                        id : 111,
                    },
                    'questions' : {},
                    'status' : 0,
                },
            }
        },
        methods: {
            fetchQuestionnaire(id, key){
                this.$http.get('http://api.endymion.pl/api/questionnaires/'+id+'/check/'+key)
                    .then(response => response.json())
                    .then(result => {
                        this.data = result.data;
                    });
            },
            back(){
                router.push({
                    path: '/questionnaires'
                })
            }
        },
        created: function () {
            this.fetchQuestionnaire(this.$route.params.id, this.$route.params.key);
            console.log('asd');
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
