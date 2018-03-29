import React from "react";
import Flux from "@4geeksacademy/react-flux-dash";
import { Link } from "react-router-dom";
import ButtonComponent from '../components/ButtonComponent.jsx';
import QuizStore from "../stores/QuizStore";
import QuizActions from "../actions/QuizActions";

export default class Question extends Flux.View {
    
    constructor(){
        super();
        this.state = {
            quizType: "",
            questionId: null,
            error: null,
            question: null,
            result: null,
            isLastQuestion: false,
            rightQuestions: 0,
            seconds: 0
        };
        this.timeLimit = null;
    }
    
    loadQuestion(questionId){
        const question = QuizStore.getQuestion(this.props.match.params.quiz_id, questionId );
        const totalQuestions = QuizStore.getQuestionCount(this.props.match.params.quiz_id);
        this.timeLimit =  QuizStore.getTimeLimit(this.props.match.params.quiz_id);
        
        let isLastQuestion = false;
        if(questionId === (totalQuestions-1)) isLastQuestion = true;
        
        
        if(!question) this.setState({error: 'Invalid question', questionId, result: null, isLastQuestion });
        else this.setState({question, questionId, result: null, isLastQuestion});
    }
    
    componentWillMount(){
        const questionId = (this.props.match.params.question_id === 'start') ? 0 : parseInt(this.props.match.params.question_id);
        this.loadQuestion(questionId);
    }
    
    componentDidMount(){
        this.timeoutTimer = setInterval(()=>{
            this.setState({ seconds: this.state.seconds+1 });
            if(this.timeLimit && this.timeLimit < this.state.seconds) this.timeIsUp();
        },1000);
    }
    
    timeIsUp(){
        clearInterval(this.timeoutTimer);
        this.props.history.push('/time-is-up');
    }
    
    checkAnswer(answer){
        this.setState({
            result: {
                correct: answer.correct,
                message: (answer.correct) ? 'Correct':"Wrong"
            },
            rightQuestions: (answer.correct) ? this.state.rightQuestions+1 : this.state.rightQuestions
        });
        
        this.nextQuestionTimer = setTimeout(()=>{
            if(this.state.isLastQuestion) this.calculateResults();
            else this.loadNextQuestion();
        },1000);
    }
    
    loadNextQuestion(){
        let nextQuestionId = parseInt(this.state.questionId)+1;
        this.props.history.push("/"+this.props.match.params.quiz_id+'/'+nextQuestionId);
        this.loadQuestion(nextQuestionId);
    }
    
    render() {
        if(this.state.error) return (<p className="alert alert-danger">{this.state.error}</p>);
        if(!this.state.question) return (<div className="text-center p-5"><h1>Loading...</h1></div>);
        
        const answers = this.state.question.a.map((answer, i) => (
            <li key={i}
                onClick={() => this.checkAnswer(answer) }
            >
                <a className="nav-link active" href="#">
                    {answer.option}
                </a>
            </li>
        ));
        
        return (
            <div className="text-center p-5">
                {   (this.state.result) ?
                        (this.state.result.correct) ? (
                            <p className="alert alert-success">{this.state.result.message}</p>
                        ) : (
                            <p className="alert alert-danger">{this.state.result.message}</p>
                        ) : ''
                }
                <h1>{this.state.question.q}</h1>
                <ul className="nav justify-content-center">
                    {answers}
                </ul>
                <div className="timer"><a className="clock"></a>{this.state.seconds}</div>
            </div>
        );
    }
}