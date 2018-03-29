import React from 'react';
import Flux from '@4geeksacademy/react-flux-dash';

class QuizStore extends Flux.Store{
    
    constructor(){
        super();
        this.state = {
            //initialize store state
            quiz: null
        };
    }
    
    _setQuiz(data){
        this.setStoreState({ quiz: data }).emit();
    }
    getSingleQuiz(){
        return this.state.quiz;
    }
    
    getTimeLimit(){
        if(typeof this.state.quiz.info.timeLimit === 'undefined') return null;
        else return this.state.quiz.info.timeLimit;
    }
    
    getQuestion(quiz_id, index){
        if(!this.state.quiz) return null;
        return this.state.quiz.questions[index];
    }
    
    getQuestionCount(quiz_id){
        if(!this.state.quiz) return 0;
        return this.state.quiz.questions.length;
    }
}
export default new QuizStore();