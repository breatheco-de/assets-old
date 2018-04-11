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
            totalQuestions: 0,
            isLastQuestion: false,
            rightQuestions: 0,
            seconds: 0,
            correctAnswer: null,
            totalResults: null,
        };
        this.timeLimit = null;
    }
    
    loadQuestion(questionId){
        const question = QuizStore.getQuestion(this.props.match.params.quiz_id, questionId );
        if(!question) window.location.href = '/'+this.props.match.params.quiz_id;
        const totalQuestions = QuizStore.getQuestionCount(this.props.match.params.quiz_id);
        this.timeLimit =  QuizStore.getTimeLimit(this.props.match.params.quiz_id);
        
        let isLastQuestion = false;
        if(questionId === (totalQuestions-1)) isLastQuestion = true;
        
        
        if(!question) this.setState({error: 'Invalid question', questionId, result: null, isLastQuestion, totalQuestions, correctAnswer: null });
        else this.setState({question, questionId, result: null, isLastQuestion, totalQuestions, correctAnswer: null});
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
    
    getCorrectAnswer(){
        const ans = this.state.question.a.filter(ans => ans.correct);
        if(ans.length === 1) return ans[0];
        else alert('Ooops! It seems that this question has more than one successfull answer, please report this to your teacher');
    }
    
    checkAnswer(answer){
        const ans = this.getCorrectAnswer();
        this.setState({
            correctAnswer: ans,
            result: {
                correct: answer.correct,
                message: (answer.correct) ? QuizStore.getRandom('correct'):QuizStore.getRandom('incorrect')
            },
            rightQuestions: (answer.correct) ? this.state.rightQuestions+1 : this.state.rightQuestions
        });
        
        this.nextQuestionTimer = setTimeout(()=>{
            if(this.state.isLastQuestion) this.setState({ totalResults: true});
            else this.loadNextQuestion();
        },2000);
    }
    
    loadNextQuestion(){
        let nextQuestionId = parseInt(this.state.questionId)+1;
        this.props.history.push("/"+this.props.match.params.quiz_id+'/'+nextQuestionId);
        this.loadQuestion(nextQuestionId);
    }
    
    printTotalResults(){
        return (<div className="text-center p-5 results">
                    You had {this.state.rightQuestions} right questions out of {this.state.totalQuestions}
        </div>);
    }
    
    render() {
        if(this.state.totalResults) return this.printTotalResults();
        if(this.state.error) return (<p className="alert alert-danger">{this.state.error}</p>);
        if(!this.state.question) return (<div className="text-center p-5"><h1>Loading...</h1></div>);
        
        const answers = this.state.question.a.map((answer, i) => (
            <li key={i} className="nav-item"
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
                            <p className="answer correct">{this.state.result.message}</p>
                        ) : (
                            <p className="answer incorrect">
                                {this.state.result.message}
                                <p>
                                    {this.state.correctAnswer.option}
                                </p>
                            </p>
                        )
                    :
                        (<span><h1>{this.state.question.q}</h1>
                            <ul className="nav justify-content-center flex-column">
                                {answers}
                            </ul>
                        </span>)
                }
                <div className="timer"><a className="clock"></a>{this.state.seconds} sec</div>
                <div className="breadcrumb">{parseInt(this.state.questionId) + 1} / {this.state.totalQuestions}</div>
            </div>
        );
    }
}