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
                    {{question.question}} {{question.id}}
                </div>
                <div class="list-group-item list-group-item-light">
                    <div v-for="answer in question.answers" class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" :ref="'answer-'+question.id" :id="answer.answer_id" :name="'answer-'+question.id" :value="answer.answer_id">
                        <label class="custom-control-label" :for="answer.answer_id">{{answer.value}}</label>
                    </div>
                </div>
                <br/> <br/>
            </div>
        </div>

        <center>
            <button v-on:click="sendQuestionnaire()" class="btn btn-success my-2 my-sm-0">Zakończ ankietę</button>
        </center>
    </div>
</template>

<script>
    import router from "../router";

    export default {
        name: 'fillQuestionnaire',
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
                this.$http.get('http://127.0.0.1:8000/api/questionnaires/'+id)
                    .then(response => response.json())
                    .then(result => {
                        this.data = result.data;
                    });
            },
            sendQuestionnaire(){
                var result = {};
                result['id'] = this.data['main-information'].id;
                result['answers'] = [];

                for (var i = 0; i < this.data['questions'].length; ++i){
                    var refs = this.$refs['answer-' + this.data['questions'][i].id];
                    var answerID = -1;

                    for (var j = 0; j < refs.length; ++j){
                        if (refs[j].checked == true){
                            answerID = refs[j].value;
                            break;
                        }
                    }

                    if (answerID == -1){
                        console.log('error');
                        return;
                    }

                    result['answers'][i] = {};
                    result['answers'][i]['quest_id'] = this.data['questions'][i].id;
                    result['answers'][i]['answer_id'] = answerID;
                }

                const resultStr = JSON.stringify(result);
                console.log(JSON.parse(resultStr));

                this.$store.dispatch('fillquestionnaire', JSON.parse(resultStr));
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
