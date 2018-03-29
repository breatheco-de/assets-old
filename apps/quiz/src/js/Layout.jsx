import React from 'react';
import Flux from "@4geeksacademy/react-flux-dash";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import Quiz from "./views/Quiz.jsx";
import Question from "./views/Question.jsx";
import QuizActions from "./actions/QuizActions";

export default class Layout extends Flux.View {
    
    constructor(){
        super();
        
    }
    
    render() {
        return (
            <div>
                <BrowserRouter>
                    <div>
                        <Switch>
                            <Route exact path="/:quiz_id" component={Quiz} />
                            <Route exact path="/:quiz_id/:question_id" component={Question} />
                            <Route render={() => <h1>Not found!</h1>} />
                        </Switch>
                    </div>
                </BrowserRouter>
            </div>
        );
    }
}
