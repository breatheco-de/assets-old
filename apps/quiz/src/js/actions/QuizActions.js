/* global fetch */
import Flux from '@4geeksacademy/react-flux-dash';

class QuizActions extends Flux.Action{
    
    fetchQuiz(){
        fetch('https://assets.breatheco.de/apis/quiz_api/quiz/rest')
          .then((response) => {
            return response.json();
          })
          .then((quiz) => {
            console.log(quiz);
            this.dispatch('QuizStore.setQuiz', quiz );
          })
          .catch(function(error){
             throw new Error(error); 
          });
    }
}
export default new QuizActions();