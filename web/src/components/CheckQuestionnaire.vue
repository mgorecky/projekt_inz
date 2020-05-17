<template>
    <div class="form main-panel border rounded p-3">
        <div class="text-center">
            <h1>
                {{this.data['main-information']['title']}}
            </h1>
        </div>
        <hr>
        <div class="list-group">
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
                },
            }
        },
        methods: {
            fetchQuestionnaire(id){
                this.$http.get('http://127.0.0.1:8000/api/questionnaires/'+id+'/check')
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
            this.fetchQuestionnaire(this.$route.params.id);
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
