import React from 'react';
import Flux from '@4geeksacademy/react-flux-dash';

class QuizStore extends Flux.Store{
    
    constructor(){
        super();
        this.state = {
            //initialize store state
            quiz: null,
            totalTime: 0,
            templates: {
                correct: ['That\'s right!','Yes! Maybe you paid attention?','You seem to know most of the anwers','Keep it up!'],
                incorrect: ['Uhh no.', 'Nop, you are wrong dude.', 'Mmmmm... almost there, but no.', 'No! Have you been paying attention?']
            }
        };
    }
    
    getRandom(type){
        const index = Math.floor(Math.random() * this.state.templates[type].length);
        return this.state.templates[type][index];
    }
    
    _setQuiz(data){
        this.setStoreState({ quiz: data }).emit();
    }
    _saveQuiz(data){
        this.setStoreState({ 
            totalTime: data.totalTime
        }).emit();
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