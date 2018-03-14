import Flux from 'react-flux-dash';

class SurveyStore extends Flux.Store{
    
    constructor(){
        super();
        this.state = {
            student: null,
            answers: null
        };
    }
    
    _setResults(results){ this.setStoreState({ results }).emit(); }
    getResults(){ return this.state.results; }
    
    _setStudent(student){ 
        this.calculateLastAnswer(student, this.state.answers);
        this.setStoreState({ student }).emit(); 
    }
    getStudent(){ return this.state.student; }
    
    _setStudentAnswers(answers){ 
        this.calculateLastAnswer(this.state.student, answers);
        this.setStoreState({ answers: answers }).emit(); 
    }
    getStudentAnswers(){ return this.state.answers; }
    
    _setAnswerData(currentAnswer){ this.setStoreState({ currentAnswer }).emit(); }
    getAnswerData(){ return this.state.currentAnswer; };
    
    calculateLastAnswer(student, answers){
        if(student != null && answers != null){
            let answerDates = answers.map(function(ans) {
              return new Date(ans.created_at.date);
            });
            student.lastAnswer = new Date(Math.max.apply(null, answerDates));
        }
        else if(student!=null) student.lastAnswer = null;
    }
}
var _theStore = new SurveyStore();
export default _theStore;