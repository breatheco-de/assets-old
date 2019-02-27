import React from "react";
import Flux from "@4geeksacademy/react-flux-dash";
import { Link } from "react-router-dom";
import QuizStore from "../stores/QuizStore";
import QuizActions from "../actions/QuizActions";
import {LanguageSwitcher} from "../components/LanguageSwitcher/LanguageSwitcher.jsx";

export default class Quiz extends Flux.View {
    
    constructor(){
        super();
        this.state = {
            quiz: QuizStore.getSingleQuiz()
        };
        this.bindStore(QuizStore, () => {
            const incomingQuiz = QuizStore.getSingleQuiz();
            this.setState({ quiz: incomingQuiz || null });
        });
    }
    
    componentWillMount(){
        if(this.state.quiz === null) this.loadQuiz(this.props.match.params.quiz_id);
    }
    loadQuiz(slug){
        this.setState({ quiz: null });
        QuizActions.fetchQuiz(slug);
    }
    render() {
        
        if(this.state.quiz === null || typeof this.state.quiz === 'undefined') return (<div className="p-5 text-center"><h1>Loading...</h1></div>);
        const regex = /^([a-zA-Z\d-_]+)\.?([a-z]{2})?$/gm;
        const match = regex.exec(this.props.match.params.quiz_id);
        const lang = match[2] || 'en';
        const slug = match[1];
        if(typeof this.state.quiz.msg != 'undefined') return (
            <div className="p-5 alert alert-error">
                <h1>There was a problem loading the quiz: </h1>
                <h2>{this.state.quiz.msg}</h2>
                { lang != "en" && (<button className="btn btn-info mr-3" 
                            onClick={() => {
                                        this.props.history.push("/"+slug);
                                        this.loadQuiz(slug);
                                    }}
                            >Try it in English?</button>)
                }
                <a target="_blank" href="https://github.com/breatheco-de/assets/issues/new" className="btn btn-default">⚠ ️Report this issue</a>
            </div>);
        return (
            <div className="p-5 text-center">
                <h1>{this.state.quiz.info.name}</h1>
                <p>{this.state.quiz.info.main}</p>
                <Link className="btn btn-primary" to={"/"+this.props.match.params.quiz_id+"/start"}>Start Quiz</Link>
                <LanguageSwitcher current={lang} 
                    translations={{
                        es: slug+".es",
                        en: slug
                    }} 
                    onClick={(newSlug) => {
                        this.props.history.push("/"+newSlug);
                        this.loadQuiz(newSlug);
                    }}
                />
            </div>
        );
    }
}