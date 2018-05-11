import Flux from "react-flux-dash";
import NPSAPI from '../utils/NPSApi';
import SurveyStore from '../stores/SurveyStore';

class SurveyActions extends Flux.Action{
    
    constructor(){
        super();
        this.client_id = process.env.DB_HOST
    }
    
    getResults(){
        NPSAPI.getResults().then((response) => {
            console.log(response);
            this.dispatch('SurveyStore.setResults', response);
        }).catch((error) => {
            this.dispatch('ErrorStore.addError', error.message);
            console.log(error);  
        });
    }
    
    getStudentData(id){
        NPSAPI.getStudentAnswers(id).then((response) => {
            console.log(response);
            if(typeof response === 'undefined') this.dispatch('ErrorStore.addError', "Unable to find the previous answers");
            else if(typeof response.code == 'undefined') this.dispatch('SurveyStore.setStudentAnswers', response);
        }).catch((error) => {
            this.dispatch('ErrorStore.addError', error.msg);
            console.log(error);  
        });
        
        
        NPSAPI.getStudent(id).then((response) => {
            if(typeof(response.code) == 'undefined'){
                this.dispatch('SurveyStore.setStudent', response);
            } 
            else
            {
                if(typeof response.msg !== 'undefined') this.dispatch('ErrorStore.addError', response.msg);
                else if(typeof response.message !== 'undefined') this.dispatch('ErrorStore.addError', response.message);
            }
        }).catch((error) => {
            this.dispatch('ErrorStore.addError', error.msg);
            console.log(error);  
        });
    }
    
    saveAnswerData(data){
        this.dispatch('SurveyStore.setAnswerData', data);
    }
    
    sendSurvey(history){
        let answer = SurveyStore.getAnswerData();
        let student = SurveyStore.getStudent();
        NPSAPI.saveStudentAnswer({answer,student}).then((response) => {
            if(response.code == 500) this.dispatch('ErrorStore.addError',response.message);
            else
            {
                history.push('/thanks');
            }
        });
    }
}
var _survayActions = new SurveyActions();
export default _survayActions;