import React from "react";
import Flux from "@4geeksacademy/react-flux-dash";
import { Link } from "react-router-dom";
import QuizStore from "../stores/QuizStore";

export default class Results extends Flux.View {
    
    constructor(){
        super();
        this.state = {
            rightAnswers: 0,
            time: 0,
            quiz: null
        };
    }
    
    componentWillMount(){
        const quiz = QuizStore.getSingleQuiz();
        if(!quiz) this.props.history.push('/');
        
        this.setState({
            rightAnswers: QuizStore.getRightAnswers(),
            time: QuizStore.getTotalTime(),
            quiz: quiz
        });
        
    }
    
    render() {
        
        if(this.state.quiz === null) return (<div className="p-5 text-center"><h1>Loading...</h1></div>);
        
        return (
            <div className="p-5 text-center">
                <h1>Quiz {this.state.quiz.info.name}</h1>
                <p>{this.state.quiz.info.main}</p>
                <Link className="btn btn-primary" to={"/"+this.props.match.params.quiz_id+"/start"}>Start</Link>
            </div>
        );
    }
}