<template>
    <div class="form main-panel border rounded p-3">
        <div class="text-center">
            <h3>
                Tytuł ankiety:
                <br/><center><input v-model="data.title" class="form-control" style="width: 50%; text-align: center;"></center>
            </h3>
        </div>
        <center>
            <div>
                <h3>Data rozpoczęcia:</h3>
                <datepicker v-model="data.start_time" :language="pl" format="d MMMM yyyy" name="start_time" style="width: 50%; text-align: center;"></datepicker>
            </div>
            <br/>
            <div>
                <h3>Data zakończenia:</h3>
                <datepicker v-model="data.end_time" :language="pl" format="d MMMM yyyy" name="end_time" style="width: 50%; text-align: center;"></datepicker>
            </div>
        </center>
        <hr>
        <div class="list-group">
            <div v-for="question in data.questions">
                <br/>
                <div class="list-group-item list-group-item-dark bg-dark text-light">
                    <input v-model="question.question" class="form-control" type="text" id="title1" name="title1">
                </div>
                <div class="input-group-item list-group-item-light">
                    <div v-for="(answer, index) in question.answers" class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" disabled>
                        </div>
                        <input type="text" v-model="question.answers[index]" class="form-control" style="width: 50%">
                    </div>
                </div>
                <br/>
                <button v-on:click="addAnswer(question)" class="btn btn-success my-2 my-sm-0">Dodaj odpowiedź</button>
            </div>
        </div>
        <hr>
        <center>
            <button v-on:click="saveQuestionnaire()" class="btn btn-secondary btn-lg btn-block">Dodaj ankietę</button>
        </center>
        <br/>
        <center>
            <button v-on:click="addQuestion()" class="btn btn-success my-2 my-sm-0">Dodaj pytanie</button>
        </center>
        <br/>
        <center>
            <button v-on:click="back()" class="btn btn-success my-2 my-sm-0">Powrót</button>
        </center>
    </div>
</template>

<script>
    import router from "../router";
    import Datepicker from 'vuejs-datepicker';
    import { pl } from 'vuejs-datepicker/dist/locale'

    export default {
        name: 'createQuestionnaire',
        data() {
            return {
                data: {
                    'title' : '',
                    'start_time' : new Date(),
                    'end_time' : new Date(),
                    'questions' : [
                        {
                            'question' : '',
                            'answers' : ['']
                        }
                    ]
                },
                pl : pl
            }
        },
        methods: {
            back(){
                router.push({
                    path: '/admin'
                })
            },
            addQuestion() {
                this.data.questions.push({
                    'question' : '',
                    'answers' : ['']
                });
            },
            addAnswer(question) {
                question.answers.push('');
            },
            saveQuestionnaire() {
                var dataSend = this.data;
                dataSend.start_time = dataSend.start_time.toMysqlFormat();
                dataSend.end_time = dataSend.end_time.toMysqlFormat();

                const resultStr = JSON.stringify(dataSend);
                console.log(JSON.parse(resultStr));

                this.$store.dispatch('storequestionnaire', JSON.parse(resultStr));
            }
        },
        components: {
            Datepicker
        }
    }

    function twoDigits(d) {
        if(0 <= d && d < 10) return "0" + d.toString();
        if(-10 < d && d < 0) return "-0" + (-1*d).toString();
        return d.toString();
    }

    Date.prototype.toMysqlFormat = function() {
        return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate()) + " 00:00:00";
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
